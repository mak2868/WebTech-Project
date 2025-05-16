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
    <script src="js/items.js"></script>
    <script src="js/cart.js"></script>
    <script src="components/Navbar/navbar.js" defer></script>
    <script src="js/darkmode.js" defer></script>
      <script src="js/wishList.js"></script>


</head>

<body>
    <?php include 'components/Navbar/navbar.php'; ?>
    <div class='topPic'>
        <img src="images/Schokopulver_Top.jpg" alt="">
    </div>

</body>
    <main class='top' tyle="padding-top: 80px">
        <div class='top'>
            <div class='top-left'>
                <!-- Produktbild-Auswahl -->
                <div class="ProduktbildAuswahl">
                    <img src="images/Choco Whey.webp" alt="Bild nicht verfügbar" onclick="switchProductbild(0)">
                    <img src="images/choco_whey.jpeg" alt="" onclick="switchProductbild(1)">
                    <img src="images/Proteinpulver_Unsplash.jpg" alt="" onclick="switchProductbild(2)">
                </div>
                <!-- Produktbild -->
                <div class='Produktbild'>
                    <img src="images/Choco Whey.webp" alt="Bild nicht verfügbar">
                </div>
            </div>

            <div class='top-rigth'>
                <!-- Produktüberschrift -->
                <div class='firstLine'>
                    <h2>Whey Protein Choco</h2>
                    <!-- Bewertungsskala -->
                    <div id="Bewertungsskala">
                        <script>
                            window.onload = () => {
                                const rating = 4.78;
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
                    <p>(4021)</p>
                </div>
                <h3>Deutschlands bestes Whey-Proteinpulver <br> mit Whey-Konzentrat und Whey-Isolat</h3>

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
                        <button class="btn" onclick="changeSelectedSize()">30g</button>
                        <button class="btn" id="selectedSize" onclick="changeSelectedSize()">500g</button>
                        <button class="btn" onclick="changeSelectedSize()">2000g</button>
                        <button class="btn" onclick="changeSelectedSize()">5000g</button>
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
                            const priceWOTax = 21;
                            const priceWTax = getTotalPrice(priceWOTax);
                            document.getElementById("priceWTax").textContent = `${priceWTax} €`;

                            const result = getPricePerKG(priceWTax, 0.25);
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
                    <img class="FavButton"  onclick="changeWishListStatus('Whey Protein Choco', 'images/Choco Whey.webp', 21.00)" src="images/Herz_unausgefüllt.png" alt="">
                    <br>
                </div>
                <p>Sofort verfügbar, Lieferzeit 2-4 Werktage</p>
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
                            <p>Unser Whey Protein Choco vereint höchste XPN Qualität mit bestem Geschmack – ideal für
                                alle,
                                die
                                ihren Muskelaufbau effektiv unterstützen und dabei nicht auf Genuss verzichten möchten.
                                <br> Mit einem hohen Eiweißanteil aus Molkenproteinkonzentrat liefert es deinem Körper
                                schnell
                                verwertbare Proteine, die zur Erhaltung und Zunahme von Muskelmasse beitragen.
                                <br> Egal ob als Shake nach dem Training, zum Frühstück oder als Zutat für deine
                                Fitnessrezepte
                                –
                                unser Choco-Whey ist vielseitig einsetzbar, leicht löslich und angenehm cremig im
                                Geschmack.
                            </p>
                        </article>

                        <article>
                            <h4>Produktbezeichnung</h4>
                            <p>Molkenproteinkonzentrat-Pulver mit Schokoladengeschmack</p>
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
                            <p>Molkenproteinkonzentrat (Milch), fettarmes Kakaopulver, Aroma, Süßungsmittel (Sucralose,
                                Steviolglykoside), Emulgator (Sojalecithin) <br>
                                Allergene: Enthält Milch und Soja. Kann Spuren von Gluten, Ei und Schalenfrüchten
                                enthalten.
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
                                        <td>1577 kJ / 373 kcal</td>
                                    </tr>
                                    <tr>
                                        <td>Fett</td>
                                        <td>5,4 g</td>
                                    </tr>
                                    <tr>
                                        <td>davon gesättigte Fettsäuren</td>
                                        <td>2,8 g</td>
                                    </tr>
                                    <tr>
                                        <td>Kohlenhydrate</td>
                                        <td>9,6 g</td>
                                    </tr>
                                    <tr>
                                        <td>davon Zucker</td>
                                        <td>5,6 g</td>
                                    </tr>
                                    <tr>
                                        <td>Ballaststoffe</td>
                                        <td>2,3 g</td>
                                    </tr>
                                    <tr>
                                        <td>Eiweiß / Protein</td>
                                        <td>72 g</td>
                                    </tr>
                                    <tr>
                                        <td>Salz</td>
                                        <td>1,1 g</td>
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
                                        <td>4,6 g</td>
                                    </tr>
                                    <tr>
                                        <td>Arginin</td>
                                        <td>2,0 g</td>
                                    </tr>
                                    <tr>
                                        <td>Asparaginsäure</td>
                                        <td>11 g</td>
                                    </tr>
                                    <tr>
                                        <td>Cystein</td>
                                        <td>2,7 g</td>
                                    </tr>
                                    <tr>
                                        <td>Glutaminsäure</td>
                                        <td>17 g</td>
                                    </tr>
                                    <tr>
                                        <td>Glycin</td>
                                        <td>1,2 g</td>
                                    </tr>
                                    <tr>
                                        <td>Histidin</td>
                                        <td>1,5 g</td>
                                    </tr>
                                    <tr>
                                        <td>Isoleucin</td>
                                        <td>6,1 g</td>
                                    </tr>
                                    <tr>
                                        <td>Leucin</td>
                                        <td>11 g</td>
                                    </tr>
                                    <tr>
                                        <td>Lysin</td>
                                        <td>9,4 g</td>
                                    </tr>
                                    <tr>
                                        <td>Methionin</td>
                                        <td>1,8 g</td>
                                    </tr>
                                    <tr>
                                        <td>Phenylalanin</td>
                                        <td>3,1 g</td>
                                    </tr>
                                    <tr>
                                        <td>Prolin</td>
                                        <td>5,0 g</td>
                                    </tr>
                                    <tr>
                                        <td>Serin</td>
                                        <td>4,7 g</td>
                                    </tr>
                                    <tr>
                                        <td>Threonin</td>
                                        <td>7,8 g</td>
                                    </tr>
                                    <tr>
                                        <td>Tryptophan</td>
                                        <td>1,7 g</td>
                                    </tr>
                                    <tr>
                                        <td>Tyrosin</td>
                                        <td>3,2 g</td>
                                    </tr>
                                    <tr>
                                        <td>Valin</td>
                                        <td>6,0 g</td>
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
                            <p>Für eine Portion mixt du 30 g Pulver mit 200 ml Wasser in einem Shaker. Du kannst auch
                                Milch
                                oder
                                einen Pflanzendrink verwenden. Beachte, dass sich dann die Nährwerte verändern.</p>
                        </article>

                        <article>
                            <h4>Empfehlung</h4>
                            <p>Wir empfehlen dir 1–3 Portionen Designer Whey pro Tag, z. B. nach dem Aufstehen, nach dem
                                oder
                                während des Trainings oder einfach zwischendurch.</p>
                        </article>

                        <article>
                            <h4>Tipp</h4>
                            <p>Du kannst unser Designer Whey auch zum Backen, Verfeinern oder für deinen Porridge
                                nutzen. <br>Hier sind ein paar Inspirationen:
                            </p>
                        </article>     
                        
                        <div class="btn-group-Rezeptidee">
                            <button id='selectedRecipe' onclick="switchRecipe(1)">Brownie</button>
                            <button onclick="switchRecipe(2)">Porridge</button>
                            <button onclick="switchRecipe(3)">Milchshake</button>
                        </div>

                        <article class="Brownie">
                            <h4>Protein Brownies mit XPN Whey Schokolade</h4>
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
                        <p>Um höchste Qualität zu gewährleisten, lassen wir unsere Produkte regelmäßig in unabhängigen
                            Laboren
                            testen.</p>
                    </div>
                    <script>
                        var targetPanel = document.querySelectorAll('.accordion')[3];
                        targetPanel.addEventListener('click', () => openPanel(3));
                    </script>
                </section>

            </div>
            <div class='klBild'>
                <img src="images/choco_whey.jpeg" alt="">
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
            <button class="go-cart" onclick="window.location.href='cart.html'">Zum Warenkorb</button>
            <!-- Link zur Warenkorbseite -->
        </div>

    </div>
</div>