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
    <script src="<?= BASE_URL ?>/js/admin copy.js" defer></script>

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
            <div id="parent-category-select-wrapper">
                <label for="newCategoryInput">Parent-Category der neuen Kategorie:</label><br>
                <select id="parent-category-select"></select>
            </div>
            <button type="button" id="addCategoryBtn">Hinzufügen</button>
        </div>

        <div id="new-product-form">
            <label for="newProductInput">Name des neuen Produkts:</label><br>
            <input type="text" id="newProductInput" name="newProduct" placeholder="Produktnamen eingeben" />
            <div id="category-select-wrapper">
                <label for="newProductInput">Parent-Category der neuen Kategorie:</label><br>
                <select id="product-select"></select>
            </div>
            <form id="product-details">
                <!-- Product Description -->
                <label for="description">Beschreibung:</label>
                <textarea id="description" name="description" rows="4" required></textarea>

                <!-- Product Variants -->
                <label for="productVariants">Produktvarianten (je Variante mit Komma, Varianten mit ; trennen):</label>
                <textarea id="productVariants" name="productVariants"
                    placeholder="z.B.: 500g, 19.99, 1, 50; 1000g, 34.99, 0, 20" required></textarea>


                <!-- Description Details -->
                <label for="descriptionDetails">Produktdetails (2 Stück, mit ; trennen):</label>
                <textarea id="descriptionDetails" name="descriptionDetails" rows="4" required></textarea>

                <!-- Description Details -->
                <label for="laboratory">Laboranalysen:</label>
                <textarea id="laboratory" name="laboratory" rows="4" required></textarea>

                <!-- Ingredients -->
                <label for="ingredients">Zutaten:</label>
                <textarea id="ingredients" name="ingredients" rows="4" required></textarea>

                <!-- Allergens -->
                <label for="allergens">Allergene:</label>
                <textarea id="allergens" name="allergens" rows="2" required></textarea>

                <!-- Nutrients -->
                <fieldset>
                    <legend>Nährwerte:</legend>
                    <label for="energy">Energie:</label>
                    <input type="text" id="energy" name="energy" placeholder="z.B. 1577 kJ / 373 kcal" required>

                    <label for="fat">Fett:</label>
                    <input type="text" id="fat" name="fat" required>

                    <label for="saturates">davon gesättigte Fettsäuren:</label>
                    <input type="text" id="saturates" name="saturates" required>

                    <label for="carbohydrates">Kohlenhydrate:</label>
                    <input type="text" id="carbohydrates" name="carbohydrates" required>

                    <label for="sugars">davon Zucker:</label>
                    <input type="text" id="sugars" name="sugars" required>

                    <label for="fibre">Ballaststoffe:</label>
                    <input type="text" id="fibre" name="fibre" required>

                    <label for="protein">Eiweiß:</label>
                    <input type="text" id="protein" name="protein" required>

                    <label for="salt">Salz:</label>
                    <input type="text" id="salt" name="salt" required>
                </fieldset>

                <!-- Amino Acids -->
                <fieldset id="aminoAcids-input">
                    <legend>Aminosäuren:</legend>
                    <label for="alanine">Alanine:</label>
                    <input type="text" id="alanine" name="alanine" required>

                    <label for="arginine">Arginine:</label>
                    <input type="text" id="arginine" name="arginine" required>

                    <label for="aspartic_acid">Asparaginsäure:</label>
                    <input type="text" id="aspartic_acid" name="aspartic_acid" required>

                    <label for="cysteine">Cystein:</label>
                    <input type="text" id="cysteine" name="cysteine" required>

                    <label for="glutamic_acid">Glutaminsäure:</label>
                    <input type="text" id="glutamic_acid" name="glutamic_acid" required>

                    <label for="glycine">Glycin:</label>
                    <input type="text" id="glycine" name="glycine" required>

                    <label for="histidine">Histidin:</label>
                    <input type="text" id="histidine" name="histidine" required>

                    <label for="isoleucine">Isoleucin:</label>
                    <input type="text" id="isoleucine" name="isoleucine" required>

                    <label for="leucine">Leucin:</label>
                    <input type="text" id="leucine" name="leucine" required>

                    <label for="lysine">Lysin:</label>
                    <input type="text" id="lysine" name="lysine" required>

                    <label for="methionine">Methionin:</label>
                    <input type="text" id="methionine" name="methionine" required>

                    <label for="phenylalanine">Phenylalanin:</label>
                    <input type="text" id="phenylalanine" name="phenylalanine" required>

                    <label for="proline">Prolin:</label>
                    <input type="text" id="proline" name="proline" required>

                    <label for="serine">Serin:</label>
                    <input type="text" id="serine" name="serine" required>

                    <label for="threonine">Threonin:</label>
                    <input type="text" id="threonine" name="threonine" required>

                    <label for="tryptophan">Tryptophan:</label>
                    <input type="text" id="tryptophan" name="tryptophan" required>

                    <label for="tyrosine">Tyrosin:</label>
                    <input type="text" id="tyrosine" name="tyrosine" required>

                    <label for="valine">Valin:</label>
                    <input type="text" id="valine" name="valine" required>
                </fieldset>

                <!-- Usage Instructions -->
                <label for="preparation">Zubereitung:</label>
                <textarea id="preparation" name="preparation" rows="4" required></textarea>

                <label for="recommendation">Empfehlung:</label>
                <textarea id="recommendation" name="recommendation" rows="4" required></textarea>

                <div id="tipDiv">
                    <label for="tip">Tipp:</label>
                    <textarea id="tip" name="tip" rows="4" required></textarea>
                </div>

                <!-- Recipes -->
                <fieldset id="recipes-input">
                    <legend>Rezepte:</legend>

                    <!-- Rezept 1 -->
                    <label for="recipeTitle1">Rezept 1 Titel:</label>
                    <input type="text" id="recipeTitle1" name="recipeTitle1" required>

                    <label for="recipeShortTitle1">Rezept 1 Kurz-Titel:</label>
                    <input type="text" id="recipeShortTitle1" name="recipeShortTitle1" required>

                    <label for="recipePortion1">Rezept 1 Portionen:</label>
                    <input type="number" id="recipePortion1" name="recipePortion1" min="1" required>

                    <label for="recipeIngredients1">Rezept 1 Zutaten (mit ; trennen):</label>
                    <textarea id="recipeIngredients1" name="recipeIngredients1"
                        placeholder="z.B.: 2 reife Bananen (zerdrückt); 2 Eier; 40 g Proteinpulver" required></textarea>

                    <label for="recipePreparation1">Rezept 1 Zubereitung (mit ; trennen):</label>
                    <textarea id="recipePreparation1" name="recipePreparation1"
                        placeholder="z.B.: Ofen auf 180 Grad vorheizen; Bananen zerdrücken; Alle Zutaten vermengen und in eine Form füllen"
                        required></textarea>

                    <!-- Rezept 2 -->
                    <label for="recipeTitle2">Rezept 2 Titel:</label>
                    <input type="text" id="recipeTitle2" name="recipeTitle2" required>

                    <label for="recipeShortTitle2">Rezept 2 Kurz-Titel:</label>
                    <input type="text" id="recipeShortTitle2" name="recipeShortTitle2" required>

                    <label for="recipePortion2">Rezept 2 Portionen:</label>
                    <input type="number" id="recipePortion2" name="recipePortion2" min="1" required>

                    <label for="recipeIngredients2">Rezept 2 Zutaten (mit ; trennen):</label>
                    <textarea id="recipeIngredients2" name="recipeIngredients2"
                        placeholder="z.B.: 2 reife Bananen (zerdrückt); 2 Eier; 40 g Proteinpulver" required></textarea>

                    <label for="recipePreparation2">Rezept 2 Zubereitung (mit ; trennen):</label>
                    <textarea id="recipePreparation2" name="recipePreparation2"
                        placeholder="z.B.: Ofen auf 180 Grad vorheizen; Bananen zerdrücken; Alle Zutaten vermengen und in eine Form füllen"
                        required></textarea>

                    <!-- Rezept 3 -->
                    <label for="recipeTitle3">Rezept 3 Titel:</label>
                    <input type="text" id="recipeTitle3" name="recipeTitle3" required>

                    <label for="recipeShortTitle3">Rezept 3 Kurz-Titel:</label>
                    <input type="text" id="recipeShortTitle3" name="recipeShortTitle3" required>

                    <label for="recipePortion3">Rezept 3 Portionen:</label>
                    <input type="number" id="recipePortion3" name="recipePortion3" min="1" required>

                    <label for="recipeIngredients3">Rezept 3 Zutaten (mit ; trennen):</label>
                    <textarea id="recipeIngredients3" name="recipeIngredients3"
                        placeholder="z.B.: 2 reife Bananen (zerdrückt); 2 Eier; 40 g Proteinpulver" required></textarea>

                    <label for="recipePreparation3">Rezept 3 Zubereitung (mit ; trennen):</label>
                    <textarea id="recipePreparation3" name="recipePreparation3"
                        placeholder="z.B.: Ofen auf 180 Grad vorheizen; Bananen zerdrücken; Alle Zutaten vermengen und in eine Form füllen"
                        required></textarea>
                </fieldset>

                <fieldset>
                    <legend>Produktbilder:</legend>

                    <label for="productPic1">Produktbild 1:</label>
                    <input type="text" id="productPic1" name="productPic1" placeholder="Pfad zum Bild" required>

                    <label for="productPic2">Produktbild 2:</label>
                    <input type="text" id="productPic2" name="productPic2" placeholder="Pfad zum Bild" required>

                    <label for="productPic3">Produktbild 3:</label>
                    <input type="text" id="productPic3" name="productPic3" placeholder="Pfad zum Bild" required>

                    <label for="smallPic">Kleines Produktbild:</label>
                    <input type="text" id="smallPic" name="smallPic" placeholder="Pfad zum Bild" required>
                </fieldset>

                <button type="button" id="addProductBtn">Hinzufügen</button>
            </form>

        </div>
    </div>
</body>

</html>