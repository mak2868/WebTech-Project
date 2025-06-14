<!--Erstellt von Merzan Köse-->
<?php require_once __DIR__ . '/../../config/config.php'; ?>
<!DOCTYPE html>
<html lang="de">

</html>

<head>
    <meta charset="UTF-8">
    <title>Logout</title>

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
    <main style="padding-top: 40px">
        <div class="form-wrapper">
            <h1>Logout</h1>
            <form action="..." method="post"></form>


            <p>Sie wurden erfolgreich abgemeldet </p>

            <a href="index.php?page=login"> Zur Anmeldung</a>
            <a href="index.php?page=home"> Zurück zur Startseite</a>
        </div>
        <?php include __DIR__ . '/../layouts/cookieBanner.php'; ?>
        <?php include __DIR__ . '/../layouts/footer.php'; ?>
    </main>
</body>

</html>