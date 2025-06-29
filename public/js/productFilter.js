// Author: Nick Zetzmann


let originalProducts = [];

document.addEventListener('DOMContentLoaded', () => {
  const priceRange = document.getElementById('price-range');
  const priceValue = document.getElementById('price-value');
  const sortSelect = document.getElementById('sort-select');
  const filterToggle = document.getElementById('filter-toggle');
  const filterDropdown = document.querySelector('.filter-dropdown');
  const container = document.querySelector('.product-grid');


  // Produkte initial speichern, damit immer ueber alle Produkte gefiltert/sortiert wird, 
  // sonst werden nach erstem aussortieren die PRodukte nicht wieder angezeigt, auch wenn deren Preis wieder ueberschritten wird
  originalProducts = Array.from(container.querySelectorAll('.product-card'));

  priceRange.addEventListener('input', () => {
    priceValue.textContent =priceRange.value + '€';
    filterAndSortProducts();
  });

  sortSelect.addEventListener('change', () => {
    filterAndSortProducts();
  });

  filterToggle.addEventListener('click', () => {
    filterDropdown.classList.toggle('active');
  });

  function filterAndSortProducts() {
    const maxPrice = parseFloat(priceRange.value);
    const sortOption = sortSelect.value;

    // Immer von den originalen Produkten ausgehen
    const filtered = originalProducts.filter(product => {
      const price = parseFloat(product.dataset.price);
      return price <= maxPrice;
    });

    // Sortierung
    filtered.sort((a, b) => {
      const priceA = parseFloat(a.dataset.price);
      const priceB = parseFloat(b.dataset.price);
      const nameA = a.querySelector('.title').textContent.trim().toLowerCase();
      const nameB = b.querySelector('.title').textContent.trim().toLowerCase();

      switch (sortOption) {
        case 'preis-asc': return priceA - priceB;
        case 'preis-desc': return priceB - priceA;
        case 'name-asc': return nameA.localeCompare(nameB);
        case 'name-desc': return nameB.localeCompare(nameA);
        default: return 0;
      }
    });

    // DOM neu befüllen
    container.innerHTML = '';
    filtered.forEach(product => container.appendChild(product));
  }
});