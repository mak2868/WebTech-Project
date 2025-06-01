<!-- erstellt von: Marvin Kunz (außer Navbar) Slider erstellt von Merzan Köse-->

<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XPN | Whey Protein Choco</title>
    <link rel="stylesheet" href="style/global.css">
    <link rel="stylesheet" href="style/items.css">
    <link rel="stylesheet" href="style/cart-slide.css">
    <link rel="stylesheet" href="components/Navbar/navbar_transparent.css">
    <link rel="stylesheet" href="components/Footer/footer.css">
    <script src="js/items.js" defer></script>
    <script src="js/cart.js" defer></script>
    <script src="js/wishList.js" defer></script>
    <script src="components/Navbar/navbar.js" defer></script>

</head>

<?php
include 'components/Navbar/navbar.php';

$messages = [];
$pid = null;
$cid = null;
$json = null;
$path = null;
$hasError = false;

if (isset($_GET["pid"])) {
    if (trim($_GET["pid"]) === '') {
        $messages[] = 'Der Parameter "pid" wurde übergeben, ist aber leer.';
    } else {
        $pid = $_GET["pid"];
    }
} else {
    $messages[] = 'Der Parameter "pid" fehlt vollständig.';
}

if (isset($_GET["cid"])) {
    if (trim($_GET["cid"]) === '') {
        $messages[] = 'Der Parameter "cid" wurde übergeben, ist aber leer.';
    } else {
        $cid = $_GET["cid"];
    }
} else {
    $messages[] = 'Der Parameter "cid" fehlt vollständig.';
}

if (!empty($messages)) {
    $hasError = true;
    foreach ($messages as $msg) {
        echo '<script>console.log(' . json_encode($msg) . ');</script>';
    }
} else {
    switch ($cid) {
        case 1:
            if ($pid >= 1 && $pid <= 12) {
                $json = @file_get_contents(__DIR__ . '/products/Pulver/WheyProteins.json');
                $path = "/products/Pulver/WheyProteins.json";
            } else {
                echo "<script>console.log(" . json_encode("Ungültige pid für Kategorie 1 (WheyProteins)") . ");</script>";
            }
            break;

        case 2:
            if ($pid >= 1 && $pid <= 6) {
                $json = @file_get_contents(__DIR__ . '/products/Pulver/Isolat.json');
                $path = "/products/Pulver/Isolat.json";
            } else {
                $hasError = true;
                echo "<script>console.log(" . json_encode("Ungültige pid für Kategorie 2 (Isolat).") . ");</script>";
            }
            break;

        case 3:
            if ($pid >= 1 && $pid <= 6) {
                $json = @file_get_contents(__DIR__ . '/products/Pulver/Vegan.json');
                $path = "/products/Pulver/Vegan.json";
            } else {
                $hasError = true;
                echo "<script>console.log(" . json_encode("Ungültige pid für Kategorie 3 (Vegan Pulver).") . ");</script>";
            }
            break;

        case 4:
            if ($pid >= 1 && $pid <= 6) {
                $json = @file_get_contents(__DIR__ . '/products/Riegel/Proteinriegel.json');
                $path = "/products/Riegel/Proteinriegel.json";
            } else {
                $hasError = true;
                echo "<script>console.log(" . json_encode("Ungültige pid für Kategorie 4 (Proteinriegel).") . ");</script>";
            }
            break;

        case 5:
            if ($pid >= 1 && $pid <= 3) {
                $json = @file_get_contents(__DIR__ . '/products/Riegel/LowCarb.json');
                $path = "/products/Riegel/LowCarb.json";
            } else {
                $hasError = true;
                echo "<script>console.log(" . json_encode("Ungültige pid für Kategorie 5 (LowCarb).") . ");</script>";
            }
            break;

        case 6:
            if ($pid >= 1 && $pid <= 3) {
                $json = @file_get_contents(__DIR__ . '/products/Riegel/Vegan.json');
                $path = "/products/Riegel/Vegan.json";
            } else {
                $hasError = true;
                echo "<script>console.log(" . json_encode("Ungültige pid für Kategorie 6 (Riegel Vegan).") . ");</script>";
            }
            break;

        default:
            $json = false;
            echo "<script>console.log(" . json_encode("Ungültige Kategorie.") . ");</script>";
    }
}

if (!$hasError) {
    if ($json === false) {
        echo "<script>console.log(" . json_encode("Error loading the file: " . $path) . ");</script>";
    } else {
        $data = json_decode($json, true);

        if ($data === null) {
            echo "<script>console.log(" . json_encode("Error loading the file: " . $path) . ");</script>";
        }
    }
}


?>

<?php if ($data !== null): ?>
    <script>
        const data = <?php echo json_encode($data); ?>;
    </script>
<?php endif; ?>



<body>
    <div id='topPic'>
        <img src="" alt="">
    </div>

    <main style="padding-top: 80px">
        <section id='top'>
            <div id='top-left'>
                <div id="ProduktbildAuswahl">
                    <img src="" alt="Erstes Produktbild" onclick="switchProductbild(0)">
                    <img src="" alt="Zweites Produktbild" onclick="switchProductbild(1)">
                    <img src="" alt="Drittes Produktbild" onclick="switchProductbild(2)">
                </div>
                <!-- Produktbild -->
                <div id='Produktbild'>
                    <img src="" alt="ausgewähltes Produktbild">
                </div>

                <div id='top-rigth'>
                    <!-- Produktüberschrift -->
                    <div id='firstLine'>
                        <h2 id="name"></h2>
                        <!-- Bewertungsskala -->
                        <div id="Bewertungsskala"></div>
                        <p id="ratersCount"></p>
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
                            <img src="images/shopping-cart.png" alt="">
                            <span>In den Warenkorb</span>
                        </button>
                        <img id="FavButton" onclick="intermediateStepChangeWishListStatus()" src="images/Herz_unausgefüllt.png" alt="">
                        <br>
                    </div>
                    <p id="statusDistribution"></p>
                </div>

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
    <?php include 'components/Footer/footer.php'; ?>
    
    <script defer>
        window.onload = () => {
            window.intermediateStepRenderItemSite(<?php echo json_encode($cid); ?>, <?php echo json_encode($pid); ?>);
        }
    </script>
     <?php include 'cartSlider.php'; ?>
</body>
</html>


