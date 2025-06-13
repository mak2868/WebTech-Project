<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XPN | Whey Protein Choco</title>
    <link rel="stylesheet" href="style/global.css">
    <link rel="stylesheet" href="style/Grid-List.css">
    <link rel="stylesheet" href="style/cart-slide.css">
    <link rel="stylesheet" href="components/Navbar/navbar_transparent.css">
    <link rel="stylesheet" href="components/Footer/footer.css">
    <link rel="stylesheet" href="style/cookieBanner.css">
    <script src="components/Navbar/navbar.js" defer></script>
    <script src="js/cart.js" defer></script>
    <script src="js/wishList.js" defer></script>
    <script src="js/cookieBanner.js" defer></script>
    <script src="js/productFilter.js" defer></script>
</head>

<?php
include 'components/Navbar/navbar.php';

$cid = isset($_GET['cid']) ? (int)$_GET['cid'] : null;
$products = [];
$jsonPath = null;

switch ($cid) {
    case 1: $jsonPath = 'products/Pulver/WheyProteinsList.json';
    break;
    case 2: $jsonPath = 'products/Pulver/IsolatList.json'; 
    break;
    case 3: $jsonPath = 'products/Pulver/VeganList.json';
    break;
    case 4: $jsonPath = 'products/Riegel/ProteinriegelList.json';
    break;
    case 5: $jsonPath = 'products/Riegel/LowCarbList.json';
     break;
    case 6: $jsonPath = 'products/Riegel/VeganList.json';
    break;
    default:
        echo "<p>Ungültige Kategorie-ID.</p>";
        exit;
}

if ($jsonPath && file_exists($jsonPath)) {
    $json = file_get_contents($jsonPath);
    $products = json_decode($json, true);
}
?>

<h1>Unsere Produkte</h1>
  <main>
    <aside class="filter-container">
      <h2>Filter</h2>
      <div class="filter-group">
        <label for="price-range">Max. Preis:</label><br>
        <input type="range" id="price-range" name="price-range" min="0" max="100" value="100">
        <span id="price-value">€100</span>
      </div>
    </aside>

    <div class="grid-container" id="product-grid">
  <?php foreach ($products as $product): ?>
    <div class="grid-item" data-price="<?= $product['price'] ?>">
      <div class="image-wrapper">
        <a href="item.php?cid=<?= $cid ?>&pid=<?= $product['id'] ?>">
          <img src="<?= $product['image'] ?>" alt="<?= $product['name'] ?>" width="250"><br>

          <div class="icons">
            <div class="icon" onclick="addToCart('<?= $product['name'] ?>', '<?= $product['image'] ?>', <?= $product['price'] ?>)">
              <img src="images/shopping-cart.png" alt="In den Warenkorb" />
            </div>
          </div>

          <div class="rating">
            <span class="stars">
              <?= str_repeat('★', floor($product['rating'])) ?>
              <?= str_repeat('☆', 5 - floor($product['rating'])) ?>
            </span>
            <span class="reviews">(<?= $product['reviews'] ?>)</span>
          </div>

          <b><?= $product['name'] ?></b>
        </a><br>

        <b><?= number_format($product['price'], 2, ',', '.') ?> € / 500g</b>
      </div>
    </div>
  <?php endforeach; ?>
</div>
  </main>

<script defer>
    window.onload = () => {
        window.intermediateStepRenderItemSite(<?= json_encode($cid) ?>);
    };
</script>

  <?php include 'cartSlider.php'; ?>
  <?php include 'cookieBanner.php'; ?>
  <?php include 'components/Footer/footer.php'; ?>
</body>
</html>

