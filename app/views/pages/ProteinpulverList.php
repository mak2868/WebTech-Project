<!--erstellt von: Nick Zetzmann (Navbar von Felix Bartel - Slider von Merzan Köse)-->

<!DOCTYPE html>
<html lang="de">

<head>
  <meta charset="UTF-8">
  <title>XPN | Proteinpulver</title>
  <link rel="stylesheet" href="style/global.css">
  <link rel="stylesheet" href="style/Grid-List.css">
  <link rel="stylesheet" href="style/cart-slide.css">
  <link rel="stylesheet" href="components/Navbar/navbar_transparent.css">
  <link rel="stylesheet" href="components/Footer/footer.css">
  <link rel="stylesheet" href="style/cookieBanner.css">


  <script src="components/Navbar/navbar.js" defer></script>
  <script src="js/items.js" defer></script>
  <script src="js/cart.js" defer></script>
  <script src="js/cookieBanner.js" defer></script>

</head>


<body style="padding-top: 80px">
      <?php include 'components/Navbar/navbar.php'; ?>

  <h1>Unser Proteinpulver</h1>
  
<main>

  <aside class="filter-container">
  <h2>Filter</h2>
  <div class="filter-group">
    <label for="price-range">Max. Preis:</label><br>
    <input type="range" id="price-range" name="price-range" min="0" max="100" value="50">
    <span id="price-value">€50</span>
  </div>
  <div class="filter-group">
    <input type="checkbox" id="vegan-filter" name="vegan-filter">
    <label for="vegan-filter">Nur vegane Produkte</label>
  </div>
