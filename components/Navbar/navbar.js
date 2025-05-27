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

  }
}