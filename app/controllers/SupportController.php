<?php
/**
 * SupportController
 *
 * Enthaelt die Logik zum Steuern der "Eingabeueberwachung", Fehlermedlung, Bestaetigung
 * für die Kontaktseite. Verwendet das SupportModel zur Datenabfrage.
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

/* Einmalige Session-Meldungen danach löschen, um Mehrfachanzeigen zu vermeiden (z.B. erneute Formularuebermittlung) */
        unset($_SESSION['contact_error'], $_SESSION['contact_success']);

/* Prueft, ob das Formular abgesendet wurde */
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $mail = trim($_POST['mail'] ?? '');
            $subject = trim($_POST['subject'] ?? '');
            $message = trim($_POST['message'] ?? '');

        /* Prueft, ob alle Felder ausgefuellt sind */
            if (empty($name) || empty($mail) || empty($subject) || empty($message)) {
                $_SESSION['contact_error'] = "Bitte füllen Sie alle Felder aus.";
        /* Validierung der E-Mail-Adresse */
            } elseif (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['contact_error'] = "Bitte geben Sie eine gültige E-Mail-Adresse ein.";
        /* Wenn alles passt: Speichern des Tickets ueber das Model */
            } else {
                require_once '../app/models/SupportModel.php';
                $saved = SupportModel::saveTicket($name, $mail, $subject, $message);
        /* Erfolgs- oder Fehlermeldung zurueckmelden */
                if ($saved) {
                    $_SESSION['contact_success'] = "Vielen Dank für Ihre Nachricht! Wir melden uns bald bei Ihnen.";
                } else {
                    $_SESSION['contact_error'] = "Ein Fehler ist aufgetreten. Bitte versuchen Sie es später erneut.";
                }
            }

        /* Nach dem POST wird die Seite neu geladen, um ein doppeltes Absenden zu verhindern redirect via header */
            header('Location: index.php?page=kontakt');
            exit;
        }

        require '../app/views/pages/contact.php';
    }
}