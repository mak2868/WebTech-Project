// Author: Marvin
// Datenbankzugriffe in Model


function changeWishListStatus(name, image, price){

    const pic = document.getElementById('FavButton');

    if(pic.src.includes("un")){
        pic.src = picProductOnListSrc;
    } else {
        pic.src = picProductNotOnListSrc;
    }

}