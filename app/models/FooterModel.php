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
}
