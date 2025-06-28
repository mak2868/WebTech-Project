<?php
require_once __DIR__ . '/../../config/config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Session nur starten, wenn sie noch nicht läuft
}
//Author: Merzan
// Aktuelle Gutschein-Daten (für Anzeige und JS)
$coupon = $_SESSION['coupon'] ?? null;

// Optional: Fehlermeldung oder Erfolg (kommt aus Controller)
$message = $message ?? null;
?>

<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <!--MetatTag das uns  respensive design ermöglicht-->
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Checkout</title>

  <!-- CSS -->
  <link rel="stylesheet" href="<?= BASE_URL ?>/css/global.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/css/index.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/css/index-darkmode.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/css/navbar_transparent.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/css/footer.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/css/cookieBanner.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/css/checkout.css">
  <script>
  window.BASE_URL = "<?= BASE_URL ?>";
</script>


  <!-- JS -->
  <script src="<?= BASE_URL ?>/js/navbar.js" defer></script>
  <script src="<?= BASE_URL ?>/js/cookieBanner.js" defer></script>
  <script src="<?= BASE_URL ?>/js/footer.js" defer></script>
  <script src="<?= BASE_URL ?>/js/loadStars.js" defer></script>
  <script src="js/checkout.js" defer></script>
  <script src="<?= BASE_URL ?>/js/merge-cart.js" defer></script>


  <!-- Head-Datei -->
  <?php include __DIR__ . '/../layouts/head.php'; ?>

  

  <!-- Daten für JavaScript bereitstellen -->
  <script>
    window.SESSION_COUPON = <?= json_encode($coupon) ?>;
    window.IS_LOGGED_IN = <?= isset($_SESSION['user']) ? 'true' : 'false' ?>;
  </script>
</head>

<body>
  <?php include __DIR__ . '/../layouts/navbar.php'; ?>

  <main class="checkout-container">

    <!-- Linke Seite: Lieferadresse -->
    <section class="checkout-form">
      <h2>Lieferung</h2>
      <form method="post" id="checkoutForm">
        <!-- Vorname & Nachname -->
        <div class="form-row">
          <div class="form-group">
            <label>Vorname:</label>
            <input type="text" name="first_name" value="<?= htmlspecialchars($user['first_name'] ?? '') ?>" required>
          </div>
          <div class="form-group">
            <label>Nachname:</label>
            <input type="text" name="last_name" value="<?= htmlspecialchars($user['last_name'] ?? '') ?>" required>
          </div>
        </div>

        <!-- Straße -->
        <div class="form-group">
          <label>Straße:</label>
          <input type="text" name="street" value="<?= htmlspecialchars($address['street'] ?? '') ?>" required>
        </div>

        <!-- PLZ & Ort -->
        <div class="form-row">
          <div class="form-group">
            <label>PLZ:</label>
            <input type="text" name="zip" value="<?= htmlspecialchars($address['postal_code'] ?? '') ?>" required>
          </div>
          <div class="form-group">
            <label>Ort:</label>
            <input type="text" name="city" value="<?= htmlspecialchars($address['city'] ?? '') ?>" required>
          </div>
        </div>

        <!-- Land -->
        <div class="form-group">
          <label>Land:</label>
          <input type="text" name="country" value="<?= htmlspecialchars($address['country'] ?? 'Deutschland') ?>" required>
        </div>

        <!-- Versteckte Daten und Button -->
        <input type="hidden" name="cart_data" id="cart_data">
        <button type="submit" name="place_order" class="checkout-btn">Jetzt bestellen</button>
      </form>
    </section>

    <!-- Rechte Seite: Warenkorbübersicht -->
    <section class="checkout-summary">
      <h2>Dein Warenkorb</h2>

      <!-- Gutschein-Eingabe -->
      <form method="post" class="promo-code">
        <?php if (!empty($message)): ?>
          <p style="color: <?= str_contains($message, 'erfolgreich') ? 'green' : 'red' ?>;">
            <?= htmlspecialchars($message) ?>
          </p>
        <?php endif; ?>

        <input type="text" name="couponCode" placeholder="Gutscheincode">
        <button type="submit" name="apply_coupon">Anwenden</button>
      </form>

      <!-- Produkte -->
      <div id="cartItems"></div>

      <!-- Rabattcode anzeigen, falls aktiv -->
      <?php if (isset($coupon)): ?>
        <div class="summary-discount">
          Rabattcode „<?= htmlspecialchars($coupon['code']) ?>“ angewendet
        </div>
      <?php endif; ?>

      <!-- Ersparnis -->
      <div class="summary-savings" id="cartSavings"></div>

      <!-- Gesamtbetrag -->
      <div class="summary-total">
        Gesamt: <span id="cartTotal">0,00 €</span>
      </div>
    </section>
  </main>

  <?php include __DIR__ . '/../layouts/cookieBanner.php'; ?>
  <?php include __DIR__ . '/../layouts/footer.php'; ?>
</body>
</html>
