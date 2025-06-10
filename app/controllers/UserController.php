<?php

class UserController
{
    public function login()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            require_once '../app/models/UserModel.php';
            $user = UserModel::authenticate($username, $password);

            // JETZT: Die Weiterleitung wieder AKTIVIEREN und Debug-Ausgaben entfernen!
            if ($user) {
                $_SESSION['user'] = $user;
                header('Location: index.php?page=profile'); // DIESE ZEILE MUSS JETZT AKTIV SEIN!
                exit; // DIESE ZEILE MUSS JETZT AKTIV SEIN!
            } else {
                $error = "Benutzername oder Passwort ist falsch!";
                // Entferne die Debug-Ausgaben hier, da der Login fehlgeschlagen ist.
            }
        }
        require '../app/views/pages/login.php';
    }


    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION = [];
        session_destroy();
        require '../app/views/pages/logout.php';
    }


    public function register()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $error = null;
        $success = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirm = $_POST['confirm'] ?? '';

            if ($password !== $confirm) {
                $error = "Die Passwörter stimmen nicht überein!";
            } elseif (strlen($password) < 10) {
                $error = "Das Passwort muss mindestens 10 Zeichen lang sein!";
            } elseif (strlen($username) < 5 || !preg_match('/[A-Z]/', $username) || !preg_match('/[a-z]/', $username)) {
                $error = "Der Benutzername muss mindestens 5 Zeichen haben, sowie Groß- und Kleinbuchstaben enthalten!";
            } else {
                require_once '../app/models/UserModel.php';
                $registration_result = UserModel::register($username, $email, $password);

                if ($registration_result === true) {
                    $user = UserModel::getUserByUsername($username);
                    if ($user) {
                        $_SESSION['user'] = $user;
                        header('Location: index.php?page=profile');
                        exit;
                    } else {
                        $error = "Registrierung erfolgreich, aber Benutzerdaten konnten nicht abgerufen werden. Bitte melden Sie sich an.";
                    }
                } else {
                    $error = $registration_result ?: "Registrierung fehlgeschlagen! Ein unerwarteter Fehler ist aufgetreten.";
                }
            }
        }
        require '../app/views/pages/registration.php';
    }


    public function profile()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user'])) {
            header('Location: index.php?page=login');
            exit;
        }

        require_once '../app/models/UserModel.php';
        $error = null;
        $success = null;

        $username_session = $_SESSION['user']['username'] ?? null;
        $userdata = null;

        if ($username_session) {
            $userdata = UserModel::getUserByUsername($username_session);
            if (!$userdata) {
                $error = "Benutzerdaten konnten nicht geladen werden.";
                session_destroy();
                header('Location: index.php?page=login');
                exit;
            }
        } else {
            $error = "Benutzername nicht in Session gefunden. Bitte neu anmelden.";
            session_destroy();
            header('Location: index.php?page=login');
            exit;
        }


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

            $result = UserModel::updateUser($username_session, $data);
            if ($result === true) {
                $success = "Profil erfolgreich aktualisiert!";
                $userdata = UserModel::getUserByUsername($username_session);
                $_SESSION['user'] = $userdata;
            } else {
                $error = $result ?: "Profil konnte nicht gespeichert werden.";
            }
        }
        require '../app/views/pages/user.php';
    }

 }