<!--         Warenkorb-Slider      -->
<!-- ============================= -->

<!-- Der gesamte Slider (standardmäßig ausgeblendet per CSS) -->
<div id="cartSlider" class="cart-slider">

    <!-- Kopfzeile des Sliders mit Titel und Schließen-Button -->
    <div class="cart-header">
        <span>🛒 Produkt hinzugefügt</span> <!-- Textanzeige -->
        <button class="close-btn" onclick="closeCart()">×</button> <!-- Schließen-Symbol -->
    </div>

    <!-- Hauptinhalt des Sliders -->
    <div class="cart-content">

        <!-- Hier wird per JavaScript das aktuell hinzugefügte Produkt angezeigt -->
        <div id="cartItems"></div>

        <!-- Aktions-Buttons unten im Slider -->
        <div class="cart-actions">
            <button onclick="closeCart()">Weiter einkaufen</button> <!-- Schließt den Slider -->
            <button class="go-cart" onclick="window.location.href='cart.php'">Zum Warenkorb</button> <!-- Link zur Warenkorbseite -->
        </div>

    </div>
</div>