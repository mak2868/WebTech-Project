<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Benutzerbereich</title>
    <link rel="stylesheet" href="style/global.css">
    <link rel="stylesheet" href="style/user.css">
    <link rel="stylesheet" href="style/logreg.css">
    <link rel="stylesheet" href="components/Navbar/navbar_transparent.css">
    <script src="components/Navbar/navbar.js" defer></script>
    <script src="js/darkmode.js" defer></script>
</head>


<body>
        <?php include 'components/Navbar/navbar.php'; ?>

<main style="padding-top: 40px">
    <div class="form-wrapper">
    <form action="..." method="post">
    <h2>Benutzerbereich</h2>
    

<form>
  <label for="firstname">Vorname:</label>
  <input type="text" id="firstname" name="firstname" required>

  <label for="lastname">Nachname:</label>
  <input type="text" id="lastname" name="lastname" required>

  <label for="email">E-Mail:</label>
  <input type="email" id="email" name="email" required>

  <label for="phone">Telefonnummer:</label>
  <input type="tel" id="phone" name="phone">

  <label for="street">Straße:</label>
  <input type="text" id="street" name="street">

  <label for="zip">PLZ:</label>
  <input type="text" id="zip" name="zip">

  <label for="city">Stadt:</label>
  <input type="text" id="city" name="city">

  <label for="birthdate">Geburtstag:</label>
  <input type="date" id="birthdate" name="birthdate">

  <label for="gender">Geschlecht:</label>
  <select id="gender" name="gender">
    <option value="">Bitte wählen</option>
    <option value="m">Männlich</option>
    <option value="w">Weiblich</option>
    <option value="d">Divers</option>
  </select>

  <button type="submit" class="btn-esn">Speichern</button>
</form>

<a href="index.php" class="form-text">Zurück zur Homepage</a>

</div>

    <script src="js/validate-user.js"></script>
    <?php include 'components/Footer/footer.php'; ?>
</main>
</body>
</html>