<?php
require_once __DIR__ . '/../../config/config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$isLoggedIn = isset($_SESSION['user']);
$username = $isLoggedIn ? htmlspecialchars($_SESSION['user']['username']) : null;
?>

<noscript>
  <link rel="stylesheet" href="<?= BASE_URL ?>/css/no-js.css" />
  <div class="no-js-modal">
    <div class="modal-content">
      <h1>JavaScript ist deaktiviert</h1>
      <p>Bitte aktiviere JavaScript in deinem Browser, um den Webshop zu nutzen.</p>
    </div>
  </div>
</noscript>

<div id="navbar" class="navbar">
  <div class="navbar-container">
    <a href="<?= BASE_URL ?>/index.php?page=home" class="navbar-brand">
      <img id="navbarLogo" src="<?= BASE_URL ?>/images/Logo_SchriftSchwarz.png" alt="Logo" width="120px" height="100px" />
    </a>

    <nav role="navigation" class="nav-menu-wrapper">
      <ul role="list" class="nav-menu">
        <li><a href="<?= BASE_URL ?>/index.php?page=about" class="nav-link">About</a></li>

        <li class="nav-dropdown">
          <div class="nav-dropdown-toggle">
            <div class="nav-dropdown-icon"></div>
            <div>Proteinpulver</div>
          </div>
          <nav class="nav-dropdown-list">
            <a href="<?= BASE_URL ?>/index.php?page=productList&cid=1" class="nav-dropdown-link">Whey Protein</a>
            <a href="<?= BASE_URL ?>/index.php?page=productList&cid=2" class="nav-dropdown-link">Isolat</a>
            <a href="<?= BASE_URL ?>/index.php?page=productList&cid=3" class="nav-dropdown-link">Vegan</a>
          </nav>
        </li>

        <li class="nav-dropdown">
          <div class="nav-dropdown-toggle">
            <div class="nav-dropdown-icon"></div>
            <div>Proteinriegel</div>
          </div>
          <nav class="nav-dropdown-list">
            <a href="<?= BASE_URL ?>/index.php?page=productList&type=productList&cid=4" class="nav-dropdown-link">Power Bar</a>
            <a href="<?= BASE_URL ?>/index.php?page=productList&type=productList&cid=5" class="nav-dropdown-link">Vegan</a>
            <a href="<?= BASE_URL ?>/index.php?page=productList&type=productList&cid=6" class="nav-dropdown-link">Low Carb</a>
          </nav>
        </li>
      </ul>
    </nav>

    <div class="icon-container">
      <a id="darkmodeBtn" class="navbar-icon">
  <img id="darkmodeIcon" src="<?= BASE_URL ?>/images/Mond.png" width="32" alt="Darkmode umschalten" />
</a>


     <div class="nav-dropdown user-dropdown">
  <?php if ($isLoggedIn): ?>
    <div class="navbar-icon">
      <img src="<?= BASE_URL ?>/images/user-logged-in.png" alt="Benutzerbereich" class="user-icon" />
    </div>
    <div class="nav-dropdown-list dropdown-left-align">
      <a href="<?= BASE_URL ?>/index.php?page=profile" class="nav-dropdown-link">Benutzerbereich</a>
      <a href="<?= BASE_URL ?>/index.php?page=logout" class="nav-dropdown-link">Abmelden</a>
    </div>
  <?php else: ?>
    <a id="userBtn" href="<?= BASE_URL ?>/index.php?page=login" class="navbar-icon">
      <img src="<?= BASE_URL ?>/images/user-logged-out.png" alt="Benutzerbereich" class="user-icon" />
    </a>
  <?php endif; ?>
</div>



      <a id="cartBtn" href="<?= BASE_URL ?>/index.php?page=cart" class="navbar-icon">
      <img id="cart-icon" src="<?= BASE_URL ?>/images/einkaufswagen.png" alt="Warenkorb">
      </a>
    </div>

    <div class="menu-button">
      <div class="w-icon-nav-menu"></div>
    </div>
  </div>
</div>
