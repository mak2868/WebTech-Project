<?php

require_once '../app/lib/DB.php';

class OrderModel
{
    public static function createOrder($userId, $cartItems, $total, $coupon = null)
    {
        $db = DB::getConnection();

        // Adresse holen
        $address = UserModel::getUserAddressByUserId($userId);
        $addressId = $address['id'] ?? null;

        // Rabatt berechnen
        $couponCode = $coupon['code'] ?? null;
        $discount = 0;
        if (is_array($coupon) && isset($coupon['type'], $coupon['value'])) {
            $rawTotal = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cartItems));
            $discount = $coupon['type'] === 'percent'
                ? round($rawTotal * $coupon['value'] / 100, 2)
                : $coupon['value'];
        }

        // Bestellung einfügen
        $stmt = $db->prepare("
            INSERT INTO orders (user_id, order_date, status, total, shipping_address_id, coupon_code, billing_address_id, discount)
            VALUES (?, NOW(), 'in Bearbeitung', ?, ?, ?, ?, ?)

        ");
        $success = $stmt->execute([
            $userId, $total, $addressId, $couponCode, $addressId, $discount
        ]);
        if (!$success) return null;

        $orderId = $db->lastInsertId();

        // Bestellpositionen einfügen
        $stmtItem = $db->prepare("
            INSERT INTO order_items (order_id, product_id, size, quantity, price)
            VALUES (?, ?, ?, ?, ?)
        ");

        foreach ($cartItems as $item) {
            if (!is_array($item)) continue;

            $productName = $item['product_name'] ?? $item['name'] ?? null;
            $size = $item['size'] ?? null;
            $quantity = $item['quantity'] ?? 1;
            $price = $item['price'] ?? 0;

            if (!$productName) continue;

            // ID aus Produktname ermitteln
            $productId = self::findProductIdByName($db, $productName);
            if (!$productId) {
                error_log("Produkt nicht gefunden: $productName");
                continue;
            }

            $stmtItem->execute([$orderId, $productId, $size, $quantity, $price]);
        }

        // Statusverlauf starten
        $stmtStatus = $db->prepare("
            INSERT INTO order_status_history (order_id, status, changed_at)
            VALUES (?, 'in Bearbeitung', NOW())

        ");
        $stmtStatus->execute([$orderId]);

        return $orderId;
    }

    private static function findProductIdByName($db, $name)
    {
        // zuerst Pulver
        $stmt = $db->prepare("SELECT pid FROM proteinpulver_products WHERE name = ?");
        $stmt->execute([$name]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) return $row['pid'];

        // dann Riegel
        $stmt = $db->prepare("SELECT pid FROM proteinriegel_products WHERE name = ?");
        $stmt->execute([$name]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) return $row['pid'];

        return null;
    }
}
