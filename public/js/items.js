// Author: Marvin


window.createStars = createStars;
window.openPanel = openPanel;
window.switchRecipe = switchRecipe;
window.switchProductbild = switchProductbild;
window.getTotalPrice = getTotalPrice;
window.getPricePerKG = getPricePerKG;
window.changeSelectedSize = changeSelectedSize;
window.renderItemSite = renderItemSite;
window.intermediateStepChangeWishListStatus = intermediateStepChangeWishListStatus;
window.intermediateStepRenderItemSite = intermediateStepRenderItemSite;

let product;
let cid;
let initial = true;
let touchStartX = 0;
let touchEndX = 0;
let selectedPic;

// Alles /controller



function intermediateStepRenderItemSite(cid, pid) {
    console.log("cid: ", cid, "pid: ", pid);
    if (isNaN(cid) && isNaN(pid) || cid == null && pid == null) {
        console.error("Parameter error (cat + pid): " + cid + ", " + pid);
    } else if (isNaN(cid) || cid == null) {
        console.error("Parameter error: " + cid);
    } else if (isNaN(pid) || pid == null) {
        console.error("Parameter error: " + pid);
    } else {
        renderItemSite(data[pid - 1], cid, pid);
    }
}

function renderItemSite(prod, lcid, pid) {

    console.log(prod);
    console.log(lcid);
    console.log(pid);

    product = prod;
    cid = lcid;

    history.pushState({ cid: cid, pid: pid }, '', '?cid=' + encodeURIComponent(cid) + '&pid=' + encodeURIComponent(pid));

    if (initial) {
        initial = false;
        let selectBox = document.getElementById('select');
        for (let i = 0; i < data.length; i++) {
            const selectItem = document.createElement('option');
            console.log(data);
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

        selectBox.selectedIndex = (pid - 1);

        select.addEventListener('change', e => {
            for (let i = 0; i < data.length; i++) {
                if (data[i].name.includes(e.target.value)) {
                    if (e.target.value == "Choco") {
                        renderItemSite(data[0], cid, data[0].pid);
                    } else if (e.target.value == "White Choco") {
                        renderItemSite(data[11], cid, data[11].pid);
                    } else {
                        renderItemSite(data[i], cid, data[i].pid);
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
    for (let i = 0; i < product.availableSizes.length; i++) {
        let button = document.createElement('button');
        button.textContent = product.availableSizes[i] + 'g';

        if (product.availableSizes[i] == 500 || product.availableSizes[i] == 45) {
            button.classList.add('active');
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
        console.log("swipe");
    }, false);

    document.getElementById('klBild').src = product.small_pic;
}

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

function createDots() {
    const dotContainer = document.getElementById('dotContainer');
    dotContainer.innerHTML = ''; // Alte Dots entfernen

    for (let i = 0; i < 3; i++) {
        const dot = document.createElement('span');
        dot.classList.add('dot');
        if (i === selectedPic) {
            dot.classList.add('active');
            console.log("aktiver dot", selectedPic);
        }
        dotContainer.appendChild(dot);
    }
}

function setPositionFirstLine() {
    console.log("ja");
    const firstLineContainer = document.getElementById('firstLine');
    const nameContainer = document.getElementById('name');
    const ratingCountContainer = document.getElementById('ratersCount');

    // Berechne, wie viel Platz verfügbar ist
    const availableWidthFL = firstLineContainer.clientWidth;

    // Berechne, wie viel Platz die einzelnen Elemente brauchen
    const nameWidth = nameContainer.offsetWidth;
    const ratingWidth = ratingCountContainer.offsetWidth;
    console.log("RC:", ratingWidth);

    // Wenn der verfügbare Platz mehr ist als die Breite der Elemente nebeneinander, dann behalte sie in einer Reihe
    if (availableWidthFL >= (nameWidth + ratingWidth + 90)) {  // 50px Puffer für gap
        console.log(availableWidthFL, nameWidth);
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

function createStars(rating) {
    return new Promise((resolve, reject) => {

        const emptyImg = new Image();
        emptyImg.src = "/WebTech-Project/public/images/StarEmpty.svg";

        const oneQImg = new Image();
        oneQImg.src = "/WebTech-Project/public/images/StarOneQuarter.svg";

        const halfImg = new Image();
        halfImg.src = "/WebTech-Project/public/images/StarHalf.svg";

        const threeQImg = new Image();
        threeQImg.src = "/WebTech-Project/public/images/StarThreeQuarter.svg";

        const fullImg = new Image();
        fullImg.src = "/WebTech-Project/public/images/StarFull.svg";

        const images = [emptyImg, oneQImg, halfImg, threeQImg, fullImg];

        let imagesLoaded = 0;
        const totalImages = images.length;

        const checkLoaded = () => {
            imagesLoaded++;
            if (imagesLoaded === totalImages) {
                resolve([emptyImg, oneQImg, halfImg, threeQImg, fullImg]);
            }
        };

        images.forEach((img) => {
            img.onload = checkLoaded;
            img.onerror = () => {
                console.error(`Fehler beim Laden des Bildes: ${img.src}`);
                reject("Error loading images");
            };
        });
    }).then(([emptyImg, oneQImg, halfImg, threeQImg, fullImg]) => {

        const mod = rating % 1;
        const fullStarsCount = Math.floor(rating);

        let threeQStarCount = false;
        let halfStarCount = false;
        let oneQStarCount = false;

        if (mod !== 0) {
            if (mod >= 0.75) {
                threeQStarCount = true;
            } else if (mod >= 0.5) {
                halfStarCount = true;
            } else if (mod >= 0.25) {
                oneQStarCount = true;
            }
        }

        const canvas = document.createElement("canvas");
        canvas.width = 90;
        canvas.height = 18;
        const ctx = canvas.getContext("2d");

        let currentPosition = 0;

        for (let i = 0; i < fullStarsCount; i++) {
            ctx.drawImage(fullImg, currentPosition * 16 + currentPosition * 2, 0);
            currentPosition++;
        }

        if (oneQStarCount) {
            ctx.drawImage(oneQImg, currentPosition * 16 + currentPosition * 2, 0);
            currentPosition++;
        }

        if (halfStarCount) {
            ctx.drawImage(halfImg, currentPosition * 16 + currentPosition * 2, 0);
            currentPosition++;
        }

        if (threeQStarCount) {
            ctx.drawImage(threeQImg, currentPosition * 16 + currentPosition * 2, 0);
            currentPosition++;
        }

        for (let i = currentPosition; i < 5; i++) {
            ctx.drawImage(emptyImg, currentPosition * 16 + currentPosition * 2, 0);
            currentPosition++;
            canvas.classList.add("invert-keep");
        }

        ctx.scale(0.15, 0.15);
        return canvas;
    }).catch((error) => {
        console.error("Fehler beim Laden der Bilder:", error);
    });
};

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


// Model: Woher Daten?
function getTotalPrice() {
    let selectedButton = document.querySelector('#VerpackungsgrößenButtons button.active');
    let buttonContent = selectedButton.textContent.slice(0, -1);
    let index;
    for (let i = 0; i < product.availableSizes.length; i++) {
        if (buttonContent == product.availableSizes[i]) {
            index = i;
            break;
        }
    }
    let returnValue = product.priceWithTax[index];

    return returnValue;
}

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

function changeSelectedSize(selctedButton) {
    const allButtons = document.querySelectorAll('#VerpackungsgrößenButtons button');
    allButtons.forEach(btn => btn.classList.remove('active'));
    selctedButton.classList.add('active');

    const priceWTax = getTotalPrice();
    document.getElementById("priceWTax").textContent = priceWTax + '€';
    const pricePerKG = getPricePerKG(priceWTax, document.querySelector('#VerpackungsgrößenButtons button.active').textContent);
    if (pricePerKG !== undefined) {
        document.getElementById("pricePerKgOutput").textContent = pricePerKG + '€/kg, inkl. MwSt. zzgl. Versand';
    }
}

// Model, ist halt so ein Daten bums
function intermediateStepChangeWishListStatus() {
    const name = product.name;
    const image = '/WebTech-Project/public/images/Choco Whey.webp';
    const price = getTotalPrice(product.priceWithoutTax);

    changeWishListStatus(name, image, price);
}

let previousWidth = window.innerWidth;

setInterval(function () {
    const currentWidth = window.innerWidth;

    if (previousWidth !== currentWidth) {
        console.log('Fenstergröße geändert!', currentWidth);
        previousWidth = currentWidth;
        setPositionFirstLine();  // Deine Funktion hier aufrufen
    }
}, 100);  

