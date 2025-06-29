<!-- Erstellt von Merzan Köse-->

<?php require_once __DIR__ . '/../../config/config.php'; ?>

<!-- Im <head> -->
<link rel="stylesheet" href="css/cart-slide.css">
<!-- Optional für Icons -->
<script src="<?= BASE_URL ?>/js/cart.js" defer></script>

<!-- Im <body> -->
<div id="cartSlider" class="cart-slider">
  <div class="cart-header">
    <span class="header-title">Warenkorb</span>
    <img id="closeCartBtn" class="close-x" src="<?= BASE_URL ?>/images/CloseButtonSlider.png" alt="Schließen">
  </div>


  <div class="cart-content">
    <div id="cartItems"></div>
  </div>

  <div class="cart-summary">
    <div class="summary-row total"><b>Gesamt:</b> <span id="cartTotal">0,00 €</span></div>
    <button class="checkout-btn" onclick="window.location.href='index.php?page=cart'">Jetzt kaufen</button>
  </div>
</div>

<!-- Am Ende des <body>: Slider rendern -->
<script>
  document.addEventListener('DOMContentLoaded', () => {
    renderCartSlider();
  });
</script>