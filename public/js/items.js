/**
 * items.js
 * zuständig für die clientseitige Steuerung + Visualisierung der item.php - Seite
 * @author Marvin Kunz
 */

window.openPanel = openPanel;
window.switchRecipe = switchRecipe;
window.switchProductbild = switchProductbild;
window.getTotalPrice = getTotalPrice;
window.getPricePerKG = getPricePerKG;
window.changeSelectedSize = changeSelectedSize;
window.renderItemSite = renderItemSite;
window.intermediateStepRenderItemSite = intermediateStepRenderItemSite;

let product;
let cid;
let initial = true;
let touchStartX = 0;
let touchEndX = 0;
let selectedPic;
let picProductNotOnListSrc = null;
let picProductOnListSrc = null;

/* =================================== */
/* Zentrale Funktionen zur Darstellung */
/* =================================== */

/**
 * Zwischenschritt, zur Vorbereitung auf den erstmaligen Aufruf der renderItemSite()
 * -> clientseitige Überprüfung der vom Server kommenden Parameter auf Existenz und korrekten Syntax
 * -> Aufruf der Funktion renderItemSite() mit allen überprüften Parametern sowie den extrahierten Daten des ausgewählten Produktes aus allen Produktdaten der Kategorie
 * @param {ID der Überkategorie des ausgewählten Produktes} parentID 
 * @param {ID der Unterkategorie des ausgewählten Produktes} cid 
 * @param {ID des ausgewählten Produktes} pid 
 * @param {Index des ausgewählten Produktes innerhalb des Datenarrays der Kategorie} idInData 
 */
function intermediateStepRenderItemSite(parentID, cid, pid, idInData) {
    if ((isNaN(cid) && isNaN(pid) && isNaN(parentID)) || (cid == null && pid == null && parentID == null)) {
        console.error("Parameter error (cid + pid + parentID):", cid, pid, parentID);
    } else if (isNaN(cid) || cid == null) {
        console.error("Parameter error (cid): " + cid);
    } else if (isNaN(pid) || pid == null) {
        console.error("Parameter error (pid): " + pid);
    } else if (isNaN(parentID) || parentID == null) {
        console.error("Parameter error (parentID): " + parentID);
    } else {
        renderItemSite(data[idInData], cid, pid, parentID, idInData);
    }

}

/**
 * Visualisierung / Befüllen aller HTML Elementen auf der item.php - Seite (teilweise abhängig von dem darzustellenden Produkt: bspw. bei Riegeln werden keine Rezepte dargestellt)
 * @param {Daten des ausgewählten Produktes} prod 
 * @param {ID der Unterkategorie des ausgewählten Produktes} lcid 
 * @param {ID des ausgewählten Produktes} pid 
 * @param {ID der Überkategorie des ausgewählten Produktes} parentID 
 * @param {Index des ausgewählten Produktes innerhalb des Datenarrays der Kategorie != pid} idInData 
 * 
 * Information: alle Übergabeparameter werden zur Visualisierung des Produktes benötigt, außer die Parent-ID -> sicherstellen der korrekten Parameter bei erneutem Aufruf der item-Seite bspw. nach Sortenwechsel
 */
