// app/models/UserModel.php
<?php
require_once '../app/lib/DB.php';

class UserModel
{
    public static function authenticate($username, $password)
    {
        // Hole den User aus der Datenbank
        // Prüfe, ob Username existiert
        // Prüfe, ob Passwort (Hash!) stimmt
        // Gib User-Objekt/Array bei Erfolg zurück, sonst false
    }

    public static function register($username, $password)
    {
        // Hash das Passwort
        // Füge neuen User in die DB ein
        // Gib true/false zurück (je nach Erfolg)
    }

    public static function getUserByUsername($username)
    {
        // Hole User-Daten aus DB (für Profil etc.)
    }
}