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
            $isAddressField = $input['isAddressField'];

            if ($isAddressField) {
                // Update in user_addresses
                $result = AdminModel::updateUserAddressData($id, $changedColumn, $changedValue);
            } else {
                // Update in users
                $result = AdminModel::updateUserData($id, $changedColumn, $changedValue);
            }

            if ($result) {
                echo json_encode(['success' => true, 'var' => $isAddressField]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Ungültige Eingabe oder Update fehlgeschlagen']);
            }
        }
    }


    public function deleteUser()
    {
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

    public function showAllParentCategories()
    {
        $results = AdminModel::getAllParentCategories();
        header('Content-Type: application/json');
        echo json_encode($results);
        exit;
    }

    public function addParentCategory()
    {
        header('Content-Type: application/json');

        // JSON-Daten einlesen
        $data = json_decode(file_get_contents("php://input"), true);

        $categoryName = $data['name'];

        try {
            // Modell aufrufen
            AdminModel::addParentCategory($categoryName);

            // Erfolgsantwort (optional: du kannst auch $result prüfen)
            echo json_encode(['success' => true, 'message' => 'Kategorie wurde hinzugefügt.']);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Fehler beim Hinzufügen: ' . $e->getMessage()
            ]);
        }
    }

    public function showAllCategories()
    {
        $resultsParentCategories = AdminModel::getAllParentCategories();
        $resultsCategories = AdminModel::getAllNonParentCategories();

        header('Content-Type: application/json');
        echo json_encode([$resultsParentCategories, $resultsCategories]);
        exit;
    }

    public function addCategory()
    {
        header('Content-Type: application/json');

        // JSON-Daten einlesen
        $data = json_decode(file_get_contents("php://input"), true);

        $parentCategory = $data['parentCategory'];
        $categoryName = $data['name'];

        try {
            // Modell aufrufen
            AdminModel::addCategory($parentCategory, $categoryName);

            // Erfolgsantwort (optional: du kannst auch $result prüfen)
            echo json_encode(['success' => true, 'message' => 'Kategorie wurde hinzugefügt.']);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Fehler beim Hinzufügen: ' . $e->getMessage()
            ]);
        }
    }

    public function getCategories()
    {
        $resultsCategories = AdminModel::getAllNonParentCategories();

        header('Content-Type: application/json');
        echo json_encode([$resultsCategories]);
        exit;
    }
}