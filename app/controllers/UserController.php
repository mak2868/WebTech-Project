<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Der UserController verwaltet alle Benutzeraktionen wie Login, Registrierung,
 * Logout und die Profilverwaltung. Er interagiert mit dem UserModel,
 * um Daten aus der Datenbank abzurufen und zu speichern, und lädt die entsprechenden Views.
 * @author Merzan
 */

class UserController
{
    /**
     * Verwaltet den Login-Prozess des Benutzers.
     * Behandelt POST-Anfragen für die Authentifizierung und lädt die Login-View.
     */
public function login()
{
    // Starte die Session, falls noch nicht aktiv
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $error = null; // Zum Anzeigen von Fehlern bei falschem Login

    // Nur wenn das Formular per POST abgeschickt wurde
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Benutzereingaben holen (Username & Passwort)
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        // Lade das UserModel für die Authentifizierung
        require_once '../app/models/UserModel.php';

        // Versuche den Benutzer mit den eingegebenen Daten zu authentifizieren
        $user = UserModel::authenticate($username, $password);

        // Wenn Authentifizierung erfolgreich war:
        if ($user) {
            // Benutzer in der Session speichern
            $_SESSION['user'] = $user;
            $_SESSION['user_id'] = $user['id'];

            // Weiterleitungsziel ermitteln (z. B. "checkout" oder "home")
            // Falls jemand als Gast zur Kasse wollte → redirect=checkout
            $redirectPage = $_POST['redirect'] ?? $_GET['redirect'] ?? 'home';

            // Weiterleitung zur Zielseite nach Login
            header('Location: index.php?page=' . urlencode($redirectPage));
            exit;
        } else {
            // Bei Fehlschlag Fehlermeldung setzen
            $error = "Benutzername oder Passwort ist falsch!";
        }
    }

