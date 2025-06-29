
/**
 * navbar.js für die Steuerung von scroll, hover, darkmode verhalten
 * auswechseln der icons je nach Status
 * Mobile Burger Menü Funktionalität
 * @author Felix Bartel
 */





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



  
  /**
   * CSS Klassen werden gesetzt, je nach Zustand der zuvor gesetzten Variblen (isDark, isScrolled, isHover)
   * Da das Darkmode-Verhalten über den Mond/Sonne Button in Navbar gesteuert wird, wird hier auch die Klasse dark-mode im body gesetzt
   */

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

  /**
   * Prüft ob der User eingeloggt ist, wenn ja holt Warenkorb serversetig (fetch),
   * wenn nein dann clientseitig aus localStorage
   * Icon ändert sich wenn Artikel im Warenkorb sind 
   * (Echtzeitaktualisierung, da die Funktion updateCartIcon bei jeglichen Funktionen im cart.js, welche Einfluss darauf haben
   * ob Produkte im Warenkorb sind, hinzugefügt wurde.)
   */


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
    mobileMenu?.classList.toggle('open'); //enthält gesamten Inhalt von Mobile Menü
    burgerIcon?.classList.toggle('open');
  });


  // Mobile Dropdowns aufklappen: Klasse .open (display:flex) wird gesetzt bei click
  document.querySelectorAll('.mobile-dropdown-toggle').forEach(toggle => {
    toggle.addEventListener('click', () => {
      const parent = toggle.closest('.mobile-dropdown');
      parent?.classList.toggle('open');
    });
  });

  updateNavbar();
  updateCartIcon();
});
