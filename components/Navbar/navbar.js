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

  // Darkmode-Button: Toggle Darkmode
  darkmodeBtn.addEventListener('click', () => {
    isDark = !isDark;
    localStorage.setItem('darkMode', isDark);
    updateNavbar();
  });

  // Navbar Hover-Events
  navbar.addEventListener('mouseenter', () => {
    isHover = true;
    updateNavbar();
  });
  navbar.addEventListener('mouseleave', () => {
    isHover = false;
    updateNavbar();
  });

  // Scroll-Event
  window.addEventListener('scroll', () => {
    isScrolled = window.scrollY > 0;
    updateNavbar();
  });

  updateNavbar();
});