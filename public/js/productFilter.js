//Filter zum ein/ausblenden der Produkte je nach eingestelltem Preis bzw. Faktor

document.addEventListener('DOMContentLoaded', () => {
  const priceRange = document.getElementById('price-range');
  const priceValue = document.getElementById('price-value');

  priceRange.addEventListener('input', () => {
    priceValue.textContent = '€' + priceRange.value;
    filterProducts();
  });

  veganFilter.addEventListener('change', filterProducts);

  function filterProducts() {
    const maxPrice = parseFloat(priceRange.value);
    const products = document.querySelectorAll('.grid-item');

    products.forEach(product => {
      const price = parseFloat(product.dataset.price);
      const show = price <= maxPrice;
      product.style.display = show ? '' : 'none';
    });
  }
});

