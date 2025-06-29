
/**
 * Baut ein user einen clientseitigen Warenkorb (localStorage) auf, loggt sich ein, und es existiert bereits 
 * in der Datenbank für diesen User ein Warenkorb, so werden die Warenkörbe addiert. 
 * Der neue addierte Stand wird in die DB geschrieben
 * Der merge passiert auf home.php (wenn eingeloggt) und auf checkout.php, welche nur eingeloggt erreichbar ist
 * @author Felix Bartel
 */





(function mergeClientCartWithServer() {
  const isLoggedIn = localStorage.getItem('isLoggedIn') === 'true';
  const isHomePage = window.location.pathname.endsWith('/home.php') || window.location.search.includes('page=home');

  // Nicht mergen, wenn NICHT eingeloggt UND auf home.php
  if (!isLoggedIn && isHomePage) return;

  const cart = JSON.parse(localStorage.getItem('cart') || '[]');
  if (!cart.length) return;

  console.log("mergeClientCartWithServer wurde aufgerufen");



  //schreibt den addierten Warenkorb in die DB, löscht den Warenkorb vom localStorage
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
