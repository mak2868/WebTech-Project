function createStars(rating) {
    return new Promise((resolve, reject) => {
        
        const basePath = "/WebTech-Project/public/images";

        const emptyImg = new Image(); emptyImg.src = basePath + "/StarEmpty.svg";
        const oneQImg = new Image(); oneQImg.src = basePath + "/StarOneQuarter.svg";
        const halfImg = new Image(); halfImg.src = basePath + "/StarHalf.svg";
        const threeQImg = new Image(); threeQImg.src = basePath + "/StarThreeQuarter.svg";
        const fullImg = new Image(); fullImg.src = basePath + "/StarFull.svg";



        const images = [emptyImg, oneQImg, halfImg, threeQImg, fullImg];
        let imagesLoaded = 0;
        const totalImages = images.length;

        const checkLoaded = () => {
            imagesLoaded++;
            if (imagesLoaded === totalImages) {
                resolve([emptyImg, oneQImg, halfImg, threeQImg, fullImg]);
            }
        };

        images.forEach(img => {
            img.onload = checkLoaded;
            img.onerror = () => reject("Fehler beim Laden: " + img.src);
        });
    }).then(([emptyImg, oneQImg, halfImg, threeQImg, fullImg]) => {
        const mod = rating % 1;
        const fullStarsCount = Math.floor(rating);
        let threeQ = false, half = false, oneQ = false;

        if (mod >= 0.75) threeQ = true;
        else if (mod >= 0.5) half = true;
        else if (mod >= 0.25) oneQ = true;

        const canvas = document.createElement("canvas");
        canvas.width = 90;
        canvas.height = 18;
        const ctx = canvas.getContext("2d");

        let currentPosition = 0;
        for (let i = 0; i < fullStarsCount; i++) {
            ctx.drawImage(fullImg, currentPosition * 18, 0);
            currentPosition++;
        }

        if (oneQ) ctx.drawImage(oneQImg, currentPosition++ * 18, 0);
        if (half) ctx.drawImage(halfImg, currentPosition++ * 18, 0);
        if (threeQ) ctx.drawImage(threeQImg, currentPosition++ * 18, 0);

        for (let i = currentPosition; i < 5; i++) {
            ctx.drawImage(emptyImg, i * 18, 0);
        }

        return canvas;
    });
}

// Beim Laden alle Bewertungen rendern
document.addEventListener("DOMContentLoaded", () => {
    const starDivs = document.querySelectorAll(".stars[data-rating]");
    starDivs.forEach(div => {
        const rating = parseFloat(div.dataset.rating);
        createStars(rating).then(canvas => {
            if (canvas) div.appendChild(canvas);
        });
    });
});
