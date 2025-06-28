<?php

require_once '../app/lib/DB.php';
require_once __DIR__ . '/../config/config.php';

class CheckoutModel
{
    /**
     * Berechnet den Gesamtpreis für alle Artikel im Warenkorb – inklusive Rabatt, falls vorhanden.
     *
     * @param array $items   Array der Warenkorbpositionen (mit Preis & Menge)
     * @param array|null $coupon Optionaler Gutschein (mit Typ 'percent' oder 'amount' und Wert)
     * @return float Der berechnete Gesamtpreis, gerundet auf 2 Nachkommastellen (mindestens 0)
     * @author Merzan
     */
    public static function calculateTotal(array $items, ?array $coupon = null): float
    {
        $sum = 0;

        // Summe aller Positionen (Preis * Menge)
        foreach ($items as $item) {
            $sum += $item['price'] * $item['quantity'];
        }

        // Rabatt anwenden, falls vorhanden
        if (is_array($coupon) && isset($coupon['type'], $coupon['value'])) {
            if ($coupon['type'] === 'percent') {
                // Prozentualer Rabatt z. B. 10% → 0.9-Faktor
                $sum *= (1 - $coupon['value'] / 100);
            } elseif ($coupon['type'] === 'amount') {
                // Fester Betrag abziehen
                $sum -= $coupon['value'];
            }
        }

        // Negativbetrag verhindern und runden
        return max(0, round($sum, 2));
    }
}
