<?php
class OrderController
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
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user'])) {
            require_once '../app/lib/DB.php';
            $db = DB::getConnection();
            $userId = $_SESSION['user']['id'];
            $cart = json_decode($_POST['cart_data'], true);

            $total = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));

            $stmt = $db->prepare("INSERT INTO orders (user_id, order_date, status, total, shipping_address_id) VALUES (?, NOW(), 'offen', ?, ?)");
            $stmt->execute([$userId, $total, $_POST['address_id']]);
            $orderId = $db->lastInsertId();

            $stmtItem = $db->prepare("INSERT INTO order_items (order_id, product_type, product_id, quantity, price) VALUES (?, ?, ?, ?, ?)");
            foreach ($cart as $item) {
                $stmtItem->execute([$orderId, $item['type'], $item['id'], $item['quantity'], $item['price']]);
            }

            $stmtStatus = $db->prepare("INSERT INTO order_status_history (order_id, status, changed_at) VALUES (?, 'offen', NOW())");
            $stmtStatus->execute([$orderId]);

            header('Location: index.php?page=profile&success=1');
            exit;
        } else {
            header('Location: index.php?page=checkout&error=1');
            exit;
        }
    }
}


