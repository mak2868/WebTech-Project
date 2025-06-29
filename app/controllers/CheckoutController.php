<?php

require_once __DIR__ . '/../lib/db.php';

//Autor: Merzan Köse

class CheckoutController
{
    /**
     * Zeigt die Checkout-Seite an und behandelt ggf. Gutscheineingabe per POST.
     */
    public function showCheckout()
    {    $message = null;
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Weiterleitung zur Login-Seite, wenn kein Benutzer eingeloggt ist
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?page=login&redirect=checkout");
            exit;
        }

        // Gutschein wird klassisch per POST über das Formular eingegeben
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['apply_coupon'])) {
            $code = trim($_POST['couponCode'] ?? '');
            if ($code !== '') {
                $db = DB::getConnection();
                $stmt = $db->prepare("
                    SELECT * FROM coupons 
                    WHERE code = :code AND (valid_until IS NULL OR valid_until >= NOW())"
                );
                $stmt->execute(['code' => $code]);
                $coupon = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($coupon) {
                    // Gutschein-Daten in der Session speichern
                    $_SESSION['coupon'] = [
                        'code'  => $coupon['code'],
                        'type'  => $coupon['discount_type'],
                        'value' => (float) $coupon['discount_value']
                    ];
                } else {
                    unset($_SESSION['coupon']); // Gutschein ungültig
                }
            }
        }

        // Checkout-Daten vorbereiten
        $userId    = $_SESSION['user']['id'];
        $user      = UserModel::getUserById($userId);
        $cartItems = CartModel::getCartItems($userId);
        $coupon    = $_SESSION['coupon'] ?? null;
        $address   = UserModel::getUserAddressByUserId($userId);

        // Checkout-View laden
        require __DIR__ . '/../views/pages/checkout.php';
    }

    /**
     * Verarbeitet die Gutschein-Eingabe per JavaScript (AJAX).
     */
    public function applyCoupon()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $data = json_decode(file_get_contents("php://input"), true);
        $code = trim($data['code'] ?? '');

        if ($code !== '') {
            $db = DB::getConnection();
            $stmt = $db->prepare("
                SELECT * FROM coupons 
                WHERE code = :code AND (valid_until IS NULL OR valid_until >= NOW())"
            );
            $stmt->execute(['code' => $code]);
            $coupon = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($coupon) {
                $_SESSION['coupon'] = [
                    'code'  => $coupon['code'],
                    'type'  => $coupon['discount_type'],
                    'value' => (float) $coupon['discount_value']
                ];
                echo json_encode(['success' => true]);
            } else {
                unset($_SESSION['coupon']);
                echo json_encode(['success' => false, 'message' => 'Ungültiger Gutscheincode.']);
            }
        }
    }

    /**
     * Speichert den Gesamtpreis des Warenkorbs in der Session (falls clientseitig berechnet).
     */
    public function setCartTotal()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $data = json_decode(file_get_contents("php://input"), true);
        $_SESSION['cart_total'] = $data['total'] ?? 0;

        echo json_encode(['success' => true]);
    }

    /**
     * Legt eine Bestellung an, löscht den Warenkorb und gibt eine Erfolgsnachricht zurück.
     */
    public function placeOrder()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Fehleranzeige deaktivieren, um saubere JSON-Antwort zu gewährleisten
        ini_set('display_errors', 0);
        error_reporting(E_ALL);

        if (!isset($_SESSION['user'])) {
            echo json_encode(['success' => false, 'message' => 'Nicht eingeloggt.']);
            return;
        }

        $userId = $_SESSION['user']['id'];
        $cart = json_decode(file_get_contents("php://input"), true);

        if (!$cart || !is_array($cart)) {
            echo json_encode(['success' => false, 'message' => 'Warenkorb ist leer oder ungültig.']);
            return;
        }

        require_once __DIR__ . '/../models/CheckoutModel.php';
        require_once __DIR__ . '/../models/OrderModel.php';
          require_once __DIR__ . '/../models/ProductModel.php';

        $coupon = $_SESSION['coupon'] ?? null;
        $total = CheckoutModel::calculateTotal($cart, $coupon);

        $orderId = OrderModel::createOrder($userId, $cart, $total, $coupon);

        if (!$orderId) {
            echo json_encode(['success' => false, 'message' => 'Bestellung fehlgeschlagen (OrderID null).']);
            return;
        }

        // Alles bereinigen nach erfolgreicher Bestellung
        CartModel::clearCart($userId);
        unset($_SESSION['coupon']);

        echo json_encode(['success' => true, 'order_id' => $orderId]);
    }
}
