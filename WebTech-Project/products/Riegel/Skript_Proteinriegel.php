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

// Hilfsfunktion zum Laden und Einfügen von Riegeln
function importBars($pdo, $filename, $barType)
{
    $json = file_get_contents($filename);
    $bars = json_decode($json, true);

    foreach ($bars as $bar) {
        // Hauptprodukt einfügen
        $stmt = $pdo->prepare("INSERT INTO proteinriegel_products
            (cid, pid, name, description, rating, raters_count, status_distribution,
            preparation, recommendation, laboratory)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->execute([
            $bar['cid'],
            $bar['pid'],
            $bar['name'] ?? null,
            $bar['description'] ?? null,
            $bar['rating'] ?? null,
            $bar['ratersCount'] ?? null,
            $bar['statusDistribution'] ?? null,
            $bar['usage']['preparation'] ?? null,
            $bar['usage']['recommendation'] ?? null,
            $bar['laboratory'] ?? null
        ]);

        $productId = $bar['pid'];

        // Preis & Größen
        foreach ($bar['availableSizes'] as $i => $size) {
            $price = $bar['priceWithTax'][$i];
            $stmt = $pdo->prepare("INSERT INTO proteinriegel_sizes_prices (product_id, size, price_with_tax) VALUES (?, ?, ?)");
            $stmt->execute([$productId, $size, $price]);
        }

        // Nährwerte
        $nutrients = $bar['substance']['nutrients'] ?? [];
        $stmt = $pdo->prepare("INSERT INTO proteinriegel_nutrients
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

        // Zutaten
        $stmt = $pdo->prepare("INSERT INTO proteinriegel_ingredients (product_id, ingredients, allergens) VALUES (?, ?, ?)");
        $stmt->execute([
            $productId,
            $bar['substance']['ingredients'] ?? null,
            $bar['substance']['allergens'] ?? null
        ]);

        // Bilder
        $pics = $bar['pics'] ?? [];
        $stmt = $pdo->prepare("INSERT INTO proteinriegel_pictures (product_id, top_pic, product_pic1, product_pic2, product_pic3, small_pic) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $productId,
            $pics['topPic'] ?? null,
            $pics['productPic1'] ?? null,
            $pics['productPic2'] ?? null,
            $pics['productPic3'] ?? null,
            $pics['smallPic'] ?? null
        ]);

        // BeschreibungDetails
        if (!empty($bar['descriptionDetails'])) {
            $detail1 = $bar['descriptionDetails'][0] ?? null;
            $detail2 = $bar['descriptionDetails'][1] ?? null;
            $stmt = $pdo->prepare("INSERT INTO proteinriegel_descriptions (product_id, detail1, detail2) VALUES (?, ?, ?)");
            $stmt->execute([$productId, $detail1, $detail2]);
        }
    }
}

// Importvorgang starten
importBars($pdo, 'Proteinriegel.json', 'normal');
importBars($pdo, 'Vegan.json', 'vegan');
importBars($pdo, 'LowCarb.json', 'lowcarb');

echo "Proteinriegel-Import abgeschlossen.";
