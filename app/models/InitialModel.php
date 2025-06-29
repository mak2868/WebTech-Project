<?php
/**
 * Initial (model) 
 * Anwendung: wird verwendet, um das Fenstersymbols des Browser mit dem Firmenlogo zu befüllen (-> wird initial auf jeder Seite aufgerufen)
 * @author: Marvin Kunz
 */
?>


<?php
require_once __DIR__ . '../../lib/DB.php';
require_once __DIR__ . '/../config/config.php';

class InitialModel {

    public static function getFenstersymbols() {
    $pdo = DB::getConnection();

    $stmt = $pdo->prepare("SELECT fenstersymbol_black, fenstersymbol_white FROM pictures");
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($rows as &$row) {
        $row['fenstersymbol_black'] = BASE_URL . $row['fenstersymbol_black'];
        $row['fenstersymbol_white'] = BASE_URL . $row['fenstersymbol_white'];
    }

    return $rows;
}

}