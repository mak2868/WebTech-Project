/**
 * wishList.js
 * zuständig für die ändernde Visualisierung des Favoriten-Buttons / Buttons, um ein Produkt der Wunschliste hinzuzufügen
 * -> bietet die Grundlage für die vollständige Implementierung einer Wunschliste
 * @author Marvin Kunz
 */

function changeWishListStatus(){

    const pic = document.getElementById('FavButton');

    if(pic.src.includes("un")){
        pic.src = picProductOnListSrc;
    } else {
        pic.src = picProductNotOnListSrc;
    }

}