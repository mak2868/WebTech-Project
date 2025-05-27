
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hero Section</title>
    <link rel="stylesheet" href="style/global.css">
    <link rel="stylesheet" href="style/index.css">
    <link rel="stylesheet" href="style/index-darkmode.css">
    <link rel="stylesheet" href="components/Navbar/navbar_transparent.css">
    <link rel="stylesheet" href="components/Footer/footer.css">
    <script src="components/Navbar/navbar.js" defer></script>


</head>
<body class="dark-page">
    <?php include 'components/Navbar/navbar.php'; ?>


<main>
  <!-- Hero Section -->
  <section class="hero">
    <div class="hero-content">
      <h1>Made for Athletes</h1>
      <p class ="hero-paragraph">Nutrition that tates as good as it works</p>
      <button class="button" onclick="window.location.href='ProteinpulverList'">Shop now</button>
    </div>
  </section>


<!-- Logo Section -->
<section class="logo-section">
  <div class="logo-section-inner">
    <p>Top brands trust us for quality that delivers</p>
    <div class="logo-slider">
      <div class="logos">
        <img src="images/Foodspring_Logo.png" alt="Foodspring Logo" class="logo">
        <img src="images/GoldsGym_Logo.png" alt="Gold's Gym Logo" class="logo">
        <img src="images/MyProtein_Logo.png" alt="MyProtein Logo" class="logo">
        <img src="images/Dm_Logo.png" alt="dm Logo" class="logo">
        <img src="images/JohnReed_Logo.png" alt="John Reed Logo" class="logo">
        <img src="images/Edeka_Logo.png" alt="Edeka Logo" class="logo">
        <!-- Wiederholung für Endlosschleife -->
        <img src="images/Foodspring_Logo.png" alt="Foodspring Logo" class="logo">
        <img src="images/GoldsGym_Logo.png" alt="Gold's Gym Logo" class="logo">
        <img src="images/MyProtein_Logo.png" alt="MyProtein Logo" class="logo">
        <img src="images/Dm_Logo.png" alt="dm Logo" class="logo">
        <img src="images/JohnReed_Logo.png" alt="John Reed Logo" class="logo">
        <img src="images/Edeka_Logo.png" alt="Edeka Logo" class="logo">
        <!-- Wiederholung für Endlosschleife -->
        <img src="images/Foodspring_Logo.png" alt="Foodspring Logo" class="logo">
        <img src="images/GoldsGym_Logo.png" alt="Gold's Gym Logo" class="logo">
      </div>
    </div>
  </div>
</section>


  

  <!-- Banner Section -->
<section class="banner-section">
  <div class="banner-container">
    <div class="banner-image">
      <img src="images/Proteinriegel_Banner.png" alt="Banner Image">
    </div>
    <div class="banner-text">
      <h2>Deutschlands Nr. 1 Proteinriegel</h2>
      <p>Protein Snack für maximalen Muskelaufbau.</p>
      <button class="button" onclick="window.location.href='ProteinpulverList.html'">20% Aktion</button>
    </div>
  </div>
</section>


 <!-- Bestseller Section -->
<section class="bestseller-section">
  <div class="container">
    <h2>Unsere Bestseller</h2>
    <p>Besonders beliebt</p>
    <div class="product-grid">
      


<!-- Produktkarte 1 -->
<div class="product-card">
  <div class="badge">Bestseller</div>
  <div class="image-wrapper">
    <img src="images/Proteinpulver_Isoclear.png" alt="Produkt">
    <div class="icons">
      <div class="icon" onclick="addToCart('Isoclear Whey Protein', 'images/Proteinpulver_Isoclear.png', 49.90)">
        <img src="images/shopping-cart.png" alt="In den Warenkorb" />
      </div>
    </div>
  </div>
  <p class="flavor">Peach Iced Tea</p>
  <h3 class="title">Isoclear Whey Protein</h3>
  <p class="desc">Erfrischend klarer Protein-Drink</p>
  <div class="rating">
    <span class="stars">★★★★☆</span>
    <span class="reviews">(5800)</span>
  </div>
  <p class="price">€49,90 <span class="price-kg">(€54,96/kg)</span></p>
</div>



<!-- Produktkarte 2 -->
<div class="product-card">
  <div class="badge">Bestseller</div>
  <div class="image-wrapper">
    <img src="images/Proteinriegel_LowCarb.png" alt="Produkt">
    <div class="icons">
      <div class="icon" onclick="addToCart('Proteinriegel Low Carb', 'images/Proteinriegel_LowCarb.png', 23.90)">
        <img src="images/shopping-cart.png" alt="In den Warenkorb" />
      </div>
    </div>
  </div>
  <p class="flavor">Peach Iced Tea</p>
  <h3 class="title">Proteinriegel Low Carb</h3>
  <p class="desc">Einzigartig im Geschmack</p>
  <div class="rating">
    <span class="stars">★★★★☆</span>
    <span class="reviews">(5800)</span>
  </div>
  <p class="price">€49,90 <span class="price-kg">(€54,96/kg)</span></p>
</div>



<!-- Produktkarte 3 -->
<div class="product-card">
  <div class="badge">Bestseller</div>
  <div class="image-wrapper">
    <img src="images/Proteinpulver_Isoclear.png" alt="Produkt">
    <div class="icons">
      <div class="icon" onclick="addToCart('Isoclear Whey Protein', 'images/Proteinpulver_Isoclear.png', 49.90)">
        <img src="images/shopping-cart.png" alt="In den Warenkorb" />
      </div>
    </div>
  </div>
  <p class="flavor">Peach Iced Tea</p>
  <h3 class="title">Isoclear Whey Protein</h3>
  <p class="desc">Einzigartig im Geschmack</p>
  <div class="rating">
    <span class="stars">★★★★☆</span>
    <span class="reviews">(5800)</span>
  </div>
  <p class="price">€49,90 <span class="price-kg">(€54,96/kg)</span></p>
</div>



<!-- Produktkarte 4 -->
<div class="product-card">
  <div class="badge">Bestseller</div>
  <div class="image-wrapper">
    <img src="images/Proteinriegel_LowCarb.png" alt="Produkt">
    <div class="icons">
      <div class="icon" onclick="addToCart('Proteinriegel Low Carb', 'images/Proteinriegel_LowCarb.png', 23.90)">
        <img src="images/shopping-cart.png" alt="In den Warenkorb" />
      </div>
    </div>
  </div>
  <p class="flavor">Peach Iced Tea</p>
  <h3 class="title">Proteinriegel Low Carb</h3>
  <p class="desc">Erfrischend klarer Protein-Drink</p>
  <div class="rating">
    <span class="stars">★★★★☆</span>
    <span class="reviews">(5800)</span>
  </div>
  <p class="price">€49,90 <span class="price-kg">(€54,96/kg)</span></p>
</div>
    </div>
  </div>
</section>
</main>
<script src="js/cart.js"></script>
  <?php include 'cartSlider.php'; ?>
  <?php include 'components/Footer/footer.php'; ?>
  </body>
  </html>
  
