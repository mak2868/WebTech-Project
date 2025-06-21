<?php
class CheckoutController
{
    public function checkout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            header('Location: index.php?page=login&redirect=checkout');
            exit;
        }

        $user = $_SESSION['user'];
        $address = UserModel::getUserAddressByUserId($user['id']);

        require '../app/views/pages/checkout.php';
    }

    public function placeOrder()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['user'])) {
            header('Location: index.php?page=checkout&error=1');
            exit;
        }

        require_once '../app/lib/DB.php';
        $db = DB::getConnection();

        $userId = $_SESSION['user']['id'];
        $cart = json_decode($_POST['cart_data'] ?? '[]', true);

        if (!is_array($cart) || count($cart) === 0) {
            header('Location: index.php?page=checkout&error=emptycart');
            exit;
        }

        $totalBeforeDiscount = array_sum(array_map(function ($item) {
            return $item['price'] * $item['quantity'];
        }, $cart));

        $discount = 0;
        $couponCode = null;

        if (!empty($_SESSION['coupon'])) {
            $coupon = $_SESSION['coupon'];
            $couponCode = $coupon['code'];

            switch ($coupon['type']) {
                case 'percent':
                    $discount = $totalBeforeDiscount * $coupon['value'] / 100;
                    break;
                case 'fixed':
                    $discount = $coupon['value'];
                    break;
            }
        }

        $total = max(0, $totalBeforeDiscount - $discount);

        // Adresse holen
        $address = UserModel::getUserAddressByUserId($userId);
        if (!$address || empty($address['id'])) {
            header('Location: index.php?page=checkout&error=noaddress');
            exit;
        }

        // Bestellung speichern
        $stmt = $db->prepare("
            INSERT INTO orders 
                (user_id, order_date, status, total, shipping_address_id, coupon_code, discount)
            VALUES 
                (?, NOW(), 'offen', ?, ?, ?, ?)
        ");
        $stmt->execute([$userId, $total, $address['id'], $couponCode, $discount]);

        $orderId = $db->lastInsertId();

        // Produkte speichern
        $stmtItem = $db->prepare("
            INSERT INTO order_items 
                (order_id, product_type, product_id, quantity, price) 
            VALUES (?, ?, ?, ?, ?)
        ");

        foreach ($cart as $item) {
            $stmtItem->execute([
                $orderId,
                $item['type'] ?? 'product',
                $item['id'],
                $item['quantity'],
                $item['price']
            ]);
        }

        // Statushistorie speichern
        $stmtStatus = $db->prepare("
            INSERT INTO order_status_history 
                (order_id, status, changed_at) 
            VALUES (?, 'offen', NOW())
        ");
        $stmtStatus->execute([$orderId]);

        unset($_SESSION['coupon']);

        header('Location: index.php?page=profile&success=1');
        exit;
    }
}
