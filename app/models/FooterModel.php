<?php
/**
 * Lädt Bildpfade, parentId + name für footer.php
 * @author: Felix Bartel
 */
?>



<?php

require_once __DIR__ . '/../lib/DB.php';

class FooterModel {
    public static function getSocialIcons() {
        $pdo = DB::getConnection();
        $stmt = $pdo->query("
            SELECT bildpfad 
            FROM pictures 
            WHERE bildpfad IN (
                '/images/facebook.png',
                '/images/youtube.png',
                '/images/instagram.png',
                '/images/tiktok.png'
            )
        ");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public static function getParentCategories() {
    $pdo = DB::getConnection();
    $stmt = $pdo->query("SELECT id, name FROM product_parent_categories");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return array_map(function($cat) {
        return [
            'name' => $cat['name'],
            'id' => $cat['id']
        ];
    }, $categories);
}

}
