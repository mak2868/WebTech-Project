// import { updateDarkmodeState } from './darkMode.js';

window.createStars = createStars;
window.openPanel = openPanel;
window.switchRecipe = switchRecipe;
window.switchProductbild = switchProductbild;
window.getTotalPrice = getTotalPrice;
window.getPricePerKG = getPricePerKG;
window.changeSelectedSize = changeSelectedSize;
window.renderItemSite = renderItemSite;

let product;

function renderItemSite(selectedProduct) {

    console.log("huh");

    if (selectedProduct === undefined) {
        selectedProduct = "Whey Protein Choco";
    }

    let selectBox = document.getElementById('select');
    for (let i = 0; i < data["Whey Proteins"].length; i++) {
        const selectItem = document.createElement('option');
        const productName = data["Whey Proteins"][i].name;

        let productShortName;
        if(productName.includes("White")){
            productShortName = "White Choco"; 
        } else {
            productShortName = productName.split(" ").pop();
        }
        selectItem.textContent = productShortName;

        selectBox.appendChild(selectItem);
    }

    product = data["Whey Proteins"].find(item => item.name === selectedProduct);
    console.log(product);

    document.getElementById('name').textContent = product.name;

    const rating = product.rating;
    const starContainer = document.getElementById("Bewertungsskala");

    createStars(rating).then((canvas) => {
        if (canvas instanceof HTMLCanvasElement) {
            starContainer.appendChild(canvas);
        }
    });

    document.getElementById('ratersCount').textContent = '(' + product.ratersCount + ')';

    document.getElementById('description').innerHTML = product.description;

    const verpackungsgrößenButtonsContainer = document.getElementById('VerpackungsgrößenButtons');
    for (let i = 0; i < product.availableSizes.length; i++) {
        let button = document.createElement('button');
        button.textContent = product.availableSizes[i] + 'g';

        if (product.availableSizes[i] == 500) {
            button.classList.add('active');
        }
        verpackungsgrößenButtonsContainer.appendChild(button);
    }


    const priceWOTax = product.priceWithoutTax;
    const priceWTax = getTotalPrice(priceWOTax);
    document.getElementById("priceWTax").textContent = priceWTax + '€';

    const pricePerKG = getPricePerKG(priceWTax, document.querySelector('#VerpackungsgrößenButtons button.active').textContent.slice(0, -1) / 1000);
    if (pricePerKG !== undefined) {
        document.getElementById("pricePerKgOutput").textContent = pricePerKG + '€/kg, inkl. MwSt. zzgl. Versand';
    }

    document.getElementById('statusDistribution').textContent = product.statusDistribution;

    document.getElementById('descriptionDetails1').textContent = product.descriptionDetails[0];
    document.getElementById('descriptionDetails2').textContent = product.descriptionDetails[1];

    document.getElementById('substanceIngredients').textContent = product.substance.ingredients;
    document.getElementById('substanceAllergens').textContent = product.substance.allergens;

    document.getElementById('substanceNutrientsEnergy').textContent = product.substance.nutrients.Energy;
    document.getElementById('substanceNutrientsFat').textContent = product.substance.nutrients.Fat;
    document.getElementById('substanceNutrientsFatOfWhichSaturates').textContent = product.substance.nutrients["of which saturates"];
    document.getElementById('substanceNutrientsCarbohydrates').textContent = product.substance.nutrients.Carbohydrates;
    document.getElementById('substanceNutrientsOfWhichSugars').textContent = product.substance.nutrients["of which sugars"];
    document.getElementById('substanceNutrientsFibre').textContent = product.substance.nutrients.Fibre;
    document.getElementById('substanceNutrientsProtein').textContent = product.substance.nutrients.Protein;
    document.getElementById('substanceNutrientsSalt').textContent = product.substance.nutrients.Salt;

    document.getElementById('substanceAminoAcidsAlanine').textContent = product.substance.aminoAcids.Alanine;
    document.getElementById('substanceAminoAcidsArginine').textContent = product.substance.aminoAcids.Arginine;
    document.getElementById('substanceAminoAcidsAsparticAcid').textContent = product.substance.aminoAcids["Aspartic acid"];
    document.getElementById('substanceAminoAcidsCysteine').textContent = product.substance.aminoAcids.Cysteine;
    document.getElementById('substanceAminoAcidsGlutamicAcid').textContent = product.substance.aminoAcids["Glutamic acid"];
    document.getElementById('substanceAminoAcidsGlycine').textContent = product.substance.aminoAcids.Glycine;
    document.getElementById('substanceAminoAcidsHistidine').textContent = product.substance.aminoAcids.Histidine;
    document.getElementById('substanceAminoAcidsIsoleucine').textContent = product.substance.aminoAcids.Isoleucine;
    document.getElementById('substanceAminoAcidsLeucine').textContent = product.substance.aminoAcids.Leucine;
    document.getElementById('substanceAminoAcidsLysine').textContent = product.substance.aminoAcids.Lysine;
    document.getElementById('substanceAminoAcidsMethionine').textContent = product.substance.aminoAcids.Methionine;
    document.getElementById('substanceAminoAcidsPhenylalanine').textContent = product.substance.aminoAcids.Phenylalanine;
    document.getElementById('substanceAminoAcidsProline').textContent = product.substance.aminoAcids.Proline;
    document.getElementById('substanceAminoAcidsSerine').textContent = product.substance.aminoAcids.Serine;
    document.getElementById('substanceAminoAcidsThreonine').textContent = product.substance.aminoAcids.Threonine;
    document.getElementById('substanceAminoAcidsTryptophan').textContent = product.substance.aminoAcids.Tryptophan;
    document.getElementById('substanceAminoAcidsTyrosine').textContent = product.substance.aminoAcids.Tyrosine;
    document.getElementById('substanceAminoAcidsValine').textContent = product.substance.aminoAcids.Valine;

    document.getElementById('usagePreparation').textContent = product.usage.preparation;
    document.getElementById('usageRecommendation').textContent = product.usage.recommendation;
    document.getElementById('usageTip').textContent = product.usage.tip;

    const recipeButtonsContainer = document.getElementById('btn-group-Rezeptidee');
    for (let i = 0; i < 3; i++) {
        let recipeButton = document.createElement('button');
        recipeButton.textContent = product.usage.recipes[i].shortTitle;

        recipeButton.onclick = () => switchRecipe(i);
        recipeButtonsContainer.appendChild(recipeButton);
    }
    switchRecipe();

    document.getElementById('laboratory').textContent = product.laboratory;


    document.querySelector('#topPic img').src = product.pics.topPic;

    const produktbildAuswahl = document.getElementById('ProduktbildAuswahl');
    produktbildAuswahl.getElementsByTagName('img')[0].src = product.pics.productPic1;
    produktbildAuswahl.getElementsByTagName('img')[1].src = product.pics.productPic2;
    produktbildAuswahl.getElementsByTagName('img')[2].src = product.pics.productPic3;

    document.querySelector('#Produktbild img').src = product.pics.productPic1;

    document.getElementById('klBild').src = product.pics.smallPic;
}


