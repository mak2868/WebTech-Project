<?php
/**
 * Controller für den Warenkorb
 * @author: Marvin Kunz, Felix Bartel
 */
?>


<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../app/models/CartModel.php';

class CartController {
    public function showCart() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $items = [];
        if (!empty($_SESSION['user_id'])) {
            $items = CartModel::getCartItems($_SESSION['user_id']);
        }

        include '../app/views/pages/cart.php';
    }

    public function addItem() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id'])) exit;

        $data = json_decode(file_get_contents("php://input"), true);
        CartModel::addItem($_SESSION['user_id'], $data);
        echo json_encode(['success' => true]);
    }

    public function getCart() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        header('Content-Type: application/json');

        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['error' => 'user_id not in session']);
            exit;
        }

        $items = CartModel::getCartItems($_SESSION['user_id']);
        echo json_encode($items);
        exit;
    }

    public function updateItem() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id'])) exit;

        $data = json_decode(file_get_contents("php://input"), true);
        CartModel::updateItem($_SESSION['user_id'], $data['item_id'], $data['quantity']);
        echo json_encode(['success' => true]);
    }

    public function removeItem() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id'])) exit;

        $data = json_decode(file_get_contents("php://input"), true);
        CartModel::removeItem($_SESSION['user_id'], $data['item_id']);
        echo json_encode(['success' => true]);
    }

    public function clearCart() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id'])) exit;

        CartModel::clearCart($_SESSION['user_id']);
        echo json_encode(['success' => true]);
    }

    // Wird durch merge-cart.js getriggert nach Login/Registration
    public function mergeCart() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405); // Method Not Allowed
            exit;
        }

        if (!isset($_SESSION['user_id'])) {
            http_response_code(401); // Unauthorized
            echo json_encode(['error' => 'Nicht eingeloggt']);
            exit;
        }

        $userId = $_SESSION['user_id'];
        $raw = file_get_contents('php://input');
        $items = json_decode($raw, true);

        if (!is_array($items)) {
            http_response_code(400);
            echo json_encode(['error' => 'Ungültige Daten']);
            exit;
        }

        foreach ($items as $item) {
            CartModel::addOrUpdateItem(
                $userId,
                $item['name'],
                $item['image'],
                $item['price'],
                $item['size'],
                $item['quantity']
            );
        }

        echo json_encode(['status' => 'success']);
    }
}
