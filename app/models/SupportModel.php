<?php
/**
 * SupportModel
 * 
 * saveTicket Funktion zum Einfügen der Anfrage in die Datenbank
 *
 * @author Nick Zetzmann
 */

require_once '../app/lib/DB.php';

class SupportModel
{
    public static function saveTicket($name, $mail, $subject, $message)
    {
        $db = DB::getConnection();
        $stmt = $db->prepare("INSERT INTO support_tickets (name, mail, subject, message) VALUES (:name, :mail, :subject, :message)");
        return $stmt->execute([
            'name' => $name,
            'mail' => $mail,
            'subject' => $subject,
            'message' => $message
        ]);
    }
}