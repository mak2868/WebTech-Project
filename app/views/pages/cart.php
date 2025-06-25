<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../config/config.php';
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="UTF-8">
  <title>XPN | Warenkorb</title>

  <!-- CSS -->
  <link rel="stylesheet" href="<?= BASE_URL ?>/css/global.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/css/cart.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/css/navbar_transparent.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/css/footer.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/css/cookieBanner.css">


  <!-- JS -->
  <script src="<?= BASE_URL ?>/js/navbar.js" defer></script>
  <script src="<?= BASE_URL ?>/js/cart.js" defer></script>
  <script src="<?= BASE_URL ?>/js/cookieBanner.js" defer></script>

  <!-- Head-Datei -->
  <?php include __DIR__ . '/../layouts/head.php'; ?>


  <script>
    const isLoggedIn = <?= !empty($_SESSION['user_id']) ? 'true' : 'false' ?>;
    localStorage.setItem('isLoggedIn', isLoggedIn);

    window.addEventListener('DOMContentLoaded', () => {
      if (isLoggedIn) {
        loadServerCart();
      } else {
        renderCart();
      }
    });

    function goToCheckout() {
  window.location.href = "index.php?page=checkout";
}

    
  </script>
</head>

<body>
  <?php include __DIR__ . '/../layouts/navbar.php'; ?>

  <main style="padding-top: 80px; min-height: 60vh" class="container">
    <div class="cart-header">
      <h2>Warenkorb</h2>
      <?php if (!empty($_SESSION['user_id'])): ?>
        <button class="removeAllBtn" onclick="clearServerCart()">
          <img src="<?= BASE_URL ?>/images/removeIcon.svg" alt="Alle Produkte entfernen">
        </button>
      <?php else: ?>
        <button class="removeAllBtn" onclick="removeAllItemsFromCart()">
          <img src="<?= BASE_URL ?>/images/removeIcon.svg" alt="Alle Produkte entfernen">
        </button>
      <?php endif; ?>
    <button class="checkout-btn" onclick="goToCheckout()">Zur Kasse</button>
    </div>

    <div class="timerSection">
      <div class="timerText">Produktreservierung läuft ab in:</div>
      <div class="time">00:00</div>
    </div>

    <div class="cart-container" id="cart-items">
      <!-- Inhalte werden dynamisch durch JS gerendert -->
    </div>

    <div id="empty-cart-message" class="empty-cart-info" style="display: none; text-align: center; padding: 4rem 2rem;">
      <h3>🛒 Dein Warenkorb ist leer</h3>
      <a href="<?= BASE_URL ?>/index.php?page=home" class="btn-esn" style="margin-top: 1rem; display: inline-block;">Jetzt shoppen</a>
    </div>

    <div class="cart-footer">
      <h3 id="cart-total">Gesamt: 0 €</h3>
    </div>
  </main>

  <?php include __DIR__ . '/../layouts/cookieBanner.php'; ?>
  <?php include __DIR__ . '/../layouts/footer.php'; ?>
</body>
</html>
