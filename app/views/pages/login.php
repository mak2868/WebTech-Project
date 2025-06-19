<!-- Erstellt von Merzan Köse -->
<?php require_once __DIR__ . '/../../config/config.php'; ?>
<!DOCTYPE html>
<html lang="de"> <!-- Sprache des Dokuments auf Deutsch eingestellt -->

<head>
    <meta charset="UTF-8"> <!-- Zeichensatz UTF-8 für Umlaute usw. -->
    <title>Login</title> <!-- Titel, der im Browser-Tab angezeigt wird -->

    <!-- CSS -->
    <!-- Einbinden der globalen und seitenbezogenen CSS-Dateien -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/global.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/logreg.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/navbar_transparent.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/cookieBanner.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/footer.css">



    <!-- JS -->
    <script src="<?= BASE_URL ?>/js/navbar.js" defer></script>
    <script src="<?= BASE_URL ?>/js/cookieBanner.js" defer></script>





</head>

<body>
    <?php include __DIR__ . '/../layouts/navbar.php'; ?>
    <!-- Wrapper für das gesamte Formular zur besseren Formatierung -->
    <main style="padding-top: 40px">
        <div class="form-wrapper">

            <!-- Formular zum Einloggen (POST wird an Server gesendet) -->
            <form action="/WebTech-Project/public/index.php?page=login" method="post">
                <?php if (!empty($error)): ?>
                    <div class="form-error"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                <!-- Logo der Anwendung mit Referenzierung auf Startseite-->
                <a href="index.php?page=home">
                    <img id="formLogo" src="images/Logo_SchriftWeiß.png" alt="XPN Logo" class="form-Logo">
                </a>

                <!-- Hauptüberschrift -->
                <h1>Login</h1>

                <!-- Benutzername-Eingabe -->
                <label for="username">Dein Name</label>
                <input type="text" id="username" name="username" placeholder="Vor- und Nachname" required>
                <!-- Pflichtfeld -->

                <!-- Passwort-Eingabe -->
                <label for="password">Passwort</label>
                <input type="password" id="password" name="password" required> <!-- Pflichtfeld -->

                <!-- Button zum Absenden des Formulars -->
                <button type="submit" id="submit" class="btn-esn">Anmelden</button>

                <!-- Hinweistext für neue Nutzer -->
                <p class="form-text">Noch kein Konto?</p>

                <!-- Button zur Weiterleitung auf die Registrierungsseite -->
                <button type="button" class="btn-esn" onclick="window.location.href='index.php?page=register'">
                    Registrieren
                </button>
            </form>
        </div>

        <!-- Einbinden der JavaScript-Datei zur Validierung -->
        <script src="<?= BASE_URL ?>/js/validate-login.js"></script>
        <?php include __DIR__ . '/../layouts/cookieBanner.php'; ?>
        <?php include __DIR__ . '/../layouts/footer.php'; ?>
    </main>
</body>

</html>