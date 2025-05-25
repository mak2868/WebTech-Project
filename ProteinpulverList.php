<!--erstellt von: Nick Zetzmann (Navbar von Felix Bartel)-->

<!DOCTYPE html>
<html lang="de">

<head>
  <meta charset="UTF-8">
  <title>XPN | Proteinpulver</title>
  <link rel="stylesheet" href="style/global.css">
  <link rel="stylesheet" href="style/Grid-List.css">
  <link rel="stylesheet" href="components/Navbar/navbar_transparent.css">
  <script src="components/Navbar/navbar.js" defer></script>
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
    <div class="grid-item" data-price="21,00" data-vegan="true">
      <a href="item.html">
        <img src="images/choco_whey.jpeg" alt="Choco" width="250"><br>
        Whey Protein Choco
      </a><br>
      21,00 € / 500g
    </div>

    <div class="grid-item" data-price="21,00" data-vegan="true">
      <a href="strawberry_whey.html">
        <img src="images/choco_whey.jpeg" alt="Strawberry" width="250"><br>
        Whey Protein Strawberry
      </a><br>
      21,00 € / 500g
    </div>

    <div class="grid-item" data-price="21,00" data-vegan="false">
      <a href="vanilla_whey.html">
        <img src="images/choco_whey.jpeg" alt="Vanilla" width="250"><br>
        Whey Protein Vanilla
      </a><br>
      21,00 € / 500g
    </div>

    <div class="grid-item" data-price="21,00" data-vegan="true">
      <a href="item.html">
        <img src="images/choco_whey.jpeg" alt="Choco" width="250"><br>
        Whey Protein Choco
      </a><br>
      21,00 € / 500g
    </div>

    <div class="grid-item" data-price="21,00" data-vegan="true">
      <a href="item.html">
        <img src="images/choco_whey.jpeg" alt="Choco" width="250"><br>
        Whey Protein Choco
      </a><br>
      21,00 € / 500g
    </div>

    <div class="grid-item" data-price="21,00" data-vegan="true">
      <a href="item.html">
        <img src="images/choco_whey.jpeg" alt="Choco" width="250"><br>
        Whey Protein Choco
      </a><br>
      21,00 € / 500g
    </div>

    <div class="grid-item" data-price="21,00" data-vegan="true">
      <a href="item.html">
        <img src="images/choco_whey.jpeg" alt="Choco" width="250"><br>
        Whey Protein Choco
      </a><br>
      21,00 € / 500g
    </div>

    <div class="grid-item" data-price="21,00" data-vegan="true">
      <a href="item.html">
        <img src="images/choco_whey.jpeg" alt="Choco" width="250"><br>
        Whey Protein Choco
      </a><br>
      21,00 € / 500g
    </div>

    <div class="grid-item" data-price="21,00" data-vegan="true">
      <a href="item.html">
        <img src="images/choco_whey.jpeg" alt="Choco" width="250"><br>
        Whey Protein Choco
      </a><br>
      21,00 € / 500g
    </div>

    <div class="grid-item" data-price="21,00" data-vegan="true">
      <a href="item.html">
        <img src="images/choco_whey.jpeg" alt="Choco" width="250"><br>
        Whey Protein Choco
      </a><br>
      21,00 € / 500g
    </div>

    <div class="grid-item" data-price="21,00" data-vegan="true">
      <a href="item.html">
        <img src="images/choco_whey.jpeg" alt="Choco" width="250"><br>
        Whey Protein Choco
      </a><br>
      21,00 € / 500g
    </div>

    <div class="grid-item" data-price="21,00" data-vegan="true">
      <a href="item.html">
        <img src="images/choco_whey.jpeg" alt="Choco" width="250"><br>
        Whey Protein Choco
      </a><br>
      21,00 € / 500g
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