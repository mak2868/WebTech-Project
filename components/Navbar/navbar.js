document.addEventListener("DOMContentLoaded", function () {
  const navbar = document.querySelector(".navbar");
  const body = document.body;
  const darkmodeBtn = document.getElementById("darkmodeBtn");

  // === SCROLL-EVENT: transparent → weiß beim echten Scrollen ===
  const updateNavbarScrollState = () => {
    if (window.scrollY > 0) {
      navbar.classList.add("scrolled");
    } else {
      navbar.classList.remove("scrolled");
    }
  };
  window.addEventListener("scroll", updateNavbarScrollState);
  updateNavbarScrollState(); // Initial prüfen

  // === HOVER-EVENT: weiß beim Überfahren ===
  navbar.addEventListener("mouseenter", () => navbar.classList.add("hover"));
  navbar.addEventListener("mouseleave", () => navbar.classList.remove("hover"));

  // === DARKMODE-TOGGLE: transparent weiß/dunkel-Modus umschalten ===
  if (darkmodeBtn) {
    darkmodeBtn.addEventListener("click", () => {
      body.classList.toggle("darkmode");
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