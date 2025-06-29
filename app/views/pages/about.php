<!-- erstellt von: Nick Zetzmann -->

<?php require_once __DIR__ . '/../../config/config.php'; ?>

<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>XPN | About</title>

  <!-- CSS -->
  <link rel="stylesheet" href="<?= BASE_URL ?>/css/global.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/css/navbar_transparent.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/css/footer.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/css/cookieBanner.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/css/about.css">

  <!-- JS -->
  <script src="<?= BASE_URL ?>/js/navbar.js" defer></script>
  <script src="<?= BASE_URL ?>/js/cookieBanner.js" defer></script>

  <!-- Head-Datei -->
  <?php include __DIR__ . '/../layouts/head.php'; ?>
</head>

<body>
  <?php include __DIR__ . '/../layouts/navbar.php'; ?>

  <main class="about-container">
    
<!-- Wer sind wir? - kurze Geschichte zu XPN und uns -->
    <section>
      <h2>Wer sind wir?</h2>
      <p>
        Wir sind vier sportbegeisterte Freunde Anfang 20, die vor ein paar Jahren gemeinsam das Abi gemacht haben. 
        Unsere Leidenschaft für Fitness und gesunde Ernährung hat uns zusammengebracht. 
        Ob beim Krafttraining, Crossfit oder Outdoor-Abenteuern, denn  Sport ist unser Lifestyle.
        Genau deshalb haben wir <strong>Extreme Performance Nutrition</strong> gegründet: 
        Ein Shop für Whey, Isolate, Proteinriegel und mehr, der für Qualität und echte Performance steht.
      </p>
      <p>
        Wir kennen die Herausforderungen, den richtigen Boost für die Trainingsziele zu finden. 
        Deshalb bieten wir nur Produkte an, von denen wir selbst überzeugt sind.
      </p><br><br>
    </section>

<!-- Teamvorstellung mit Bilder & Kurztext -->
    <section>
      <h2>Unser Team</h2>

      <div class="team-member">
        <img src="<?= BASE_URL . $Felix ?>" alt="Felix Bartel">
        <div class="team-member-text">
          <h4>Felix Bartel</h4>
          <p>Felix ist unser Fitnessguru und Produktexperte. Er verbringt seine Freizeit am liebsten im Gym und kennt sich bestens mit den neuesten Supplement-Trends aus.</p>
        </div>
      </div>

      <div class="team-member">
        <img src="<?= BASE_URL . $Marvin ?>" alt="Marvin Kunz">
        <div class="team-member-text">
          <h4>Marvin Kunz</h4>
          <p>Marvin kümmert sich um Marketing und Social Media. Er verbindet Sport mit Lifestyle und sorgt dafür, dass unsere Community immer auf dem Laufenden bleibt.</p>
        </div>
      </div>

      <div class="team-member">
        <img src="<?= BASE_URL . $Merzan ?>" alt="Merzan Köse">
        <div class="team-member-text">
          <h4>Merzan Köse</h4>
          <p>Merzan liebt Outdoor-Sportarten und entwickelt gemeinsam mit dem Team innovative Ideen für neue Produkte und Aktionen.</p>
        </div>
      </div>

      <div class="team-member">
        <img src="<?= BASE_URL . $Nick ?>" alt="Nick Zetzmann">
        <div class="team-member-text">
          <h4>Nick Zetzmann</h4>
          <p>Nick ist unser Logistik-Profi. Er sorgt dafür, dass eure Bestellungen schnell und zuverlässig bei euch ankommen, damit ihr direkt durchstarten könnt.</p>
        </div>
      </div><br><br>
    </section>

<!-- Unsere Motivation -->
    <section>
      <h2>Unsere Motivation</h2>
      <p>
        Unsere Leidenschaft für Sport, gesunde Ernährung und Leistung hat uns dazu bewegt, Extreme Performance Nutrition zu gründen.
        Wir möchten Menschen dabei unterstützen, ihre Fitnessziele zu erreichen mit hochwertigen Produkten, denen wir selbst vertrauen.
        Ob Proteinriegel oder Proteinpulver, ob Low Carb oder Vegan. Jedes Produkt, das wir anbieten, wurde mit höchstem Anspruch an Qualität, Geschmack und Wirkung ausgewählt.
        Für uns ist es mehr als nur ein Geschäft. Es ist unsere Leidenschaft.
      </p><br><br>
    </section>
  </main>

  <?php include __DIR__ . '/../layouts/cookieBanner.php'; ?>
  <?php include __DIR__ . '/../layouts/footer.php'; ?>
</body>
</html>