    // Login-Formular anzeigen (inkl. möglicher Fehlermeldung)
    require '../app/views/pages/login.php';
}


    /**
     * Verwaltet den Logout-Prozess des Benutzers.
     * Zerstört die aktuelle Benutzersession und leitet zur Login-Seite um.
     */
    public function logout()
    {
        // Startet die Session, falls noch nicht geschehen.
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Löscht alle Session-Variablen.
        $_SESSION = [];

        // Zerstört die Session auf dem Server.
        session_destroy();

        // Leitet den Benutzer zur Login-Seite um.
        header('Location: index.php?page=login');
        exit;
    }

    /**
     * Verwaltet den Registrierungsprozess eines neuen Benutzers.
     * Behandelt POST-Anfragen für die Registrierung und lädt die Registrierungs-View.
     */
    public function register()
    {
        // Startet die Session, falls noch nicht geschehen.
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $error = null;   // Variable zum Speichern von Fehlermeldungen.
        $success = null; // Variable zum Speichern von Erfolgsmeldungen (hier nicht direkt genutzt für Redirect).

        // Prüft, ob das Registrierungsformular per POST abgeschickt wurde.
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Empfängt Registrierungsdaten aus dem Formular.
            $username = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirm = $_POST['confirm'] ?? ''; // Bestätigungspasswort.
            $firstName = $_POST['first_name'] ?? null; // Vorname (optional, falls im Formular)
            $lastName = $_POST['last_name'] ?? null;   // Nachname (optional, falls im Formular)

            // Validierung der Eingaben (serverseitig).
            if ($password !== $confirm) {
                $error = "Die Passwörter stimmen nicht überein!";
            } elseif (strlen($password) < 10) {
                $error = "Das Passwort muss mindestens 10 Zeichen lang sein!";
            } elseif (strlen($username) < 5 || !preg_match('/[A-Z]/', $username) || !preg_match('/[a-z]/', $username)) {
                $error = "Der Benutzername muss mindestens 5 Zeichen haben, sowie Groß- und Kleinbuchstaben enthalten!";
            } else {
                // Lädt das UserModel für die Registrierungslogik.
                require_once '../app/models/UserModel.php';

                // Ruft die register-Methode im UserModel auf.
                // Gibt true bei Erfolg oder eine Fehlermeldung zurück.
                $registration_result = UserModel::register($username, $email, $password, $firstName, $lastName);

                // Prüft das Ergebnis der Registrierung.
                if ($registration_result === true) {
                    // Bei erfolgreicher Registrierung den neu erstellten Benutzer abrufen
                    // und in die Session legen, um ihn direkt einzuloggen.
                    $user = UserModel::getUserByUsername($username);
                    if ($user) {
                        $_SESSION['user'] = $user;
                        $_SESSION['user_id'] = $user['id'];

                        header('Location: index.php?page=home'); // Leitet zur Profilseite um.
                        exit;
                    } else {
                        $error = "Registrierung erfolgreich, aber Benutzerdaten konnten nicht abgerufen werden. Bitte melden Sie sich an.";
                    }
                } else {
                    // Wenn die Registrierung fehlschlägt, Fehlermeldung setzen.
                    $error = $registration_result ?: "Registrierung fehlgeschlagen! Ein unerwarteter Fehler ist aufgetreten.";
                }
            }
        }
        // Lädt die Registrierungs-View. Die $error-Variable ist in dieser View verfügbar.
        require '../app/views/pages/registration.php';
    }

    /**
     * Zeigt die Benutzerprofilseite an und verwaltet die Aktualisierung der Profildaten.
     * Stellt sicher, dass der Benutzer eingeloggt ist.
     */
    public function profile()
    {
        // Startet die Session, falls noch nicht geschehen.
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Prüft, ob der Benutzer eingeloggt ist (überprüft die 'id' in der Session).
        if (empty($_SESSION['user']['id'])) {
            header('Location: index.php?page=login'); // Leitet zur Login-Seite um, wenn nicht eingeloggt.
            exit;
        }

        // Lädt das UserModel für Datenbankoperationen.
        require_once '../app/models/UserModel.php';

        $error = null;   // Für Fehlermeldungen.
        $success = null; // Für Erfolgsmeldungen.

        $userId = $_SESSION['user']['id']; // Holt die Benutzer-ID aus der Session.

        // Ruft Benutzerdaten und Adressdaten aus dem UserModel ab.
        $userdata = UserModel::getUserById($userId); // Holt persönliche Benutzerdaten.
        $addressdata = UserModel::getUserAddressByUserId($userId); // Holt die Adressdaten.
        $orderHistory = UserModel::getOrdersWithItems($userId);


        // Prüft, ob die Benutzerdaten geladen werden konnten.
        if (!$userdata) {
            $error = "Benutzerdaten konnten nicht geladen werden. Bitte melden Sie sich erneut an.";
            session_destroy(); // Session löschen, falls Daten nicht abrufbar sind.
            header('Location: index.php?page=login');
            exit;
        }

        // Verarbeitet das Absenden des Profil-Update-Formulars.
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Erstellt ein Array mit persönlichen Daten zur Aktualisierung in der 'users'-Tabelle.
            $userDataToUpdate = [
                'first_name' => $_POST['first_name'] ?? '',
                'last_name' => $_POST['last_name'] ?? '',
                'email' => $_POST['email'] ?? '',
                'phone' => $_POST['phone'] ?? '',
                'birthdate' => $_POST['birthdate'] ?? '',
                'gender' => $_POST['gender'] ?? ''
            ];

            // Erstellt ein Array mit Adressdaten zur Aktualisierung/Speicherung in der 'user_addresses'-Tabelle.
            $addressDataToUpdate = [
                'street' => $_POST['street'] ?? '',
                'zip' => $_POST['zip'] ?? '', // 'zip' aus dem Formular wird als 'postal_code' im Model gespeichert
                'city' => $_POST['city'] ?? '',
                'country' => $_POST['country'] ?? 'Deutschland' // Standardwert, falls nicht im Formular
            ];

            // Ruft die Update-Methoden in UserModel auf.
            $userUpdateResult = UserModel::updateUser($userId, $userDataToUpdate);
            $addressSaveResult = UserModel::saveUserAddress($userId, $addressDataToUpdate);

            // Prüft, ob beide Update-Operationen erfolgreich waren.
            if ($userUpdateResult === true && $addressSaveResult === true) {
                $success = "Profil und Adresse erfolgreich aktualisiert!";

                // Aktualisiert die Session mit den neuesten Benutzerdaten (wichtig, falls sich z.B. der Name ändert).
                $_SESSION['user'] = UserModel::getUserById($userId);

                // Aktualisiert die lokalen Variablen für die erneute Anzeige der View.
                $userdata = $_SESSION['user'];
                $addressdata = UserModel::getUserAddressByUserId($userId);
            } else {
                $error = "Profil oder Adresse konnte nicht gespeichert werden.";
                // Hier könnte man detailliertere Fehlermeldungen hinzufügen,
                // je nachdem, welcher der beiden Updates fehlgeschlagen ist.
            }
        }

        // Lädt die Benutzerprofil-View.
        // Die Variablen $userdata, $addressdata, $error und $success sind in dieser View verfügbar.
        require '../app/views/pages/user.php';
    }
}
