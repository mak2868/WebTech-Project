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

      <!-- Logo der Anwendung mit der Referenzierung auf Homepage -->
      <a href="index.php">
        <img src="images/Logo_SchriftSchwarz.png" alt="XPN Logo" class="form-Logo">
      </a>

      <!-- Hauptüberschrift -->
      <h1>Registrierung</h1>

      <!-- Formularbeginn: sendet Daten per POST an den Server -->
      <form action="..." method="post">

        <!-- Eingabefeld für den Benutzernamen -->
        <label for="name">Benutzername</label>
        <input type="text" id="name" name="name" required> <!-- Pflichtfeld -->
        <p id="username-rule" class="form-rule hint neutral">
          Mindestens 5 Zeichen, mindestens ein Groß- und ein Kleinbuchstabe
        </p>

        <div class="form-grid">
          <!-- Eingabefeld für den Vornamen -->
          <div class="form-field">
            <label for="firstname">Vorname:</label>
            <input type="text" id="firstname" name="firstname" required>
          </div>

          <div class="form-field">
            <label for="lastname">Nachname:</label>
            <input type="text" id="lastname" name="lastname" required>
          </div>

          <div class="form-field">
            <label for="email">E-Mail:</label>
            <input type="email" id="email" name="email" required>
          </div>

          <div class="form-field">
            <label for="phone">Telefonnummer:</label>
            <input type="tel" id="phone" name="phone">
          </div>

          <div class="form-field">
            <label for="birthdate">Geburtsdatum:</label>
            <input type="date" id="birthdate" name="birthdate">
          </div>

          <div class="form-field">
            <label for="gender">Geschlecht:</label>
            <select id="gender" name="gender">
              <option value="">Bitte wählen</option>
              <option value="m">Männlich</option>
              <option value="w">Weiblich</option>
              <option value="d">Divers</option>
            </select>
          </div>

          <!-- Straße volle Breite -->
          <div class="form-field" style="grid-column: span 2;">
            <label for="street">Straße:</label>
            <input type="text" id="street" name="street">
          </div>

          <!-- PLZ & Stadt nebeneinander -->
          <div class="form-field">
            <label for="zip">PLZ:</label>
            <input type="text" id="zip" name="zip">
          </div>

          <div class="form-field">
            <label for="city">Stadt:</label>
            <input type="text" id="city" name="city">
          </div>

          <!-- Eingabefeld für das Passwort -->
          <label for="password">Passwort</label>
          <input type="password" id="password" name="password" required> <!-- Pflichtfeld -->

          <!-- Eingabefeld zur Wiederholung des Passworts -->
          <label for="confirm">Passwort wiederholen</label>
          <input type="password" id="confirm" name="confirm" required> <!-- Pflichtfeld -->
          <p id="confirm-rule" class="form-rule hint-neutral">
            Passwort muss aus mind. 10 Zeichen bestehen und mit der
            Passwortwiederholung übereinstimmen
          </p>
        </div>

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