</aside>

  <div class="grid-container">

    <div class="grid-item" data-price="21.00" data-vegan="true">
    <div class="image-wrapper">
      <a href="item.php?pid=1">
        <img src="images/Proteinpulver_Isoclear.png" alt="Choco" width="250"><br>
      <div class="icons">
        <div class="icon" onclick="addToCart('Whey Protein Choco', 'images/Proteinpulver_Isoclear.png', 21.00)">
          <img src="images/shopping-cart.png" alt="In den Warenkorb" />
        </div>
      </div>
    </div>
    <div class="rating">
          <span class="stars">★★★★☆</span>
          <span class="reviews">(5800)</span>
        </div>
        <b>Whey Protein Choco</b>
      </a><br>
      <b>21,00 € / 500g</b>
    </div>

    <div class="grid-item" data-price="21.00" data-vegan="true">
    <div class="image-wrapper">
      <a href="item.php?pid=2">
        <img src="images/Proteinpulver_Isoclear.png" alt="Strawberry" width="250"><br>
      <div class="icons">
        <div class="icon" onclick="addToCart('Whey Protein Strawberry', 'images/Proteinpulver_Isoclear.png', 21.00)">
          <img src="images/shopping-cart.png" alt="In den Warenkorb" />
        </div>
      </div>
    </div>
    <div class="rating">
          <span class="stars">★★★★☆</span>
          <span class="reviews">(5800)</span>
        </div>
        <b>Whey Protein Strawberry</b>
      </a><br>
      <b>21,00 € / 500g</b>
    </div>

    <div class="grid-item" data-price="21.00" data-vegan="true">
    <div class="image-wrapper">
      <a href="item.php?pid=3">
        <img src="images/Proteinpulver_Isoclear.png" alt="Vanilla" width="250"><br>
      <div class="icons">
        <div class="icon" onclick="addToCart('Whey Protein Vanilla', 'images/Proteinpulver_Isoclear.png', 21.00)">
          <img src="images/shopping-cart.png" alt="In den Warenkorb" />
        </div>
      </div>
    </div>
    <div class="rating">
          <span class="stars">★★★★☆</span>
          <span class="reviews">(5800)</span>
        </div>
        <b>Whey Protein Vanilla</b>
      </a><br>
      <b>21,00 € / 500g</b>
    </div>

    <div class="grid-item" data-price="21.00" data-vegan="true">
    <div class="image-wrapper">
      <a href="item.php?pid=4">
        <img src="images/Proteinpulver_Isoclear.png" alt="Caramel" width="250"><br>
      <div class="icons">
        <div class="icon" onclick="addToCart('Whey Protein Caramel', 'images/Proteinpulver_Isoclear.png', 21.00)">
          <img src="images/shopping-cart.png" alt="In den Warenkorb" />
        </div>
      </div>
    </div>
    <div class="rating">
          <span class="stars">★★★★☆</span>
          <span class="reviews">(5800)</span>
        </div>
        <b>Whey Protein Caramel</b>
      </a><br>
      <b>21,00 € / 500g</b>
    </div>

    <div class="grid-item" data-price="21.00" data-vegan="true">
    <div class="image-wrapper">
      <a href="item.php?pid=5">
        <img src="images/Proteinpulver_Isoclear.png" alt="Banana" width="250"><br>
      <div class="icons">
        <div class="icon" onclick="addToCart('Whey Protein Banana', 'images/Proteinpulver_Isoclear.png', 21.00)">
          <img src="images/shopping-cart.png" alt="In den Warenkorb" />
        </div>
      </div>
    </div>
    <div class="rating">
          <span class="stars">★★★★☆</span>
          <span class="reviews">(5800)</span>
        </div>
        <b>Whey Protein Banana</b>
      </a><br>
      <b>21,00 € / 500g</b>
    </div>

    <div class="grid-item" data-price="21.00" data-vegan="true">
    <div class="image-wrapper">
      <a href="item.php?pid=6">
        <img src="images/Proteinpulver_Isoclear.png" alt="Hazelnut" width="250"><br>
      <div class="icons">
        <div class="icon" onclick="addToCart('Whey Protein Hazelnut', 'images/Proteinpulver_Isoclear.png', 21.00)">
          <img src="images/shopping-cart.png" alt="In den Warenkorb" />
        </div>
      </div>
    </div>
    <div class="rating">
          <span class="stars">★★★★☆</span>
          <span class="reviews">(5800)</span>
        </div>
        <b>Whey Protein Hazelnut</b>
      </a><br>
      <b>21,00 € / 500g</b>
    </div>

    <div class="grid-item" data-price="21.00" data-vegan="true">
    <div class="image-wrapper">
      <a href="item.php?pid=7">
        <img src="images/Proteinpulver_Isoclear.png" alt="Caffee" width="250"><br>
      <div class="icons">
        <div class="icon" onclick="addToCart('Whey Protein Caffee', 'images/Proteinpulver_Isoclear.png', 21.00)">
          <img src="images/shopping-cart.png" alt="In den Warenkorb" />
        </div>
      </div>
    </div>
    <div class="rating">
          <span class="stars">★★★★☆</span>
          <span class="reviews">(5800)</span>
        </div>
        <b>Whey Protein Caffee</b>
      </a><br>
      <b>21,00 € / 500g</b>
    </div>

    <div class="grid-item" data-price="21.00" data-vegan="true">
    <div class="image-wrapper">
      <a href="item.php?pid=8">
        <img src="images/Proteinpulver_Isoclear.png" alt="Raspberry" width="250"><br>
      <div class="icons">
        <div class="icon" onclick="addToCart('Whey Protein Raspberry', 'images/Proteinpulver_Isoclear.png', 21.00)">
          <img src="images/shopping-cart.png" alt="In den Warenkorb" />
        </div>
      </div>
    </div>
    <div class="rating">
          <span class="stars">★★★★☆</span>
          <span class="reviews">(5800)</span>
        </div>
        <b>Whey Protein Raspberry</b>
      </a><br>
      <b>21,00 € / 500g</b>
    </div>

    <div class="grid-item" data-price="21.00" data-vegan="true">
    <div class="image-wrapper">
      <a href="item.php?pid=9">
        <img src="images/Proteinpulver_Isoclear.png" alt="Pistachio" width="250"><br>
      <div class="icons">
        <div class="icon" onclick="addToCart('Whey Protein Pistachio', 'images/Proteinpulver_Isoclear.png', 21.00)">
          <img src="images/shopping-cart.png" alt="In den Warenkorb" />
        </div>
      </div>
    </div>
    <div class="rating">
          <span class="stars">★★★★☆</span>
          <span class="reviews">(5800)</span>
        </div>
        <b>Whey Protein Pistachio</b>
      </a><br>
      <b>21,00 € / 500g</b>
    </div>

    <div class="grid-item" data-price="21.00" data-vegan="true">
    <div class="image-wrapper">
      <a href="item.php?pid=10">
        <img src="images/Proteinpulver_Isoclear.png" alt="Matcha" width="250"><br>
      <div class="icons">
        <div class="icon" onclick="addToCart('Whey Protein Matcha', 'images/Proteinpulver_Isoclear.png', 21.00)">
          <img src="images/shopping-cart.png" alt="In den Warenkorb" />
        </div>
      </div>
    </div>
    <div class="rating">
          <span class="stars">★★★★☆</span>
          <span class="reviews">(5800)</span>
        </div>
        <b>Whey Protein Matcha</b>
      </a><br>
      <b>21,00 € / 500g</b>
    </div>

    <div class="grid-item" data-price="21.00" data-vegan="true">
    <div class="image-wrapper">
      <a href="item.php?pid=11">
        <img src="images/Proteinpulver_Isoclear.png" alt="Gingerbread" width="250"><br>
      <div class="icons">
        <div class="icon" onclick="addToCart('Whey Protein Gingerbread', 'images/Proteinpulver_Isoclear.png', 21.00)">
          <img src="images/shopping-cart.png" alt="In den Warenkorb" />
        </div>
      </div>
    </div>
    <div class="rating">
          <span class="stars">★★★★☆</span>
          <span class="reviews">(5800)</span>
        </div>
        <b>Whey Protein Gingerbread</b>
      </a><br>
      <b>21,00 € / 500g</b>
    </div>

    <div class="grid-item" data-price="21.00" data-vegan="true">
    <div class="image-wrapper">
      <a href="item.php?pid=12">
        <img src="images/Proteinpulver_Isoclear.png" alt="White Choco" width="250"><br>
      <div class="icons">
        <div class="icon" onclick="addToCart('Whey Protein White Choco', 'images/Proteinpulver_Isoclear.png', 21.00)">
          <img src="images/shopping-cart.png" alt="In den Warenkorb" />
        </div>
      </div>
    </div>
    <div class="rating">
          <span class="stars">★★★★☆</span>
          <span class="reviews">(5800)</span>
        </div>
        <b>Whey Protein White Choco</b>
      </a><br>
      <b>21,00 € / 500g</b>
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
        product.style.display = '';
      } else {
        product.style.display = 'none';
      }
    });
  }
  </script>

  <script src="js/cart.js"></script>
  <?php include 'cartSlider.php'; ?>
  <?php include 'cookieBanner.php'; ?>
  <?php include 'components/Footer/footer.php'; ?>
</body>
</html>

