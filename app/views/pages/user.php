<!-- Erstellt von Merzan-->
<?php require_once __DIR__ . '/../../config/config.php'; ?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XPN | Benutzerbereich</title> 

    <!-- Einbindung globaler und registrierungsbezogener CSS-Dateien -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/global.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/logreg.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/user.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/navbar_transparent.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/cookieBanner.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/footer.css">


    <!-- JS -->
    <script src="<?= BASE_URL ?>/js/navbar.js" defer></script>
    <script src="<?= BASE_URL ?>/js/cookieBanner.js" defer></script>
    <script src="<?= BASE_URL ?>/js/merge-cart.js" defer></script>

     <!-- Head-Datei -->
    <?php include __DIR__ . '/../layouts/head.php'; ?>


</head>
<body>
    <?php include __DIR__ . '/../layouts/navbar.php'; ?>

    <main class="user-main">
        <!-- Userbereich: breites Formular -->
        <div class="form-wrapper user-wrapper">
            <?php
// Initialisiert Benutzer- und Adressdaten-Variablen, falls sie nicht gesetzt sind.
// Verhindert PHP-Fehler beim ersten Laden der Seite.
if (!isset($userdata) || !is_array($userdata)) {
    $userdata = [];
}
if (!isset($addressdata) || !is_array($addressdata)) {
    $addressdata = [];
}

/**
 * Hilfsfunktion zum sicheren Abrufen und Maskieren von Werten für HTML-Felder.
 * @param array $data_array Array mit Daten (z.B. $userdata, $addressdata).
 * @param string $field_name Name des abzurufenden Feldes.
 * @param string $default Standardwert, falls Feld nicht existiert.
 * @return string Sicherer Wert für HTML-Ausgabe.
 */
function get_field_value($data_array, $field_name, $default = '') {
    return htmlspecialchars($data_array[$field_name] ?? $default);
}

