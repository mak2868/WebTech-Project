<!--erstellt von: Nick Zetzmann (Navbar von Felix Bartel)-->

<!DOCTYPE html>
<html lang="de">

<head>
  <meta charset="UTF-8">
  <title>XPN | About</title>
  <link rel="stylesheet" href="style/global.css">
  <link rel="stylesheet" href="components/Navbar/navbar_transparent.css">
  <link rel="stylesheet" href="components/Footer/footer.css">
  <script src="components/Navbar/navbar.js" defer></script>
  <script src="js/darkmode.js" defer></script>



</head>

<body>
  <?php include 'components/Navbar/navbar.php'; ?>


  <main style="padding-top: 80px">
    <section>
      <h2>Über uns</h2>
      <p>
        Extreme Performance Nutrition wird von einem engagierten Team betrieben: <br>
      <h4> Felix Bartel</h4> <img src="images/Felix_Bartel.jpg" alt="Bild nicht verfügbar"> <br>
      <h4>Marvin Kunz</h4> <img src="images/Marvin_Kunz.jpg" alt="Bild nicht verfügbar"> <br>
      <h4>Merzan Köse</h4> <img src="images/Merzan_Köse.jpg" alt="Bild nicht verfügbar"> <br>
      <h4>Nick Zetzmann</h4> <img src="images/Nick_Zetzmann.jpg" alt="Bild nicht verfügbar"> <br><br>
      </p>
    </section>

    <section>
      <h2>Unsere Motivation</h2>
      <p>
        Unsere Leidenschaft für Sport, gesunde Ernährung und Leistung hat uns dazu bewegt, Extreme Performance Nutrition
        zu gründen. <br>
        Wir möchten Menschen dabei unterstützen, ihre Fitnessziele zu erreichen - mit hochwertigen Produkten, denen wir
        selbst vertrauen. <br>
        Ob Proteinriegel oder Proteinpulver, ob Low Carb oder Vegan - jedes Produkt, das wir anbieten, wurde mit
        höchstem Anspruch an Qualität, Geschmack und Wirkung ausgewählt. <br>
        Für uns ist es mehr als nur ein Geschäft - es ist unsere Leidenschaft.
      </p><br>
    </section>

    <section>
      <h2> Kontakt</h2>
      <p>
        Extreme Performance Nutrition (XPN) GmbH <br>
        Esplanade 10 <br>
        85049 Ingolstadt <br>
        Telefon: 0841 100000 <br>
        Mail: mail@xpn.de
      </p>
    </section>
  </main>
  <?php include 'components/Footer/footer.php'; ?>

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
        <button class="go-cart" onclick="window.location.href='cart.html'">Zum Warenkorb</button>
        <!-- Link zur Warenkorbseite -->
      </div>

    </div>
  </div>

</body>

</html>