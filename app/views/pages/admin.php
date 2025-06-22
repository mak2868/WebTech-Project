<?php require_once __DIR__ . '/../../config/config.php'; ?>
<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title id="pageTitle"></title>
    <title>Verwaltungsbereich</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/admin.css">

    <!-- CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/navbar_transparent.css">

    <!-- JS -->
    <script src="<?= BASE_URL ?>/js/navbar.js" defer></script>

</head>

<?php
include __DIR__ . '/../layouts/navbar.php';
?>

<body>
    <div class="container">
        <h1>Hallo, Admin!</h1>
        <form>
            <label for="menu">Wählen Sie eine Option:</label>
            <select id="menu" name="menu">
                <option value="benutzerverwaltung">Benutzerverwaltung</option>
                <option value="bestellverwaltung">Bestellverwaltung</option>
                <option value="hinzufuegen">Hinzufügen von neuen Elementen</option>
            </select>
        </form>

        <div class="sub-options" id="sub-options">
            <div class="hinzufuegen-option" id="hinzufuegen-options" style="display:none;">
                <h2>Hinzufügen von neuen Elementen</h2>
                <select>
                    <option value="ueberkategorie">Überkategorie</option>
                    <option value="unterkategorie">Unterkategorie</option>
                    <option value="produkte">Produkte</option>
                </select>
            </div>
            <div class="benutzerverwaltung-option" id="benutzerverwaltung-options" style="display:none;">
                <h2>Benutzerverwaltung</h2>
                <!-- Hier könnte man noch Inhalte hinzufügen -->
            </div>
            <div class="bestellverwaltung-option" id="bestellverwaltung-options" style="display:none;">
                <h2>Bestellverwaltung</h2>
                <!-- Hier könnte man noch Inhalte hinzufügen -->
            </div>
        </div>
    </div>

    <script>
        const menuSelect = document.getElementById('menu');
        const hinzufuegenOptions = document.getElementById('hinzufuegen-options');
        const benutzerverwaltungOptions = document.getElementById('benutzerverwaltung-options');
        const bestellverwaltungOptions = document.getElementById('bestellverwaltung-options');

        menuSelect.addEventListener('change', function() {
            // Alle Optionen ausblenden
            hinzufuegenOptions.style.display = 'none';
            benutzerverwaltungOptions.style.display = 'none';
            bestellverwaltungOptions.style.display = 'none';

            // Die ausgewählte Option anzeigen
            if (menuSelect.value === 'hinzufuegen') {
                hinzufuegenOptions.style.display = 'block';
            } else if (menuSelect.value === 'benutzerverwaltung') {
                benutzerverwaltungOptions.style.display = 'block';
            } else if (menuSelect.value === 'bestellverwaltung') {
                bestellverwaltungOptions.style.display = 'block';
            }
        });
    </script>
</body>

</html>