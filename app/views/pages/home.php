<?php require_once __DIR__ . '/../../config/config.php'; ?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hero Section</title>

    <!-- CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/global.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/index.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/index-darkmode.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/navbar_transparent.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/footer.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/cart-slide.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/cookieBanner.css">

    <!-- JS -->
    <script src="<?= BASE_URL ?>/js/navbar.js" defer></script>
    <script src="<?= BASE_URL ?>/js/cookieBanner.js" defer></script>
</head>

<body class="dark-page">
<?php include '../app/views/layouts/navbar.php'; ?>

<main>
  <!-- Hero Section -->
  <section class="hero">
    <div class="hero-content">
      <h1>Made for Athletes</h1>
      <p class="hero-paragraph">Nutrition that tastes as good as it works</p>
      <button class="button" onclick="window.location.href='?page=ProteinpulverList'">Shop now</button>
    </div>
  </section>

  <!-- Logo Section -->
  <section class="logo-section">
    <div class="logo-section-inner">
      <p>Top brands trust us for quality that delivers</p>
      <div class="logo-slider">
        <div class="logos">
          <?php
            $logos = [
              'Foodspring_Logo.png', 'GoldsGym_Logo.png', 'MyProtein_Logo.png',
              'Dm_Logo.png', 'JohnReed_Logo.png', 'Edeka_Logo.png'
            ];
            // Logos mehrfach für Endlosschleife
            for ($i = 0; $i < 3; $i++) {
              foreach ($logos as $logo) {
                echo '<img src="' . BASE_URL . '/images/' . $logo . '" alt="' . $logo . '" class="logo">';
              }
            }
          ?>
        </div>
      </div>
    </div>
  </section>

  <!-- Banner Section -->
  <section class="banner-section">
    <div class="banner-container">
      <div class="banner-image">
        <img src="<?= BASE_URL ?>/images/Proteinriegel_Banner.png" alt="Banner Image">
      </div>
      <div class="banner-text">
        <h2>Deutschlands Nr. 1 Proteinriegel</h2>
        <p>Protein Snack für maximalen Muskelaufbau.</p>
        <button class="button" onclick="window.location.href='?page=ProteinpulverList'">20% Aktion</button>
      </div>
    </div>
  </section>

  <!-- Bestseller Section -->
  <section class="bestseller-section">
    <div class="container">
      <h2>Unsere Bestseller</h2>
      <p>Besonders beliebt</p>
      <div class="product-grid">
        <?php foreach ($produkte as $produkt): ?>
          <div class="product-card">
            <div class="badge">Bestseller</div>
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
    </div>
  </section>
</main>

<script src="<?= BASE_URL ?>/js/cart.js"></script>
<?php include __DIR__ . '/../layouts/cartSlider.php'; ?>
<?php include __DIR__ . '/../layouts/cookieBanner.php'; ?>
<?php include __DIR__ . '/../layouts/footer.php'; ?>


</body>
</html>
