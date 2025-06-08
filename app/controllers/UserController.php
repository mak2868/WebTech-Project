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
        require_once '../app/views/pages/logout.php';
    }

    public function register()
    {
        require_once '../app/views/pages/registration.php';
    }


    public function profile()
    {
        require_once '../app/views/pages/user.php';
    }
}
