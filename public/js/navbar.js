document.addEventListener('DOMContentLoaded', () => {
  const navbar = document.getElementById('navbar');
  const burgerBtn = document.getElementById('burgerBtn');
  const mobileMenu = document.querySelector('.mobile-menu');
  const burgerIcon = burgerBtn?.querySelector('.burger-icon');

  const darkmodeBtn = document.getElementById('darkmodeBtn');
  const darkmodeIcon = document.getElementById('darkmodeIcon');
  const cartIcon = document.getElementById('cart-icon');
  const body = document.body;

  let isDark = localStorage.getItem('darkMode') === 'true';
  let isScrolled = false;
  let isHover = false;

  function updateNavbar() {
    navbar.classList.toggle('dark-mode', isDark);
    navbar.classList.toggle('scrolled', isScrolled);
    navbar.classList.toggle('hover', isHover);
    body.classList.toggle('dark-mode', isDark);

    if (darkmodeIcon) {
      darkmodeIcon.src = isDark
        ? 'images/Sonne.png'
        : 'images/Mond.png';
    }
  }

  // Cart-Icon-Update
  window.updateCartIcon = function updateCartIcon() {
    if (!cartIcon) return;

    const BASE_URL = '/WebTech-Project/public';
    const isLoggedIn = localStorage.getItem('isLoggedIn') === 'true';

    if (isLoggedIn) {
      fetch('index.php?page=get-cart')
        .then(res => res.json())
        .then(cart => {
          cartIcon.src = cart.length > 0
            ? BASE_URL + '/images/einkaufswagen-full.png'
            : BASE_URL + '/images/einkaufswagen.png';
        })
        .catch(() => {
          cartIcon.src = BASE_URL + '/images/einkaufswagen.png';
        });
    } else {
      const cart = JSON.parse(localStorage.getItem('cart') || '[]');
      cartIcon.src = cart.length > 0
        ? BASE_URL + '/images/einkaufswagen-full.png'
        : BASE_URL + '/images/einkaufswagen.png';
    }
  };

  // Darkmode Toggle
  darkmodeBtn?.addEventListener('click', () => {
    isDark = !isDark;
    localStorage.setItem('darkMode', isDark);
    updateNavbar();
  });

  // Hover-Effekt
  navbar?.addEventListener('mouseenter', () => {
    isHover = true;
    updateNavbar();
  });

  navbar?.addEventListener('mouseleave', () => {
    isHover = false;
    updateNavbar();
  });

  // Scroll-Effekt
  window.addEventListener('scroll', () => {
    isScrolled = window.scrollY > 0;
    updateNavbar();
  });

  // Burger Menü Logik
  burgerBtn?.addEventListener('click', () => {
  mobileMenu?.classList.toggle('open');
  burgerIcon?.classList.toggle('open');
});


  // Mobile Dropdowns aufklappen
  document.querySelectorAll('.mobile-dropdown-toggle').forEach(toggle => {
    toggle.addEventListener('click', () => {
      const parent = toggle.closest('.mobile-dropdown');
      parent?.classList.toggle('open');
    });
  });

  updateNavbar();
  updateCartIcon();
});
