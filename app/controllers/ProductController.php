<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class ProductController
{

    public function renderItemSite($categoryID)
    {
        $produkte = ProductModel::getAllItemsOfKategory($categoryID);

        // View laden und Daten übergeben
        require_once '../app/views/pages/item.php';
    }
}
