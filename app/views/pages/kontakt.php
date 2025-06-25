<?php

$host = 'localhost';
$db = 'webtech-projekt';
$user = 'root';
$pass = '';
$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Datenbankverbindung fehlgeschlagen: " . $e->getMessage());
}


$name = $email = $betreff = $nachricht = "";
$fehler = "";
$erfolg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $betreff = trim($_POST["betreff"]);
    $nachricht = trim($_POST["nachricht"]);

    if (empty($name) || empty($email) || empty($betreff) || empty($nachricht)) {
        $fehler = "Bitte füllen Sie alle Felder aus.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $fehler = "Bitte geben Sie eine gültige E-Mail-Adresse ein.";
    } else {
    try {
    $stmt = $pdo->prepare("INSERT INTO support_ticket (name, email, betreff, nachricht) VALUES (:name, :email, :betreff, :nachricht)");
    $stmt->execute([
        ':name' => $name,
        ':email' => $email,
        ':betreff' => $betreff,
        ':nachricht' => $nachricht
    ]);

    $erfolg = "Vielen Dank für Ihre Anfrage, $name. Wir haben Ihre Nachricht erhalten und werden sie schnellst möglichst verarbeiten.";
    $name = $email = $betreff = $nachricht = "";

} catch (PDOException $e) {
    $fehler = "Fehler beim Speichern des Tickets: " . $e->getMessage();
}

        $name = $email = $betreff = $nachricht = "";
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Kontaktformular</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; }
        .formular { background-color: white; padding: 20px; border-radius: 8px; max-width: 500px; margin: auto; }
        input, textarea { width: 100%; padding: 10px; margin: 8px 0; border-radius: 4px; border: 1px solid #ccc; }
        input[type="submit"] { background-color: #28a745; color: white; border: none; cursor: pointer; }
        .fehler { color: red; }
        .erfolg { color: green; }
    </style>
</head>
<body>

<div class="formular">
    <h2>Kontaktieren Sie uns</h2>
    
    <?php if ($fehler): ?><p class="fehler"><?= htmlspecialchars($fehler) ?></p><?php endif; ?>
    <?php if ($erfolg): ?><p class="erfolg"><?= htmlspecialchars($erfolg) ?></p><?php endif; ?>
    
    <form action="kontakt.php" method="post">
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" value="<?= htmlspecialchars($name) ?>">

        <label for="email">E-Mail:</label>
        <input type="email" name="email" id="email" value="<?= htmlspecialchars($email) ?>">

        <label for="betreff">Betreff:</label>
        <input type="text" name="betreff" id="betreff" value="<?= htmlspecialchars($betreff) ?>">

        <label for="nachricht">Nachricht:</label>
        <textarea name="nachricht" id="nachricht" rows="5"><?= htmlspecialchars($nachricht) ?></textarea>

        <input type="submit" value="Absenden">
    </form>
</div>

</body>
</html>