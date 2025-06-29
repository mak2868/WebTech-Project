// Author: Nick Zetzmann

/* Globale Variable zum Speichern aller Original-Produkte */
let originalProducts = [];

document.addEventListener('DOMContentLoaded', () => {
  const priceRange = document.getElementById('price-range');
  const priceValue = document.getElementById('price-value');
  const sortSelect = document.getElementById('sort-select');
  const filterToggle = document.getElementById('filter-toggle');
  const filterDropdown = document.querySelector('.filter-dropdown');
  const container = document.querySelector('.product-grid');


/*Produkte initial speichern, damit immer ueber alle Produkte gefiltert/sortiert wird,
 * sonst werden nach erstem aussortieren die Produkte nicht wieder angezeigt, auch wenn deren Preis wieder ueberschritten wird */   
  originalProducts = Array.from(container.querySelectorAll('.product-card'));

/* Preis-Slider reagiert auf Eingaben und zeigt neuen Preiswert und filtert*/
  priceRange.addEventListener('input', () => {
    priceValue.textContent =priceRange.value + '€'; /*Preis wird aktualisiert */
    filterAndSortProducts();
  });

/* Wenn der Nutzer die Sortierung aendert, werden die PRodukte hier neu sortiert */
  sortSelect.addEventListener('change', () => {
    filterAndSortProducts();
  });

/* Filter Menu wird nach click ein bzw. ausgeblendet */  
  filterToggle.addEventListener('click', () => {
    filterDropdown.classList.toggle('active');
  });

/* Zentrale Funktion: Filtert und sortiert die Produktliste neu */  
  function filterAndSortProducts() {
    const maxPrice = parseFloat(priceRange.value);
    const sortOption = sortSelect.value;

/* Filtere Produkte, die unter oder gleich dem eingestellten Maximalpreis sind */
    const filtered = originalProducts.filter(product => {
      const price = parseFloat(product.dataset.price);
      return price <= maxPrice;
    });

/* Sortiere die gefilterten Produkte je nach Auswahl */
    filtered.sort((a, b) => {
      const priceA = parseFloat(a.dataset.price);
      const priceB = parseFloat(b.dataset.price);
      const nameA = a.querySelector('.title').textContent.trim().toLowerCase();
      const nameB = b.querySelector('.title').textContent.trim().toLowerCase();

/** Sortierlogik nach Preis bzw. Name */
      switch (sortOption) {
        case 'preis-asc': return priceA - priceB;
        case 'preis-desc': return priceB - priceA;
        case 'name-asc': return nameA.localeCompare(nameB);
        case 'name-desc': return nameB.localeCompare(nameA);
        default: return 0;
      }
    });

    // DOM neu befuellen
    container.innerHTML = '';
    filtered.forEach(product => container.appendChild(product));
  }
});