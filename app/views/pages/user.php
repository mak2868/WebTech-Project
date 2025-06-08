<?php require_once __DIR__ . '/../../config/config.php'; ?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   


  <!-- Einbindung globaler und registrierungsbezogener CSS-Dateien -->
  <link rel="stylesheet" href="<?= BASE_URL ?>/css/global.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/css/user.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/css/logreg.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/css/navbar_transparent.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/css/cookieBanner.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/css/footer.css">


   <!-- JS -->
  <script src="<?= BASE_URL ?>/js/navbar.js" defer></script>
  <script src="<?= BASE_URL ?>/js/cookieBanner.js" defer></script>


    <title>Benutzerbereich</title>
  

</head>
<body>
      <?php include __DIR__ . '/../layouts/navbar.php'; ?>

    <main style="padding-top: 40px">
        <!-- Userbereich: breites Formular -->
        <div class="form-wrapper user-wrapper">
            <form action="..." method="post">
                <h2>Benutzerbereich</h2>

                <!-- Vorname & Nachname nebeneinander -->
                <div class="form-row">
                    <div class="form-field">
                        <label for="firstname">Vorname:</label>
                        <input type="text" id="firstname" name="firstname" required>
                    </div>
                    <div class="form-field">
                        <label for="lastname">Nachname:</label>
                        <input type="text" id="lastname" name="lastname" required>
                    </div>
                </div>

                <!-- E-Mail & Telefonnummer nebeneinander -->
                <div class="form-row">
                    <div class="form-field">
                        <label for="email">E-Mail:</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-field">
                        <label for="phone">Telefonnummer:</label>
                        <input type="tel" id="phone" name="phone">
                    </div>
                </div>

                <!-- Straße & PLZ nebeneinander -->
                <div class="form-row">
                    <div class="form-field">
                        <label for="street">Straße:</label>
                        <input type="text" id="street" name="street">
                    </div>
                    <div class="form-field">
                        <label for="zip">PLZ:</label>
                        <input type="text" id="zip" name="zip">
                    </div>
                </div>

                <!-- Stadt & Geburtstag nebeneinander -->
                <div class="form-row">
                    <div class="form-field">
                        <label for="city">Stadt:</label>
                        <input type="text" id="city" name="city">
                    </div>
                    <div class="form-field">
                        <label for="birthdate">Geburtstag:</label>
                        <input type="date" id="birthdate" name="birthdate">
                    </div>
                </div>

                <!-- Geschlecht -->
                <div class="form-row">
                    <div class="form-field" style="width:100%;">
                        <label for="gender">Geschlecht:</label>
                        <select id="gender" name="gender">
                            <option value="">Bitte wählen</option>
                            <option value="m">Männlich</option>
                            <option value="w">Weiblich</option>
                            <option value="d">Divers</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="btn-esn">Speichern</button>
            </form>

            <a href="index.php?page=home" class="form-text">Zurück zur Homepage</a>
        </div>

   <script src="<?= BASE_URL ?>/js/validate-user.js"></script>
    <?php include __DIR__ . '/../layouts/cookieBanner.php'; ?>
    <?php include __DIR__ . '/../layouts/footer.php'; ?>
    
</main>
</body>
</html>