// Das Formular sendet Daten an die 'profile'-Seite des Controllers.
?>
<form action="index.php?page=profile" method="post">
    <h2>Benutzerbereich</h2>

    <?php
    // Zeigt eine Fehlermeldung an, falls vom Controller gesetzt.
    if (!empty($error)): ?>
        <div class="form-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php
    // Zeigt eine Erfolgsmeldung an, falls vom Controller gesetzt.
    if (!empty($success)): ?>
        <div class="form-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>


                <!-- Vorname & Nachname nebeneinander -->
                <div class="form-row">
                    <div class="form-field">
                        <label for="first_name">Vorname:</label>
                        <!-- Wert aus $userdata['first_name'] -->
                        <input type="text" id="first_name" name="first_name" value="<?= get_field_value($userdata, 'first_name') ?>" required>
                    </div>
                    <div class="form-field">
                        <label for="last_name">Nachname:</label>
                        <!-- Wert aus $userdata['last_name'] -->
                        <input type="text" id="last_name" name="last_name" value="<?= get_field_value($userdata, 'last_name') ?>" required>
                    </div>
                </div>

                <!-- E-Mail & Telefonnummer nebeneinander -->
                <div class="form-row">
                    <div class="form-field">
                        <label for="email">E-Mail:</label>
                        <!-- Wert aus $userdata['email'] -->
                        <input type="email" id="email" name="email" value="<?= get_field_value($userdata, 'email') ?>" required>
                    </div>
                    <div class="form-field">
                        <label for="phone">Telefonnummer:</label>
                        <!-- Wert aus $userdata['phone'] -->
                        <input type="tel" id="phone" name="phone" value="<?= get_field_value($userdata, 'phone') ?>">
                    </div>
                </div>

                <!-- Straße & PLZ nebeneinander -->
                <div class="form-row">
                    <div class="form-field">
                        <label for="street">Straße:</label>
                        <!-- Wert aus $addressdata['street'] -->
                        <input type="text" id="street" name="street" value="<?= get_field_value($addressdata, 'street') ?>">
                    </div>
                    <div class="form-field">
                        <label for="zip">PLZ:</label>
                        <!-- Wert aus $addressdata['postal_code'] -->
                        <input type="text" id="zip" name="zip" value="<?= get_field_value($addressdata, 'postal_code') ?>">
                    </div>
                </div>

                <!-- Stadt & Geburtstag nebeneinander -->
                <div class="form-row">
                    <div class="form-field">
                        <label for="city">Stadt:</label>
                        <!-- Wert aus $addressdata['city'] -->
                        <input type="text" id="city" name="city" value="<?= get_field_value($addressdata, 'city') ?>">
                    </div>
                    <div class="form-field">
                        <label for="birthdate">Geburtstag:</label>
                        <?php
                        // Das Input-Feld ist type="date", erwartet das Format YYYY-MM-DD.
                        // Der Wert aus der Datenbank sollte idealerweise bereits in diesem Format sein.
                        $birthdate_value = get_field_value($userdata, 'birthdate');
                        ?>
                        <!-- Wert aus $userdata['birthdate'] -->
                        <input type="date" id="birthdate" name="birthdate" value="<?= $birthdate_value ?>">
                    </div>
                </div>

                <!-- Geschlecht -->
                <div class="form-row">
                    <div class="form-field" style="width:100%;">
                        <label for="gender">Geschlecht:</label>
                        <?php
                        // Ermitteln, welcher Wert aktuell in der Datenbank für 'gender' gespeichert ist.
                        $selectedGender = get_field_value($userdata, 'gender');
                        ?>
                        <select id="gender" name="gender">
                            <option value="">Bitte wählen</option>
                            <!-- 'selected'-Attribut hinzufügen, wenn der Wert mit $selectedGender übereinstimmt -->
                            <option value="male" <?= ($selectedGender === 'male') ? 'selected' : '' ?>>Männlich</option>
                            <option value="female" <?= ($selectedGender === 'female') ? 'selected' : '' ?>>Weiblich</option>
                            <!-- Achten Sie darauf, dass der 'value' ('d') mit dem Wert in Ihrer Datenbank übereinstimmt -->
                            <option value="d" <?= ($selectedGender === 'd') ? 'selected' : '' ?>>Divers</option>
                        </select>
                    </div>
                </div>

                <!-- Land Feld hinzufügen (falls gewünscht, da es in user_addresses enthalten ist) -->
                <div class="form-row">
                    <div class="form-field" style="width:100%;">
                        <label for="country">Land:</label>
                        <input type="text" id="country" name="country" value="<?= get_field_value($addressdata, 'country', 'Deutschland') ?>">
                    </div>
                </div>

                <button type="submit" class="btn-esn">Speichern</button>
            </form>
            

            <a href="index.php?page=home" class="form-text">Zurück zur Homepage</a>
        </div>



 <!-- Bestellhistorie daneben -->
<div class="order-history">
    <h2>Deine Bestellungen</h2>
    <?php if (!empty($orderHistory)): ?>
        <?php foreach ($orderHistory as $order): ?>
            <div class="order-box">
                <?php foreach ($order['items'] as $item): ?>
                    <div class="order-item">
                        <img src="<?= BASE_URL . '/' . ltrim($item['product_image'], '/') ?>" alt="Produktbild">
                        <div class="item-info">
                            <strong><?= htmlspecialchars($item['product_name']) ?></strong><br>
                            <?= $item['quantity'] ?> x <?= htmlspecialchars($item['size']) ?>g
                        </div>
                    </div>
                <?php endforeach; ?>
                <div class="order-meta">
                    <p><strong>Datum:</strong> <?= $order['order_date'] ?></p>
                    <p><strong>Status:</strong> <?= ucfirst($order['status']) ?></p>
                    <p><strong>Versandadresse:</strong> <?= htmlspecialchars($order['shipping_address']) ?></p>
                    <p style="text-align:right;"><strong>Gesamt:</strong> <?= number_format($order['total'], 2) ?> €</p>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Keine Bestellungen vorhanden.</p>
    <?php endif; ?>
</div>

</main>



        <script src="<?= BASE_URL ?>/js/validate-user.js"></script>
        <?php include __DIR__ . '/../layouts/cookieBanner.php'; ?>
        <?php include __DIR__ . '/../layouts/footer.php'; ?>
  
</body>
</html>
