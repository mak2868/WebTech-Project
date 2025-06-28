<?php
/**
 * SupportController
 *
 * Enthält die Logik zum Steuern der "Eingabeüberwachung", Fehlermedlung, Bestätigung
 * für die Kontaktseite. Verwendet das AupportModel zur Datenabfrage.
 *
 * @author Nick Zetzmann
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class SupportController
{
    public function contact()
    {
        $error = $_SESSION['contact_error'] ?? null;
        $success = $_SESSION['contact_success'] ?? null;

        // Einmalige Session-Meldungen danach löschen
        unset($_SESSION['contact_error'], $_SESSION['contact_success']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $mail = trim($_POST['mail'] ?? '');
            $subject = trim($_POST['subject'] ?? '');
            $message = trim($_POST['message'] ?? '');

            if (empty($name) || empty($mail) || empty($subject) || empty($message)) {
                $_SESSION['contact_error'] = "Bitte füllen Sie alle Felder aus.";
            } elseif (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['contact_error'] = "Bitte geben Sie eine gültige E-Mail-Adresse ein.";
            } else {
                require_once '../app/models/SupportModel.php';
                $saved = SupportModel::saveTicket($name, $mail, $subject, $message);

                if ($saved) {
                    $_SESSION['contact_success'] = "Vielen Dank für Ihre Nachricht! Wir melden uns bald bei Ihnen.";
                } else {
                    $_SESSION['contact_error'] = "Ein Fehler ist aufgetreten. Bitte versuchen Sie es später erneut.";
                }
            }

            // redirect via header, um erneute Formularübermittlung zu verhindern
            header('Location: index.php?page=kontakt');
            exit;
        }

        require '../app/views/pages/contact.php';
    }
}