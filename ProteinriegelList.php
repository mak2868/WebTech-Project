<!--erstellt von: Nick Zetzmann (Navbar von Felix Bartel)-->

<!DOCTYPE html>
<html lang="de">

<head>
  <meta charset="UTF-8">
  <title>XPN | Proteinriegel</title>
  <label class="darkmode-toggle">
    <input type="checkbox" id="darkmode-toggle">
    Dark Mode
  </label>
  <link rel="stylesheet" href="style/global.css">
  <link rel="stylesheet" href="style/Grid-List.css">
  <link rel="stylesheet" href="components/Navbar/navbar_transparent.css">
  <script src="components/Navbar/navbar.js" defer></script>
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
    <div class="grid-item" data-price="1,79" data-vegan="false">
      <a href="choco_bar.html">
        <img src="images/Proteinriegel_LowCarb.png" alt="Choco" width="220"><br>
        Choco Bar
      </a><br>
      1,79 € je Bar
    </div>

    <div class="grid-item" data-price="1,79" data-vegan="true">
      <a href="strawberry_bar.html">
        <img src="images/Proteinriegel_LowCarb.png" alt="Strawberry" width="220"><br>
        Strawberry Bar
      </a><br>
      1,79 € je Bar
    </div>

    <div class="grid-item" data-price="1,99" data-vegan="false">
      <a href="caramel_bar.html">
        <img src="images/Proteinriegel_LowCarb.png" alt="Caramel" width="220"><br>
        Caramel Bar
      </a><br>
      1,99 € je Bar
    </div>

    <div class="grid-item" data-price="1,79" data-vegan="false">
      <a href="choco_bar.html">
        <img src="images/Proteinriegel_LowCarb.png" alt="Choco" width="220"><br>
        Choco Bar
      </a><br>
      1,79 € je Bar
    </div>

    <div class="grid-item" data-price="1,79" data-vegan="false">
      <a href="choco_bar.html">
        <img src="images/Proteinriegel_LowCarb.png" alt="Choco" width="220"><br>
        Choco Bar
      </a><br>
      1,79 € je Bar
    </div>

    <div class="grid-item" data-price="1,79" data-vegan="false">
      <a href="choco_bar.html">
        <img src="images/Proteinriegel_LowCarb.png" alt="Choco" width="220"><br>
        Choco Bar
      </a><br>
      1,79 € je Bar
    </div>

    <div class="grid-item" data-price="1,79" data-vegan="false">
      <a href="choco_bar.html">
        <img src="images/Proteinriegel_LowCarb.png" alt="Choco" width="220"><br>
        Choco Bar
      </a><br>
      1,79 € je Bar
    </div>

    <div class="grid-item" data-price="1,79" data-vegan="false">
      <a href="choco_bar.html">
        <img src="images/Proteinriegel_LowCarb.png" alt="Choco" width="220"><br>
        Choco Bar
      </a><br>
      1,79 € je Bar
    </div>

    <div class="grid-item" data-price="1,79" data-vegan="false">
      <a href="choco_bar.html">
        <img src="images/Proteinriegel_LowCarb.png" alt="Choco" width="220"><br>
        Choco Bar
      </a><br>
      1,79 € je Bar
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

<script>
  const toggle = document.getElementById('darkmode-toggle');

  if (localStorage.getItem('dark-mode') === 'true') {
    document.body.classList.add('dark-mode');
    toggle.checked = true;
  }

  toggle.addEventListener('change', () => {
    if (toggle.checked) {
      document.body.classList.add('dark-mode');
      localStorage.setItem('dark-mode', 'true');
    } else {
      document.body.classList.remove('dark-mode');
      localStorage.setItem('dark-mode', 'false');
    }
  });
</script> 
  <?php include 'components/Footer/footer.php'; ?>
</body>
</html>