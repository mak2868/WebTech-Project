// Author: Marvin
// Datenbankzugriffe in Model


function changeWishListStatus(name, image, price){

    const pic = document.getElementById('FavButton');

    const picProductNotOnListSrc = "/WebTech-Project//images/Herz_unausgefüllt.png";
    const picProductOnListSrc = "/WebTech-Project//images/Herz_ausgefüllt.png"; 

    if(pic.src.includes("un")){
        pic.src = picProductOnListSrc;
    } else {
        pic.src = picProductNotOnListSrc;
    }

}