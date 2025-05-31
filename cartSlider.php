<!--         Warenkorb-Slider      -->
<!-- ============================= -->
<div id="cartSlider" class="cart-slider">
  <div class="cart-header">
    <span>🛒 Warenkorb</span>
    <button class="close-btn" onclick="closeCart()">×</button>
  </div>

  <div class="cart-content">
    <!-- Rabattcode -->
    <div class="cart-promo">
      <input type="text" id="promoCode" placeholder="Gutscheincode" />
      <button onclick="applyPromo()">Anwenden</button>
    </div>

    <!-- Cart-Items -->
    <div id="cartItems"></div>
  </div>

  <div class="cart-summary">
    <div class="summary-row">Du sparst: <span id="cartSavings">0,00 €</span></div>
    <div class="summary-row total"><b>Gesamt:</b> <span id="cartTotal">0,00 €</span></div>
    <button class="checkout-btn">Jetzt kaufen</button>
  </div>
</div>
