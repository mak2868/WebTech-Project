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
        ua.type, ua.street, ua.city, ua.postal_code, ua.country, ua.created_at, ua.updated_at
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

        $stmt = $pdo->prepare(
            "UPDATE user_addresses SET 
            $changedColumn = ?
            WHERE user_id = ?"
        );

        return $stmt->execute([
            $changedValue,
            $userID
        ]);
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

}


// $user = AdminModel::getAllUser();
// echo '<pre>';
// print_r($user);
// echo '</pre>';