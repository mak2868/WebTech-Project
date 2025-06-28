(function mergeClientCartWithServer() {
  const cart = JSON.parse(localStorage.getItem('cart') || '[]');
  if (!cart.length) return;

  console.log("mergeClientCartWithServer wurde aufgerufen");

  fetch('index.php?page=merge-cart', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(cart)
  })
    .then(() => {
      localStorage.removeItem('cart');
    })
    .catch(err => {
      console.error('Fehler beim Mergen des Warenkorbs:', err);
    });
})();
