<?php

require_once '../app/models/AdminModel.php';

class AdminController
{

    public function userManagement()
    {

        $users = AdminModel::getAllUser();

        // View laden und Variablen an die View übergeben
        header('Content-Type: application/json');

        echo json_encode($users, JSON_UNESCAPED_UNICODE);

        exit();

        // require_once '../app/views/pages/admin.php';
    }

    public function showAdmin()
    {

        // View laden und Variablen an die View übergeben
        require_once '../app/views/pages/admin.php';
    }

    public function updateUserData()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $input = json_decode(file_get_contents("php://input"), true);

        $id = $input['id'] ?? null;
        $changedColumn = $input['field'] ?? null;
        $changedValue = $input['value'] ?? null;
        $isAddressField = $input['isAddressField'] ?? false;

        if ($isAddressField) {
            // Update in user_addresses
            $result = AdminModel::updateUserAddressData($id, $changedColumn, $changedValue);
        } else {
            // Update in users
            $result = AdminModel::updateUserData($id, $changedColumn, $changedValue);
        }

        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Ungültige Eingabe oder Update fehlgeschlagen']);
        }
    }
}


    public function deleteUser(){
         if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = json_decode(file_get_contents("php://input"), true);

            $id = $input['id'] ?? null;

            $result = AdminModel::deleteUser($id);

            if ($result) {
                echo json_encode(['success' => $result]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Ungültige Eingabe']);
            }


         }
    }
}