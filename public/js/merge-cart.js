function mergeClientCartWithServer() {
  console.log("mergeClientCartWithServer wurde aufgerufen");

  const cart = JSON.parse(localStorage.getItem('cart') || '[]');
  if (!cart.length) return;

  fetch('index.php?page=merge-cart', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(cart)
  })
    .then(() => {
      localStorage.removeItem('cart');
    })
    .catch(err => {
      console.error('Fehler beim mergen des Warenkorbs:', err);
    });
}

window.addEventListener("DOMContentLoaded", () => {
  mergeClientCartWithServer();
});
