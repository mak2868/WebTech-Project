import { updateDarkmodeState } from './darkMode.js';

window.createStars = createStars;
window.openPanel = openPanel;
window.switchRecipe = switchRecipe;
window.switchProductbild = switchProductbild;
window.getTotalPrice = getTotalPrice;
window.getPricePerKG = getPricePerKG;
window.changeSelectedSize = changeSelectedSize;

function createStars(rating) {
    return new Promise((resolve, reject) => {

        const emptyImg = new Image();
        emptyImg.src = "../images/StarEmpty.svg";

        const oneQImg = new Image();
        oneQImg.src = "../images/StarOneQuarter.svg";

        const halfImg = new Image();
        halfImg.src = "../images/StarHalf.svg";

        const threeQImg = new Image();
        threeQImg.src = "../images/StarThreeQuarter.svg";

        const fullImg = new Image();
        fullImg.src = "../images/StarFull.svg";

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
    const acc = document.querySelectorAll('.accordion');

    if (activatedIndex == 2) {
        let selectedRecipe = document.getElementsByClassName('Brownie')[0];
        selectedRecipe.style.display = 'block';
        let nonSelectedRecipe1 = document.getElementsByClassName('Milchshake')[0];
        nonSelectedRecipe1.style.display = 'none';
        let nonSelectedRecipe2 = document.getElementsByClassName('Porridge')[0];
        nonSelectedRecipe2.style.display = 'none';
    }
    const button = acc[activatedIndex];
    const panel = button.nextElementSibling;

    panel.classList.toggle('open');
    button.classList.toggle('active');

    if (panel.classList.contains('open')) {
        panel.style.maxHeight = panel.scrollHeight + 20 + 'px';
    } else {
        panel.style.maxHeight = '0px';
    }

    if (panel.classList.contains('open')) {
        panel.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    document.getElementById('selectedRecipe').classList.add('active');
}


function switchRecipe(buttonNumber) {
    let selectedRecipe;
    let nonSelectedRecipe1;
    let nonSelectedRecipe2;

    if (buttonNumber == 1 ) {
        selectedRecipe = document.getElementsByClassName('Brownie')[0];
        nonSelectedRecipe1 = document.getElementsByClassName('Milchshake')[0];
        nonSelectedRecipe2 = document.getElementsByClassName('Porridge')[0];
    } else if (buttonNumber == 2) {
        selectedRecipe = document.getElementsByClassName('Porridge')[0];
        nonSelectedRecipe1 = document.getElementsByClassName('Brownie')[0];
        nonSelectedRecipe2 = document.getElementsByClassName('Milchshake')[0];
    } else if (buttonNumber == 3) {
        selectedRecipe = document.getElementsByClassName('Milchshake')[0];
        nonSelectedRecipe1 = document.getElementsByClassName('Brownie')[0];
        nonSelectedRecipe2 = document.getElementsByClassName('Porridge')[0];
    }

    nonSelectedRecipe1.style.display = 'none';
    nonSelectedRecipe2.style.display = 'none';
    selectedRecipe.style.display = 'block';

    document.querySelectorAll('.btn-group-Rezeptidee button').forEach(button => {
        button.addEventListener('click', () => {
            document.querySelectorAll('.btn-group-Rezeptidee button').forEach(btn => {
                btn.classList.remove('active');
            });
            button.classList.add('active');
        });
    });
}

function switchProductbild(pictureNumber) {
    const pics = document.querySelectorAll('.ProduktbildAuswahl img');

    const picsSrc = [
        '../images/Choco Whey.webp',
        '../images/choco_whey.jpeg',
        '../images/Proteinpulver_Unsplash.jpg'
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

    const productPic = document.querySelector('.Produktbild img');
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

let isFirstCallchangeSelectedSize = true;

function changeSelectedSize() {

    if (isFirstCallchangeSelectedSize) {
        isFirstCallchangeSelectedSize = false;

        document.getElementById('selectedSize').classList.add('active');
    }
    document.querySelectorAll('.VerpackungsgrößenButtons .btn').forEach(button => {
        button.addEventListener('click', () => {
            document.querySelectorAll('.VerpackungsgrößenButtons .btn').forEach(btn => {
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
        linkElement.href = "../components/Navbar/navbar_intransparent.css";
        updateDarkmodeState();
    } else {
        linkElement.href = "../components/Navbar/navbar_transparent.css";
        updateDarkmodeState();
    }

});

