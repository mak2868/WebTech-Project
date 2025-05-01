function calcStars(rating) {
    return new Promise((resolve, reject) => {
        // Bilder erstellen
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
        
        // Bilder-Array
        const images = [emptyImg, oneQImg, halfImg, threeQImg, fullImg];
        
        let imagesLoaded = 0;
        const totalImages = images.length;

        // Funktion, um zu überprüfen, ob alle Bilder geladen sind
        const checkLoaded = () => {
            imagesLoaded++;
            if (imagesLoaded === totalImages) {
                resolve([emptyImg, oneQImg, halfImg, threeQImg, fullImg]);
            }
        };

        // Setze onload-Handler für jedes Bild
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

        // Zeichne die vollen Sterne
        for (let i = 0; i < fullStarsCount; i++) {
            ctx.drawImage(fullImg, currentPosition * 16 + currentPosition * 2, 0);
            currentPosition++;
        }

        // Zeichne den Viertelstern, falls vorhanden
        if (oneQStarCount) {
            ctx.drawImage(oneQImg,  currentPosition * 16 + currentPosition * 2, 0);
            currentPosition++;
        }

        // Zeichne den halben Stern, falls vorhanden
        if (halfStarCount) {
            ctx.drawImage(halfImg,  currentPosition * 16 + currentPosition * 2, 0);
            // currentPosition += halfImg.width + 5;
            currentPosition++;

        }

        // Zeichne den Dreiviertelstern, falls vorhanden
        if (threeQStarCount) {
            ctx.drawImage(threeQImg, currentPosition * 16 + currentPosition * 2, 0);
            currentPosition++;
        }

        // Zeichne leere Sterne, bis 5 erreicht sind
        for (let i = currentPosition; i < 5; i++) {
            ctx.drawImage(emptyImg, currentPosition * 16 + currentPosition * 2, 0);
            currentPosition++;
        }

        ctx.scale(0.15, 0.15); 
        return canvas;  
    }).catch((error) => {
        console.error("Fehler beim Laden der Bilder:", error);
    });
}
