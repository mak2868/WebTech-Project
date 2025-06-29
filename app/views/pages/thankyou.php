<?php
/**
 * @author: Merzan Köse
 */
?>

<?php
// Session starten, falls noch nicht aktiv
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../config/config.php';

// Nach Abschluss der Bestellung: Gutschein und Cart-Total aus der Session entfernen
unset($_SESSION['coupon']);
unset($_SESSION['cart_total']);
?>

<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <title>Danke für deine Bestellung</title>

  <!-- Stylesheets -->
  <link rel="stylesheet" href="<?= BASE_URL ?>/css/global.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/css/cart.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/css/navbar_transparent.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/css/footer.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/css/cookieBanner.css">

  <!-- JavaScript-Dateien -->
  <script src="<?= BASE_URL ?>/js/navbar.js" defer></script>
  <script src="<?= BASE_URL ?>/js/cart.js" defer></script>
  <script src="<?= BASE_URL ?>/js/cookieBanner.js" defer></script>

   <!-- Head-Datei -->
  <?php include __DIR__ . '/../layouts/head.php'; ?>

  
</head>

<body>
  <!-- Navigationsleiste -->
  <?php include __DIR__ . '/../layouts/navbar.php'; ?>

  <!-- Hauptinhalt -->
  <main style="text-align:center; padding: 4rem;">
    <h1>Vielen Dank für deine Bestellung!</h1>
    <p>Deine Bestellung wurde erfolgreich aufgegeben.</p>
    <a href="index.php" class="btn" style="margin-top: 2rem;">Zurück zur Startseite</a>
  </main>

  <!-- Footer und Cookie-Banner -->
  <?php include __DIR__ . '/../layouts/footer.php'; ?>
  <?php include __DIR__ . '/../layouts/cookieBanner.php'; ?>
</body>
</html>
