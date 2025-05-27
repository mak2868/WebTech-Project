document.addEventListener('DOMContentLoaded', () => {
  const navbar      = document.getElementById('navbar');
  const darkmodeBtn = document.getElementById('darkmodeBtn');
  const logoImg     = document.getElementById('navbarLogo');
  const body        = document.body;

  let isDark     = localStorage.getItem('darkMode') === 'true';
  let isScrolled = false;
  let isHover    = false;

  function updateNavbar() {
    navbar.classList.toggle('dark-mode', isDark);
    navbar.classList.toggle('scrolled', isScrolled);
    navbar.classList.toggle('hover', isHover);
    body.classList.toggle('dark-mode', isDark);
  }

  darkmodeBtn.addEventListener('click', () => {
    isDark = !isDark;
    localStorage.setItem('darkMode', isDark);
    updateNavbar();
  });

  navbar.addEventListener('mouseenter', () => {
    isHover = true;
    updateNavbar();
  });
  navbar.addEventListener('mouseleave', () => {
    isHover = false;
    updateNavbar();
  });

  window.addEventListener('scroll', () => {
    isScrolled = window.scrollY > 0;
    updateNavbar();
  });

  updateNavbar();

  // === USER-ICON DROPDOWN ===
  const userBtn = document.getElementById('userBtn');
  const dropdown = document.getElementById('userDropdown');
  const logoutLink = document.getElementById('logoutLink');

  if (userBtn && dropdown) {
    const loggedIn = localStorage.getItem('userLoggedIn') === 'true';

    if (loggedIn) {
      userBtn.addEventListener('click', function (e) {
        e.preventDefault();
        dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
      });

      document.addEventListener('click', function (event) {
        if (!userBtn.contains(event.target) && !dropdown.contains(event.target)) {
          dropdown.style.display = 'none';
        }
      });

      if (logoutLink) {
        logoutLink.addEventListener('click', function (e) {
          e.preventDefault();
          localStorage.removeItem('userLoggedIn');
          localStorage.removeItem('username');
          window.location.href = 'logout.php';
        });
      }
    } else {
      userBtn.href = "login.php";
      dropdown.style.display = "none";
    }
  }
});