<?php
require_once __DIR__ . '/../lib/DB.php';
require_once __DIR__ . '/../config/config.php';


class AdminModel
{

    public static function getAllUser()
    {
        $pdo = DB::getConnection();

        $stmtUser = $pdo->prepare("
        SELECT id, username, email, phone, birthdate, gender, first_name, last_name, created_at
        FROM users
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

}


// $user = AdminModel::getAllUser();
// echo '<pre>';
// print_r($user);
// echo '</pre>';