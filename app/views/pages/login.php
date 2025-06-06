<!-- Erstellt von Merzan Köse -->

<!DOCTYPE html>
<html lang="de"> <!-- Sprache des Dokuments auf Deutsch eingestellt -->

<head>
    <meta charset="UTF-8"> <!-- Zeichensatz UTF-8 für Umlaute usw. -->
    <title>Login</title> <!-- Titel, der im Browser-Tab angezeigt wird -->

    <!-- Einbinden der globalen und seitenbezogenen CSS-Dateien -->
    <link rel="stylesheet" href="./style/global.css">
    <link rel="stylesheet" href="./style/logreg.css">
    <link rel="stylesheet" href="components/Navbar/navbar_transparent.css">
    <link rel="stylesheet" href="style/cookieBanner.css">
    <script src="components/Navbar/navbar.js" defer></script>
    <script src="js/cookieBanner.js" defer></script>



</head>

<body>
    <?php include 'components/Navbar/navbar.php'; ?>
    <!-- Wrapper für das gesamte Formular zur besseren Formatierung -->
    <main style="padding-top: 40px">
        <div class="form-wrapper">

            <!-- Formular zum Einloggen (POST wird an Server gesendet) -->
            <form action="..." method="post">

                <!-- Logo der Anwendung mit Referenzierung auf Startseite-->
                <a href="index.php">
                    <img id="formLogo" src="images/Logo_SchriftWeiß.png" alt="XPN Logo" class="form-Logo">
                </a>

                <!-- Hauptüberschrift -->
                <h1>Login</h1>

                <!-- Benutzername-Eingabe -->
                <label for="username">Dein Name</label>
                <input type="text" id="username" name="username"  placeholder="Vor- und Nachname" required> <!-- Pflichtfeld -->

                <!-- Passwort-Eingabe -->
                <label for="password">Passwort</label>
                <input type="password" id="password" name="password" required> <!-- Pflichtfeld -->

                <!-- Button zum Absenden des Formulars -->
                <button type="submit" id="submit" class="btn-esn">Anmelden</button>

                <!-- Hinweistext für neue Nutzer -->
                <p class="form-text">Noch kein Konto?</p>

                <!-- Button zur Weiterleitung auf die Registrierungsseite -->
                <button type="button" class="btn-esn" onclick="window.location.href='registration.php'">
                    Registrieren
                </button>
            </form>
        </div>

    <!-- Einbinden der JavaScript-Datei zur Validierung -->
    <script src="js/validate-login.js"></script>
    <?php include 'cookieBanner.php'; ?>
    <?php include 'components/Footer/footer.php'; ?>
</main>
</body>

</html>