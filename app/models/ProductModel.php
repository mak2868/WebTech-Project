<?php
require_once __DIR__ . '/../lib/DB.php';
require_once __DIR__ . '/../config/config.php';

class ProductModel
{


   public static function getBestseller() {
    $pdo = DB::getConnection();

    $allParentIDs = ProductModel::getAllParentIDs();
    $results = [];
    $usedProductIDs = [];

    if (!empty($allParentIDs)) {
        for ($i = 0; $i < 4; $i++) {
            $newProduct = false;

            $attempts = 0;
            $maxAttempts = 10; // um Endlosschleifen zu vermeiden

            do {
                $attempts++;
                if ($attempts > $maxAttempts) break;

                $randomKey = array_rand($allParentIDs);
                $randomParentID = $allParentIDs[$randomKey]['id'];

                $tablePrefixArray = ProductModel::getParentCategoryNameFromParentID($randomParentID);
                $tablePrefix = strtolower($tablePrefixArray[0]['name']);

                $tablePics = $tablePrefix . "_pictures";
                $tableSizesPrices = $tablePrefix . "_sizes_prices";
                $tableProducts = $tablePrefix . "_products";

                //Tabellenvorhandensein prüfen 
                // (den wenn wir eine neue Kategorie im Adminbereich hinzufügen,
                // so ist erstmal keine entsprechende Tabelle vorhanden)
                $checkStmt = $pdo->prepare("SHOW TABLES LIKE ?");
                $checkStmt->execute([$tableProducts]);
                if ($checkStmt->rowCount() === 0) {
                    continue; // Tabelle existiert nicht, dann skip
                }

                $sql = "SELECT 
                    p.pid,
                    p.cid, 
                    p.name, 
                    p.description, 
                    p.raters_count, 
                    p.rating, 
                    pi.product_pic1,
                    pi.product_pic2,
                    pi.product_pic3,
                    pi.small_pic,
                    sp.price_with_tax AS preis,
                    sp.size AS size,
                    ppc.id AS parent_id
                FROM $tableProducts p
                JOIN $tableSizesPrices sp 
                    ON sp.product_id = p.pid AND sp.bestseller = 1
                JOIN product_categories pc 
                    ON pc.id = p.cid
                JOIN product_parent_categories ppc 
                    ON ppc.id = pc.parent_id
                LEFT JOIN $tablePics pi 
                    ON pi.product_id = p.pid
                ORDER BY RAND()
                LIMIT 1;";

                try {
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute();
                    $product = $stmt->fetch(PDO::FETCH_ASSOC);
                } catch (PDOException $e) {
                    // Wenn z. B. JOIN-Tabellen fehlen, weiter zur nächsten Kategorie
                    continue;
                }

                if ($product && !in_array($product['pid'], $usedProductIDs)) {
                    foreach (['product_pic1', 'product_pic2', 'product_pic3', 'small_pic'] as $picField) {
                        if (!empty($product[$picField])) {
                            $product[$picField] = BASE_URL . $product[$picField];
                        }
                    }

                    $product['bild'] = $product['product_pic1'] ?? '';
                    $usedProductIDs[] = $product['pid'];
                    $results[] = $product;
                    $newProduct = true;
                }

            } while (!$newProduct);
        }
    }

    return $results;
}





    public static function getAllItemsOfCategory($categoryID)
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
                //Bilder laden 
                $stmtPic = $pdo->prepare(
                    'SELECT shopping_cart, Herz_unausgefuellt, Herz_ausgefuellt FROM pictures;'
                );
                $stmtPic->execute();
                $pictures = $stmtPic->fetchAll(PDO::FETCH_ASSOC);

                // Bilder zu jedem Produkt hinzufügen
                foreach ($products as &$product) {  // Mit Referenz arbeiten
                    // Alle Bilder zuweisen
                    $product['shopping_cart'] = !empty($pictures[0]['shopping_cart']) ? BASE_URL . $pictures[0]['shopping_cart'] : '';
                    $product['Herz_unausgefuellt'] = !empty($pictures[0]['Herz_unausgefuellt']) ? BASE_URL . $pictures[0]['Herz_unausgefuellt'] : '';
                    $product['Herz_ausgefuellt'] = !empty($pictures[0]['Herz_ausgefuellt']) ? BASE_URL . $pictures[0]['Herz_ausgefuellt'] : '';
                }

                unset($product);  // Referenz löschen
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

/** @author Nick Zetzmann
 * 
*/


