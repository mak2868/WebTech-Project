<?php
/**
 * Impressum
 * @author: Felix Bartel
 */
?>



<?php require_once __DIR__ . '/../../config/config.php'; ?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>XPN | Impressum</title>

  <!-- CSS -->
  <link rel="stylesheet" href="<?= BASE_URL ?>/css/global.css" />
  <link rel="stylesheet" href="<?= BASE_URL ?>/css/impressum.css" />
  <link rel="stylesheet" href="<?= BASE_URL ?>/css/navbar_transparent.css" />
  <link rel="stylesheet" href="<?= BASE_URL ?>/css/footer.css" />
  <link rel="stylesheet" href="<?= BASE_URL ?>/css/cookieBanner.css" />


  <!-- JS -->
  <script src="<?= BASE_URL ?>/js/navbar.js" defer></script>
  <script src="<?= BASE_URL ?>/js/cookieBanner.js" defer></script>

  <!-- Head-Datei -->
  <?php include __DIR__ . '/../layouts/head.php'; ?>

  

</head>

<body>
  <?php include __DIR__ . '/../layouts/navbar.php'; ?>

  <main class="container content-wrapper">
    <h2>Impressum</h2>

    <h3>Angaben gemäß § 5 TMG</h3>
    <p>
      Felix Bartel<br>
      Esplanade 10<br>
      85057 Ingolstadt<br>
      Deutschland
    </p>

    <h3>Kontakt</h3>
    <p>
      Telefon: 0160/93466321<br>
      E-Mail: <a href="mailto:info@xpn.de">info@xpn.de</a>
    </p>

    <h3>Verantwortlich für den Inhalt nach § 55 Abs. 2 RStV</h3>
    <p>Felix Bartel, Marvin Kunz, Nick Zetzmann, Merzan Köse<br>
    Esplanade 10–13<br>
    85057 Ingolstadt</p>

    <h3>EU-Streitschlichtung</h3>
    <p>
      Die Europäische Kommission stellt eine Plattform zur Online-Streitbeilegung (OS) bereit: <br>
      <a href="https://ec.europa.eu/consumers/odr/" target="_blank" rel="noopener noreferrer">https://ec.europa.eu/consumers/odr/</a><br>
      Unsere E-Mail-Adresse findest du oben im Impressum.
    </p>

    <h3>Haftung für Inhalte</h3>
    <p>
      Als Diensteanbieter sind wir gemäß § 7 Abs.1 TMG für eigene Inhalte auf diesen Seiten nach den allgemeinen Gesetzen verantwortlich.
      Nach §§ 8 bis 10 TMG sind wir jedoch nicht verpflichtet, übermittelte oder gespeicherte fremde Informationen zu überwachen.
    </p>

    <h3>Haftung für Links</h3>
    <p>
      Unser Angebot enthält Links zu externen Websites Dritter. Für diese Inhalte übernehmen wir keine Gewähr. Verantwortlich ist stets der jeweilige Anbieter.
    </p>

    <h3>Urheberrecht</h3>
    <p>
      Die auf dieser Seite erstellten Inhalte unterliegen dem deutschen Urheberrecht. Die Nutzung außerhalb der Grenzen des Urheberrechts bedarf der Zustimmung des Autors.
    </p>
  </main>

  <?php include __DIR__ . '/../layouts/cookieBanner.php'; ?>
  <?php include __DIR__ . '/../layouts/footer.php'; ?>
</body>
</html>
