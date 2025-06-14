<?php
require_once '../app/lib/DB.php';

class ProductModel {
    public static function getBestseller() {
        $pdo = DB::getConnection();

        // 2 Bestseller aus Proteinpulver mit Bild und Preis
        $stmt1 = $pdo->prepare("
            SELECT 
                p.id, p.name, p.description,
                (SELECT pp.top_pic 
                 FROM proteinpulver_pictures pp 
                 WHERE pp.product_id = p.id 
                 LIMIT 1) AS bild,
                (SELECT sp.price_with_tax 
                 FROM proteinpulver_sizes_prices sp 
                 WHERE sp.product_id = p.id 
                 ORDER BY sp.price_with_tax ASC 
                 LIMIT 1) AS preis
            FROM proteinpulver_products p
            WHERE p.bestseller = 1
            LIMIT 2
        ");
        $stmt1->execute();
        $pulver = $stmt1->fetchAll(PDO::FETCH_ASSOC);

        // 2 Bestseller aus Proteinriegel mit Bild und Preis
        $stmt2 = $pdo->prepare("
            SELECT 
                p.id, p.name, p.description,
                (SELECT pp.top_pic 
                 FROM proteinriegel_pictures pp 
                 WHERE pp.product_id = p.id 
                 LIMIT 1) AS bild,
                (SELECT sp.price_with_tax 
                 FROM proteinriegel_sizes_prices sp 
                 WHERE sp.product_id = p.id 
                 ORDER BY sp.price_with_tax ASC 
                 LIMIT 1) AS preis
            FROM proteinriegel_products p
            WHERE p.bestseller = 1
            LIMIT 2
        ");
        $stmt2->execute();
        $riegel = $stmt2->fetchAll(PDO::FETCH_ASSOC);

        // Zusammenführen
        return array_merge($pulver, $riegel);
    }
}
