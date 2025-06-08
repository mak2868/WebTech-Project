<?php

class UserController
{

    public function login()
    {
        // Session starten, falls noch nicht passiert
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $error = null; // Variable für die Fehlermeldung

        // Prüfen, ob das Formular per POST abgeschickt wurde
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            require_once '../app/models/UserModel.php';
            $user = UserModel::authenticate($username, $password);

            if ($user) {
                // Login erfolgreich: User in Session speichern und weiterleiten
                $_SESSION['user'] = $user;
                header('Location: index.php?page=profile');
                exit;
            } else {
                // Fehlertext für die View
                $error = "Benutzername oder Passwort ist falsch!";
            }
        }

        // Zeige das Login-Formular an, ggf. mit Fehlermeldung
        require '../app/views/pages/login.php';
    }


    public function logout()
    {
        // Session starten, falls nicht schon aktiv
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Alle Session-Daten löschen (ausloggen)
        $_SESSION = [];
        session_destroy();

        // (Optional) Weiterleiten zur Start- oder Login-Seite:
        // header('Location: index.php?page=login');
        // exit;

        // Oder eine Logout-Bestätigungsseite anzeigen:
        require '../app/views/pages/logout.php';
    }



    public function register()
    {
        // Session starten (wichtig für spätere Anmeldung oder Erfolgsmeldung)
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $error = null;
        $success = null;

        // Wurde das Formular abgeschickt?
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirm = $_POST['confirm'] ?? '';

            // Eingaben prüfen (Mindestlänge, gleiche Passwörter etc.)
            if ($password !== $confirm) {
                $error = "Die Passwörter stimmen nicht überein!";
            } elseif (strlen($password) < 10) {
                $error = "Das Passwort muss mindestens 10 Zeichen lang sein!";
            } elseif (strlen($username) < 5 || !preg_match('/[A-Z]/', $username) || !preg_match('/[a-z]/', $username)) {
                $error = "Der Benutzername muss mindestens 5 Zeichen haben, sowie Groß- und Kleinbuchstaben enthalten!";
            } else {
                require_once '../app/models/UserModel.php';
                $success = UserModel::register($username, $email, $password);

                if ($success === true) {
                    // Erfolg: leite auf Startseite um
                    header('Location: index.php?page=home');
                    exit;
                } else {
                    // Fehlertext aus dem Model übernehmen
                    $error = $success ?: "Registrierung fehlgeschlagen!";
                    $success = null;
                }
            }
        }

        // Zeige das Formular an, mit eventuellem Fehler/Erfolg
        require '../app/views/pages/registration.php';
    }


    public function profile()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Prüfen, ob User eingeloggt ist
        if (empty($_SESSION['user'])) {
            header('Location: index.php?page=login');
            exit;
        }

        require_once '../app/models/UserModel.php';
        $error = null;
        $success = null;

        // 1. Userdaten aus der DB holen
        $userdata = UserModel::getUserByUsername($_SESSION['user']['username']);

        // 2. Wurde das Formular zum Ändern abgeschickt?
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'firstname' => $_POST['firstname'] ?? '',
                'lastname' => $_POST['lastname'] ?? '',
                'email' => $_POST['email'] ?? '',
                'phone' => $_POST['phone'] ?? '',
                'street' => $_POST['street'] ?? '',
                'zip' => $_POST['zip'] ?? '',
                'city' => $_POST['city'] ?? '',
                'birthdate' => $_POST['birthdate'] ?? '',
                'gender' => $_POST['gender'] ?? ''
            ];

            // Daten speichern (Model aufrufen)
            $result = UserModel::updateUser($_SESSION['user']['username'], $data);
            if ($result === true) {
                $success = "Profil erfolgreich aktualisiert!";
                $userdata = UserModel::getUserByUsername($_SESSION['user']['username']); // Neue Werte laden
            } else {
                $error = $result ?: "Profil konnte nicht gespeichert werden.";
            }
        }

        // View anzeigen, mit Userdaten, Erfolg/Fehler
        require '../app/views/pages/user.php';
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
