<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


require_once __DIR__ . '/../../config/config.php';
?>


<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XPN | Home</title>

    <!-- CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/global.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/home.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/navbar_transparent.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/footer.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/cart-slide.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/cookieBanner.css">


    <!-- JS -->
    <script src="<?= BASE_URL ?>/js/navbar.js" defer></script>
    <script src="<?= BASE_URL ?>/js/cookieBanner.js" defer></script>
    <script src="<?= BASE_URL ?>/js/loadStars.js" defer></script>
    <script src="<?= BASE_URL ?>/js/cart.js" defer></script>


    <!-- Head-Datei -->
    <?php include __DIR__ . '/../layouts/head.php'; ?>


    <?php if (!empty($_SESSION['user_id'])): ?>
    <script>
      localStorage.setItem('isLoggedIn', 'true');
    </script>
  <?php else: ?>
    <script>
      localStorage.setItem('isLoggedIn', 'false');
    </script>
  <?php endif; ?>


</head>

<body class="dark-page">
<?php include '../app/views/layouts/navbar.php'; ?>

<main>
  <!-- Hero Section -->


  <!-- Ausnahme für inline-CSS weil Hintergrundbild, wo der Pfad aus DB geladen wird (war zuvor im css) -->
  <section class="hero" style="background-image: url('<?= BASE_URL . $heroBackground ?>');">
  <div class="hero-content">
    <h1>Made for Athletes</h1>
    <p class="hero-paragraph">Nutrition that tastes as good as it works</p>
    <button class="button" onclick="window.location.href='?page=productList&cid=1'">Shop now</button>
  </div>
</section>



  <!-- Logo Section -->
  <section class="logo-section">
    <div class="logo-section-inner">
      <p>Top brands trust us for quality that delivers</p>
      <div class="logo-slider">
        <div class="logos">
          <?php
           for ($i = 0; $i < 3; $i++) {
              foreach ($logos as $logoPath) {
                  echo '<img src="' . BASE_URL . $logoPath . '" alt="Logo" class="logo">';
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
        <img src="<?= BASE_URL . $bannerImage ?>" alt="Banner Image">
      </div>
      <div class="banner-text">
        <h2>Deutschlands Nr. 1 Proteinriegel</h2>
        <p>Protein Snack für maximalen Muskelaufbau.</p>
        <button class="button" onclick="window.location.href='?page=productList&cid=4'">Riegel entdecken</button>
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
            <a href="<?= BASE_URL ?>/index.php?page=item&parent=<?= urlencode($produkt['parent_id']) ?>&cid=<?= urlencode($produkt['cid']) ?>&pid=<?= urlencode($produkt['pid']) ?>">
              <img src="<?= htmlspecialchars($produkt['bild']) ?>" alt="<?= htmlspecialchars($produkt['name']) ?>">
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
          <p class="desc"><?= htmlspecialchars($produkt['description'] ?? '') ?></p>
          <div class="rating">
            <div class="stars" data-rating="<?= htmlspecialchars($produkt['rating'] ?? '0') ?>"></div>
            <span class="reviews">(<?= htmlspecialchars($produkt['raters_count'] ?? '0') ?>)</span>
          </div>
          <p class="price"><?= number_format(floatval($produkt['preis']), 2, ',', '.') ?>€</p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>



</main>

<?php include __DIR__ . '/../layouts/cartSlider.php'; ?>
<?php include __DIR__ . '/../layouts/cookieBanner.php'; ?>
<?php include __DIR__ . '/../layouts/footer.php'; ?>


</body>
</html>
