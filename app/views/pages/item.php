<!-- erstellt von: Marvin Kunz -->

<?php require_once __DIR__ . '/../../config/config.php'; ?>
<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title id="pageTitle"></title>

    <!-- CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/cart-slide.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/global.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/items.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/navbar_transparent.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/footer.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/cookieBanner.css">


    <!-- JS -->
    <script src="<?= BASE_URL ?>/js/items.js" defer></script>
    <script src="<?= BASE_URL ?>/js/cart.js" defer></script>
    <script src="<?= BASE_URL ?>/js/wishList.js" defer></script>
    <script src="<?= BASE_URL ?>/js/navbar.js" defer></script>
    <script src="<?= BASE_URL ?>/js/cookieBanner.js" defer></script>
    <script src="<?= BASE_URL ?>/js/initial.js" defer></script>


    <!-- Head-Datei -->
    <?php include __DIR__ . '/../layouts/head.php'; ?>

</head>

<?php
include __DIR__ . '/../layouts/navbar.php';
?>

<?php if (!empty($produkte)): ?>
    <script>
        const data = <?php echo json_encode($produkte, JSON_UNESCAPED_UNICODE); ?>;
        console.log("Daten geladen:", data);
    </script>
<?php else: ?>
    <script>
        console.error("Keine Produktdaten vorhanden.");
    </script>
<?php endif; ?>




