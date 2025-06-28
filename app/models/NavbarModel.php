<?php
/**
 * Navbar Controller
 *
 * Datenbankabfrage für Categories und Subcategrories (name + id)
 * Datenbankabfrage für Bildpfade
 *
 * @author Felix Bartel
 */



require_once __DIR__ . '/../lib/DB.php';

class NavbarModel {
    public function getCategoriesWithSubcategories() {
        $pdo = DB::getConnection();

        // Elternkategorien holen
        $stmt = $pdo->prepare("SELECT id, name FROM product_parent_categories ORDER BY id ASC");
        $stmt->execute();
        $parents = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $result = [];

        foreach ($parents as $parent) {
            // Zu jeder Elternkategorie die Unterkategorien mit ID und Name holen
            $stmt2 = $pdo->prepare("SELECT id, name FROM product_categories WHERE parent_id = ?");
            $stmt2->execute([$parent['id']]);
            $subcategories = $stmt2->fetchAll(PDO::FETCH_ASSOC); // wichtig!

            $result[] = [
                'name' => $parent['name'],
                'subcategories' => $subcategories // enthält jetzt id + name
            ];
        }

        return $result;
    }


    //Holt die Bildpfade aus der Datenbank und speichert sie in ein Array
    //Das Array wird von getImagePath($image, $keyword) in navbar.php nach dem entsprechenden Bild durchsucht
    public function getNavbarImages() {
        $pdo = DB::getConnection();
        $stmt = $pdo->prepare("SELECT bildpfad FROM pictures");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}
