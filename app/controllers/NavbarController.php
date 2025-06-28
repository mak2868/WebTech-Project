<?php
/**
 * Navbar Controller
 *
 * Enthält die Logik zum Laden von Kategorien und Bildern
 * für die Navigationsleiste. Verwendet das NavbarModel zur Datenabfrage.
 *
 * @author Felix Bartel
 */




require_once __DIR__ . '/../models/NavbarModel.php';

class NavbarController {
    private $model;

    public function __construct() {
        $this->model = new NavbarModel();
    }


    public function getNavbarData() {
        $categories = $this->model->getCategoriesWithSubcategories();
        $images = $this->model->getNavbarImages();

        return [
            'categories' => $categories,
            'images' => $images
        ];
    }
}
