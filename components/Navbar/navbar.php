<div id="navbar" class="navbar">
  <div class="navbar-container">
    <a href="index.php" class="navbar-brand">
      <img id="navbarLogo" src="images/Logo_SchriftSchwarz.png" alt="Logo" width="120px" height="100px" />
    </a>
    <nav role="navigation" class="nav-menu-wrapper">
      <ul role="list" class="nav-menu">
        <li><a href="about.php" class="nav-link">About</a></li>
        <li class="nav-dropdown">
          <div class="nav-dropdown-toggle">
            <div class="nav-dropdown-icon"></div>
            <div>Proteinpulver</div>
          </div>
          <nav class="nav-dropdown-list">
            <a href="ProteinpulverList.php" class="nav-dropdown-link">Whey Protein</a>
            <a href="ProteinpulverList.php" class="nav-dropdown-link">Isolat</a>
            <a href="ProteinpulverList.php" class="nav-dropdown-link">Vegan</a>
          </nav>
        </li>
        <li class="nav-dropdown">
          <div class="nav-dropdown-toggle">
            <div class="nav-dropdown-icon"></div>
            <div>Proteinriegel</div>
          </div>
          <nav class="nav-dropdown-list">
            <a href="ProteinriegelList.php" class="nav-dropdown-link">Vegan</a>
            <a href="ProteinriegelList.php" class="nav-dropdown-link">Low Carb</a>
          </nav>
        </li>
      </ul>
    </nav>
    <div class="icon-container">
<a id="darkmodeBtn" class="navbar-icon">
        <img src="images/Mond.png" width="32" alt="Darkmode umschalten" />
      </a>

      <!-- USER ICON MIT DROPDOWN-MENÜ     -->
      <div class="user-menu-container" style="position:relative; display:inline-block;">
  <a id="userBtn" class="navbar-icon" href="#"><img src="images/user.png" width="32" alt="User" /></a>
  <div id="userDropdown" class="user-dropdown"
     style="display:none; position:absolute; right:0; top:40px; border-radius:8px; z-index:1000;">
  <a href="user.php" style="display:block; padding:8px 16px;">Mein Bereich</a>
  <a href="logout.php" id="logoutLink" style="display:block; padding:8px 16px;">Abmelden</a>
</div>

</div>
  <a id="cartBtn" href="cart.php" class="navbar-icon">
        <img src="images/shopping-cart.png" width="32" alt="Warenkorb" />
      </a>    </div>
    <div class="menu-button">
      <div class="w-icon-nav-menu"></div>
    </div>
  </div>
</div>
