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

    public static function getAllUserIDs()
    {
        $pdo = DB::getConnection();

        $stmtUser = $pdo->prepare("
        SELECT id FROM users");
        $stmtUser->execute();
        $userIDs = $stmtUser->fetchAll(PDO::FETCH_COLUMN);

        return $userIDs;
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

    public static function updateOrderStatus($orderId, $newStatus)
    {
        $pdo = DB::getConnection();
        $stmt = $pdo->prepare("UPDATE `orders` SET `status` = :status WHERE `id` = :id");
        $stmt->execute([
            'status' => $newStatus,
            'id' => $orderId
        ]);

        return $stmt->rowCount(); // Gibt z. B. 1 zurück, wenn eine Zeile geändert wurde
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

    public static function addCategory($parentCategoryName, $categoryName)
    {
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

    public static function checkIfPulver($value)
    {
        $pdo = DB::getConnection();

        // Hole die parent_id von "Proteinpulver"
        $stmtParent = $pdo->prepare("SELECT id FROM product_parent_categories WHERE name = ?");
        $stmtParent->execute(['Proteinpulver']);
        $parentRow = $stmtParent->fetch();

        if (!$parentRow) {
            return false; // "Proteinpulver" nicht gefunden
        }

        $proteinpulverId = (int) $parentRow['id'];

        // Hole die parent_id der Kategorie, die geprüft wird
        $stmt = $pdo->prepare("SELECT parent_id FROM product_categories WHERE name = ?");
        $stmt->execute([$value]);
        $row = $stmt->fetch();

        return $row && (int) $row['parent_id'] === $proteinpulverId;
    }

    public static function saveFullProduct($data)
    {
        $pdo = DB::getConnection();
        $stmt = $pdo->prepare("
    SELECT name
    FROM product_parent_categories
");
        $stmt->execute();
        $names = $stmt->fetchAll(PDO::FETCH_COLUMN);

        $maxPid = 0;

        foreach ($names as $name) {
            $table = $name . "_products";

            $stmt = $pdo->prepare("SELECT MAX(pid) AS max_pid FROM $table");
            $stmt->execute();
            $tmp = $stmt->fetchColumn();
            if ($maxPid < $tmp) {
                $maxPid = $tmp;
            }
        }

        $productId = $maxPid + 1;

        $stmt = $pdo->prepare("
    SELECT ppc.name AS parent_name
    FROM product_categories pc
    JOIN product_parent_categories ppc ON pc.parent_id = ppc.id
    WHERE pc.name = ?
");
        $stmt->execute([$data['category']]);
        $tablePrefix = $stmt->fetchColumn();

        if (!$tablePrefix) {
            throw new Exception("Keine Parent-Category für '{$data['category']}' gefunden");
        }


        try {

            $stmtCategoryID = $pdo->prepare("SELECT id FROM product_categories WHERE `name` = ?");
            $stmtCategoryID->execute([$data['category']]);
            $cid = $stmtCategoryID->fetchColumn();  // Holt direkt den Wert der ersten Spalte (id)


            // atomare Datenbankänderungen — alles oder nichts

            $productTable = $tablePrefix . "_products";
            $stmt = $pdo->prepare("");

            AdminModel::createTableIfNeeded("test");

            $pdo->beginTransaction();


            if (AdminModel::hasColumn($pdo, $productTable, 'tip')) {
                $stmt = $pdo->prepare("INSERT INTO $productTable (`description`, preparation, recommendation, tip, laboratory, cid, `name`, pid) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $params = [
                    $data['description'],
                    $data['preparation'],
                    $data['recommendation'],
                    $data['tip'] ?? null,
                    $data['laboratory'],
                    $cid,
                    $data['name'],
                    $productId
                ];
            } else {
                $stmt = $pdo->prepare("INSERT INTO $productTable (`description`, preparation, recommendation, laboratory, cid, `name`, pid) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $params = [
                    $data['description'],
                    $data['preparation'],
                    $data['recommendation'],
                    $data['laboratory'],
                    $cid,
                    $data['name'],
                    $productId
                ];
            }

            if (!$stmt->execute($params)) {
                $error = $stmt->errorInfo();
                throw new Exception("SQL-Fehler: " . $error[2]);
            }


            $nutrientTable = $tablePrefix . "_nutrients";

            $sql = "
    CREATE TABLE IF NOT EXISTS $nutrientTable (
        product_id INT(11) NOT NULL,
        energy VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
        fat VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
        saturates VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
        carbohydrates VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
        sugars VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
        fibre VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
        protein VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
        salt VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
        PRIMARY KEY (product_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
";

            $pdo->exec($sql);

            $stmt = $pdo->prepare("INSERT INTO $nutrientTable (product_id, energy, fat, saturates, carbohydrates, sugars, fibre, protein, salt) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $productId,
                $data['energy'],
                $data['fat'],
                $data['saturates'],
                $data['carbohydrates'],
                $data['sugars'],
                $data['fibre'],
                $data['protein'],
                $data['salt']
            ]);

            // Optional: Nur wenn Pulver
            if (strtolower(trim($tablePrefix)) === "proteinpulver") {

                $aminoAcidsTable = $tablePrefix . "_amino_acids";

                $stmt = $pdo->prepare("INSERT INTO $aminoAcidsTable (product_id, alanine, arginine, aspartic_acid, cysteine, glutamic_acid, glycine, histidine, isoleucine, leucine, lysine, methionine, phenylalanine, proline, serine, threonine, tryptophan, tyrosine, valine) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([
                    $productId,
                    $data['alanine'] ?? null,
                    $data['arginine'] ?? null,
                    $data['aspartic_acid'] ?? null,
                    $data['cysteine'] ?? null,
                    $data['glutamic_acid'] ?? null,
                    $data['glycine'] ?? null,
                    $data['histidine'] ?? null,
                    $data['isoleucine'] ?? null,
                    $data['leucine'] ?? null,
                    $data['lysine'] ?? null,
                    $data['methionine'] ?? null,
                    $data['phenylalanine'] ?? null,
                    $data['proline'] ?? null,
                    $data['serine'] ?? null,
                    $data['threonine'] ?? null,
                    $data['tryptophan'] ?? null,
                    $data['tyrosine'] ?? null,
                    $data['valine'] ?? null
                ]);
            }

            // Rezepte speichern
            for ($i = 1; $i <= 3; $i++) {
                if (!empty($data["recipeTitle{$i}"])) {
                    $stmt = $pdo->prepare("INSERT INTO proteinpulver_recipes (product_id, title, short_title, `portion`) VALUES (?, ?, ?, ?)");
                    $stmt->execute([
                        $productId,
                        $data["recipeTitle{$i}"],
                        $data["recipeShortTitle{$i}"] ?? null,
                        $data["recipePortion{$i}"] ?? null
                    ]);

                    $recipeId = $pdo->lastInsertId();

                    $rohe_zutaten = $data["recipeIngredients{$i}"];
                    $zutaten_array = array_filter(array_map('trim', explode(';', $rohe_zutaten)));

                    for ($g = 0; $g < count($zutaten_array); $g++) {
                        $stmt1 = $pdo->prepare("INSERT INTO proteinpulver_recipe_ingredients (recipe_id, ingredient) VALUES (?, ?)");
                        $stmt1->execute([
                            $recipeId,
                            $zutaten_array[$g]
                        ]);
                    }

                    $schritte = $data["recipePreparation{$i}"];
                    $schritte_array = array_filter(array_map('trim', explode(';', $schritte)));

                    for ($l = 0; $l < count($schritte_array); $l++) {
                        $stmt1 = $pdo->prepare("INSERT INTO proteinpulver_recipe_steps (recipe_id, step_number, instruction) VALUES (?, ?, ?)");
                        $stmt1->execute([
                            $recipeId,
                            ($l + 1),
                            $schritte_array[$l]
                        ]);
                    }
                }
            }

            $pictureTable = $tablePrefix . "_pictures";

            $sql = "
    CREATE TABLE IF NOT EXISTS $pictureTable (
        product_id INT(11) NOT NULL,
        product_pic1 VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
        product_pic2 VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
        product_pic3 VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
        small_pic VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
        PRIMARY KEY (product_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
";

            $pdo->exec($sql);

            $stmt = $pdo->prepare("INSERT INTO $pictureTable (product_id, product_pic1 , product_pic2, product_pic3, small_pic) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([
                $productId,
                $data['productPic1'] ?? null,
                $data['productPic2'] ?? null,
                $data['productPic3'] ?? null,
                $data['smallPic'] ?? null
            ]);

            $details = $data['descriptionDetails'];
            $details_array = array_filter(array_map('trim', explode(';', $details)));

            $descriptionTable = $tablePrefix . "_descriptions";

            $sql = "
    CREATE TABLE IF NOT EXISTS $descriptionTable (
        product_id INT(11) NOT NULL,
        detail1 TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
        detail2 TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
        PRIMARY KEY (product_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
";

            $pdo->exec($sql);

            $stmt = $pdo->prepare("INSERT INTO $descriptionTable (product_id, detail1, detail2) VALUES (?, ?, ?)");
            $stmt->execute([
                $productId,
                $details_array[0] ?? null,
                $details_array[1] ?? null
            ]);

            $ingredientsTable = $tablePrefix . "_ingredients";

            $sql = "
    CREATE TABLE IF NOT EXISTS $ingredientsTable (
        product_id INT(11) NOT NULL,
        ingredients TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
        allergens TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
        PRIMARY KEY (product_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
";

            $pdo->exec($sql);

            $stmt = $pdo->prepare("INSERT INTO $ingredientsTable (product_id, ingredients, allergens) VALUES (?, ?, ?)");
            $stmt->execute([
                $productId,
                $data['ingredients'] ?? null,
                $data['allergens'] ?? null
            ]);



            $productVariants = $data['productVariants'];
            $productVariants_array = array_filter(array_map('trim', explode(';', $productVariants)));

            $spTable = $tablePrefix . "_sizes_prices";

            $sql = "
    CREATE TABLE IF NOT EXISTS $spTable (
        id INT(11) NOT NULL AUTO_INCREMENT,
        product_id INT(11) DEFAULT NULL,
        size VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
        price_with_tax DECIMAL(10,2) DEFAULT NULL,
        bestseller TINYINT(1) DEFAULT 0,
        quantity_available INT(10) UNSIGNED NOT NULL DEFAULT 0,
        PRIMARY KEY (id),
        KEY idx_product_id (product_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
";

            $pdo->exec($sql);

            $stmt = $pdo->prepare("INSERT INTO $spTable (product_id, `size`, price_with_tax, bestseller, quantity_available) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([
                $productId,
                $productVariants_array[0] ?? null,
                $productVariants_array[1] ?? null,
                $productVariants_array[2] ?? null,
                $productVariants_array[3] ?? 3,
            ]);

            $pdo->commit();
            return true;
        } catch (Exception $e) {

            $pdo->rollBack();
            error_log("Fehler beim Speichern des Produkts: " . $e->getMessage());
            throw $e;  // WICHTIG: Exception weiterwerfen
        }
    }

    public static function createTableIfNeeded($tableName)
    {
        $pdo = DB::getConnection();

        // Dynamischen Tabellennamen einfügen
        $sql = "
    CREATE TABLE IF NOT EXISTS `$tableName` (
        pid INT(11) NOT NULL AUTO_INCREMENT,
        cid INT(11) DEFAULT NULL,
        name VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
        description TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
        rating DECIMAL(3,2) DEFAULT NULL,
        raters_count INT(11) DEFAULT NULL,
        status_distribution VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
        preparation TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
        recommendation TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
        laboratory TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
        bestseller TINYINT(1) DEFAULT 0,
        PRIMARY KEY (pid),
        KEY idx_cid (cid)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    ";

        try {
            $pdo->exec($sql);
            echo "Tabelle '$tableName' wurde erfolgreich erstellt oder existiert bereits.";
        } catch (PDOException $e) {
            echo "Fehler bei der Erstellung der Tabelle '$tableName': " . $e->getMessage();
        }
    }


    public static function hasColumn(PDO $pdo, string $table, string $column): bool
    {
        $pdo = DB::getConnection();
        $stmt = $pdo->prepare("SHOW COLUMNS FROM `$table` LIKE ?");
        $stmt->execute([$column]);
        return $stmt->rowCount() > 0;
    }

    public static function isPulver($cid)
    {
        $pdo = DB::getConnection();

    }



}


// $user = AdminModel::getAllUser();
// echo '<pre>';
// print_r($user);
// echo '</pre>';

// AdminModel::createTableIfNeeded("tue_products");