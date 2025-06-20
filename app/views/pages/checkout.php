<?php
require_once __DIR__ . '/../../config/config.php';
require_once '../app/models/UserModel.php';
require_once '../app/lib/DB.php';

// Weiterleitung zur Login-Seite, wenn nicht eingeloggt
if (!isset($_SESSION['user'])) {
    header('Location: index.php?page=login&redirect=checkout');
    exit;
}

$user = $_SESSION['user'];
$userId = $user['id'];
$address = UserModel::getUserAddressByUserId($userId);

// Bestellung abschicken
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    $db = DB::getConnection();
    $cart = json_decode($_POST['cart_data'], true);
    $total = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));

    // Bestellung speichern
    $stmt = $db->prepare("INSERT INTO orders (user_id, order_date, status, total, shipping_address_id) VALUES (?, NOW(), 'offen', ?, ?)");
    $stmt->execute([$userId, $total, $address['id']]);
    $orderId = $db->lastInsertId();

    // Einzelne Produkte speichern
    $stmtItem = $db->prepare("INSERT INTO order_items (order_id, product_type, product_id, quantity, price) VALUES (?, ?, ?, ?, ?)");
    foreach ($cart as $item) {
        $stmtItem->execute([$orderId, $item['type'], $item['id'], $item['quantity'], $item['price']]);
    }

    // Statusverlauf speichern
    $stmtStatus = $db->prepare("INSERT INTO order_status_history (order_id, status, changed_at) VALUES (?, 'offen', NOW())");
    $stmtStatus->execute([$orderId]);

    echo '<div class="success">Bestellung erfolgreich! <a href="index.php">Zur Startseite</a></div>';
    exit;
}
?>

<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <script src="js/cart.js" defer></script>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/global.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/index.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/index-darkmode.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/navbar_transparent.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/footer.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/cookieBanner.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/checkout.css">

    <script src="<?= BASE_URL ?>/js/navbar.js" defer></script>
    <script src="<?= BASE_URL ?>/js/cookieBanner.js" defer></script>
    <script src="<?= BASE_URL ?>/js/footer.js" defer></script>
    <script src="<?= BASE_URL ?>/js/loadStars.js" defer></script>

</head>

<body>
    <?php include __DIR__ . '/../layouts/navbar.php'; ?>
    <main class="checkout-container">
  <!-- Linke Seite: Benutzerdaten -->
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

      <!-- Hidden und Button -->
      <input type="hidden" name="cart_data" id="cart_data">
      <button type="submit" name="place_order" class="checkout-btn">Jetzt bestellen</button>
    </form>
  </section>

        <!-- Rechte Seite: Warenkorb -->
        <section class="checkout-summary">
            <h2>Dein Warenkorb</h2>

            <div class="promo-code">
                <input type="text" placeholder="Rabattcode oder Gutschein" id="promoCode">
                <button onclick="applyPromo()">Anwenden</button>
            </div>

            <div id="cartItems"></div>

            <div class="summary-total">
                Gesamt: <span id="cartTotal">0,00 €</span>
            </div>
        </section>
    </main>

    <script>
        window.IS_LOGGED_IN = <?= isset($_SESSION['user']) ? 'true' : 'false' ?>;
    </script>
    <script src="js/checkout.js" defer></script>
    <?php include __DIR__ . '/../layouts/cookieBanner.php'; ?>
    <?php include __DIR__ . '/../layouts/footer.php'; ?>
</body>

</html>