<?php

require_once '../app/lib/DB.php';
class CheckoutModel {
    public static function calculateTotal(array $items, ?array $coupon = null): float {
        $sum = 0;
        foreach ($items as $item) {
            $sum += $item['price'] * $item['quantity'];
        }

        if ($coupon) {
            if ($coupon['type'] === 'percent') {
                $sum *= (1 - $coupon['value'] / 100);
            } elseif ($coupon['type'] === 'amount') {
                $sum -= $coupon['value'];
            }
        }

        return max(0, round($sum, 2));
    }
}



