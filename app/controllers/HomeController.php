<?php
/**
 * Controller für die view home.php
 * @author: Felix Bartel
 */
?>



<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}



require_once '../app/models/ProductModel.php';
require_once '../app/models/HomeModel.php';

class HomeController {
    public function index() {
        $heroBackground = HomeModel::getHeroBackground();
        $logos = HomeModel::getLogos();
        $bannerImage = HomeModel::getBannerImage();
        $produkte = ProductModel::getBestseller();


        include '../app/views/pages/home.php';
    }
}

