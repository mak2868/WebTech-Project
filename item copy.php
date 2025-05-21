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
    <link rel="stylesheet" href="components/Footer/footer.css">
    <link rel="stylesheet" href="components/Navbar/navbar_transparent.css">
    <script src="components/Navbar/navbar.js" defer></script>
    <script type="module" src="js/items.js"></script>
    <script src="js/cart.js"></script>
    <script type="module" src="js/darkmode.js" defer></script>
    <script src="js/wishList.js"></script>
</head>

<?php include 'components/Navbar/navbar.php';
$initial = true;

$json = file_get_contents(__DIR__ . '/products/WheyProteins.json');
$data = json_decode($json, true);

// Fehlerbehandlung
if ($initial) {
    $initial = false;

    if (!isset($data["Whey Proteins"][0])) {
        echo "Produkt nicht gefunden.";
        exit;
    } else {
        $selectedProduct = $data["Whey Proteins"][0];
        echo "<pre>" . json_encode($selectedProduct, JSON_PRETTY_PRINT) . "</pre>";
    }
}



?>

<body>
    <div class='topPic'>
    <img src="<?php echo htmlspecialchars($selectedProduct['pics']['topPic']); ?>" alt="">

        <main style="padding-top: 80px">
            <section class='top'>
                <div class='top-left'>
                    <div class="ProduktbildAuswahl">
                        <img src="<?php echo htmlspecialchars($selectedProduct['pics']['productPic1']); ?>" alt="Bild nicht verfügbar" onclick="switchProductbild(0)">
                        <img src="<?php echo htmlspecialchars($selectedProduct['pics']['productPic2']); ?>" alt="" onclick="switchProductbild(1)">
                        <img src="<?php echo htmlspecialchars($selectedProduct['pics']['productPic3']); ?>" alt="" onclick="switchProductbild(2)">
                    </div>
                    <!-- Produktbild -->
                    <div class='Produktbild'>
                        <img src="<?php echo htmlspecialchars($selectedProduct['pics']['productPic1']); ?>" alt="Bild nicht verfügbar">
                    </div>
                </div>

                <div class='top-rigth'>
                    <!-- Produktüberschrift -->
                    <div class='firstLine'>
                        <!-- <h2>Whey Protein Choco</h2> -->
                         <h2><?php echo htmlspecialchars($selectedProduct["name"]); ?></h2>
                        <!-- Bewertungsskala -->
                        <div id="Bewertungsskala">
                            <script>
                                window.onload = () => {
                                    const rating = <?php echo htmlspecialchars($selectedProduct["rating"]); ?>;
                                    const container = document.getElementById("Bewertungsskala");

                                    createStars(rating).then((canvas) => {
                                        if (canvas instanceof HTMLCanvasElement) {
                                            container.appendChild(canvas);
                                        }
                                    }).catch((error) => {
                                        console.error("Fehler:", error);
                                    });
                                };
                            </script>
                        </div>
                        <p><?php echo htmlspecialchars($selectedProduct["ratersCount"]); ?></p>
                    </div>
                    <h3><?php echo htmlspecialchars($selectedProduct["description"]); ?></h3>

                    <!-- Sortenauswahl -->
                    <div class="select-wrapper">
                        <p>Geschmack</p>
                        <select>
                            <option>Choco</option>
                            <option>Strawberry</option>
                            <option>Vanilla</option>
                        </select>
                    </div>

                    <!-- Verpackungsgrößen -->
                    <div class='Verpackungsgrößen'>
                        <p>Verpackungsgrößen</p>
                        <div class='VerpackungsgrößenButtons'>
                            <button class="btn" onclick="changeSelectedSize()"><?php echo htmlspecialchars($selectedProduct["availableSizes"][0]); ?></button>
                            <button class="btn" id="selectedSize" onclick="changeSelectedSize()"><?php echo htmlspecialchars($selectedProduct["availableSizes"][1]); ?></button>
                            <button class="btn" onclick="changeSelectedSize()"><?php echo htmlspecialchars($selectedProduct["availableSizes"][2]); ?></button>
                            <button class="btn" onclick="changeSelectedSize()"><?php echo htmlspecialchars($selectedProduct["availableSizes"][3]); ?></button>
                        </div>
                    </div>
                    <script>
                        document.addEventListener('DOMContentLoaded', () => {
                            changeSelectedSize();
                        });
                    </script>


                    <!-- Preis -->
                    <div class='Preis'>
                        <p id="priceWTax"></p>
                        <p id="pricePerKgOutput"></p>
                        <script>
                            document.addEventListener("DOMContentLoaded", () => {
                                const priceWOTax = <?php echo htmlspecialchars($selectedProduct["priceWithoutTax"]); ?>;
                                const priceWTax = getTotalPrice(priceWOTax);
                                document.getElementById("priceWTax").textContent = `${priceWTax} €`;

                                const result = getPricePerKG(priceWTax, document.querySelector('.VerpackungsgrößenButtons .btn.active').textContent);
                                if (result !== undefined) {
                                    document.getElementById("pricePerKgOutput").textContent = `${result}€/kg, inkl. MwSt. zzgl. Versand`;
                                }
                            });
                        </script>
                    </div>


                    <!-- Versand + Favoriten-->
                    <div class='VersandFavoriten'>
                        <button class="VersandButton"
                            onclick="addToCart('Whey Protein Choco', 'images/Choco Whey.webp', 21.00); openCart();">
                            <img src="images/shopping-cart.png" alt="">
                            <span>In den Warenkorb</span>
                        </button>
                        <img class="FavButton" onclick="changeWishListStatus('Whey Protein Choco', 'images/Choco Whey.webp', 21.00)" src="images/Herz_unausgefüllt.png" alt="">
                        <br>
                    </div>
                    <p><?php echo htmlspecialchars($selectedProduct["statusDistribution"]); ?></p>
                </div>

            </section>


            <div class='ProduktinformationKlBild'>

                <!-- Produktinformationen -->
                <div class='Produktinformationen'>

                    <!-- Beschreibung -->
                    <section>
                        <button class="accordion">Beschreibung</button>
                        <div class="panel">
                            <article>
                                <p><?php echo htmlspecialchars($selectedProduct["descriptionDetails"][0]); ?></p>
                            </article>

                            <article>
                                <h4>Produktbezeichnung</h4>
                                <p><?php echo htmlspecialchars($selectedProduct["descriptionDetails"][1]); ?></p>
                            </article>
                        </div>
                        <script>
                            var targetPanel = document.querySelectorAll('.accordion')[0];
                            targetPanel.addEventListener('click', () => openPanel(0));
                        </script>
                    </section>

                    <!-- Inhalt -->
                    <section>
                        <button class="accordion">Inhalt</button>
                        <div class="panel">
                            <article>
                                <h4>Zutaten</h4>
                                <p><?php echo htmlspecialchars($selectedProduct["substance"]["ingredients"]); ?>
                            <br>
                            <?php echo htmlspecialchars($selectedProduct["substance"]["allergens"]);?>
                            </p>
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
                                            <td><?php echo htmlspecialchars($selectedProduct["substance"]["nutrients"]["Energy"]);?></td>
                                        </tr>
                                        <tr>
                                            <td>Fett</td>
                                            <td><?php echo htmlspecialchars($selectedProduct["substance"]["nutrients"]["Fat"]);?></td>
                                        </tr>
                                        <tr>
                                            <td>davon gesättigte Fettsäuren</td>
                                            <td><?php echo htmlspecialchars($selectedProduct["substance"]["nutrients"]["of which saturates"]);?></td>
                                        </tr>
                                        <tr>
                                            <td>Kohlenhydrate</td>
                                            <td><?php echo htmlspecialchars($selectedProduct["substance"]["nutrients"]["Carbohydrates"]);?></td>
                                        </tr>
                                        <tr>
                                            <td>davon Zucker</td>
                                            <td><?php echo htmlspecialchars($selectedProduct["substance"]["nutrients"]["of which sugars"]);?></td>
                                        </tr>
                                        <tr>
                                            <td>Ballaststoffe</td>
                                            <td><?php echo htmlspecialchars($selectedProduct["substance"]["nutrients"]["Fibre"]);?></td>
                                        </tr>
                                        <tr>
                                            <td>Eiweiß / Protein</td>
                                            <td><?php echo htmlspecialchars($selectedProduct["substance"]["nutrients"]["Protein"]);?></td>
                                        </tr>
                                        <tr>
                                            <td>Salz</td>
                                            <td><?php echo htmlspecialchars($selectedProduct["substance"]["nutrients"]["Salt"]);?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </article>

                            <article>
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
                                            <td><?php echo htmlspecialchars($selectedProduct["substance"]["aminoAcids"]["Alanine"]);?></td>
                                        </tr>
                                        <tr>
                                            <td>Arginin</td>
                                            <td><?php echo htmlspecialchars($selectedProduct["substance"]["aminoAcids"]["Arginine"]);?></td>
                                        </tr>
                                        <tr>
                                            <td>Asparaginsäure</td>
                                            <td><?php echo htmlspecialchars($selectedProduct["substance"]["aminoAcids"]["Aspartic acid"]);?></td>
                                        </tr>
                                        <tr>
                                            <td>Cystein</td>
                                            <td><?php echo htmlspecialchars($selectedProduct["substance"]["aminoAcids"]["Cysteine"]);?></td>
                                        </tr>
                                        <tr>
                                            <td>Glutaminsäure</td>
                                            <td><?php echo htmlspecialchars($selectedProduct["substance"]["aminoAcids"]["Glutamic acid"]);?></td>
                                        </tr>
                                        <tr>
                                            <td>Glycin</td>
                                            <td><?php echo htmlspecialchars($selectedProduct["substance"]["aminoAcids"]["Glycine"]);?></td>
                                        </tr>
                                        <tr>
                                            <td>Histidin</td>
                                            <td><?php echo htmlspecialchars($selectedProduct["substance"]["aminoAcids"]["Histidine"]);?></td>
                                        </tr>
                                        <tr>
                                            <td>Isoleucin</td>
                                            <td><?php echo htmlspecialchars($selectedProduct["substance"]["aminoAcids"]["Isoleucine"]);?></td>
                                        </tr>
                                        <tr>
                                            <td>Leucin</td>
                                            <td><?php echo htmlspecialchars($selectedProduct["substance"]["aminoAcids"]["Leucine"]);?></td>
                                        </tr>
                                        <tr>
                                            <td>Lysin</td>
                                            <td><?php echo htmlspecialchars($selectedProduct["substance"]["aminoAcids"]["Lysine"]);?></td>
                                        </tr>
                                        <tr>
                                            <td>Methionin</td>
                                            <td><?php echo htmlspecialchars($selectedProduct["substance"]["aminoAcids"]["Methionine"]);?></td>
                                        </tr>
                                        <tr>
                                            <td>Phenylalanin</td>
                                            <td><?php echo htmlspecialchars($selectedProduct["substance"]["aminoAcids"]["Phenylalanine"]);?></td>
                                        </tr>
                                        <tr>
                                            <td>Prolin</td>
                                            <td><?php echo htmlspecialchars($selectedProduct["substance"]["aminoAcids"]["Proline"]);?></td>
                                        </tr>
                                        <tr>
                                            <td>Serin</td>
                                            <td><?php echo htmlspecialchars($selectedProduct["substance"]["aminoAcids"]["Serine"]);?></td>
                                        </tr>
                                        <tr>
                                            <td>Threonin</td>
                                            <td><?php echo htmlspecialchars($selectedProduct["substance"]["aminoAcids"]["Threonine"]);?></td>
                                        </tr>
                                        <tr>
                                            <td>Tryptophan</td>
                                            <td><?php echo htmlspecialchars($selectedProduct["substance"]["aminoAcids"]["Tryptophan"]);?></td>
                                        </tr>
                                        <tr>
                                            <td>Tyrosin</td>
                                            <td><?php echo htmlspecialchars($selectedProduct["substance"]["aminoAcids"]["Tyrosine"]);?></td>
                                        </tr>
                                        <tr>
                                            <td>Valin</td>
                                            <td><?php echo htmlspecialchars($selectedProduct["substance"]["aminoAcids"]["Valine"]);?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </article>
                        </div>
                        <script>
                            var targetPanel = document.querySelectorAll('.accordion')[1];
                            targetPanel.addEventListener('click', () => openPanel(1));
                        </script>
                    </section>

                    <!-- Anwendung -->
                    <section>
                        <button class="accordion">Anwendung</button>
                        <div class="panel">
                            <article>
                                <h4>Zubereitung</h4>
                                <p><?php echo htmlspecialchars($selectedProduct["usage"]["preparation"]);?></p>
                            </article>

                            <article>
                                <h4>Empfehlung</h4>
                                <p><?php echo htmlspecialchars($selectedProduct["usage"]["recommendation"]);?></p>
                            </article>

                            <article>
                                <h4>Tipp</h4>
                                <p><?php echo htmlspecialchars($selectedProduct["usage"]["tip"]);?></p>
                            </article>

                            <?php $recipeIndex = 0; ?>
                            <div class="btn-group-Rezeptidee">
                                <button id='selectedRecipe' onclick="switchRecipe(1)"><?php echo htmlspecialchars($selectedProduct["usage"]["recipes"][0]["shortTitle"]);?></button>
                                <button onclick="switchRecipe(2)"><?php echo htmlspecialchars($selectedProduct["usage"]["recipes"][1]["shortTitle"]);?></button>
                                <button onclick="switchRecipe(3)"><?php echo htmlspecialchars($selectedProduct["usage"]["recipes"][2]["shortTitle"]);?></button>
                            </div>

                            <article class="Brownie">
                                <h4>Protein-Brownies mit XPN Whey Schokolade</h4>
                                <h5>Zutaten (für ca. 6 Stück):</h5>
                                <ul>
                                    <li>2 reife Bananen (zerdrückt)</li>
                                    <li>2 Eier</li>
                                    <li>40 g XPN Whey Protein Choco</li>
                                    <li>30 g Kakaopulver (ungesüßt)</li>
                                    <li>60 g Haferflocken (zart oder gemahlen)</li>
                                    <li>1 TL Backpulver</li>
                                    <li>1 TL Kokosöl (geschmolzen)</li>
                                    <li>1–2 EL Honig oder Ahornsirup (optional)</li>
                                    <li>1 Prise Salz</li>
                                    <li>Optional: Zartbitterschoko-Stückchen oder Nüsse für extra Crunch</li>
                                </ul>
                                <h5>Zubereitung:</h5>
                                <ol start='1'>
                                    <li>Den Backofen auf 175 °C (Ober-/Unterhitze) vorheizen und eine kleine Form (ca. 20x20
                                        cm)
                                        mit
                                        Backpapier auslegen oder leicht einfetten.</li>
                                    <li>Die Bananen mit einer Gabel zerdrücken und mit den Eiern verrühren.</li>
                                    <li>XPN Whey Protein, Kakaopulver, Haferflocken, Backpulver und Salz untermischen.</li>
                                    <li>Kokosöl und Süßungsmittel hinzufügen und alles zu einem glatten Teig verrühren.
                                        Optional
                                        Schokostückchen oder Nüsse unterheben.</li>
                                    <li>Den Teig gleichmäßig in die Form geben und ca. 20–25 Minuten backen.</li>
                                    <li>Kurz abkühlen lassen, in Stücke schneiden – fertig!</li>
                                </ol>
                            </article>

                            <article class="Porridge">
                                <h4>Protein-Porridge mit XPN Whey Choco</h4>
                                <p>Ein energiereiches Frühstück mit hochwertigem Eiweiß – ideal für Fitness- und
                                    Leistungssport.</p>

                                <h5>Zutaten (für 1 Portion)</h5>
                                <ul>
                                    <li>50 g Haferflocken (zart oder kernig)</li>
                                    <li>200 ml Milch oder Pflanzendrink</li>
                                    <li>1 Messlöffel XPN Whey Protein Choco</li>
                                    <li>1 Prise Salz</li>
                                    <li>Optional: Zimt, Vanille, Banane oder Beeren als natürliche Süße</li>
                                    <li>Topping: Nüsse, Samen, Chiasamen oder Nussmus</li>
                                </ul>

                                <h5>Zubereitung</h5>
                                <ol>
                                    <li>Haferflocken und Milch in einen kleinen Topf geben und bei mittlerer Hitze erhitzen.
                                    </li>
                                    <li>Salz und optional Zimt hinzufügen.</li>
                                    <li>Unter Rühren aufkochen und ca. 5–7 Minuten köcheln lassen, bis ein cremiger Brei
                                        entsteht.</li>
                                    <li>Topf vom Herd nehmen und das XPN Whey Protein Choco unterrühren (nicht kochen!).
                                    </li>
                                    <li>Mit Toppings nach Wahl servieren.</li>
                                </ol>

                            </article>

                            <article class="Milchshake">

                                <h4>Protein-Milchshake mit XPN Whey Choco</h4>
                                <p>Perfekt als Pre- oder Post-Workout-Drink – schnell gemixt und voller Eiweiß.</p>

                                <h5>Zutaten (für 1 Portion)</h5>
                                <ul>
                                    <li>200 ml fettarme Milch oder Pflanzendrink</li>
                                    <li>1 Messlöffel XPN Whey Protein Choco</li>
                                    <li>1 gefrorene Banane oder 1 EL Erdnussbutter (optional, für Energie)</li>
                                    <li>1 TL Kakaopulver (ungesüßt, optional für mehr Schoko-Geschmack)</li>
                                    <li>Optional: etwas Honig oder Datteln zum Süßen</li>
                                </ul>

                                <h5>Zubereitung</h5>
                                <ol>
                                    <li>Alle Zutaten in einen Mixer geben.</li>
                                    <li>So lange mixen, bis die Konsistenz cremig und schaumig ist.</li>
                                    <li>In ein Glas füllen und direkt genießen.</li>
                                </ol>

                                <h5>Tipp</h5>
                                <p>Für mehr Frische kann eine Handvoll Eiswürfel mitgemixt werden. Ideal direkt nach dem
                                    Workout.</p>
                            </article>
                        </div>
                        <script>
                            document.addEventListener('DOMContentLoaded', () => {
                                switchRecipe(1);
                            });
                        </script>
                        <script>
                            var targetPanel = document.querySelectorAll('.accordion')[2];
                            targetPanel.addEventListener('click', () => openPanel(2));
                        </script>
                    </section>

                    <!-- Laboranalysen -->
                    <section>
                        <button class="accordion">Laboranalysen</button>
                        <div class="panel">
                            <p><?php echo htmlspecialchars($selectedProduct["laboratory"]);?></p>
                        </div>
                        <script>
                            var targetPanel = document.querySelectorAll('.accordion')[3];
                            targetPanel.addEventListener('click', () => openPanel(3));
                        </script>
                    </section>

                </div>
                <div class='klBild'>
                <img src="<?php echo htmlspecialchars($selectedProduct['pics']['smallPic']); ?>" alt="">
                </div>

            </div>
        </main>
        <?php include 'components/Footer/footer.php'; ?>
</body>

</html>


<!-- ============================= -->
<!--         Warenkorb-Slider      -->
<!-- ============================= -->

<!-- Der gesamte Slider (standardmäßig ausgeblendet per CSS) -->
<div id="cartSlider" class="cart-slider">

    <!-- Kopfzeile des Sliders mit Titel und Schließen-Button -->
    <div class="cart-header">
        <span>🛒 Produkt hinzugefügt</span> <!-- Textanzeige -->
        <button class="close-btn" onclick="closeCart()">×</button> <!-- Schließen-Symbol -->
    </div>

    <!-- Hauptinhalt des Sliders -->
    <div class="cart-content">

        <!-- Hier wird per JavaScript das aktuell hinzugefügte Produkt angezeigt -->
        <div id="cartItems"></div>

        <!-- Aktions-Buttons unten im Slider -->
        <div class="cart-actions">
            <button onclick="closeCart()">Weiter einkaufen</button> <!-- Schließt den Slider -->
            <button class="go-cart" onclick="window.location.href='cart.html'">Zum Warenkorb</button> <!-- Link zur Warenkorbseite -->
        </div>

    </div>
</div>