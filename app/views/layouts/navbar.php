<?php
require_once __DIR__ . '/../../config/config.php';

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

$isLoggedIn = isset($_SESSION['user']) || isset($_SESSION['is_admin']);
$isAdmin = isset($_SESSION['is_admin']);
$username = $isLoggedIn && !$isAdmin ? htmlspecialchars($_SESSION['user']['username']) : null;

$navbarData = $_SESSION['navbarData'] ?? ['categories' => [], 'images' => []];
$categories = $navbarData['categories'] ?? [];

function getImagePath($images, $keyword)
{
  foreach ($images as $img) {
    if (stripos($img, $keyword) !== false) {
      return BASE_URL . $img;
    }
  }
  return ''; // fallback
}
?>

<div id="navbar" class="navbar">
  <div class="navbar-container">

    <!-- LINKS: Logo -->
    <a href="<?= BASE_URL ?>/index.php?page=home" class="navbar-brand">
      <img id="navbarLogo" src="<?= getImagePath($navbarData['images'], 'Logo_SchriftSchwarz') ?>" alt="Logo" />
    </a>

    <!-- MITTE: Hauptnavigation -->
    <nav class="nav-menu-wrapper">
      <ul class="nav-menu">
        <li><a href="<?= BASE_URL ?>/index.php?page=about" class="nav-link">About</a></li>
        <?php foreach ($categories as $category): ?>
          <li class="nav-dropdown">
            <div class="nav-dropdown-toggle">
              <div class="nav-dropdown-icon"></div>
              <div><?= htmlspecialchars($category['name']) ?></div>
            </div>
            <nav class="nav-dropdown-list">
              <?php foreach ($category['subcategories'] as $sub): ?>
                <a href="<?= BASE_URL ?>/index.php?page=productList&cid=<?= $sub['id'] ?>" class="nav-dropdown-link">
                  <?= htmlspecialchars($sub['name']) ?>
                </a>
              <?php endforeach; ?>
            </nav>
          </li>
        <?php endforeach; ?>
      </ul>
    </nav>

    <!-- RECHTS: Icons -->
    <div class="navbar-icons">
      <a id="darkmodeBtn" class="navbar-icon">
        <img id="darkmodeIcon" src="<?= getImagePath($navbarData['images'], 'mond') ?>" alt="Darkmode" />
      </a>

      <div class="nav-dropdown user-dropdown">
        <?php if ($isLoggedIn): ?>
          <div class="navbar-icon">
            <img src="<?= getImagePath($navbarData['images'], $isAdmin ? 'admin' : 'user-logged-in') ?>" alt="User"
              class="user-icon" />
          </div>
          <div class="nav-dropdown-list dropdown-left-align">
            <?php if (!$isAdmin): ?>
              <a href="<?= BASE_URL ?>/index.php?page=profile" class="nav-dropdown-link">Benutzerbereich</a>
            <?php else: ?>
              <a href="<?= BASE_URL ?>/index.php?page=admin" class="nav-dropdown-link">Adminbereich</a>
            <?php endif; ?>
            <a href="<?= BASE_URL ?>/index.php?page=logout" class="nav-dropdown-link">Abmelden</a>
          </div>
        <?php else: ?>
          <a id="userBtn" href="<?= BASE_URL ?>/index.php?page=login" class="navbar-icon">
            <img src="<?= getImagePath($navbarData['images'], 'user-logged-out') ?>" alt="Login" class="user-icon" />
          </a>
        <?php endif; ?>
      </div>

      <a id="cartBtn" href="<?= BASE_URL ?>/index.php?page=cart" class="navbar-icon">
        <img id="cart-icon" src="<?= getImagePath($navbarData['images'], 'einkaufswagen') ?>" alt="Warenkorb" />
      </a>
    </div>

    <!-- RECHTS außen (nur Mobile): Burger -->
    <button id="burgerBtn" class="burger-button">
      <div class="burger-icon">
        <span></span>
        <span></span>
        <span></span>
      </div>
    </button>
  </div>

  <!-- MOBILE MENU -->
  <div id="mobileMenu" class="mobile-menu">
    <ul class="mobile-menu-list">
      <li><a class="mobile-link" href="<?= BASE_URL ?>/index.php?page=about">About</a></li>

      <?php foreach ($categories as $category): ?>
        <li class="mobile-dropdown">
          <div class="mobile-dropdown-toggle"><?= htmlspecialchars($category['name']) ?></div>
          <div class="mobile-submenu">
            <?php foreach ($category['subcategories'] as $sub): ?>
              <a href="<?= BASE_URL ?>/index.php?page=productList&cid=<?= $sub['id'] ?>">
                <?= htmlspecialchars($sub['name']) ?>
              </a>
            <?php endforeach; ?>
          </div>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
</div>