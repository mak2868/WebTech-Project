<?php
// Einbindung der Datenbankverbindungsklasse.
// Stellt sicher, dass die DB::getConnection() Methode verfügbar ist.
require_once '../app/lib/DB.php';

/**
 * Das UserModel ist für alle Datenbankoperationen zuständig,
 * die Benutzerdaten und Benutzeradressen betreffen.
 * Es agiert als Schnittstelle zwischen der Anwendung und den 'users'-
 * und 'user_addresses'-Tabellen in der Datenbank.
 * @author Merzan Köse
 * @author (Bestellhistorie) Felix Bartel
 */
class UserModel
{
    /**
     * Authentifiziert einen Benutzer anhand von Benutzername und Passwort.
     * Überprüft die Existenz des Benutzernamens und gleicht das gehashte Passwort ab.
     *
     * @param string $username Der Benutzername, der zur Authentifizierung verwendet wird.
     * @param string $password Das vom Benutzer eingegebene Klartext-Passwort.
     * @return array|false Gibt das Benutzer-Array bei erfolgreicher Authentifizierung zurück (enthält alle Spalten des Benutzers),
     * andernfalls false, wenn Benutzername/Passwort nicht übereinstimmen.
     */
    public static function authenticate($username, $password)
    {
        // Holt eine Instanz der Datenbankverbindung.
        $db = DB::getConnection();

        // Bereitet eine SQL-Abfrage vor, um alle Daten eines Benutzers anhand seines Benutzernamens abzurufen.
        // Ein Prepared Statement wird verwendet, um SQL-Injections zu verhindern.
        $stmt = $db->prepare("SELECT * FROM users WHERE username = :username");

        // Führt die Abfrage aus und bindet den Benutzernamen an den Platzhalter :username.
        $stmt->execute(['username' => $username]);

        // Holt die erste Zeile des Abfrageergebnisses als assoziatives Array.
        // Wenn kein Benutzer gefunden wird, ist $user false.
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Prüft, ob ein Benutzer mit dem gegebenen Benutzernamen gefunden wurde
        // UND ob das eingegebene Passwort mit dem gespeicherten Hash übereinstimmt.
        // password_verify() ist die sichere Methode zum Vergleichen von Klartext-Passwörtern mit bcrypt-Hashes.
        if ($user && password_verify($password, $user['password_hash'])) {
            return $user; // Authentifizierung erfolgreich: Gibt die Benutzerdaten zurück.
        }
        return false; // Authentifizierung fehlgeschlagen.
    }

    /**
     * Registriert einen neuen Benutzer in der 'users'-Tabelle.
     * Überprüft, ob Benutzername oder E-Mail bereits existieren, und speichert das Passwort gehasht.
     *
     * @param string $username Der gewünschte Benutzername für den neuen Account.
     * @param string $email Die E-Mail-Adresse des neuen Benutzers.
     * @param string $password Das Klartext-Passwort für den neuen Account.
     * @param string|null $firstName (Optional) Der Vorname des Benutzers. Standard ist null.
     * @param string|null $lastName (Optional) Der Nachname des Benutzers. Standard ist null.
     * @return bool|string Gibt true bei erfolgreicher Registrierung zurück,
     * andernfalls eine Fehlermeldung (string), wenn Benutzername/E-Mail bereits vergeben ist.
     */
    public static function register($username, $email, $password, $firstName = null, $lastName = null)
    {
        $db = DB::getConnection();

        // Prüft, ob der Benutzername oder die E-Mail-Adresse bereits in der Datenbank existiert.
        // Dies verhindert doppelte Einträge.
        $stmt = $db->prepare("SELECT COUNT(*) FROM users WHERE username = :username OR email = :email");
        $stmt->execute(['username' => $username, 'email' => $email]);
        if ($stmt->fetchColumn() > 0) {
            return "Benutzername oder E-Mail ist bereits vergeben!"; // Gibt eine Fehlermeldung zurück.
        }

        // Hashen des Passworts mit dem Standard-Algorithmus (aktuell bcrypt).
        // Passwörter sollten niemals im Klartext in der Datenbank gespeichert werden.
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // Bereitet die SQL-Abfrage zum Einfügen eines neuen Benutzers vor.
        // Enthält auch first_name und last_name.
        $stmt = $db->prepare("INSERT INTO users (username, email, password_hash, first_name, last_name) VALUES (:username, :email, :password_hash, :first_name, :last_name)");

        // Führt die Einfügeoperation aus.
        $success = $stmt->execute([
            'username' => $username,
            'email' => $email,
            'password_hash' => $passwordHash,
            'first_name' => $firstName, // Bindet den Vornamen.
            'last_name' => $lastName    // Bindet den Nachnamen.
        ]);

        return $success === true; // Gibt true bei Erfolg, false bei Fehlschlag zurück.
    }

