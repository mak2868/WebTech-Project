<?php

require_once '../app/lib/DB.php';
class OrderModel {
    public static function createOrder($userId, $cartItems, $total, $coupon = null) {
        require_once __DIR__ . '/../lib/DB.php';
        $db = DB::getConnection();

        // Dummy-Adresse holen oder anpassen je nach Struktur
        $address = UserModel::getUserAddressByUserId($userId);
        $addressId = $address['id'] ?? null;

        // Bestellung speichern
        $stmt = $db->prepare("INSERT INTO orders (user_id, order_date, status, total, shipping_address_id, coupon_code, billing_address_id, discount) VALUES (?, NOW(), 'offen', ?, ?, ?, ?, ?)");

        $couponCode = $coupon['code'] ?? null;
        $discount = 0;
        if ($coupon) {
            if ($coupon['type'] === 'percent') {
                $rawTotal = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cartItems));
                $discount = round($rawTotal * $coupon['value'] / 100, 2);
            } elseif ($coupon['type'] === 'amount') {
                $discount = $coupon['value'];
            }
        }

        $stmt->execute([$userId, $total, $addressId, $couponCode, $addressId, $discount]);
        $orderId = $db->lastInsertId();

        // order_items speichern
        $stmtItem = $db->prepare("INSERT INTO order_items (order_id, product_type, product_id, quantity, price) VALUES (?, ?, ?, ?, ?)");
        foreach ($cartItems as $item) {
            $stmtItem->execute([$orderId, $item['type'], $item['id'], $item['quantity'], $item['price']]);
        }

        // Statusverlauf anlegen
        $stmtStatus = $db->prepare("INSERT INTO order_status_history (order_id, status, changed_at) VALUES (?, 'offen', NOW())");
        $stmtStatus->execute([$orderId]);

        return $orderId;
    }
}
