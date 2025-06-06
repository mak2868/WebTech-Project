<!-- Erstellt von Merzan Köse -->

<!DOCTYPE html>
<html lang="de"> <!-- Sprache des Dokuments ist Deutsch -->

<head>
  <meta charset="UTF-8"> <!-- Zeichencodierung UTF-8 für Sonderzeichen -->
  <title>Registrierung</title> <!-- Titel, der im Browser-Tab angezeigt wird -->

  <!-- Einbindung globaler und registrierungsbezogener CSS-Dateien -->
  <link rel="stylesheet" href="./style/global.css">
  <link rel="stylesheet" href="./style/logreg.css">
  <link rel="stylesheet" href="components/Navbar/navbar_transparent.css">
  <link rel="stylesheet" href="style/cookieBanner.css">
  <script src="components/Navbar/navbar.js" defer></script>
  <script src="js/cookieBanner.js" defer></script>

</head>


<body>
  <?php include 'components/Navbar/navbar.php'; ?>


  <main style="padding-top: 40px">
    <!-- Wrapper zur zentrierten Darstellung des Formulars -->
    <div class="form-wrapper">

      <!-- Logo der Anwendung mit der Referenzierung auf Homepage -->
      <a href="index.php">
        <img id="formLogo" src="images/Logo_SchriftWeiß.png" alt="XPN Logo" class="form-Logo">
      </a>

      <!-- Hauptüberschrift -->
      <h1>Registrierung</h1>

      <!-- Formularbeginn: sendet Daten per POST an den Server -->
      <form action="..." method="post">

        <!-- Eingabefeld für den Benutzernamen -->
        <label for="name">Dein Name</label>
        <input type="text" id="name" name="name" placeholder="Vor- und Nachname" required> <!-- Pflichtfeld -->
        <p id="username-rule" class="form-rule hint neutral">
          Mindestens 5 Zeichen, mindestens ein Groß- und ein Kleinbuchstabe
        </p>

        <label for="email">E-Mail:</label>
        <input type="email" id="email" name="email" required>


        <!-- Eingabefeld für das Passwort -->
        <label for="password">Passwort</label>
        <input type="password" id="password" name="password" placeholder="mindestens 10 Zeichen" required> <!-- Pflichtfeld -->

        <!-- Eingabefeld zur Wiederholung des Passworts -->
        <label for="confirm">Passwort wiederholen</label>
        <input type="password" id="confirm" name="confirm" required> <!-- Pflichtfeld -->
        <p id="confirm-rule" class="form-rule hint-neutral">
          Passwort muss aus mind. 10 Zeichen bestehen und mit der
          Passwortwiederholung übereinstimmen
        </p>


        <!-- Button-Leiste: Abbrechen & Registrieren -->
        <div style="display: flex; justify-content: space-between;">
          <!-- Button, um zur Login-Seite zurückzukehren -->
          <button type="button" onclick="window.location.href='login.php'" class="btn-esn">
            abbrechen
          </button>

          <!-- Button zum Absenden des Formulars (initial deaktiviert) -->
          <button type="submit" id="submit" class="btn-esn" disabled>
            registrieren
          </button>
        </div>
      </form>
      <!-- Formularende -->

    </div>

    <!-- Einbinden der JavaScript-Datei zur Validierung -->
    <script src="js/validate-registration.js"></script>
    <?php include 'cookieBanner.php'; ?>
    <?php include 'components/Footer/footer.php'; ?>
  </main>
</body>

</html>