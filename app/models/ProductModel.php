<?php
require_once '../../lib/DB.php';

class ProductModel
{


    public static function getBestseller()
    {
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

    public static function getAllItemsOfKategory($categoryID)
    {
        $pdo = DB::getConnection();

        // Produkttyp ermitteln
        $stmtType = $pdo->prepare("
        SELECT ppc.name AS parent_name
        FROM product_categories pc
        JOIN product_parent_categories ppc ON pc.parent_id = ppc.id
        WHERE pc.id = ?
        ");
        $stmtType->execute([$categoryID]);
        $typeRow = $stmtType->fetch(PDO::FETCH_ASSOC);

        $parentName = strtolower($typeRow['parent_name']); // z. B. 'proteinpulver' oder 'proteinriegel'

        // Passende Tabellennamen definieren
        if ($parentName === 'proteinpulver') {
            $productTable = 'proteinpulver_products';
            $pictureTable = 'proteinpulver_pictures';
            $nutrientTable = 'proteinpulver_nutrients';
            $ingredrientTable = 'proteinpulver_ingredients';
            $aminoTable = 'proteinpulver_amino_acids';
            $sizesPricesTable = 'proteinpulver_sizes_prices';
            $descriptionTable = null;
        } elseif ($parentName === 'proteinriegel') {
            $productTable = 'proteinriegel_products';
            $pictureTable = 'proteinriegel_pictures';
            $nutrientTable = 'proteinriegel_nutrients';
            $ingredrientTable = 'proteinriegel_ingredients';
            $aminoTable = null;
            $sizesPricesTable = 'proteinriegel_sizes_prices';
            $descriptionTable = 'proteinriegel_descriptions';
        } else {
            throw new Exception("Unbekannter Produkttyp: $parentName");
        }

        // 1. Produkte aus Kategorie laden
        if (isset($productTable) && $productTable != null) {
            if ($parentName === 'proteinpulver') {
                $stmt = $pdo->prepare("
                SELECT 
                p.pid, p.cid, p.name, p.description, p.rating, p.raters_count, 
                p.status_distribution, p.preparation, p.recommendation, 
                p.tip, p.laboratory,
                pi.top_pic, pi.product_pic1, pi.product_pic2, pi.product_pic3, pi.small_pic
                FROM $productTable p
                LEFT JOIN $pictureTable pi ON p.pid = pi.product_id
                WHERE p.cid = :categoryID
                ");
            } else if ($parentName === 'proteinriegel') {
                $stmt = $pdo->prepare("
                SELECT 
                p.pid, p.cid, p.name, p.description, p.rating, p.raters_count, 
                p.status_distribution, p.preparation, p.recommendation, p.laboratory,
                pi.top_pic, pi.product_pic1, pi.product_pic2, pi.product_pic3, pi.small_pic
                FROM $productTable p
                LEFT JOIN $pictureTable pi ON p.pid = pi.product_id
                WHERE p.cid = :categoryID
                ");
            }

            if (isset($stmt)) {
                $stmt->execute(['categoryID' => $categoryID]);
                $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Für jedes Produkt weitere Details holen
                foreach ($products as &$product) {
                    $productId = $product['pid'];

                    // 2. Zutaten und Allergene
                    if (isset($ingredrientTable) && $ingredrientTable != null) {
                        $stmtIng = $pdo->prepare("SELECT ingredients, allergens FROM $ingredrientTable WHERE product_id = ?");
                        $stmtIng->execute([$productId]);
                        $product['substance'] = $stmtIng->fetch(PDO::FETCH_ASSOC);
                    }

                    // 3. Nährwerte
                    if (isset($nutrientTable) && $nutrientTable != null) {
                        $stmtNut = $pdo->prepare("
                SELECT energy, fat, saturates, carbohydrates, sugars, fibre, protein, salt 
                FROM  $nutrientTable
                WHERE product_id = ?
                ");
                        $stmtNut->execute([$productId]);
                        $product['substance']['nutrients'] = $stmtNut->fetch(PDO::FETCH_ASSOC);
                    }

                    // 4. Aminosäuren
                    if (isset($aminoTable) && $aminoTable != null) {
                        $stmtAmino = $pdo->prepare("SELECT * FROM $aminoTable WHERE product_id = ?");
                        $stmtAmino->execute([$productId]);
                        $aminoAcids = $stmtAmino->fetch(PDO::FETCH_ASSOC);
                        unset($aminoAcids['product_id']); // 'product_id' entfernen
                        $product['substance']['aminoAcids'] = $aminoAcids;
                    }

                    // 5. Verpackungsgrößen und Preise
                    if (isset($sizesPricesTable) && $sizesPricesTable != null) {
                        $stmtSize = $pdo->prepare("
                SELECT size, price_with_tax 
                FROM $sizesPricesTable 
                WHERE product_id = ? 
                ");
                        $stmtSize->execute([$productId]);
                        $sizes = $stmtSize->fetchAll(PDO::FETCH_ASSOC);
                        $product['availableSizes'] = array_column($sizes, 'size');
                        $product['priceWithTax'] = array_column($sizes, 'price_with_tax');
                    }

                    // 6. Description-Details
                    if (isset($descriptionTable) && $descriptionTable != null) {
                        $stmtDesc = $pdo->prepare("
                        SELECT detail1, detail2 
                        FROM $descriptionTable 
                        WHERE product_id = ?
                        ");
                        $stmtDesc->execute([$productId]);
                        $desc = $stmtDesc->fetch(PDO::FETCH_ASSOC);

                        $product['descriptionDetails'] = [
                            $desc['detail1'] ?? '',
                            $desc['detail2'] ?? ''
                        ];
                    }

                    //     // 6. Optional: Rezepte (wenn vorhanden)
                    //     $stmtRec = $pdo->prepare("
                    //     SELECT title, short_title, portion, ingredients, preparation 
                    //     FROM proteinpulver_recipes 
                    //     WHERE product_id = ?
                    // ");
                    // $stmtRec->execute([$productId]);
                    // $recipes = [];
                    // while ($r = $stmtRec->fetch(PDO::FETCH_ASSOC)) {
                    //     $r['ingredients'] = json_decode($r['ingredients'], true);
                    //     $r['preparation'] = json_decode($r['preparation'], true);
                    //     $recipes[] = $r;
                    // }
                    $product['usage'] = [
                        'preparation' => $product['preparation'],
                        'recommendation' => $product['recommendation'],
                        // 'recipes' => $recipes
                    ];

                    if (isset($product['tip'])) {
                        $product['usage']['tip'] = $product['tip'];
                    }
                }
            }
        }

        return $products;
    }
}

// $products = ProductModel::getAllItemsOfKategory(3); // z. B. Kategorie "Vegan Whey"
// echo '<pre>';
// print_r($products);
// echo '</pre>';
