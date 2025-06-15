<?php
// DB-Verbindung herstellen
$host = 'localhost';
$db   = 'webtech-projekt';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("Verbindung fehlgeschlagen: " . $e->getMessage());
}

// Hilfsfunktion zum Laden und Einfügen von Produkten
function importProducts($pdo, $filename, $proteinType)
{
    $json = file_get_contents($filename);
    $products = json_decode($json, true);

    foreach ($products as $product) {
        // Hauptprodukt einfügen
        $stmt = $pdo->prepare("INSERT INTO proteinpulver_products
            (cid, pid, name, description, rating, raters_count, status_distribution,
            preparation, recommendation, tip, laboratory)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->execute([
            $product['cid'],
            $product['pid'],
            $product['name'] ?? null,
            $product['description'] ?? null,
            $product['rating'] ?? null,
            $product['ratersCount'] ?? null,
            $product['statusDistribution'] ?? null,
            $product['usage']['preparation'] ?? null,
            $product['usage']['recommendation'] ?? null,
            $product['usage']['tip'] ?? null,
            $product['laboratory'] ?? null
        ]);

        $productId =  $product['pid'];

        // Preis & Größen
        foreach ($product['availableSizes'] as $i => $size) {
            $price = $product['priceWithTax'][$i];
            $stmt = $pdo->prepare("INSERT INTO proteinpulver_sizes_prices (product_id, size, price_with_tax) VALUES (?, ?, ?)");
            $stmt->execute([$productId, (int)$size, $price]);
        }

        // Nährwerte
        $nutrients = $product['substance']['nutrients'] ?? [];
        $stmt = $pdo->prepare("INSERT INTO proteinpulver_nutrients
            (product_id, energy, fat, saturates, carbohydrates, sugars, fibre, protein, salt)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $productId,
            $nutrients['Energy'] ?? null,
            $nutrients['Fat'] ?? null,
            $nutrients['of which saturates'] ?? null,
            $nutrients['Carbohydrates'] ?? null,
            $nutrients['of which sugars'] ?? null,
            $nutrients['Fibre'] ?? null,
            $nutrients['Protein'] ?? null,
            $nutrients['Salt'] ?? null
        ]);

        // Aminosäuren (falls vorhanden)
        if (!empty($product['substance']['aminoAcids'])) {
            $acids = $product['substance']['aminoAcids'];
            $stmt = $pdo->prepare("INSERT INTO proteinpulver_amino_acids
                (product_id, alanine, arginine, aspartic_acid, cysteine, glutamic_acid, glycine, histidine,
                 isoleucine, leucine, lysine, methionine, phenylalanine, proline, serine, threonine,
                 tryptophan, tyrosine, valine)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $productId,
                $acids['Alanine'] ?? null,
                $acids['Arginine'] ?? null,
                $acids['Aspartic acid'] ?? null,
                $acids['Cysteine'] ?? null,
                $acids['Glutamic acid'] ?? null,
                $acids['Glycine'] ?? null,
                $acids['Histidine'] ?? null,
                $acids['Isoleucine'] ?? null,
                $acids['Leucine'] ?? null,
                $acids['Lysine'] ?? null,
                $acids['Methionine'] ?? null,
                $acids['Phenylalanine'] ?? null,
                $acids['Proline'] ?? null,
                $acids['Serine'] ?? null,
                $acids['Threonine'] ?? null,
                $acids['Tryptophan'] ?? null,
                $acids['Tyrosine'] ?? null,
                $acids['Valine'] ?? null
            ]);
        }

        // Zutaten
        $stmt = $pdo->prepare("INSERT INTO proteinpulver_ingredients (product_id, ingredients, allergens) VALUES (?, ?, ?)");
        $stmt->execute([
            $productId,
            $product['substance']['ingredients'] ?? null,
            $product['substance']['allergens'] ?? null
        ]);

        // BeschreibungDetails
        if (!empty($product['descriptionDetails'])) {
            $detail1 = $product['descriptionDetails'][0] ?? null;
            $detail2 = $product['descriptionDetails'][1] ?? null;
            $stmt = $pdo->prepare("INSERT INTO proteinpulver_descriptions (product_id, detail1, detail2) VALUES (?, ?, ?)");
            $stmt->execute([$productId, $detail1, $detail2]);
        }

        // Rezepte
        if (!empty($product['usage']['recipes'])) {
        foreach ($product['usage']['recipes'] as $recipe) {
        // Rezeptdaten einfügen
        $stmt = $pdo->prepare("INSERT INTO proteinpulver_recipes (product_id, title, short_title, portion) VALUES (?, ?, ?, ?)");
        $stmt->execute([
            $productId,
            $recipe['title'] ?? null,
            $recipe['shortTitle'] ?? null,
            $recipe['portion'] ?? null
        ]);

        $recipeId = $pdo->lastInsertId();

        // Zutaten einfügen
        if (!empty($recipe['ingredients'])) {
            $stmtIng = $pdo->prepare("INSERT INTO proteinpulver_recipe_ingredients (recipe_id, ingredient) VALUES (?, ?)");
            foreach ($recipe['ingredients'] as $ingredient) {
                $stmtIng->execute([$recipeId, $ingredient]);
            }
        }

        // Zubereitungsschritte einfügen
        if (!empty($recipe['preparation'])) {
            $stmtPrep = $pdo->prepare("INSERT INTO proteinpulver_recipe_steps (recipe_id, step_number, instruction) VALUES (?, ?, ?)");
            foreach ($recipe['preparation'] as $index => $step) {
                $stmtPrep->execute([$recipeId, $index + 1, $step]);
            }
        }
    }
}



        // Bilder
        $pics = $product['pics'] ?? [];
        $stmt = $pdo->prepare("INSERT INTO proteinpulver_pictures (product_id, top_pic, product_pic1, product_pic2, product_pic3, small_pic) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $productId,
            $pics['topPic'] ?? null,
            $pics['productPic1'] ?? null,
            $pics['productPic2'] ?? null,
            $pics['productPic3'] ?? null,
            $pics['smallPic'] ?? null
        ]);
    }
}

// Importvorgang starten
importProducts($pdo, 'WheyProteins.json', 'whey');
importProducts($pdo, 'Isolat.json', 'isolat');
importProducts($pdo, 'Vegan.json', 'vegan');

echo "Proteinpulver Import abgeschlossen.";
?>
