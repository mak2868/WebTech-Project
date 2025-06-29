
<?php
/**
 * Warenkorb (serversetig)
 * @author: Felix Bartel
 */
?>





<?php

require_once '../app/lib/DB.php';

class CartModel {
    public static function getCartId($userId) {
        $pdo = DB::getConnection();
        $stmt = $pdo->prepare("SELECT id FROM carts WHERE user_id = ?");
        $stmt->execute([$userId]);
        $cart = $stmt->fetch();
        if ($cart) return $cart['id'];

        $stmt = $pdo->prepare("INSERT INTO carts (user_id) VALUES (?)");
        $stmt->execute([$userId]);
        return $pdo->lastInsertId();
    }

    public static function getCartItems($userId) {
        $cartId = self::getCartId($userId);
        $pdo = DB::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM cart_items WHERE cart_id = ?");
        $stmt->execute([$cartId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function addItem($userId, $item) {
        $cartId = self::getCartId($userId);
        $pdo = DB::getConnection();
        $stmt = $pdo->prepare("SELECT id FROM cart_items WHERE cart_id = ? AND product_name = ? AND size = ?");
        $stmt->execute([$cartId, $item['name'], $item['size']]);
        $existing = $stmt->fetch();

        if ($existing) {
            $stmt = $pdo->prepare("UPDATE cart_items SET quantity = quantity + 1 WHERE id = ?");
            $stmt->execute([$existing['id']]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO cart_items (cart_id, product_name, image, price, size, quantity) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$cartId, $item['name'], $item['image'], $item['price'], $item['size'], $item['quantity']]);
        }
    }

    public static function updateItem($userId, $itemId, $quantity) {
        $pdo = DB::getConnection();
        $cartId = self::getCartId($userId);
        $stmt = $pdo->prepare("UPDATE cart_items SET quantity = ? WHERE id = ? AND cart_id = ?");
        $stmt->execute([$quantity, $itemId, $cartId]);
    }

    public static function removeItem($userId, $itemId) {
        $pdo = DB::getConnection();
        $cartId = self::getCartId($userId);
        $stmt = $pdo->prepare("DELETE FROM cart_items WHERE id = ? AND cart_id = ?");
        $stmt->execute([$itemId, $cartId]);
    }

    public static function clearCart($userId) {
        $pdo = DB::getConnection();
        $cartId = self::getCartId($userId);
        $stmt = $pdo->prepare("DELETE FROM cart_items WHERE cart_id = ?");
        $stmt->execute([$cartId]);
    }

    

    public static function addOrUpdateItem($userId, $name, $image, $price, $size, $quantity) {
        $cartId = self::getCartId($userId);
        $pdo = DB::getConnection();

        $stmt = $pdo->prepare("SELECT id, quantity FROM cart_items WHERE cart_id = ? AND product_name = ? AND size = ?");
        $stmt->execute([$cartId, $name, $size]);
        $existing = $stmt->fetch();

        if ($existing) {
            $newQty = $existing['quantity'] + $quantity;
            $update = $pdo->prepare("UPDATE cart_items SET quantity = ? WHERE id = ?");
            $update->execute([$newQty, $existing['id']]);
        } else {
            $insert = $pdo->prepare("INSERT INTO cart_items (cart_id, product_name, image, price, size, quantity) VALUES (?, ?, ?, ?, ?, ?)");
            $insert->execute([$cartId, $name, $image, $price, $size, $quantity]);
        }
    }
}
