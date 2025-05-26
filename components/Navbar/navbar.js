document.addEventListener('DOMContentLoaded', () => {
  const navbar      = document.getElementById('navbar');
  const darkmodeBtn = document.getElementById('darkmodeBtn');
  const logoImg     = document.getElementById('navbarLogo');
  const body        = document.body;

  let isDark     = localStorage.getItem('darkMode') === 'true';
  let isScrolled = false;
  let isHover    = false;

  function updateNavbar() {
    // Navbar Klassen setzen
    navbar.classList.toggle('dark-mode', isDark);
    navbar.classList.toggle('scrolled', isScrolled);
    navbar.classList.toggle('hover', isHover);

    // Body Darkmode-Klasse
    body.classList.toggle('dark-mode', isDark);

    // Logo optional invertieren
    if (logoImg) {
      logoImg.style.filter = isDark ? 'invert(1)' : 'invert(0)';
    }

    // Navbar Icons invertieren
    document.querySelectorAll('.navbar-icon img').forEach(img => {
      img.style.filter = isDark ? 'invert(1)' : 'invert(0)';
    });
  }


  // === USER-ICON DROPDOWN ===
const userBtn = document.getElementById('userBtn');          // User-Icon in der Navbar
const dropdown = document.getElementById('userDropdown');    // Dropdown-Menü für User
const logoutLink = document.getElementById('logoutLink');    // Abmelde-Link im Dropdown

if (userBtn && dropdown) {
  const loggedIn = localStorage.getItem('userLoggedIn') === 'true'; // Login-Status prüfen

  if (loggedIn) {
    userBtn.addEventListener('click', function (e) {
      e.preventDefault();                                     // Standardverhalten verhindern
      // Dropdown ein-/ausblenden
      dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
    });

    // Klick außerhalb schließt Dropdown
    document.addEventListener('click', function (event) {
      if (!userBtn.contains(event.target) && !dropdown.contains(event.target)) {
        dropdown.style.display = 'none';
      }
    });

    if (logoutLink) {
      // Beim Klick auf Abmelden:
      logoutLink.addEventListener('click', function (e) {
        e.preventDefault();                                   // Kein Seitenwechsel
        localStorage.removeItem('userLoggedIn');              // Logout-Status löschen
        localStorage.removeItem('username');                  // Benutzernamen löschen
        window.location.href = 'logout.php';                  // Weiterleitung auf Logout-Seite
      });
    }
  } else {
    // Wenn nicht eingeloggt:
    userBtn.href = "login.php";         // User-Icon führt zu Login
    dropdown.style.display = "none";    // Dropdown bleibt ausgeblendet
  }
}
});