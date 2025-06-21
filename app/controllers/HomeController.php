<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}



require_once '../app/models/ProductModel.php';

class HomeController {
    public function index() {
        // Bestseller-Produkte aus dem Model holen
        $produkte = ProductModel::getBestseller();

        // View laden und Daten übergeben
        include '../app/views/pages/home.php';
    }
}
