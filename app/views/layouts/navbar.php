<?php require_once __DIR__ . '/../../config/config.php'; ?>

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
            <a href="<?= BASE_URL ?>/index.php?page=productList&type=proteinpulver&category=wheyprotein" class="nav-dropdown-link">Whey Protein</a>
            <a href="<?= BASE_URL ?>/index.php?page=productList&type=proteinpulver&category=isolat" class="nav-dropdown-link">Isolat</a>
            <a href="<?= BASE_URL ?>/index.php?page=productList&type=proteinpulver&category=vegan" class="nav-dropdown-link">Vegan</a>
          </nav>
        </li>

        <li class="nav-dropdown">
          <div class="nav-dropdown-toggle">
            <div class="nav-dropdown-icon"></div>
            <div>Proteinriegel</div>
          </div>
          <nav class="nav-dropdown-list">
            <a href="<?= BASE_URL ?>/index.php?page=productList&type=proteinriegel&category=vegan" class="nav-dropdown-link">Vegan</a>
            <a href="<?= BASE_URL ?>/index.php?page=productList&type=proteinriegel&category=lowcarb" class="nav-dropdown-link">Low Carb</a>
          </nav>
        </li>
      </ul>
    </nav>

    <div class="icon-container">
      <a id="darkmodeBtn" class="navbar-icon">
        <img src="<?= BASE_URL ?>/images/Mond.png" width="32" alt="Darkmode umschalten" />
      </a>
      <a id="userBtn" href="<?= BASE_URL ?>/index.php?page=login" class="navbar-icon">
        <img src="<?= BASE_URL ?>/images/user.png" width="32" alt="Benutzerbereich" />
      </a>
      <a id="cartBtn" href="<?= BASE_URL ?>/index.php?page=cart" class="navbar-icon">
        <img src="<?= BASE_URL ?>/images/shopping-cart.png" width="32" alt="Warenkorb" />
      </a>
    </div>

    <div class="menu-button">
      <div class="w-icon-nav-menu"></div>
    </div>
  </div>
</div>