function renderItemSite(prod, lcid, pid, parentID, idInData) {
    product = prod;
    cid = lcid;

    history.pushState(
        { cid: cid, pid: pid, parentID: parentID },
        '',
        '?page=item&parent=' + encodeURIComponent(parentID) +
        '&cid=' + encodeURIComponent(cid) +
        '&pid=' + encodeURIComponent(pid)
    );

    if (initial) {
        initial = false;
        let selectBox = document.getElementById('select');
        for (let i = 0; i < data.length; i++) {
            const selectItem = document.createElement('option');
            const productName = data[i].name;

            let productShortName;
            if (productName.includes("White")) {
                productShortName = "White Choco";
            } else {
                productShortName = productName.split(" ").pop();
            }
            selectItem.textContent = productShortName;

            selectBox.appendChild(selectItem);
        }

        selectBox.selectedIndex = (idInData);

        select.addEventListener('change', e => {
            for (let i = 0; i < data.length; i++) {
                if (data[i].name.includes(e.target.value)) {
                    if (e.target.value == "Choco") {
                        renderItemSite(data[0], cid, data[0].pid, parentID, idInData);
                    } else if (e.target.value == "White Choco") {
                        renderItemSite(data[11], cid, data[11].pid, parentID, idInData);
                    } else {
                        renderItemSite(data[i], cid, data[i].pid, parentID, idInData);
                    }
                }
            }
        })
    }

    document.getElementById('pageTitle').textContent = "XPN | " + product.name;

    document.getElementById('name').textContent = product.name;

    const rating = product.rating;
    const starContainer = document.getElementById("Bewertungsskala");
    createStars(rating).then((canvas) => {
        if (canvas instanceof HTMLCanvasElement) {
            const c = starContainer.querySelector("canvas");
            if (c) {
                starContainer.removeChild(c);
            }
            starContainer.appendChild(canvas);
        }
    });
    document.getElementById('ratersCount').textContent = '(' + product.raters_count + ')';

    setPositionFirstLine();

    document.getElementById('description').innerHTML = product.description;

    const verpackungsgrößenButtonsContainer = document.getElementById('VerpackungsgrößenButtons');
    verpackungsgrößenButtonsContainer.innerHTML = "";
    for (let i = 0; i < product.sizes.length; i++) {
        let button = document.createElement('button');
        button.textContent = product.sizes[i] + 'g';

        if (product.sizes[i] == 500 || product.sizes[i] == 45) {
            button.classList.add('active');
        }

        if (product.quantityPerSize[i] === 0) {
            button.classList.add('notAvailable');
        }

        button.onclick = () => changeSelectedSize(button);
        verpackungsgrößenButtonsContainer.appendChild(button);
    }

    const priceWTax = getTotalPrice();
    document.getElementById("priceWTax").textContent = priceWTax + '€';
    const pricePerKG = getPricePerKG(priceWTax, document.querySelector('#VerpackungsgrößenButtons button.active').textContent);
    if (pricePerKG !== undefined) {
        document.getElementById("pricePerKgOutput").textContent = pricePerKG + '€/kg, inkl. MwSt. zzgl. Versand';
    }

    document.getElementById('VersandButtonImg').src = product.shopping_cart;

    picProductNotOnListSrc = product.Herz_unausgefuellt;
    picProductOnListSrc = product.Herz_ausgefuellt;
    document.getElementById('FavButton').src = product.Herz_unausgefuellt;

    document.getElementById('statusDistribution').textContent = product.statusDistribution;

    document.getElementById('descriptionDetails1').textContent = product.descriptionDetails[0];
    document.getElementById('descriptionDetails2').textContent = product.descriptionDetails[1];

    document.getElementById('substanceIngredients').textContent = product.substance.ingredients;
    document.getElementById('substanceAllergens').textContent = product.substance.allergens;

    document.getElementById('substanceNutrientsEnergy').textContent = product.substance.nutrients.energy;
    document.getElementById('substanceNutrientsFat').textContent = product.substance.nutrients.fat;
    document.getElementById('substanceNutrientsFatOfWhichSaturates').textContent = product.substance.nutrients.saturates;
    document.getElementById('substanceNutrientsCarbohydrates').textContent = product.substance.nutrients.carbohydrates;
    document.getElementById('substanceNutrientsOfWhichSugars').textContent = product.substance.nutrients.sugars;
    document.getElementById('substanceNutrientsFibre').textContent = product.substance.nutrients.fibre;
    document.getElementById('substanceNutrientsProtein').textContent = product.substance.nutrients.protein;
    document.getElementById('substanceNutrientsSalt').textContent = product.substance.nutrients.salt;

    if (cid < 4) {
        document.getElementById('substanceAminoAcidsAlanine').textContent = product.substance.aminoAcids.alanine;
        document.getElementById('substanceAminoAcidsArginine').textContent = product.substance.aminoAcids.arginine;
        document.getElementById('substanceAminoAcidsAsparticAcid').textContent = product.substance.aminoAcids.aspartic_acid;
        document.getElementById('substanceAminoAcidsCysteine').textContent = product.substance.aminoAcids.cysteine;
        document.getElementById('substanceAminoAcidsGlutamicAcid').textContent = product.substance.aminoAcids.glutamic_acid;
        document.getElementById('substanceAminoAcidsGlycine').textContent = product.substance.aminoAcids.glycine;
        document.getElementById('substanceAminoAcidsHistidine').textContent = product.substance.aminoAcids.histidine;
        document.getElementById('substanceAminoAcidsIsoleucine').textContent = product.substance.aminoAcids.isoleucine;
        document.getElementById('substanceAminoAcidsLeucine').textContent = product.substance.aminoAcids.leucine;
        document.getElementById('substanceAminoAcidsLysine').textContent = product.substance.aminoAcids.lysine;
        document.getElementById('substanceAminoAcidsMethionine').textContent = product.substance.aminoAcids.methionine;
        document.getElementById('substanceAminoAcidsPhenylalanine').textContent = product.substance.aminoAcids.phenylalanine;
        document.getElementById('substanceAminoAcidsProline').textContent = product.substance.aminoAcids.proline;
        document.getElementById('substanceAminoAcidsSerine').textContent = product.substance.aminoAcids.serine;
        document.getElementById('substanceAminoAcidsThreonine').textContent = product.substance.aminoAcids.threonine;
        document.getElementById('substanceAminoAcidsTryptophan').textContent = product.substance.aminoAcids.tryptophan;
        document.getElementById('substanceAminoAcidsTyrosine').textContent = product.substance.aminoAcids.tyrosine;
        document.getElementById('substanceAminoAcidsValine').textContent = product.substance.aminoAcids.valine;
    } else {
        document.getElementById('aminoAcidProfile').style.display = 'none';
    }

    document.getElementById('usagePreparation').textContent = product.usage.preparation;
    document.getElementById('usageRecommendation').textContent = product.usage.recommendation;
    if (cid < 4) {
        document.getElementById('usageTip').textContent = product.usage.tip;
        if (cid != 2) {
            const recipeButtonsContainer = document.getElementById('btn-group-Rezeptidee');
            recipeButtonsContainer.innerHTML = "";
            for (let i = 0; i < 3; i++) {
                let recipeButton = document.createElement('button');
                recipeButton.textContent = product.usage.recipes[i].short_title;

                recipeButton.onclick = () => switchRecipe(i);
                recipeButtonsContainer.appendChild(recipeButton);
            }
            switchRecipe();
        } else {
            document.getElementById('recipePreparationHeading').style.display = 'none';
            document.getElementById('recipePreparation').style.display = 'none';
            document.getElementById('recipeIngredients').style.display = 'none';
            document.getElementById('tipHeading').style.display = 'none';
            document.getElementById('tipHeading').style.marginTop = '0rem';
        }
    } else {
        document.getElementById('recipePreparationHeading').style.display = 'none';
        document.getElementById('recipePreparation').style.display = 'none';
        document.getElementById('recipeIngredients').style.display = 'none';
        document.getElementById('tipHeading').style.display = 'none';
    }

    document.getElementById('laboratory').textContent = product.laboratory;

    const produktbildAuswahl = document.getElementById('ProduktbildAuswahl');
    produktbildAuswahl.getElementsByTagName('img')[0].src = product.product_pic1;
    produktbildAuswahl.getElementsByTagName('img')[1].src = product.product_pic2;
    produktbildAuswahl.getElementsByTagName('img')[2].src = product.product_pic3;

    const activeProductPic = document.querySelector('#Produktbild img')
    activeProductPic.src = product.product_pic1;
    selectedPic = 0;
    createDots();

    activeProductPic.addEventListener('touchstart', function (event) {
        touchStartX = event.changedTouches[0].screenX;
    }, false);

    activeProductPic.addEventListener('touchend', function (event) {
        touchEndX = event.changedTouches[0].screenX;
        handleSwipe();
    }, false);

    document.getElementById('klBild').src = product.small_pic;
}

