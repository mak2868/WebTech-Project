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

    public function isPulver()
    {
        $value = $_GET['value'] ?? null;

        if ($value === null) {
            echo "Kein Wert übergeben.";
            return;
        }

        $result = AdminModel::checkIfPulver($value);

        echo json_encode(['success' => true, 'isPulver' => $result]);

    }

    public function addProduct()
    {
        header('Content-Type: application/json');

        // JSON-Daten aus dem Request einlesen
        $data = json_decode(file_get_contents("php://input"), true);

        if (!$data) {
            echo json_encode([
                'success' => false,
                'message' => 'Ungültige oder leere JSON-Daten übermittelt.'
            ]);
            return;
        }

        try {
            // Übergabe an Model zur Speicherung
            $success = AdminModel::saveFullProduct($data);

            echo json_encode([
                'success' => $success,
                'message' => $success ? 'Produkt wurde erfolgreich gespeichert.' : 'Produkt konnte nicht gespeichert werden.'
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Fehler beim Speichern: ' . $e->getMessage()
            ]);
        }
    }

    public function getAllOrders()
    {
        $userIDs = AdminModel::getAllUserIDs();
        $allOrderHistories = [];

        foreach ($userIDs as $userID) {
            $orders = UserModel::getOrdersWithItems($userID);
            foreach ($orders as $orderID => $order) {
                $order['user_id'] = $userID;
                $allOrderHistories[] = $order;
            }
        }

        // Sortieren nach user_id
        usort($allOrderHistories, fn($a, $b) => $a['user_id'] <=> $b['user_id']);

        header('Content-Type: application/json');
        echo json_encode($allOrderHistories);
        exit;
    }

    public function updateOrderStatus()
{
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['order_id'], $data['new_status'])) {
        http_response_code(400);
        echo json_encode(["error" => "Ungültige Daten"]);
        exit;
    }

    $orderId = $data['order_id'];
    $newStatus = $data['new_status'];

    $affectedRows = AdminModel::updateOrderStatus($orderId, $newStatus);

if ($affectedRows > 0) {
    echo json_encode(["success" => true]);
} else {
    // Es wurde nichts geändert (z. B. gleicher Status wie vorher)
    http_response_code(200); // Kein Fehler
    echo json_encode(["success" => false, "message" => "Kein Update notwendig"]);
}

}

}