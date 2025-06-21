<?php
require_once '../../lib/DB.php';
require_once __DIR__ . '/../config/config.php';

class ProductModel
{


    public static function getBestseller()
    {
        $pdo = DB::getConnection();

        $allParentIDs = ProductModel::getAllParentIDs();
        $results = [];
        $usedProductIDs = []; // <--- IDs merken

        if (!empty($allParentIDs)) {
            for ($i = 0; $i < 4; $i++) {
                $newProduct = false;
                do {
                    $randomKey = array_rand($allParentIDs);
                    $randomParentID = $allParentIDs[$randomKey]['id'];

                    $tablePrefixArray = ProductModel::getParentCategoryNameFromParentID($randomParentID);
                    $tablePrefix = strtolower($tablePrefixArray[0]['name']);

                    $tablePics = $tablePrefix . "_pictures";
                    $tableSizesPrices = $tablePrefix . "_sizes_prices";
                    $tableProducts = $tablePrefix . "_products";

                    $sql = "
           SELECT 
    p.pid, p.name, p.description,
    (SELECT pp.product_pic1
     FROM $tablePics pp 
     WHERE pp.product_id = p.pid 
     LIMIT 1) AS bild,
    (SELECT sp.price_with_tax 
     FROM $tableSizesPrices sp 
     WHERE sp.product_id = p.pid 
       AND sp.bestseller = 1
     ORDER BY sp.price_with_tax ASC 
     LIMIT 1) AS preis
FROM $tableProducts p
ORDER BY RAND()
LIMIT 1

            ";

                    $stmt = $pdo->prepare($sql);
                    $stmt->execute();
                    $product = $stmt->fetch(PDO::FETCH_ASSOC);

                    // Prüfen, ob Produkt gültig und noch nicht verwendet
                    if ($product && !in_array($product['pid'], $usedProductIDs)) {
                        $usedProductIDs[] = $product['pid'];
                        $results[] = $product;
                        $newProduct = true;
                    }

                } while (!$newProduct);
            }
        }

        return $results;

        // 2 Bestseller aus Proteinpulver mit Bild und Preis
        //     $stmt1 = $pdo->prepare("
        //         SELECT 
        //             p.id, p.name, p.description,
        //             (SELECT pp.top_pic 
        //              FROM proteinpulver_pictures pp 
        //              WHERE pp.product_id = p.id 
        //              LIMIT 1) AS bild,
        //             (SELECT sp.price_with_tax 
        //              FROM proteinpulver_sizes_prices sp 
        //              WHERE sp.product_id = p.id 
        //              ORDER BY sp.price_with_tax ASC 
        //              LIMIT 1) AS preis
        //         FROM proteinpulver_products p
        //         WHERE p.bestseller = 1
        //         LIMIT 2
        //     ");
        //     $stmt1->execute();
        //     $pulver = $stmt1->fetchAll(PDO::FETCH_ASSOC);

        //     // 2 Bestseller aus Proteinriegel mit Bild und Preis
        //     $stmt2 = $pdo->prepare("
        //         SELECT 
        //             p.id, p.name, p.description,
        //             (SELECT pp.top_pic 
        //              FROM proteinriegel_pictures pp 
        //              WHERE pp.product_id = p.id 
        //              LIMIT 1) AS bild,
        //             (SELECT sp.price_with_tax 
        //              FROM proteinriegel_sizes_prices sp 
        //              WHERE sp.product_id = p.id 
        //              ORDER BY sp.price_with_tax ASC 
        //              LIMIT 1) AS preis
        //         FROM proteinriegel_products p
        //         WHERE p.bestseller = 1
        //         LIMIT 2
        //     ");
        //     $stmt2->execute();
        //     $riegel = $stmt2->fetchAll(PDO::FETCH_ASSOC);

        //    // Zusammenführen
        //     return array_merge($pulver, $riegel);
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

        $productTable = $parentName . '_products';
        $pictureTable = $parentName . '_pictures';
        $nutrientTable = $parentName . '_nutrients';
        $ingredrientTable = $parentName . '_ingredients';
        $aminoTable = $parentName . '_amino_acids';
        if (!ProductModel::tableExists($pdo, $aminoTable)) {
            $aminoTable = null;
        }
        $sizesPricesTable = $parentName . '_sizes_prices';
        $descriptionTable = $parentName . '_descriptions';


        // 1. Produkte aus Kategorie laden
        if (isset($productTable) && $productTable != null) {
            if ($parentName === 'proteinpulver') {
                $stmt = $pdo->prepare("
                SELECT 
                p.pid, p.cid, p.name, p.description, p.rating, p.raters_count, 
                p.status_distribution, p.preparation, p.recommendation, 
                p.tip, p.laboratory,
                pi.product_pic1, pi.product_pic2, pi.product_pic3, pi.small_pic
                FROM $productTable p
                LEFT JOIN $pictureTable pi ON p.pid = pi.product_id
                WHERE p.cid = :categoryID
                ");
            } else {
                $stmt = $pdo->prepare("
                SELECT 
                p.pid, p.cid, p.name, p.description, p.rating, p.raters_count, 
                p.status_distribution, p.preparation, p.recommendation, p.laboratory,
                pi.product_pic1, pi.product_pic2, pi.product_pic3, pi.small_pic
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

                    // 2. Bildpfad zusammensetzen (wenn vorhanden)
                    if (!empty($product['product_pic1'])) {
                        $product['product_pic1'] = BASE_URL . $product['product_pic1'];
                    }

                    if (!empty($product['product_pic2'])) {
                        $product['product_pic2'] = BASE_URL . $product['product_pic2'];
                    }

                    if (!empty($product['product_pic3'])) {
                        $product['product_pic3'] = BASE_URL . $product['product_pic3'];
                    }

                    if (!empty($product['small_pic'])) {
                        $product['small_pic'] = BASE_URL . $product['small_pic'];
                    }

                    // 3. Zutaten und Allergene
                    if (isset($ingredrientTable) && $ingredrientTable != null) {
                        $stmtIng = $pdo->prepare("SELECT ingredients, allergens FROM $ingredrientTable WHERE product_id = ?");
                        $stmtIng->execute([$productId]);
                        $product['substance'] = $stmtIng->fetch(PDO::FETCH_ASSOC);
                    }

                    // 4. Nährwerte
                    if (isset($nutrientTable) && $nutrientTable != null) {
                        $stmtNut = $pdo->prepare("
                SELECT energy, fat, saturates, carbohydrates, sugars, fibre, protein, salt 
                FROM  $nutrientTable
                WHERE product_id = ?
                ");
                        $stmtNut->execute([$productId]);
                        $product['substance']['nutrients'] = $stmtNut->fetch(PDO::FETCH_ASSOC);
                    }

                    // 5. Aminosäuren
                    if (isset($aminoTable) && $aminoTable != null) {
                        $stmtAmino = $pdo->prepare("SELECT * FROM $aminoTable WHERE product_id = ?");
                        $stmtAmino->execute([$productId]);
                        $aminoAcids = $stmtAmino->fetch(PDO::FETCH_ASSOC);
                        unset($aminoAcids['product_id']); // 'product_id' entfernen
                        $product['substance']['aminoAcids'] = $aminoAcids;
                    }

                    // 6. Verpackungsgrößen und Preise
                    if ($parentName === 'proteinriegel') {
                        if (isset($sizesPricesTable) && $sizesPricesTable != null) {
                            $stmtSize = $pdo->prepare("
                        SELECT size, price_with_tax, quantity_available
                        FROM $sizesPricesTable 
                        WHERE product_id = ?
                        ORDER BY CAST(size AS UNSIGNED) DESC
                        ");
                            $stmtSize->execute([$productId]);
                            $sizes = $stmtSize->fetchAll(PDO::FETCH_ASSOC);
                            $product['sizes'] = array_column($sizes, 'size');
                            $product['quantityPerSize'] = array_column($sizes, 'quantity_available');
                            $product['priceWithTax'] = array_column($sizes, column_key: 'price_with_tax');
                        }
                    } else {
                        if (isset($sizesPricesTable) && $sizesPricesTable != null) {
                            $stmtSize = $pdo->prepare("
                        SELECT size, price_with_tax, quantity_available 
                        FROM $sizesPricesTable 
                        WHERE product_id = ?
                        ORDER BY CAST(size AS UNSIGNED) ASC
                        ");
                            $stmtSize->execute([$productId]);
                            $sizes = $stmtSize->fetchAll(PDO::FETCH_ASSOC);
                            $product['sizes'] = array_column($sizes, 'size');
                            $product['quantityPerSize'] = array_column($sizes, 'quantity_available');
                            $product['priceWithTax'] = array_column($sizes, column_key: 'price_with_tax');
                        }

                    }

                    // 7. Description-Details
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

                    // 8. Optional: Rezepte (wenn vorhanden)
                    $stmtRec = $pdo->prepare("
                        SELECT id, title, short_title, `portion`
                        FROM proteinpulver_recipes 
                        WHERE product_id = ?
                    ");
                    $stmtRec->execute([$productId]);

                    $recipes = [];

                    while ($r = $stmtRec->fetch(PDO::FETCH_ASSOC)) {
                        $recipeId = $r['id'];

                        // Zutaten (Ingredients) abrufen
                        $stmtIngr = $pdo->prepare("
                            SELECT ingredient 
                            FROM proteinpulver_recipe_ingredients 
                            WHERE recipe_id = ?
                            ORDER BY id ASC
                        ");
                        $stmtIngr->execute([$recipeId]);
                        $r['ingredients'] = $stmtIngr->fetchAll(PDO::FETCH_COLUMN); // array of ingredients

                        // Zubereitungsschritte (Preparation) abrufen
                        $stmtPrep = $pdo->prepare("
                            SELECT instruction 
                            FROM proteinpulver_recipe_steps 
                            WHERE recipe_id = ?
                            ORDER BY step_number ASC
                        ");
                        $stmtPrep->execute([$recipeId]);
                        $r['preparation'] = $stmtPrep->fetchAll(PDO::FETCH_COLUMN); // array of steps

                        $recipes[] = $r;
                    }

                    // Zuweisung ans Produkt
                    $product['usage'] = [
                        'preparation' => $product['preparation'],
                        'recommendation' => $product['recommendation'],
                        'recipes' => $recipes
                    ];

                    if (isset($product['tip'])) {
                        $product['usage']['tip'] = $product['tip'];
                    }

                }
            }
        }

        return $products;
    }

    public static function getAllParentIDs()
    {
        $pdo = DB::getConnection();

        $stmt = $pdo->prepare("
            SELECT id FROM product_parent_categories
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getAllCids($parentID)
    {
        $pdo = DB::getConnection();

        $stmt = $pdo->prepare("
            SELECT id FROM product_categories WHERE parent_id = ?
        ");
        $stmt->execute([$parentID]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getAllPidsOfOneCids($parentID, $categoryID)
    {
        $pdo = DB::getConnection();

        $tablePrefixArray = ProductModel::getParentCategoryNameFromParentID($parentID);
        $tablePrefix = strtolower($tablePrefixArray[0]['name']);

        $table = $tablePrefix . "_products";
        // return ($tablePrefix . "_products");


        $stmt = $pdo->prepare("
            SELECT pid FROM $table WHERE cid = ?
        ");
        $stmt->execute([$categoryID]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getParentCategoryNameFromParentID($parentID)
    {
        $pdo = DB::getConnection();

        $stmt = $pdo->prepare("
            SELECT name FROM product_parent_categories WHERE id = ?
        ");
        $stmt->execute([$parentID]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function tableExists(PDO $pdo, string $tableName): bool
    {
        $stmt = $pdo->prepare("
        SELECT COUNT(*) 
        FROM INFORMATION_SCHEMA.TABLES 
        WHERE TABLE_SCHEMA = :db 
          AND TABLE_NAME = :table
    ");
        $stmt->execute([
            ':db' => $pdo->query("SELECT DATABASE()")->fetchColumn(),
            ':table' => $tableName
        ]);

        return $stmt->fetchColumn() > 0;
    }

}

// $products = ProductModel::getAllItemsOfKategory(3); // z. B. Kategorie "Vegan Whey"
// echo '<pre>';
// print_r($products);
// echo '</pre>';

// $cids = ProductModel::getAllCids();
// echo '<pre>';
// print_r($cids);
// echo '</pre>';

// $pids = ProductModel::getAllPidsOfOneCids(2, 4);
// echo '<pre>';
// print_r($pids);
// echo '</pre>';

// $parentids = ProductModel::getAllParentIDs();
// echo '<pre>';
// print_r($parentids);
// echo '</pre>';

// $bestseller = ProductModel::getBestseller();
// echo '<pre>';
// print_r($bestseller);
// echo '</pre>';