/* ================================ */
/* Steuerung des Responsive Designs */
/* ================================ */

/**
 * Steuerung des Wechsel der Produktbilder bei kleinen Endgeräten durch eine Wisch-/Swipebewegung
 */
function handleSwipe() {
    const swipeThreshold = 50;

    if (touchEndX < touchStartX - swipeThreshold) {
        // Swipe nach links → nächstes Bild
        if (selectedPic < 3) {
            selectedPic++;
            switchProductbild(selectedPic);
        }
    }

    if (touchEndX > touchStartX + swipeThreshold) {
        // Swipe nach rechts → vorheriges Bild
        if (selectedPic > 0) {
            selectedPic--;
            switchProductbild(selectedPic);
        }
    }
}

/**
 * Erstellung einer Anzeige (kleine Punkte), die das ausgewählte Bild bei kleinen Endgeräten zeigt
 */
function createDots() {
    const dotContainer = document.getElementById('dotContainer');
    dotContainer.innerHTML = ''; // Alte Dots entfernen

    for (let i = 0; i < 3; i++) {
        const dot = document.createElement('span');
        dot.classList.add('dot');
        if (i === selectedPic) {
            dot.classList.add('active');
        }
        dotContainer.appendChild(dot);
    }
}

/**
 * steuert die Darstellung der ersten Zeile (Produktüberschrift, Bewertungssterne, Anzeige der Anzahl der Bewertungen)
 */
