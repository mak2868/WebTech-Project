<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <title>Warenkorb</title>
  <link rel="stylesheet" href="style/global.css">
  <link rel="stylesheet" href="style/cart.css">
  <link rel="stylesheet" href="components/Navbar/navbar_transparent.css">
  <script src="components/Navbar/navbar.js" defer></script>
  <script src="js/cart.js" defer></script>

</head>
<body onload="renderCart()">
  <?php include 'components/Navbar/navbar.php'; ?>

<main style="padding-top: 80px" class="container">
  <div class="cart-header">
    <h2>Warenkorb</h2>
    <button class="checkout-btn">Zur Kasse</button>
  </div>
  <div class="cart-container" id="cart-items">
    <!-- Produkte werden dynamisch eingefügt -->
  </div>

  <div class="cart-footer">
    <h3 id="cart-total">Gesamt: 0 €</h3>
  </div>
  <?php include 'components/Footer/footer.php'; ?>
</main>
</body>
</html>
