<?php
/**
 * Datenbankschnittstelle um mit getConnection() redundaten Code zu sparen - Wiederverwendbarkeit
 * @author: Felix Bartel
 */
?>




<?php

class DB {
    private static $pdo = null;

    public static function getConnection() {
        if (self::$pdo === null) {
            try {
                self::$pdo = new PDO(
                    'mysql:host=localhost;dbname=webtech-projekt;charset=utf8',
                    'root',
                    ''
                );
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die('Verbindung fehlgeschlagen: ' . $e->getMessage());
            }
        }
        return self::$pdo;
    }
}