function createStars(rating) {
    return new Promise((resolve, reject) => {

        const emptyImg = new Image();
        emptyImg.src = "/WebTech-Project/images/StarEmpty.svg";

        const oneQImg = new Image();
        oneQImg.src = "/WebTech-Project/images/StarOneQuarter.svg";

        const halfImg = new Image();
        halfImg.src = "/WebTech-Project/images/StarHalf.svg";

        const threeQImg = new Image();
        threeQImg.src = "/WebTech-Project/images/StarThreeQuarter.svg";

        const fullImg = new Image();
        fullImg.src = "/WebTech-Project/images/StarFull.svg";

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


function switchProductbild(pictureNumber) {
    const pics = document.querySelectorAll('#ProduktbildAuswahl img');

    const picsSrc = [
        product.pics.productPic1,
        product.pics.productPic2,
        product.pics.productPic3
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

    selectedPic.style.opacity = 0.8;
    nonSelectedPic1.style.opacity = 0.3;
    nonSelectedPic2.style.opacity = 0.3;

    const productPic = document.querySelector('#Produktbild img');
    productPic.src = picsSrc[pictureNumber];
}

function getTotalPrice(priceWOTax) {
    let returnValue = priceWOTax * 1.19;

    return returnValue.toFixed(2);
}

function getPricePerKG(price, totalWeight) {
    if (isNaN(price) || isNaN(totalWeight) || price <= 0 || totalWeight <= 0) {
        return console.error("Preis oder Gewicht der Packung sind unzulässig!");
    }

    let returnValue = price / totalWeight

    return returnValue.toFixed(2);
}

function changeSelectedSize() {
    document.querySelectorAll('#VerpackungsgrößenButtons button').forEach(button => {
        button.addEventListener('click', () => {
            document.querySelectorAll('#VerpackungsgrößenButtons button').forEach(btn => {
                btn.classList.remove('active');
            });
            button.classList.add('active');
        });
    });
}


export const scrollBorder = 40;

window.addEventListener('scroll', function () {
    const scrollY = window.scrollY;

    const linkElement = document.querySelector('link[href*="navbar_transparent.css"], link[href*="navbar_intransparent.css"]');

    if (!linkElement) return;

    if (scrollY > scrollBorder) {
        linkElement.href = "/WebTech-Project/components/Navbar/navbar_intransparent.css";
        // updateDarkmodeState();
    } else {
        linkElement.href = "/WebTech-Project/components/Navbar/navbar_transparent.css";
        // updateDarkmodeState();
    }

});
