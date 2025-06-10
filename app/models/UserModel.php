<?php
require_once '../app/lib/DB.php';

class UserModel
{
    public static function authenticate($username, $password)
    {
        $db = DB::getConnection();
        
        // User anhand des Namens holen
        $stmt = $db->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Prüfen, ob User existiert und Passwort stimmt
        if ($user && password_verify($password, $user['password_hash'])) {
            return $user; // User-Array zurückgeben (Login erfolgreich)
        }
        return false; // Login fehlgeschlagen
    }


    public static function register($username, $email, $password)
    {
        $db = DB::getConnection();

        // Prüfen, ob Username oder E-Mail schon vergeben sind
        $stmt = $db->prepare("SELECT COUNT(*) FROM users WHERE username = :username OR email = :email");
        $stmt->execute(['username' => $username, 'email' => $email]);
        if ($stmt->fetchColumn() > 0) {
            return "Benutzername oder E-Mail ist bereits vergeben!";
        }

        // Passwort hashen (niemals Klartext speichern!)
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // User einfügen
        $stmt = $db->prepare("INSERT INTO users (username, email, password_hash) VALUES (:username, :email, :password_hash)");
        $success = $stmt->execute([
            'username' => $username,
            'email' => $email,
            'password_hash' => $passwordHash
        ]);

        // Erfolg (true) oder Fehler (false) zurückgeben
        return $success === true;
    }


    public static function getUserByUsername($username)
    {
        $db = DB::getConnection();
        $stmt = $db->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        return $stmt->fetch(PDO::FETCH_ASSOC); // Gibt Userdaten als Array zurück (oder false)
    }


    public static function updateUser($username, $data)
    {
        $db = DB::getConnection();
        $stmt = $db->prepare("UPDATE users SET 
        firstname = :firstname,
        lastname = :lastname,
        email = :email,
        phone = :phone,
        street = :street,
        zip = :zip,
        city = :city,
        birthdate = :birthdate,
        gender = :gender
        WHERE username = :username");
        return $stmt->execute([
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'street' => $data['street'],
            'zip' => $data['zip'],
            'city' => $data['city'],
            'birthdate' => $data['birthdate'],
            'gender' => $data['gender'],
            'username' => $username
        ]);
    }
}