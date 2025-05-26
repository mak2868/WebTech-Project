<!--erstellt von: Nick Zetzmann (Navbar von Felix Bartel - Slider von Merzan Köse)-->

<!DOCTYPE html>
<html lang="de">

<head>
  <meta charset="UTF-8">
  <title>XPN | Proteinriegel</title>
  <link rel="stylesheet" href="style/global.css">
  <link rel="stylesheet" href="style/Grid-List.css">
  <link rel="stylesheet" href="style/cart-slide.css">
  <link rel="stylesheet" href="components/Navbar/navbar_transparent.css">
  <link rel="stylesheet" href="components/Footer/footer.css">
  <script src="components/Navbar/navbar.js" defer></script>
  <script src="js/cart.js"></script>
  <script src="js/items.js" defer></script>
</head>


<body style="padding-top: 80px">
    <?php include 'components/Navbar/navbar.php'; ?>

  <h1>Unsere Proteinriegel</h1>
<main>
  <aside class="filter-container">
    <h2>Filter</h2>
    <div class="filter-group">
      <label for="price-range">Max. Preis:</label><br>
      <input type="range" id="price-range" name="price-range" min="0" max="10" value="5">
      <span id="price-value">€5</span>
    </div>
    <div class="filter-group">
      <input type="checkbox" id="vegan-filter" name="vegan-filter">
      <label for="vegan-filter">Nur vegane Produkte</label>
    </div>
  </aside>

  <div class="grid-container">

    <div class="grid-item" data-price="1.79" data-vegan="false">
    <div class="image-wrapper">
      <a href="item.php?pid=20">
        <img src="images/Proteinriegel_LowCarb.png" alt="Choco" width="250"><br>
      <div class="icons">
        <div class="icon" onclick="addToCart('Choco Bar', 'images/Proteinriegel_LowCarb.png', 1.79)">
          <img src="images/shopping-cart.png" alt="In den Warenkorb" />
        </div>
      </div>
    </div>
    <div class="rating">
          <span class="stars">★★★★☆</span>
          <span class="reviews">(400)</span>
        </div>
        <b> Choco Bar </b>
      </a><br>
      <b> 1,79 € je Bar </b>
    </div>

    <div class="grid-item" data-price="1.79" data-vegan="true">
    <div class="image-wrapper">
      <a href="item.php?pid=21">
        <img src="images/Proteinriegel_LowCarb.png" alt="Strawberry" width="250"><br>
      <div class="icons">
        <div class="icon" onclick="addToCart('Strawberry Bar', 'images/Proteinriegel_LowCarb.png', 1.79)">
          <img src="images/shopping-cart.png" alt="In den Warenkorb" />
        </div>
      </div>
    </div>
    <div class="rating">
          <span class="stars">★★★★☆</span>
          <span class="reviews">(400)</span>
        </div>
        <b> Strawberry Bar </b>
      </a><br>
      <b> 1,79 € je Bar </b>
    </div>

    <div class="grid-item" data-price="1.99" data-vegan="true">
    <div class="image-wrapper">
      <a href="item.php?pid=22">
        <img src="images/Proteinriegel_LowCarb.png" alt="Vanilla" width="250"><br>
      <div class="icons">
        <div class="icon" onclick="addToCart('Vanilla Bar', 'images/Proteinriegel_LowCarb.png', 1.99)">
          <img src="images/shopping-cart.png" alt="In den Warenkorb" />
        </div>
      </div>
    </div>
    <div class="rating">
          <span class="stars">★★★★☆</span>
          <span class="reviews">(400)</span>
        </div>
        <b> Vanilla Bar </b>
      </a><br>
      <b> 1,99 € je Bar </b>
    </div>
    
    <div class="grid-item" data-price="1.99" data-vegan="false">
    <div class="image-wrapper">
      <a href="item.php?pid=23">
        <img src="images/Proteinriegel_LowCarb.png" alt="Caramel" width="250"><br>
      <div class="icons">
        <div class="icon" onclick="addToCart('Caramel Bar', 'images/Proteinriegel_LowCarb.png', 1.99)">
          <img src="images/shopping-cart.png" alt="In den Warenkorb" />
        </div>
      </div>
    </div>
    <div class="rating">
          <span class="stars">★★★★☆</span>
          <span class="reviews">(400)</span>
        </div>
        <b> Caramel Bar </b>
      </a><br>
      <b> 1,99 € je Bar </b>
    </div>

    <div class="grid-item" data-price="1.79" data-vegan="true">
    <div class="image-wrapper">
      <a href="item.php?pid=24">
        <img src="images/Proteinriegel_LowCarb.png" alt="Banana" width="250"><br>
      <div class="icons">
        <div class="icon" onclick="addToCart('Banana Bar', 'images/Proteinriegel_LowCarb.png', 1.79)">
          <img src="images/shopping-cart.png" alt="In den Warenkorb" />
        </div>
      </div>
    </div>
    <div class="rating">
          <span class="stars">★★★★☆</span>
          <span class="reviews">(400)</span>
        </div>
        <b> Banana Bar </b>
      </a><br>
      <b> 1,79 € je Bar </b>
    </div>

    <div class="grid-item" data-price="1.99" data-vegan="true">
    <div class="image-wrapper">
      <a href="item.php?pid=25">
        <img src="images/Proteinriegel_LowCarb.png" alt="Hazelnut" width="250"><br>
      <div class="icons">
        <div class="icon" onclick="addToCart('Hazelnut Bar', 'images/Proteinriegel_LowCarb.png', 1.99)">
          <img src="images/shopping-cart.png" alt="In den Warenkorb" />
        </div>
      </div>
    </div>
    <div class="rating">
          <span class="stars">★★★★☆</span>
          <span class="reviews">(400)</span>
        </div>
        <b> Hazelnut Bar </b>
      </a><br>
      <b> 1,99 € je Bar </b>
    </div>

    <div class="grid-item" data-price="1.79" data-vegan="false">
    <div class="image-wrapper">
      <a href="item.php?pid=26">
        <img src="images/Proteinriegel_LowCarb.png" alt="Caffee" width="250"><br>
      <div class="icons">
        <div class="icon" onclick="addToCart('Caffe Bar', 'images/Proteinriegel_LowCarb.png', 1.79)">
          <img src="images/shopping-cart.png" alt="In den Warenkorb" />
        </div>
      </div>
    </div>
    <div class="rating">
          <span class="stars">★★★★☆</span>
          <span class="reviews">(400)</span>
        </div>
        <b> Caffee Bar </b>
      </a><br>
      <b> 1,79 € je Bar </b>
    </div>

    <div class="grid-item" data-price="1.79" data-vegan="true">
    <div class="image-wrapper">
      <a href="item.php?pid=27">
        <img src="images/Proteinriegel_LowCarb.png" alt="Raspberry" width="250"><br>
      <div class="icons">
        <div class="icon" onclick="addToCart('Raspberry Bar', 'images/Proteinriegel_LowCarb.png', 1.79)">
          <img src="images/shopping-cart.png" alt="In den Warenkorb" />
        </div>
      </div>
    </div>
    <div class="rating">
          <span class="stars">★★★★☆</span>
          <span class="reviews">(400)</span>
        </div>
        <b> Raspberry Bar </b>
      </a><br>
      <b> 1,79 € je Bar </b>
    </div>

    <div class="grid-item" data-price="2.19" data-vegan="true">
    <div class="image-wrapper">
      <a href="item.php?pid=28">
        <img src="images/Proteinriegel_LowCarb.png" alt="Pistachio" width="250"><br>
      <div class="icons">
        <div class="icon" onclick="addToCart('Pistachio Bar', 'images/Proteinriegel_LowCarb.png', 2.19)">
          <img src="images/shopping-cart.png" alt="In den Warenkorb" />
        </div>
      </div>
    </div>
    <div class="rating">
          <span class="stars">★★★★☆</span>
          <span class="reviews">(400)</span>
        </div>
        <b> Pistachio Bar </b>
      </a><br>
      <b> 2,19 € je Bar </b>
    </div>

  </div>
</main>


<script>
  // Aktualisieren der Anzeige des aktuellen Preises, der mit einem Slider eingestellt werden soll
  const priceRange = document.getElementById('price-range');
  const priceValue = document.getElementById('price-value');
  const veganFilter = document.getElementById('vegan-filter');

  priceRange.addEventListener('input', () => {
    priceValue.textContent = '€' + priceRange.value;
    filterProducts();
  });

  veganFilter.addEventListener('change', () => {
  filterProducts();
});

  function filterProducts() {
    const maxPrice = parseFloat(priceRange.value);
    const veganOnly = document.getElementById('vegan-filter').checked;
    const products = document.querySelectorAll('.grid-item');

    products.forEach(product => {
      const price = parseFloat(product.dataset.price);
      const isVegan = product.dataset.vegan === 'true';

      // Nur anzeigen wenn Preis max + evtl. vegan
      if (price <= maxPrice && (!veganOnly || isVegan)) {
        product.style.display = 'block';
      } else {
        product.style.display = 'none';
      }
    });
  }
  </script>

  <?php include 'components/Footer/footer.php'; ?>
</body>
</html>


<!-- ============================= -->
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