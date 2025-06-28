<!-- erstellt von: Nick Zetzmann -->

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
  <script src="<?= BASE_URL ?>/js/loadStars.js" defer></script>


   <!-- Head-Datei -->
    <?php include __DIR__ . '/../layouts/head.php'; ?>

    
</head>


  <?php include __DIR__ . '/../layouts/navbar.php'; ?>

  <main>
    <!-- Filter- und Sortierleiste -->
    <div class="filter-sort-bar">
      <div class="filter-dropdown">
        <button id="filter-toggle"> Filter</button>
      <div class="filter-content" id="filter-content">
        <label for="price-range">Max. Preis:</label>
        <div class="slider-wrapper">
         <input type="range" id="price-range" min="0" max="230" value="180">
        <div id="price-value">180€</div>
      </div>
      </div>
    </div>

    <div class="sort-dropdown">
      <label for="sort-select">Sortieren:</label>
      <select id="sort-select">
        <option> Bitte wählen </option>
        <option value="preis-asc">Preis ↑</option>
        <option value="preis-desc">Preis ↓</option>
        <option value="name-asc">Name A-Z</option>
        <option value="name-desc">Name Z-A</option>
      </select>
    </div>
  </div>

    <div class="container">
      <div class="product-grid">
      <?php foreach ($produkte as $produkt): ?>
        <div class="product-card" data-price="<?= floatval($produkt['preis']) ?>">
          <div class="image-wrapper">
            <a href="<?= BASE_URL ?>/index.php?page=item&parent=<?= urlencode($produkt['parent_id']) ?>&cid=<?= urlencode($produkt['cid']) ?>&pid=<?= urlencode($produkt['pid']) ?>">
              <img src="<?= BASE_URL . '/' . ltrim($produkt['bild'], '/') ?>" alt="<?= htmlspecialchars($produkt['name']) ?>">
            </a>
            <div class="icons">
              <div class="icon" onclick="addToCart(
                '<?= htmlspecialchars($produkt['name']) ?>',
                '<?= htmlspecialchars($produkt['bild']) ?>',
                <?= floatval($produkt['preis']) ?>,
                '<?= htmlspecialchars($produkt['size']) ?>'
              )">
                <img src="<?= BASE_URL ?>/images/einkaufswagen.png" alt="In den Warenkorb" />
              </div>
      </div>
          </div>
          <p class="flavor"><?= htmlspecialchars($produkt['geschmack'] ?? '') ?></p>
          <h3 class="title"><?= htmlspecialchars($produkt['name']) ?></h3>
          <p class="size"><?= htmlspecialchars($produkt['size'] ?? '') ?> g</p>
          <div class="rating">
            <div class="stars" data-rating="<?= htmlspecialchars($produkt['rating'] ?? '0') ?>"></div>
            <span class="reviews">(<?= htmlspecialchars($produkt['raters_count'] ?? '0') ?>)</span>
          </div>
          <p class="desc"><?= htmlspecialchars($produkt['description'] ?? '') ?></p>
          
          <p class="price"><?= number_format(floatval($produkt['preis']), 2, '.') ?>€</p>
        </div>
      <?php endforeach; ?>
    </div>
      </div>
      </div>
  </main>

  <?php include __DIR__ . '/../layouts/cartSlider.php'; ?>
  <?php include __DIR__ . '/../layouts/cookieBanner.php'; ?>
  <?php include __DIR__ . '/../layouts/footer.php'; ?>
</body>
</html>

