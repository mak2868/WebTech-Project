<?php
/**
 * AboutModel
 * 
 * Datenbankabfrage für Bildpfade
 *
 * @author Nick Zetzmann
 */

require_once __DIR__ . '/../lib/DB.php';

class AboutModel {

   public static function getFelix() {
    $pdo = DB::getConnection();
    $stmt = $pdo->prepare("SELECT bildpfad FROM pictures WHERE bildpfad = ?");
    $stmt->execute(['/images/Felix_Bartel.jpeg']);
    return $stmt->fetchColumn();
    }

    public static function getMarvin() {
    $pdo = DB::getConnection();
    $stmt = $pdo->prepare("SELECT bildpfad FROM pictures WHERE bildpfad = ?");
    $stmt->execute(['/images/Marvin_Kunz.jpg']);
    return $stmt->fetchColumn();
    }

    public static function getMerzan() {
    $pdo = DB::getConnection();
    $stmt = $pdo->prepare("SELECT bildpfad FROM pictures WHERE bildpfad = ?");
    $stmt->execute(['/images/Merzan_Koese.jpeg']);
    return $stmt->fetchColumn();
    }

    public static function getNick() {
    $pdo = DB::getConnection();
    $stmt = $pdo->prepare("SELECT bildpfad FROM pictures WHERE bildpfad = ?");
    $stmt->execute(['/images/Nick_Zetzmann.jpeg']);
    return $stmt->fetchColumn();
    }
}