    /**
     * Ruft alle persönlichen Daten eines Benutzers anhand seines Benutzernamens ab.
     * Wird typischerweise nach einem Login verwendet, um das Session-Array zu befüllen.
     *
     * @param string $username Der Benutzername des gesuchten Benutzers.
     * @return array|false Gibt das Benutzer-Array bei Erfolg zurück, andernfalls false, wenn kein Benutzer gefunden wird.
     */
    public static function getUserByUsername($username)
    {
        $db = DB::getConnection();
        $stmt = $db->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Ruft alle persönlichen Daten eines Benutzers anhand seiner Benutzer-ID ab.
     * Wird häufig verwendet, wenn der Benutzer bereits eingeloggt ist und die ID in der Session gespeichert ist.
     *
     * @param int $userId Die ID des gesuchten Benutzers.
     * @return array|false Gibt das Benutzer-Array bei Erfolg zurück, andernfalls false.
     */
    public static function getUserById($userId)
    {
        $db = DB::getConnection();
        $stmt = $db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Aktualisiert die persönlichen Daten eines Benutzers in der 'users'-Tabelle.
     * Ermöglicht die Aktualisierung von Vorname, Nachname, E-Mail, Telefon, Geburtstag und Geschlecht.
     *
     * @param int $userId Die ID des Benutzers, dessen Daten aktualisiert werden sollen.
     * @param array $data Ein assoziatives Array, das die zu aktualisierenden Daten enthält.
     * Erwartete Schlüssel: 'first_name', 'last_name', 'email', 'phone', 'birthdate', 'gender'.
     * @return bool Gibt true bei erfolgreicher Aktualisierung zurück, andernfalls false.
     */
    public static function updateUser($userId, $data)
    {
        $db = DB::getConnection();
        // Bereitet die SQL-Abfrage zum Aktualisieren der Benutzerdaten vor.
        // Die Aktualisierung erfolgt anhand der eindeutigen Benutzer-ID.
        $stmt = $db->prepare("UPDATE users SET 
            first_name = :first_name,
            last_name = :last_name,
            email = :email,
            phone = :phone,
            birthdate = :birthdate,
            gender = :gender
            WHERE id = :id");

        // Führt die Aktualisierung aus.
        // Der Null Coalescing Operator (?? null) stellt sicher, dass NULL in die Datenbank geschrieben wird,
        // wenn ein Feld im $data-Array nicht vorhanden ist, anstatt eines Fehlers.
        return $stmt->execute([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'birthdate' => $data['birthdate'] ?? null,
            'gender' => $data['gender'] ?? null,
            'id' => $userId // Bindet die Benutzer-ID für die WHERE-Klausel.
        ]);
    }

    /**
     * Ruft die Adresse eines Benutzers aus der 'user_addresses'-Tabelle ab.
     * Da in diesem Setup ein Benutzer nur eine "primäre" Adresse hat, wird LIMIT 1 verwendet.
     *
     * @param int $userId Die ID des Benutzers, dessen Adresse abgerufen werden soll.
     * @return array|false Gibt das Adress-Array bei Erfolg zurück, andernfalls false, wenn keine Adresse gefunden wird.
     */
    public static function getUserAddressByUserId($userId)
    {
        $db = DB::getConnection();
        // Holt die erste Adresse, die der user_id entspricht.
        // 'ORDER BY id ASC LIMIT 1' stellt sicher, dass immer nur eine Adresse zurückgegeben wird,
        // selbst wenn durch einen Fehler mehrere existieren sollten.
        $stmt = $db->prepare("SELECT * FROM user_addresses WHERE user_id = :user_id ORDER BY id ASC LIMIT 1");
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Speichert oder aktualisiert die Adresse eines Benutzers in der 'user_addresses'-Tabelle.
     * Wenn für den Benutzer bereits eine Adresse existiert, wird diese aktualisiert.
     * Andernfalls wird eine neue Adresse eingefügt.
     *
     * @param int $userId Die ID des Benutzers, dessen Adresse gespeichert/aktualisiert werden soll.
     * @param array $data Ein assoziatives Array mit den Adressdaten.
     * Erwartete Schlüssel: 'street', 'zip' (im Code wird es zu 'postal_code'), 'city', 'country'.
     * @return bool Gibt true bei Erfolg zurück, andernfalls false.
     */
    public static function saveUserAddress($userId, $data)
    {
        $db = DB::getConnection();

        // Prüft, ob bereits eine Adresse für diesen Benutzer in der Datenbank existiert.
        $existingAddress = self::getUserAddressByUserId($userId);

        if ($existingAddress) {
            // Wenn eine Adresse existiert, wird sie aktualisiert.
            $stmt = $db->prepare("UPDATE user_addresses SET 
                street = :street,
                city = :city,
                postal_code = :postal_code,
                country = :country,
                updated_at = CURRENT_TIMESTAMP
                WHERE id = :id"); // Aktualisiert anhand der Adress-ID.
            return $stmt->execute([
                'street' => $data['street'] ?? null,
                'city' => $data['city'] ?? null,
                'postal_code' => $data['zip'] ?? null, // Formularfeld 'zip' entspricht DB-Spalte 'postal_code'.
                'country' => $data['country'] ?? 'Deutschland',
                'id' => $existingAddress['id'] // ID der bestehenden Adresse.
            ]);
        } else {
            // Wenn keine Adresse existiert, wird eine neue Adresse eingefügt.
            $stmt = $db->prepare("INSERT INTO user_addresses (user_id, type, street, city, postal_code, country) VALUES (:user_id, :type, :street, :city, :postal_code, :country)");
            return $stmt->execute([
                'user_id' => $userId,
                'type' => 'billing', // Setzt den Adresstyp auf 'billing', da dies ein ENUM-Wert in der DB ist.
                // Wenn Sie verschiedene Adresstypen haben, müsste dieser Wert dynamisch übergeben werden.
                'street' => $data['street'] ?? null,
                'city' => $data['city'] ?? null,
                'postal_code' => $data['zip'] ?? null, // Formularfeld 'zip' entspricht DB-Spalte 'postal_code'.
                'country' => $data['country'] ?? 'Deutschland'
            ]);
        }
    }

    public static function authenticateAdmin($username, $password)
    {
        $db = DB::getConnection();
        $stmt = $db->prepare("SELECT * FROM admin_users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin && password_verify($password, $admin['password_hash'])) {
            return true;
        } else {
            return false;
        }
    }




/**
 * Holt sich die, für die Bestellübersicht nötigen, Daten aus der Tabelle orders und order_items
 * @author: Felix Bartel
 */

    public static function getOrdersWithItems($userId)
    {
        $db = DB::getConnection();

        $stmt = $db->prepare("
        -- Bestellungen mit Proteinpulver
        SELECT 
            o.id as order_id,
            o.order_date,
            o.status,
            o.total,
            ua.street,
            ua.postal_code,
            ua.city,
            ua.country,
            pp.name as product_name,
            ppic.product_pic1 as product_image,
            oi.quantity,
            oi.size
        FROM orders o
        JOIN order_items oi ON o.id = oi.order_id
        LEFT JOIN user_addresses ua ON o.shipping_address_id = ua.id
        JOIN proteinpulver_products pp ON oi.product_id = pp.pid
        JOIN proteinpulver_pictures ppic ON pp.pid = ppic.product_id
        WHERE o.user_id = :user_id

        UNION ALL

        -- Bestellungen mit Proteinriegeln
        SELECT 
            o.id as order_id,
            o.order_date,
            o.status,
            o.total,
            ua.street,
            ua.postal_code,
            ua.city,
            ua.country,
            pr.name as product_name,
            rpic.product_pic1 as product_image,
            oi.quantity,
            oi.size
        FROM orders o
        JOIN order_items oi ON o.id = oi.order_id
        LEFT JOIN user_addresses ua ON o.shipping_address_id = ua.id
        JOIN proteinriegel_products pr ON oi.product_id = pr.pid
        JOIN proteinriegel_pictures rpic ON pr.pid = rpic.product_id
        WHERE o.user_id = :user_id

        ORDER BY order_date DESC
    ");

        $stmt->execute(['user_id' => $userId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Gruppieren nach Bestellung
        $orders = [];
        foreach ($rows as $row) {
            $id = $row['order_id'];
            if (!isset($orders[$id])) {
                $orders[$id] = [
                    'order_date' => $row['order_date'],
                    'status' => $row['status'],
                    'total' => $row['total'],
                    'shipping_address' => "{$row['street']}, {$row['postal_code']} {$row['city']}, {$row['country']}",
                    'items' => []
                ];
            }
            $orders[$id]['items'][] = [
                'product_name' => $row['product_name'],
                'product_image' => $row['product_image'],
                'quantity' => $row['quantity'],
                'size' => $row['size'] ?? null
            ];
        }

        return $orders;
    }
}