  public static function getProductsByCategory($cid)
{
    $pdo = DB::getConnection();

/* wantedSize zur Sicherstellung, dass bei entsprechender cid nur die Produktkarten mit der entsprechenden Groesse gezeigt/geladen wird */
    if (in_array($cid, [1, 2, 3])) {
        $wantedSize = '500';
    } elseif (in_array($cid, [4, 5, 6])) {
        $wantedSize = '45';
    } else {
        $wantedSize = null;
    }

    if (!$wantedSize) {
        return []; /* Für andere Kategorien leer zurueckgeben */
    }

/* Bestimme parent_id zu dieser Kategorie */
    $sqlParent = "SELECT parent_id FROM product_categories WHERE id = :cid";
    $stmtParent = $pdo->prepare($sqlParent);
    $stmtParent->execute([':cid' => $cid]);
    $parentRow = $stmtParent->fetch(PDO::FETCH_ASSOC);

    if (!$parentRow) {
        return [];
    }

    
    $parentID = $parentRow['parent_id'];

/* Hole Tabellenpraefix durch uebergeordnete Kategorie (z. B. "pulver") */
    $tablePrefixArray = ProductModel::getParentCategoryNameFromParentID($parentID);
    $tablePrefix = strtolower($tablePrefixArray[0]['name']);

/* Bestimme konkrete Tabellennamen */
    $tablePics = $tablePrefix . "_pictures";
    $tableSizesPrices = $tablePrefix . "_sizes_prices";
    $tableProducts = $tablePrefix . "_products";

/* SQL-Abfrage zum laden von Produkten mit bestimmter Größe in dieser Kategorie */
    $sql = "SELECT 
                p.pid,
                p.cid, 
                p.name, 
                p.description, 
                p.raters_count, 
                p.rating, 
                (SELECT pp.product_pic1
                 FROM $tablePics pp 
                 WHERE pp.product_id = p.pid 
                 LIMIT 1) AS bild,
                sp.price_with_tax AS preis,
                sp.size AS size,
                ppc.id AS parent_id
            FROM $tableProducts p
            JOIN $tableSizesPrices sp 
                ON sp.product_id = p.pid AND sp.size = :wantedSize
            JOIN product_categories pc 
                ON pc.id = p.cid
            JOIN product_parent_categories ppc 
                ON ppc.id = pc.parent_id
            WHERE p.cid = :cid";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':cid' => $cid,
        ':wantedSize' => $wantedSize
    ]);

/* Rueckgabe des Array der Produkte */
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($products as &$product) {
if (!empty($product['bild'])) {
                        $product['bild'] = BASE_URL . $product['bild'];
    }
}

    return $products;
}

public static function getProductsByParentCategory($parentID)
{
    $pdo = DB::getConnection();

/* Hole alle Unterkategorien mit dieser parentID */
    $sqlCids = "SELECT id FROM product_categories WHERE parent_id = :parentID";
    $stmtCids = $pdo->prepare($sqlCids);
    $stmtCids->execute([':parentID' => $parentID]);
    $categoryRows = $stmtCids->fetchAll(PDO::FETCH_ASSOC);

    if (!$categoryRows) {
        return [];
    }

/* Extrahiere nur die IDs der Unterkategorien */
    $categoryIds = array_column($categoryRows, 'id');

/* Tabellenpraefix bestimmen (z. B. "pulver") */
    $tablePrefixArray = ProductModel::getParentCategoryNameFromParentID($parentID);
    $tablePrefix = strtolower($tablePrefixArray[0]['name']);

    $tablePics = $tablePrefix . "_pictures";
    $tableSizesPrices = $tablePrefix . "_sizes_prices";
    $tableProducts = $tablePrefix . "_products";

/* Dynamische Placeholder für IN-Klausel erzeugen
 * Grund: Wir wissen nicht im Voraus, wie viele Kategorien (p.cid) abgefragt werden muessen.
 * SQL erlaubt keine direkte Uebergabe eines Arrays bei "IN (...)", daher muss pro Kategorie ein eigener Platzhalter erstellt werden.*/
    $placeholders = [];
    $params = [];
    foreach ($categoryIds as $index => $catId) {
        $key = ':cid' . $index;
        $placeholders[] = $key;
        $params[$key] = $catId;
    }

/* SQL-Abfrage mit dynamischer IN-Liste */
    $sql = "SELECT 
                p.pid,
                p.cid, 
                p.name, 
                p.description, 
                p.raters_count, 
                p.rating, 
                (SELECT pp.product_pic1
                 FROM $tablePics pp 
                 WHERE pp.product_id = p.pid 
                 LIMIT 1) AS bild,
                sp.price_with_tax AS preis,
                sp.size AS size,
                ppc.id AS parent_id
            FROM $tableProducts p
            JOIN $tableSizesPrices sp 
                ON sp.product_id = p.pid
            JOIN product_categories pc 
                ON pc.id = p.cid
            JOIN product_parent_categories ppc 
                ON ppc.id = pc.parent_id
            WHERE p.cid IN (" . implode(',', $placeholders) . ")";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

/* Rueckgabe aller Produkte der Unterkategorien */    
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($products as &$product) {
    if (!empty($product['bild'])) {
        $product['bild'] = BASE_URL . $product['bild'];
    }
}

return $products;
}


}

// $products = ProductModel::getAllItemsOfCategory(3); // z. B. Kategorie "Vegan Whey"
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
