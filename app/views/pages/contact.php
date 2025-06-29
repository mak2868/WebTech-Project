<!-- erstellt von: Nick Zetzmann -->

<?php require_once __DIR__ . '/../../config/config.php'; ?>

<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>XPN | Kontakt</title>

    <!-- CSS -->
  <link rel="stylesheet" href="<?= BASE_URL ?>/css/global.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/css/navbar_transparent.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/css/cookieBanner.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/css/footer.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/css/contact.css">

<!-- JS -->
  <script src="<?= BASE_URL ?>/js/navbar.js" defer></script>
  <script src="<?= BASE_URL ?>/js/cookieBanner.js" defer></script>
  <script src="<?= BASE_URL ?>/js/validate-contact.js" defer></script>

  <!-- Head-Datei -->
  <?php include __DIR__ . '/../layouts/head.php'; ?>
</head>

<body>
  <?php include __DIR__ . '/../layouts/navbar.php'; ?>

  <main class="contact-container">

      <h1>Kontaktformular</h1>

<!-- PHP-Bedingung: Zeigt Fehlermeldung, wenn $error gesetzt ist -->
<!-- Wenn kein Fehler vorhanden, sondern erfolgreich abgelaufen: Zeigt Erfolgsnachricht -->
      <?php if (!empty($error)): ?>
        <p class="form-message error"><?= htmlspecialchars($error) ?></p>
      <?php elseif (!empty($success)): ?>
        <p class="form-message success"><?= htmlspecialchars($success) ?></p>
      <?php endif; ?>

<!-- HTML-Formular zur Kontaktaufnahme mittels Name, E-Mail, Betreff und die Nachricht-->
      <form id="contactForm" action="index.php?page=kontakt" method="post">
        <label for="name">Name</label>
        <input type="text" name="name" id="name" required>

        <label for="mail">E-Mail</label>
        <input type="email" name="mail" id="mail" required>

        <label for="subject">Betreff</label>
        <input type="text" name="subject" id="subject" required>

        <label for="message">Nachricht</label>
        <textarea name="message" id="message" required></textarea>
        
<!-- Absende-Button (initial deaktiviert) -->
        <button type="submit" id="submitBtn" disabled>Absenden</button>
      </form>
    </div>
</main>

    <?php include __DIR__ . '/../layouts/cookieBanner.php'; ?>
    <?php include __DIR__ . '/../layouts/footer.php'; ?>
  
</body>
</html>