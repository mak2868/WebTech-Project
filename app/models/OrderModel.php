<?php

require_once '../app/lib/DB.php';

class OrderModel
{
    /**
     * Erstellt eine neue Bestellung mit zugehörigen Artikeln und Statusverlauf.
     *
     * @param int $userId       ID des eingeloggten Benutzers
     * @param array $cartItems  Array mit Produktdaten aus dem Warenkorb
     * @param float $total      Gesamtpreis vor Rabatt
     * @param array|null $coupon Optionaler Rabattcode (Typ und Wert)
     * @return int|null         Die ID der neuen Bestellung oder null bei Fehler
     * @author Merzan
     */
    public static function createOrder($userId, $cartItems, $total, $coupon = null)
    {
        $db = DB::getConnection();

        // Adresse des Benutzers holen
        $address = UserModel::getUserAddressByUserId($userId);
        $addressId = $address['id'] ?? null;

        // Rabattcode auslesen und Rabatt berechnen
        $couponCode = $coupon['code'] ?? null;
        $discount = 0;

        if (is_array($coupon) && isset($coupon['type'], $coupon['value'])) {
            // Zwischensumme des Warenkorbs berechnen (für Rabattberechnung)
            $rawTotal = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cartItems));

            if ($coupon['type'] === 'percent') {
                $discount = round($rawTotal * $coupon['value'] / 100, 2);
            } elseif ($coupon['type'] === 'amount') {
                $discount = $coupon['value'];
            }
        }

        // Bestellung in der Tabelle 'orders' einfügen
        $sql = "INSERT INTO orders (user_id, order_date, status, total, shipping_address_id, coupon_code, billing_address_id, discount)
                VALUES (?, NOW(), 'offen', ?, ?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        $success = $stmt->execute([
            $userId,
            $total,
            $addressId,
            $couponCode,
            $addressId,
            $discount
        ]);

        if (!$success) {
            return null; // Fehler beim Einfügen
        }

        // ID der neu erstellten Bestellung abfragen
        $orderId = $db->lastInsertId();

        // Produkte im Warenkorb als 'order_items' speichern
        $stmtItem = $db->prepare(
            "INSERT INTO order_items (order_id, product_type, product_id, quantity, price) 
             VALUES (?, ?, ?, ?, ?)"
        );

        foreach ($cartItems as $item) {
            if (!is_array($item)) continue;

            $type = $item['type'] ?? 'default';      // Produktart (z. B. Pulver, Riegel, etc.)
            $productId = $item['id'] ?? null;         // Produkt-ID aus dem Shop
            $quantity = $item['quantity'] ?? 1;       // Menge
            $price = $item['price'] ?? 0;             // Preis pro Stück

            if ($productId === null) continue; // ungültiges Produkt überspringen

            $stmtItem->execute([$orderId, $type, $productId, $quantity, $price]);
        }

        // Eintrag in die Statushistorie der Bestellung (z. B. 'offen')
        $stmtStatus = $db->prepare(
            "INSERT INTO order_status_history (order_id, status, changed_at)
             VALUES (?, 'offen', NOW())"
        );
        $stmtStatus->execute([$orderId]);

        return $orderId;
    }
}