function setPositionFirstLine() {
    const firstLineContainer = document.getElementById('firstLine');
    const nameContainer = document.getElementById('name');
    const ratingCountContainer = document.getElementById('ratersCount');

    // Berechne, wie viel Platz verfügbar ist
    const availableWidthFL = firstLineContainer.clientWidth;

    // Berechne, wie viel Platz die einzelnen Elemente brauchen
    const nameWidth = nameContainer.offsetWidth;
    const ratingWidth = ratingCountContainer.offsetWidth;

    // Wenn der verfügbare Platz mehr ist als die Breite der Elemente nebeneinander, dann behalte sie in einer Reihe
    if (availableWidthFL >= (nameWidth + ratingWidth + 90)) {
        firstLineContainer.style.flexDirection = 'row';
        firstLineContainer.style.alignItems = 'center';
        firstLineContainer.style.gap = '0.5rem';
    } else {
        // Wenn nicht genug Platz vorhanden ist, dann die Elemente untereinander anordnen
        firstLineContainer.style.flexDirection = 'column-reverse';
        firstLineContainer.style.alignItems = 'flex-start';
        firstLineContainer.style.gap = 0;
    }
}

/* =============================== */
/* Funktionalitäten / Berechnungen */
/* =============================== */

/**
 * steurt das Öffnen / die Visualisierung eines Panels (bspw. Inhalt)
 * @param {Index, des zu öffnenden Panels} activatedIndex 
 */
