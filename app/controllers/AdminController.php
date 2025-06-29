<?php
/**
 * Adminbereich (controller)
 * @author: Marvin Kunz
 */
?>

<?php

require_once '../app/models/AdminModel.php';

class AdminController
{

    /**
     * Funktion um den Adminbereich zu Visualisieren (aber: nur als Admin möglich)
     * @return void
     */
    public function showAdmin()
    {
          if (!isset($_SESSION['is_admin'])) {
            header("Location: index.php?page=login&redirect=admin");
            exit;
            }

        // View laden und Variablen an die View übergeben
        require_once '../app/views/pages/admin.php';
    }

    /**
     * Funktion zum Laden aller Nutzer (Benutzerverwaltung)
     * @return never
     */
    public function userManagement()
    {

        $users = AdminModel::getAllUser();

        // View laden und Variablen an die View übergeben
        header('Content-Type: application/json');

        echo json_encode($users, JSON_UNESCAPED_UNICODE);

        exit();
    }

    /**
     * Funktion die die Änderung der Benutzerdaten steuert -> Aufruf der passenden Funktion im Model anhand der erfolgten Änderung (Unterscheidung, welche Tabelle betroffen ist)
     * @return void
     */
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

    /**
     * Funktion die das Löschen eines Nutzers steuert
     * @return void
     */
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

    /**
     * Funktion, die die Visualisierung der Überkategorien steuert
     * @return never
     */
    public function showAllParentCategories()
    {
        $results = AdminModel::getAllParentCategories();
        header('Content-Type: application/json');
        echo json_encode($results);
        exit;
    }

    /**
     * steuert das Erstellen einer neuen Überkategorie
     * @return void
     */
    public function addParentCategory()
    {
        header('Content-Type: application/json');

        $data = json_decode(file_get_contents("php://input"), true);

        $categoryName = $data['name'];

        try {
            AdminModel::addCategoryWithTables($categoryName);

            echo json_encode(['success' => true, 'message' => 'Kategorie wurde hinzugefügt.']);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Fehler beim Hinzufügen: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Funktion die alle Kategorien anzeigt (-> ruft dafür zwei entsprechenden Model Funktionen auf)
     * @return never
     */
    public function showAllCategories()
    {
        $resultsParentCategories = AdminModel::getAllParentCategories();
        $resultsCategories = AdminModel::getAllNonParentCategories();

        header('Content-Type: application/json');
        echo json_encode([$resultsParentCategories, $resultsCategories]);
        exit;
    }

    /**
     * steuert das Hinzufügen einer neuen Kategorie
     * @return void
     */
    public function addCategory()
    {
        header('Content-Type: application/json');

        $data = json_decode(file_get_contents("php://input"), true);

        $parentCategory = $data['parentCategory'];
        $categoryName = $data['name'];

        try {
            AdminModel::addCategory($parentCategory, $categoryName);

            echo json_encode(['success' => true, 'message' => 'Kategorie wurde hinzugefügt.']);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Fehler beim Hinzufügen: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * steuert das Anzeigen aller Unterkategorien
     * @return never
     */
    public function getCategories()
    {
        $resultsCategories = AdminModel::getAllNonParentCategories();

        header('Content-Type: application/json');
        echo json_encode([$resultsCategories]);
        exit;
    }

    /**
     * steuert die Überprüfung, ob ein Kategorie ein Pulver ist oder nicht 
     * @return void
     */
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

    /**
     * steurt das Hizufügen eines neuen Produktes
     * @return void
     */
    public function addProduct()
    {
        header('Content-Type: application/json');

        $data = json_decode(file_get_contents("php://input"), true);

        if (!$data) {
            echo json_encode([
                'success' => false,
                'message' => 'Ungültige oder leere JSON-Daten übermittelt.'
            ]);
            return;
        }

        try {
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

    /**
     * steuert die Visualisierung aller Bestellungen
     * @return never
     */
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

        usort($allOrderHistories, fn($a, $b) => $a['user_id'] <=> $b['user_id']);

        header('Content-Type: application/json');
        echo json_encode($allOrderHistories);
        exit;
    }

    /**
     * steuert die Änderung des Status einer Bestellung 
     * @return void
     */
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

    /**
     * steuert die Visualisierung aller Supportanfragen
     * @return never
     */
    public function getAllSupportTickets()
    {
        $resultsTickets = AdminModel::getAllSupportTickets();

        header('Content-Type: application/json');
        echo json_encode($resultsTickets); 
        exit;
    }

    /**
     * steuert die Änderung des Status eines Supportticktes 
     * @return void
     */
    public function updateTicketStatus(){
        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['ticket_id'], $data['new_status'])) {
            http_response_code(400);
            echo json_encode(["error" => "Ungültige Daten"]);
            exit;
        }

        $ticketId = $data['ticket_id'];
        $newStatus = $data['new_status'];

        $affectedRows = AdminModel::updateTicketStatus($ticketId, $newStatus);

        if ($affectedRows > 0) {
            echo json_encode(["success" => true]);
        } else {
            // Es wurde nichts geändert (z. B. gleicher Status wie vorher)
            http_response_code(200); // Kein Fehler
            echo json_encode(["success" => false, "message" => "Kein Update notwendig"]);
        }
    }
    
}