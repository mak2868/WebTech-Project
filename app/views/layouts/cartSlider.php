<?php require_once __DIR__ . '/../../config/config.php'; ?>

<!-- Im <head> -->
<link rel="stylesheet" href="css/cart-slide.css">
<!-- Optional für Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="<?= BASE_URL ?>/js/cart.js" defer></script>

<!-- Im <body> -->
<div id="cartSlider" class="cart-slider">
  <div class="cart-header">
    <span class="header-title">Warenkorb</span>
    <i class="fa-solid fa-xmark close-icon" aria-label="Schließen"></i>
  </div>

  <div class="cart-promo">
    <input type="text" id="promoCode" placeholder="Gutscheincode" />
    <button onclick="applyPromo()">Anwenden</button>
  </div>

  <div class="cart-content">
    <div id="cartItems"></div>
  </div>

  <div class="cart-summary">
    <div class="summary-row">Du sparst: <span id="cartSavings">0,00 €</span></div>
    <div class="summary-row total"><b>Gesamt:</b> <span id="cartTotal">0,00 €</span></div>
    <button class="checkout-btn">Jetzt kaufen</button>
  </div>
</div>

<!-- Am Ende des <body>: Slider rendern -->
<script>
  document.addEventListener('DOMContentLoaded', () => {
    renderCartSlider();
  });
</script>