function openPanel(activatedIndex) {
    const acc = document.querySelectorAll('#accordion');

    if (activatedIndex == 2) {
        switchRecipe(0);
    }
    const button = acc[activatedIndex];
    const panel = button.nextElementSibling;

    panel.classList.toggle('open');
    button.classList.toggle('active');

    if (panel.classList.contains('open')) {
        panel.style.maxHeight = panel.scrollHeight + 20 + 'px';
        panel.scrollIntoView({ behavior: 'smooth', block: 'start' });
    } else {
        panel.style.maxHeight = '0px';
    }

    if (panel.classList.contains('open')) {
        panel.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

}

/**
 * steuert die Anzeige des ausgewählten Rezeptes
 * @param {Index des ausgewählten Rezeptes} selectedRecipe 
 */
function switchRecipe(selectedRecipe) {
    if (cid == 1 || cid == 3) {
        if (selectedRecipe == undefined) {
            selectedRecipe = 0;
            document.querySelector('#btn-group-Rezeptidee button:first-child').classList.add('active');
        }
        document.querySelectorAll('#btn-group-Rezeptidee button').forEach(button => {
            button.addEventListener('click', () => {
                document.querySelectorAll('#btn-group-Rezeptidee button').forEach(btn => {
                    btn.classList.remove('active');
                });
                button.classList.add('active');
            });
        });

        document.getElementById('recipeTitle').textContent = product.usage.recipes[selectedRecipe].title;

        if (product.usage.recipes[selectedRecipe].portion == 1) {
            document.getElementById('recipeIngredientsHeading').textContent = "Zutaten (für 1 Portion):";
        } else {
            document.getElementById('recipeIngredientsHeading').textContent = "Zutaten (für " + product.usage.recipes[selectedRecipe].portion + " Portionen):";
        }

        const ingredientsList = document.getElementById('recipeIngredients');
        ingredientsList.innerHTML = "";
        for (let i = 0; i < product.usage.recipes[selectedRecipe].ingredients.length; i++) {
            const listElement = document.createElement('li');
            listElement.textContent = product.usage.recipes[selectedRecipe].ingredients[i];

            ingredientsList.appendChild(listElement);
        }

        const preparationList = document.getElementById('recipePreparation');
        preparationList.innerHTML = "";
        for (let i = 0; i < product.usage.recipes[selectedRecipe].preparation.length; i++) {
            const listElement = document.createElement('li');
            listElement.textContent = product.usage.recipes[selectedRecipe].preparation[i];

            preparationList.appendChild(listElement);
        }
    }
}

/**
 * steuert den Wechsel zwischen den verschiedenen Produktbildern (große Anzeige) sowie dem Auswahlbereich (kleine Anzeige aller Bilder)
 * @param {Index des neu ausgewählten Bildes} pictureNumber 
 */
function switchProductbild(pictureNumber) {
    const pics = document.querySelectorAll('#ProduktbildAuswahl img');

    const picsSrc = [
        product.product_pic1,
        product.product_pic2,
        product.product_pic3
    ];

    let selectedPic;
    let nonSelectedPic1;
    let nonSelectedPic2;

    if (pictureNumber === 0) {
        selectedPic = pics[0];
        nonSelectedPic1 = pics[1];
        nonSelectedPic2 = pics[2];
    } else if (pictureNumber === 1) {
        selectedPic = pics[1];
        nonSelectedPic1 = pics[0];
        nonSelectedPic2 = pics[2];
    } else if (pictureNumber === 2) {
        selectedPic = pics[2];
        nonSelectedPic1 = pics[0];
        nonSelectedPic2 = pics[1];
    }

    if (selectedPic != undefined && nonSelectedPic1 != undefined && nonSelectedPic2 != undefined) {
        selectedPic.style.opacity = 0.8;
        nonSelectedPic1.style.opacity = 0.3;
        nonSelectedPic2.style.opacity = 0.3;

        const productPic = document.querySelector('#Produktbild img');
        productPic.src = picsSrc[pictureNumber];
        createDots()
    }
}

/**
 * clientseitige Berechnung des Produktpreises anhand der ausgewählten Produktgröße
 * @returns Produktpreises des ausgewählten Produktes 
 */
function getTotalPrice() {
    let selectedButton = document.querySelector('#VerpackungsgrößenButtons button.active');
    let buttonContent = selectedButton.textContent.slice(0, -1);
    let index;
    for (let i = 0; i < product.sizes.length; i++) {
        if (buttonContent == product.sizes[i]) {
            index = i;
            break;
        }
    }
    let returnValue = product.priceWithTax[index];

    return returnValue;
}

/**
 * clientseitige Berechnung des Kilopreises anhand der übergebenen Parameter
 * @param {Preis der Produktgröße} price 
 * @param {Gewicht der Produktgröße} totalWeight 
 * @returns 
 */
function getPricePerKG(price, totalWeight) {
    totalWeight = String(totalWeight).slice(0, -1);
    if (totalWeight == "12x45") {
        price = price / 12;
        totalWeight = 45;
    } else if (isNaN(price) || isNaN(totalWeight) || price <= 0 || totalWeight <= 0) {
        return console.error("Preis oder Gewicht der Packung sind unzulässig!");
    }

    let returnValue = price / (totalWeight / 1000);

    return returnValue.toFixed(2);
}

/**
 * Wechsel zwischen den verschiedenen Produktgröße -> korrekte Visualisierung der Buttons, Aufruf entsprechender Preisberechnungsfunktionen & Visualisierung
 * @param {*} selectedButton 
 */
function changeSelectedSize(selectedButton) {
    const allButtons = document.querySelectorAll('#VerpackungsgrößenButtons button');
    if (!selectedButton.classList.contains('notAvailable')) {
        allButtons.forEach(btn => btn.classList.remove('active'));
        selectedButton.classList.add('active');

        const priceWTax = getTotalPrice();
        document.getElementById("priceWTax").textContent = priceWTax + '€';
        const pricePerKG = getPricePerKG(priceWTax, document.querySelector('#VerpackungsgrößenButtons button.active').textContent);
        if (pricePerKG !== undefined) {
            document.getElementById("pricePerKgOutput").textContent = pricePerKG + '€/kg, inkl. MwSt. zzgl. Versand';
        }
    }
}

/**
 * Aufruf der Funktion zur Darstellung der ersten Zeile bei Anpassung der Bildschirmgröße (-> Responsive Design ohne Neuladen der Seite)
 */
let previousWidth = window.innerWidth;

setInterval(function () {
    const currentWidth = window.innerWidth;

    if (previousWidth !== currentWidth) {
        previousWidth = currentWidth;
        setPositionFirstLine();
    }
}, 100);