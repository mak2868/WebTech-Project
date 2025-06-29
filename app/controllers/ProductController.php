<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class ProductController
{

    /**
     * steurt die Visualisierung der Item-Seite 
     * @param mixed $parentID: ID der Überkategorie, des anzuzeigenden Produktes
     * @param mixed $cid: ID der Unterkategorie, des anzuzeigenden Produktes
     * @param mixed $pid: ID des anzuzeigenden Produktes
     * @param mixed $idInData: Positionierung / Index des Produktes innerhalb der Daten einer Kategorie
     * @return void
     * @author Marvin Kunz
     */
    public function renderItemSite($parentID, $cid, $pid, $idInData)
    {
        $produkte = ProductModel::getAllItemsOfCategory($cid);

        // View laden und Variablen an die View übergeben
        require_once '../app/views/pages/item.php';
    }

    /**
     * Überprüfung der in der URL angegebenen Parameter auf Existenz, sowie Korrektheit der Syntax und der Semantik
     * @return array<bool|int|mixed|null>
     */
    public function validateParams()
    {
        $messages = [];
        $parentID = null;
        $cid = null;
        $pid = null;
        $idInData = null;
        $allParamsCorrect = true;

        if (isset($_GET["parent"])) {
            if (trim($_GET["parent"]) === '') {
                $allParamsCorrect = false;
                $messages[] = 'Der Parameter "parent" wurde übergeben, ist aber leer.';
            } else {
                $parentID = $_GET["parent"];
            }
        } else {
            $allParamsCorrect = false;
            $messages[] = 'Der Parameter "parent" fehlt vollständig.';
        }

        if (isset($_GET["cid"])) {
            if (trim($_GET["cid"]) === '') {
                $allParamsCorrect = false;
                $messages[] = 'Der Parameter "cid" wurde übergeben, ist aber leer.';
            } else {
                $cid = $_GET["cid"];
            }
        } else {
            $allParamsCorrect = false;
            $messages[] = 'Der Parameter "cid" fehlt vollständig.';
        }

        if (isset($_GET["pid"])) {
            if (trim($_GET["pid"]) === '') {
                $allParamsCorrect = false;
                $messages[] = 'Der Parameter "pid" wurde übergeben, ist aber leer.';
            } else {
                $pid = $_GET["pid"];
            }
        } else {
            $allParamsCorrect = false;
            $messages[] = 'Der Parameter "pid" fehlt vollständig.';
        }


        if (!empty($messages)) {
            foreach ($messages as $msg) {
                echo '<script>console.log(' . json_encode($msg) . ');</script>';
            }
        } else {


            $allCorrectParentIDs = ProductModel::getAllParentIDs();

            $wrongParentID = true;

            for ($i = 0; $i < count($allCorrectParentIDs); $i++) {
                if ($parentID == $allCorrectParentIDs[$i]['id']) {
                    $wrongParentID = false;
                    break;
                }
            }

            if ($wrongParentID) {
                $allParamsCorrect = false;
                echo "<script>console.log(" . json_encode("Ungültige Parent-Kategorie.") . ");</script>";
            } else {
                $allCorrectCIDs = ProductModel::getAllCids($parentID);

                $wrongCID = true;

                for ($i = 0; $i < count($allCorrectCIDs); $i++) {
                    if ($cid == $allCorrectCIDs[$i]['id']) {
                        $wrongCID = false;
                        break;
                    }
                }

                if ($wrongCID) {
                    $allParamsCorrect = false;
                    echo "<script>console.log(" . json_encode("Ungültige Kategorie für die angegebene parent-id.") . ");</script>";
                } else {
                    $allCorrectPIDs = ProductModel::getAllPidsOfOneCids($parentID, $cid);

                    $wrongPID = true;

                    for ($i = 0; $i < count($allCorrectPIDs); $i++) {
                        if ($pid == $allCorrectPIDs[$i]['pid']) {
                            $wrongPID = false;
                            $idInData = $i;
                            break;
                        }
                    }

                    if ($wrongPID) {
                        $allParamsCorrect = false;
                        echo "<script>console.log(" . json_encode("Ungültige pid für die angegebene cid") . ");</script>";
                    }
                }

            }
        }

        return [$allParamsCorrect, $parentID, $cid, $pid, $idInData];

    }


    /** @author Nick Zetzmann
     * Zeigt eine Produktliste basierend auf der uebergebenen Kategorie-ID (cid) oder Oberkategorie-ID (parentId) an.
     * 
     * Wird 'parentId' uebergeben, laedt die Funktion alle Produkte aus den zugehoerigen Unterkategorien.
     * Wird 'cid' uebergeben, laedt sie nur die Produkte aus dieser einen Kategorie.
     * 
     * @return void Gibt eine HTML-View aus oder eine Fehlermeldung, wenn keine ID uebergeben wurde.
     */

    public function showProducts()
    {
        /* Hole Kategorie-ID oder Oberkategorie-ID aus der URL (?cid= oder ?parentId=) */
        $cid = $_GET['cid'] ?? null;
        $parentId = $_GET['parentId'] ?? null;

        if ($parentId !== null) {
            /* Falls parentId gesetzt ist, lade alle Produkte der Unterkategorien */
            $produkte = ProductModel::getProductsByParentCategory((int) $parentId);
        } elseif ($cid !== null) {
            /* Ansonsten, wenn cid gesetzt ist, lade Produkte dieser Kategorie* */
            $produkte = ProductModel::getProductsByCategory((int) $cid);
        } else {
            echo "Keine Kategorie-ID oder Oberkategorie-ID übergeben.";
            return;
        }

        include '../app/views/pages/ProductList.php';
    }
}