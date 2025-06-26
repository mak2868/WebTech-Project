<?php
require_once __DIR__ . '/../lib/DB.php';
require_once __DIR__ . '/../config/config.php';


class AdminModel
{

    public static function getAllUser()
    {
        $pdo = DB::getConnection();

        $stmtUser = $pdo->prepare("
        SELECT 
        u.id, u.username, u.email, u.phone, u.birthdate, u.gender, 
        u.first_name, u.last_name, u.created_at,
        ua.type, ua.street, ua.city, ua.postal_code, ua.country, ua.created_at AS addCreated_at, ua.updated_at AS addUpdated_at
        FROM 
        users u
        LEFT JOIN 
        user_addresses ua ON u.id = ua.user_id;
        ");
        $stmtUser->execute();
        $users = $stmtUser->fetchAll(PDO::FETCH_ASSOC);

        return $users;
    }

    public static function updateUserData($userID, $changedColumn, $changedValue)
    {
        $pdo = DB::getConnection();

        $stmt = $pdo->prepare(
            "UPDATE users SET 
            $changedColumn = ?
            WHERE id = ?"
        );

        return $stmt->execute([
            $changedValue,
            $userID
        ]);
    }

    public static function updateUserAddressData($userID, $changedColumn, $changedValue)
    {
        $pdo = DB::getConnection();

        // 1. Prüfen, ob Adresse für diesen Benutzer existiert
        $checkStmt = $pdo->prepare("SELECT 1 FROM user_addresses WHERE user_id = ?");
        $checkStmt->execute([$userID]);
        $exists = $checkStmt->fetchColumn() !== false;

        if ($exists) {
            // 2. Falls vorhanden: UPDATE
            $stmt = $pdo->prepare(
                "UPDATE user_addresses SET 
                $changedColumn = ?, 
                updated_at = NOW()
                WHERE user_id = ?"
            );

            return $stmt->execute([
                $changedValue,  
                $userID
            ]);
        } else {
            // 3. Falls nicht vorhanden: INSERT
            if ($changedColumn === 'type') {
                $stmt = $pdo->prepare("INSERT INTO user_addresses (user_id, $changedColumn) VALUES (?, ?)");
                return $stmt->execute([$userID, $changedValue]);
            } else {
                $stmt = $pdo->prepare("INSERT INTO user_addresses (user_id, `type`, $changedColumn) VALUES (?, 'billing', ?)");
                return $stmt->execute([$userID, $changedValue]);
            }
        }
    }


    public static function deleteUser($userID)
    {
        $pdo = DB::getConnection();

        $stmt1 = $pdo->prepare("DELETE FROM user_addresses WHERE user_id = ?");
        $stmt1->execute([$userID]);

        $stmt2 = $pdo->prepare(
            "DELETE FROM users WHERE id = ?"
        );

        return $stmt2->execute([
            $userID
        ]);
    }

    public static function getAllParentCategories()
    {
        $pdo = DB::getConnection();

        $stmt = $pdo->prepare("SELECT * FROM product_parent_categories");
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }


    public static function addParentCategory($categoryName)
    {
        $pdo = DB::getConnection();

        $stmt = $pdo->prepare("INSERT INTO product_parent_categories(name) VALUES (:name)");
        $stmt->execute([':name' => $categoryName]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getAllNonParentCategories()
    {
        $pdo = DB::getConnection();

        $stmt = $pdo->prepare("SELECT * FROM product_categories");
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

public static function addCategory($parentCategoryName, $categoryName) {
    $pdo = DB::getConnection();
    
    // 1. Holen der parent_id anhand des Namens der übergeordneten Kategorie
    $stmt = $pdo->prepare("SELECT id FROM product_parent_categories WHERE name = :name");
    $stmt->execute([':name' => $parentCategoryName]);
    $parentCategoryId = $stmt->fetchColumn(); // Gibt die ID zurück, falls vorhanden
    
    if (!$parentCategoryId) {
        // Fehler: Elternkategorie nicht gefunden
        throw new Exception("Die übergeordnete Kategorie '$parentCategoryName' existiert nicht.");
    }

    // 2. Nun die neue Kategorie einfügen
    $stmt = $pdo->prepare("INSERT INTO product_categories(name, parent_id) VALUES (:name, :parent_id)");
    $stmt->execute([':name' => $categoryName, ':parent_id' => $parentCategoryId]);

    // 3. Erfolg zurückgeben (z.B. mit ID der neuen Kategorie)
    return $pdo->lastInsertId(); // Gibt die ID der neuen Kategorie zurück
}


}


// $user = AdminModel::getAllUser();
// echo '<pre>';
// print_r($user);
// echo '</pre>';