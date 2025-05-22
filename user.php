<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Benutzerbereich</title>
    <link rel="stylesheet" href="style/global.css">
    <link rel="stylesheet" href="style/user.css">
    <link rel="stylesheet" href="style/logreg.css">
    <link rel="stylesheet" href="style/navbar_transparent.css">
    <script src="components/Navbar/navbar.js" defer></script>

</head>


<body>
        <?php include 'components/Navbar/navbar.php'; ?>

<main style="padding-top: 40px">
    <div class="form-wrapper">
    <form action="..." method="post">
    <h2>Benutzerbereich</h2>
    
        <label for="username">Benutzername:</label>
        <input type="text" id="username" name="username"  required>
        <br><br>

        <label for="password">Passwort:</label>
        <input type="password" id="password" name="password" required>
        <br><br>

        <button type="submit" id="submit" class="btn-esn">Speichern</button>

    </form>

    <br>
    <a href="index.html">Zurück zur Homepage</a>
    <script src="js/validate-user.js"></script>
    <?php include 'components/Footer/footer.php'; ?>
</main>
</body>
</html>