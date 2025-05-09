// cart.js

// Füge ein Produkt zum Warenkorb hinzu
function addToCart(name, image, price) {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    let existing = cart.find(item => item.name === name);
    if (existing) {
      existing.quantity += 1;
    } else {
      cart.push({ name, image, price, quantity: 1 });
    }
    localStorage.setItem('cart', JSON.stringify(cart));
    alert(`${name} wurde dem Warenkorb hinzugefügt.`);
  }
  
  // Lese den Warenkorb aus dem localStorage
  function getCart() {
    return JSON.parse(localStorage.getItem('cart')) || [];
  }
  
  // Entferne ein Produkt aus dem Warenkorb (per Index)
  function removeFromCart(index) {
    let cart = getCart();
    cart.splice(index, 1);
    localStorage.setItem('cart', JSON.stringify(cart));
    renderCart(); // nur auf cart.html nötig
  }
  
  // Ändere die Menge eines Produkts
  function updateQuantity(index, newQuantity) {
    let cart = getCart();
    if (newQuantity <= 0) {
      removeFromCart(index);
      return;
    }
    cart[index].quantity = newQuantity;
    localStorage.setItem('cart', JSON.stringify(cart));
    renderCart(); // nur auf cart.html nötig
  }
  
  // Render-Funktion für cart.html
  function renderCart() {
    const cart = getCart();
    const container = document.getElementById('cart-items');
    const totalDisplay = document.getElementById('cart-total');
  
    if (!container || !totalDisplay) return;
  
    container.innerHTML = '';
  
    let total = 0;
    cart.forEach((item, index) => {
      const itemTotal = item.price * item.quantity;
      total += itemTotal;
  
      const row = document.createElement('div');
      row.className = 'cart-item';
      row.innerHTML = `
        <img src="${item.image}" alt="${item.name}" width="60" />
        <span>${item.name}</span>
        <input type="number" min="1" value="${item.quantity}" onchange="updateQuantity(${index}, this.value)">
        <span>${itemTotal.toFixed(2)} €</span>
        <button onclick="removeFromCart(${index})">Entfernen</button>
      `;
      container.appendChild(row);
    });
  
    totalDisplay.textContent = `Gesamt: ${total.toFixed(2)} €`;
  }
  