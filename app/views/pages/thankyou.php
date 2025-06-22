<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../config/config.php';
unset($_SESSION['coupon']); // Rabatt zurücksetzen
unset($_SESSION['cart_total']); // Optional: total zurücksetzen
?>

<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <title>Danke für deine Bestellung</title>
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
  
</head>
<body>
  <?php include __DIR__ . '/../layouts/navbar.php'; ?>
  <main style="text-align:center; padding: 4rem;">
    <h1>Vielen Dank für deine Bestellung!</h1>
    <p>Deine Bestellung wurde erfolgreich aufgegeben.</p>
    <a href="index.php" class="btn" style="margin-top: 2rem;">Zurück zur Startseite</a>
  </main>
  <?php include __DIR__ . '/../layouts/footer.php'; ?>
   <?php include __DIR__ . '/../layouts/cookieBanner.php'; ?>
</body>
</html>
