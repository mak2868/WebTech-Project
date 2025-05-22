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
  <script src="components/Navbar/navbar.js" defer></script>
</head>


<body>
      <?php include 'components/Navbar/navbar.php'; ?>


<main style="padding-top: 40px">
  <!-- Wrapper zur zentrierten Darstellung des Formulars -->
  <div class="form-wrapper">

    <!-- Logo der Anwendung -->
    <img src="./images/Logo_SchriftSchwarz.png" alt="XPN Logo" class="form-Logo">

    <!-- Hauptüberschrift -->
    <h1>Registrierung</h1>

    <!-- Formularbeginn: sendet Daten per POST an den Server -->
    <form action="..." method="post">

      <!-- Eingabefeld für den Benutzernamen -->
      <label for="name">Benutzername</label>
      <input type="text" id="name" name="name" required> <!-- Pflichtfeld -->

      <!-- Eingabefeld für das Passwort -->
      <label for="password">Passwort</label>
      <input type="password" id="password" name="password" required> <!-- Pflichtfeld -->

      <!-- Eingabefeld zur Wiederholung des Passworts -->
      <label for="confirm">Passwort wiederholen</label>
      <input type="password" id="confirm" name="confirm" required> <!-- Pflichtfeld -->

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
  <?php include 'components/Footer/footer.php'; ?>
</main>
</body>
</html>
