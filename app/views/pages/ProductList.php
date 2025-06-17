<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XPN | Produkte</title>

    <!-- CSS -->
  <link rel="stylesheet" href="<?= BASE_URL ?>/css/global.css" />
  <link rel="stylesheet" href="<?= BASE_URL ?>/css/Grid-List.css" />
  <link rel="stylesheet" href="<?= BASE_URL ?>/css/navbar_transparent.css" />
  <link rel="stylesheet" href="<?= BASE_URL ?>/css/footer.css" />
  <link rel="stylesheet" href="<?= BASE_URL ?>/css/cookieBanner.css" />
  <link rel="stylesheet" href="<?= BASE_URL ?>/css/cart-slide.css" />

  <!-- JS -->
  <script src="<?= BASE_URL ?>/js/navbar.js" defer></script>
  <script src="<?= BASE_URL ?>/js/cookieBanner.js" defer></script>
  <script src="<?= BASE_URL ?>/js/cart.js" defer></script>
  <script src="<?= BASE_URL ?>/js/wishList.js" defer></script>
  <script src="<?= BASE_URL ?>/js/productFilter.js" defer></script>
</head>


  <?php include __DIR__ . '/../layouts/navbar.php'; ?>

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

    <div class="container">
      <div class="product-grid">
        <?php foreach ($produkte as $produkt): ?>
          <div class="product-card">
            <div class="image-wrapper">
              <img src="<?= BASE_URL ?>/<?= htmlspecialchars($produkt['bild']) ?>" alt="<?= htmlspecialchars($produkt['name']) ?>">
              <div class="icons">
                <div class="icon" onclick="addToCart(
                  '<?= htmlspecialchars($produkt['name']) ?>',
                  '<?= BASE_URL ?>/images/<?= htmlspecialchars($produkt['bild']) ?>',
                  <?= floatval($produkt['preis']) ?>
                )">
                  <img src="<?= BASE_URL ?>/images/shopping-cart.png" alt="In den Warenkorb" />
                </div>
              </div>
            </div>
            <p class="flavor"><?= htmlspecialchars($produkt['geschmack'] ?? '') ?></p>
            <h3 class="title"><?= htmlspecialchars($produkt['name']) ?></h3>
            <p class="desc"><?= htmlspecialchars($produkt['description'] ?? '') ?></p>
            <div class="rating">
              <span class="stars">★★★★☆</span>
              <span class="reviews">(5800)</span>
            </div>
            <p class="price">€<?= number_format(floatval($produkt['preis']), 2, ',', '.') ?></p>
          </div>
        <?php endforeach; ?>
      </div>
  </main>

  <?php include __DIR__ . '/../layouts/cartSlider.php'; ?>
  <?php include __DIR__ . '/../layouts/cookieBanner.php'; ?>
  <?php include __DIR__ . '/../layouts/footer.php'; ?>
</body>
</html>

