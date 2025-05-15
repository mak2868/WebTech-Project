function changeWishListStatus(name, image, price){

    const pic = document.querySelector('.FavButton');

    const picProductNotOnListSrc = "../images/Herz_unausgefüllt.png";
    const picProductOnListSrc = "../images/Herz_ausgefüllt.png"; 

    if(pic.src.includes("un")){
        pic.src = picProductOnListSrc;
    } else {
        pic.src = picProductNotOnListSrc;
    }
   
}