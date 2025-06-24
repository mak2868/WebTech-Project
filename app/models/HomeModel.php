<?php

require_once __DIR__ . '/../lib/DB.php';

class HomeModel {


   public static function getHeroBackground() {
    $pdo = DB::getConnection();
    $stmt = $pdo->prepare("SELECT bildpfad FROM pictures WHERE bildpfad = ?");
    $stmt->execute(['/images/Hintergrundbild_Startseite.jpg']);
    return $stmt->fetchColumn();
}



    public static function getLogos() {
        $pdo = DB::getConnection();
        $stmt = $pdo->query("SELECT bildpfad FROM pictures WHERE bildpfad LIKE '/images/%_Logo.png'");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    
    public static function getBannerImage() {
        $pdo = DB::getConnection();
        $stmt = $pdo->prepare("SELECT bildpfad FROM pictures WHERE bildpfad = ?");
        $stmt->execute(['/images/Proteinriegel_Banner.png']);
        return $stmt->fetchColumn();
    }

}
