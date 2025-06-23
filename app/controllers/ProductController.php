<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class ProductController
{

  public function renderItemSite($parentID, $cid, $pid, $idInData)
{
    $produkte = ProductModel::getAllItemsOfCategory($cid);

    // View laden und Variablen an die View übergeben
    require_once '../app/views/pages/item.php';
}


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


public function showProducts()
{
    $cid = $_GET['cid'] ?? null;

    if ($cid === null) {
        echo "Keine Kategorie-ID übergeben.";
        return;
    }

    $produkte = ProductModel::getProductsByCategory($cid);

    require_once '../app/views/ProductList.php';
}
}