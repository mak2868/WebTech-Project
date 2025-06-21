<?php
require_once __DIR__ . '/../lib/db.php';
class CheckoutController
{
    public function showCheckout()
    {
        $message = null;

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            header("Location: index.php?page=login&redirect=checkout");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['apply_coupon'])) {
            $code = trim($_POST['couponCode'] ?? '');
            if ($code !== '') {
                $db = DB::getConnection();
                $stmt = $db->prepare("SELECT * FROM coupons WHERE code = :code AND (valid_until IS NULL OR valid_until >= NOW())");
                $stmt->execute(['code' => $code]);
                $coupon = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($coupon) {
                    $_SESSION['coupon'] = [
                        'code' => $coupon['code'],
                        'type' => $coupon['discount_type'],
                        'value' => (float) $coupon['discount_value']
                    ];
                  
                } else {
                    unset($_SESSION['coupon']);
                    $message = "Fehler";
                }
            }
        }

        $userId = $_SESSION['user']['id'];
        $user = UserModel::getUserById($userId);
        $cartItems = CartModel::getCartItems($userId);
        $coupon = $_SESSION['coupon'] ?? null;
        $address = UserModel::getUserAddressByUserId($userId);

        require __DIR__ . '/../views/pages/checkout.php';
    }

    public function applyCoupon()
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();

        $data = json_decode(file_get_contents("php://input"), true);
        $code = trim($data['code'] ?? '');

        if ($code) {
            $db = DB::getConnection();
            $stmt = $db->prepare("SELECT * FROM coupons WHERE code = :code AND (valid_until IS NULL OR valid_until >= NOW())");
            $stmt->execute(['code' => $code]);
            $coupon = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($coupon) {
                $_SESSION['coupon'] = [
                    'code' => $coupon['code'],
                    'type' => $coupon['discount_type'],
                    'value' => (float) $coupon['discount_value']
                ];
                echo json_encode(['success' => true]);
            } else {
                unset($_SESSION['coupon']);
                echo json_encode(['success' => false, 'message' => 'Ungültiger Gutscheincode.']);
            }
        }
    }

    public function setCartTotal()
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();
        $data = json_decode(file_get_contents("php://input"), true);
        $_SESSION['cart_total'] = $data['total'] ?? 0;

        echo json_encode(['success' => true]);
    }

    public function placeOrder()
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();

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
        $coupon = $_SESSION['coupon'] ?? null;
        $total = CheckoutModel::calculateTotal($cart, $coupon);

        require_once __DIR__ . '/../models/OrderModel.php';
        $orderId = OrderModel::createOrder($userId, $cart, $total, $coupon);

        CartModel::clearCart($userId);
        unset($_SESSION['coupon']);

        echo json_encode(['success' => true, 'order_id' => $orderId]);
    }
}
