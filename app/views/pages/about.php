<?php require_once __DIR__ . '/../../config/config.php'; ?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <title>XPN | About</title>

  <!-- CSS -->
  <link rel="stylesheet" href="<?= BASE_URL ?>/css/global.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/css/navbar_transparent.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/css/footer.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/css/cookieBanner.css">

  <!-- JS -->
  <script src="<?= BASE_URL ?>/js/navbar.js" defer></script>
  <script src="<?= BASE_URL ?>/js/cookieBanner.js" defer></script>
</head>

<body>
  <?php include __DIR__ . '/../layouts/navbar.php'; ?>

  <main style="padding-top: 80px" class="container content-wrapper">
    <section>
      <h2>Über uns</h2>
      <p>Extreme Performance Nutrition wird von einem engagierten Team betrieben:</p>

      <h4>Felix Bartel</h4>
      <img src="<?= BASE_URL ?>/images/Felix_Bartel.jpg" alt="Felix Bartel">

      <h4>Marvin Kunz</h4>
      <img src="<?= BASE_URL ?>/images/Marvin_Kunz.jpg" alt="Marvin Kunz">

      <h4>Merzan Köse</h4>
      <img src="<?= BASE_URL ?>/images/Merzan_Köse.jpg" alt="Merzan Köse">

      <h4>Nick Zetzmann</h4>
      <img src="<?= BASE_URL ?>/images/Nick_Zetzmann.jpg" alt="Nick Zetzmann">
    </section>

    <section>
      <h2>Unsere Motivation</h2>
      <p>
        Unsere Leidenschaft für Sport, gesunde Ernährung und Leistung hat uns dazu bewegt, Extreme Performance Nutrition zu gründen.
        Wir möchten Menschen dabei unterstützen, ihre Fitnessziele zu erreichen – mit hochwertigen Produkten, denen wir selbst vertrauen.
        Ob Proteinriegel oder Proteinpulver, ob Low Carb oder Vegan – jedes Produkt, das wir anbieten, wurde mit höchstem Anspruch an Qualität, Geschmack und Wirkung ausgewählt.
        Für uns ist es mehr als nur ein Geschäft – es ist unsere Leidenschaft.
      </p>
    </section>

    <section>
      <h2>Kontakt</h2>
      <p>
        Extreme Performance Nutrition (XPN) GmbH<br>
        Esplanade 10<br>
        85049 Ingolstadt<br>
        Telefon: 0841 100000<br>
        Mail: mail@xpn.de
      </p>
    </section>
  </main>

  <?php include __DIR__ . '/../layouts/cookieBanner.php'; ?>
  <?php include __DIR__ . '/../layouts/footer.php'; ?>
</body>
</html>