<body>
    <main style="padding-top: 80px">
        <section id='top'>
            <div id='top-left'>
                <!-- Produktbildauswahl -->
                <div id="ProduktbildAuswahl">
                    <img src="" alt="Erstes Produktbild" onclick="switchProductbild(0)">
                    <img src="" alt="Zweites Produktbild" onclick="switchProductbild(1)">
                    <img src="" alt="Drittes Produktbild" onclick="switchProductbild(2)">
                </div>
                <!-- Produktbild -->
                <div id="dotContainer" class="dots"></div>
                <div id='Produktbild'>
                    <img src="" alt="ausgewähltes Produktbild">
                </div>
            </div>

            <div id='top-rigth'>
                <!-- Produktüberschrift -->
                <div id='firstLine'>
                    <h2 id="name"></h2>
                    <!-- Bewertungsskala -->
                    <div id="BewertungsskalaRatersCount">
                        <div id="Bewertungsskala"></div>
                        <p id="ratersCount"></p>
                    </div>
                </div>
                <h4 id="description"></h4>
                <!-- Sortenauswahl -->
                <div id="select-wrapper">
                    <p>Geschmack</p>
                    <select id="select"></select>
                </div>

                <!-- Verpackungsgrößen -->
                <div id='Verpackungsgrößen'>
                    <p>Verpackungsgrößen</p>
                    <div id='VerpackungsgrößenButtons'></div>
                </div>

                <!-- Preis -->
                <div id='Preis'>
                    <p id="priceWTax"></p>
                    <p id="pricePerKgOutput"></p>
                </div>

                <!-- Versand + Favoriten-->
                <div id='VersandFavoriten'>
                    <button id="VersandButton" onclick="intermediateStepAddToCart(); openCart();">
                        <img id="VersandButtonImg" src="" alt="">
                        <span>In den Warenkorb</span>
                    </button>
                    <img id="FavButton" onclick="changeWishListStatus()" src="" alt="">
                    <br>
                </div>
                <p id="statusDistribution"></p>

        </section>


        <div id='ProduktinformationKlBild'>

            <!-- Produktinformationen -->
            <div id='Produktinformationen'>

                <!-- Beschreibung -->
                <section>
                    <button id="accordion">Beschreibung</button>
                    <div id="panel">
                        <article>
                            <p id="descriptionDetails1"></p>
                        </article>

                        <article>
                            <h4>Produktbezeichnung</h4>
                            <p id="descriptionDetails2"></p>
                        </article>
                    </div>
                    <script>
                        var targetPanel = document.querySelectorAll('#accordion')[0];
                        targetPanel.addEventListener('click', () => openPanel(0));
                    </script>
                </section>

                <!-- Inhalt -->
                <section>
                    <button id="accordion">Inhalt</button>
                    <div id="panel">
                        <article>
                            <h4>Zutaten</h4>
                            <p id="substanceIngredients"></p>
                            <p id="substanceAllergens"></p>
                        </article>

                        <article>
                            <h4>Nährwerte</h4>
                            <table>
                                <caption>Nährwerte (pro 100g)</caption>
                                <thead>
                                    <tr>
                                        <th>Nährstoffe / Zusammensetzung</th>
                                        <th>pro 100g</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Energie</td>
                                        <td id="substanceNutrientsEnergy"></td>
                                    </tr>
                                    <tr>
                                        <td>Fett</td>
                                        <td id="substanceNutrientsFat"></td>
                                    </tr>
                                    <tr>
                                        <td>davon gesättigte Fettsäuren</td>
                                        <td id="substanceNutrientsFatOfWhichSaturates"></td>
                                    </tr>
                                    <tr>
                                        <td>Kohlenhydrate</td>
                                        <td id="substanceNutrientsCarbohydrates"></td>
                                    </tr>
                                    <tr>
                                        <td>davon Zucker</td>
                                        <td id="substanceNutrientsOfWhichSugars"></td>
                                    </tr>
                                    <tr>
                                        <td>Ballaststoffe</td>
                                        <td id="substanceNutrientsFibre"></td>
                                    </tr>
                                    <tr>
                                        <td>Eiweiß / Protein</td>
                                        <td id="substanceNutrientsProtein"></td>
                                    </tr>
                                    <tr>
                                        <td>Salz</td>
                                        <td id="substanceNutrientsSalt"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </article>

                        <article id="aminoAcidProfile">
                            <h4>Aminosäureprofil</h4>
                            <table>
                                <caption>Aminosäureprofil (pro 100g)</caption>
                                <thead>
                                    <tr>
                                        <th>Aminosäurebilanz</th>
                                        <th>pro 100g Protein</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Alanin</td>
                                        <td id="substanceAminoAcidsAlanine"></td>
                                    </tr>
                                    <tr>
                                        <td>Arginin</td>
                                        <td id="substanceAminoAcidsArginine"></td>
                                    </tr>
                                    <tr>
                                        <td>Asparaginsäure</td>
                                        <td id="substanceAminoAcidsAsparticAcid"></td>
                                    </tr>
                                    <tr>
                                        <td>Cystein</td>
                                        <td id="substanceAminoAcidsCysteine"></td>
                                    </tr>
                                    <tr>
                                        <td>Glutaminsäure</td>
                                        <td id="substanceAminoAcidsGlutamicAcid"></td>
                                    </tr>
                                    <tr>
                                        <td>Glycin</td>
                                        <td id="substanceAminoAcidsGlycine"></td>
                                    </tr>
                                    <tr>
                                        <td>Histidin</td>
                                        <td id="substanceAminoAcidsHistidine"></td>
                                    </tr>
                                    <tr>
                                        <td>Isoleucin</td>
                                        <td id="substanceAminoAcidsIsoleucine"></td>
                                    </tr>
                                    <tr>
                                        <td>Leucin</td>
                                        <td id="substanceAminoAcidsLeucine"></td>
                                    </tr>
                                    <tr>
                                        <td>Lysin</td>
                                        <td id="substanceAminoAcidsLysine"></td>
                                    </tr>
                                    <tr>
                                        <td>Methionin</td>
                                        <td id="substanceAminoAcidsMethionine"></td>
                                    </tr>
                                    <tr>
                                        <td>Phenylalanin</td>
                                        <td id="substanceAminoAcidsPhenylalanine"></td>
                                    </tr>
                                    <tr>
                                        <td>Prolin</td>
                                        <td id="substanceAminoAcidsProline"></td>
                                    </tr>
                                    <tr>
                                        <td>Serin</td>
                                        <td id="substanceAminoAcidsSerine"></td>
                                    </tr>
                                    <tr>
                                        <td>Threonin</td>
                                        <td id="substanceAminoAcidsThreonine"></td>
                                    </tr>
                                    <tr>
                                        <td>Tryptophan</td>
                                        <td id="substanceAminoAcidsTryptophan"></td>
                                    </tr>
                                    <tr>
                                        <td>Tyrosin</td>
                                        <td id="substanceAminoAcidsTyrosine"></td>
                                    </tr>
                                    <tr>
                                        <td>Valin</td>
                                        <td id="substanceAminoAcidsValine"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </article>
                    </div>
                    <script>
                        var targetPanel = document.querySelectorAll('#accordion')[1];
                        targetPanel.addEventListener('click', () => openPanel(1));
                    </script>
                </section>

                <!-- Anwendung -->
                <section>
                    <button id="accordion">Anwendung</button>
                    <div id="panel">
                        <article>
                            <h4>Zubereitung</h4>
                            <p id="usagePreparation"></p>
                        </article>

                        <article>
                            <h4>Empfehlung</h4>
                            <p id="usageRecommendation"></p>
                        </article>

                        <article>
                            <h4 id="tipHeading">Tipp</h4>
                            <p id="usageTip"></p>
                        </article>

                        <?php $recipeIndex = 0; ?>
                        <div id="btn-group-Rezeptidee"></div>

                        <article id="recipe">
                            <h4 id="recipeTitle"></h4>
                            <h5 id="recipeIngredientsHeading"></h5>
                            <ul id="recipeIngredients"></ul>
                            <h5 id="recipePreparationHeading">Zubereitung:</h5>
                            <ol id="recipePreparation" start='1'></ol>
                        </article>
                    </div>
                    <script>
                        var targetPanel = document.querySelectorAll('#accordion')[2];
                        targetPanel.addEventListener('click', () => openPanel(2));
                    </script>
                </section>

                <!-- Laboranalysen -->
                <section>
                    <button id="accordion">Laboranalysen</button>
                    <div id="panel">
                        <p id="laboratory"></p>
                    </div>
                    <script>
                        var targetPanel = document.querySelectorAll('#accordion')[3];
                        targetPanel.addEventListener('click', () => openPanel(3));
                    </script>
                </section>

            </div>
            <div id='klBildContainer'>
                <img id='klBild' src="" alt="kleines Produktbild">
            </div>

        </div>
    </main>
    <?php include '../app/views/layouts/cookieBanner.php'; ?>
    <?php include '../app/views/layouts/footer.php'; ?>

    <script defer>
        document.addEventListener("DOMContentLoaded", () => {
            const parentID = <?php echo json_encode($parentID); ?>;
            const cid = <?php echo json_encode($cid); ?>;
            const pid = <?php echo json_encode($pid); ?>;
            const idInData = <?php echo json_encode($idInData); ?>;
            const data = <?php echo json_encode($produkte); ?>;

            window.intermediateStepRenderItemSite(parentID, cid, pid, idInData, data);
        });
    </script>
    <?php include '../app/views/layouts/cartSlider.php'; ?>
</body>

</html>