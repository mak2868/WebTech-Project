<?php require_once __DIR__ . '/../../config/config.php'; ?>
<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Adminbereich</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/admin.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/navbar_transparent.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/global.css">
    <script src="<?= BASE_URL ?>/js/admin.js" defer></script>

</head>

<body>
    <div class="container">
        <h1>Hallo, Admin!</h1>

        <form>
            <label for="menu">Wählen Sie eine Option:</label>
            <select id="menu" name="menu">
                <option value="" disabled selected hidden>Bitte wählen...</option>
                <option value="benutzerverwaltung">Benutzerverwaltung</option>
                <option value="bestellverwaltung">Bestellverwaltung</option>
                <option value="hinzufuegen">Hinzufügen von neuen Elementen</option>
            </select>
        </form>

        <div class="sub-options" id="sub-options">
            <div class="hinzufuegen-option" id="hinzufuegen-options" style="display:none;">
                <h2>Hinzufügen von neuen Elementen</h2>
                <select id="hinzufuegen-select">
                    <option value="" disabled selected hidden>Bitte wählen...</option>
                    <option value="ueberkategorie">Überkategorie</option>
                    <option value="unterkategorie">Unterkategorie</option>
                    <option value="produkte">Produkte</option>
                </select>
            </div>
        </div>

        <div id="adminContent"></div>

        <div id="new-parent-category-form">
            <label for="newParentCategoryInput">Name der neuen Kategorie:</label><br>
            <input type="text" id="newParentCategoryInput" name="newCategory" placeholder="Kategorie eingeben" />
            <button type="button" id="addParentCategoryBtn">Hinzufügen</button>
        </div>

        <div id="new-category-form">
            <label for="newCategoryInput">Name der neuen Kategorie:</label><br>
            <input type="text" id="newCategoryInput" name="newCategory" placeholder="Kategorie eingeben" />
            <button type="button" id="addCategoryBtn">Hinzufügen</button>
        </div>
    </div>
</body>

</html>