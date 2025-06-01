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
=======
function addToCart(name, image, price, size) {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    let existing = cart.find(item => item.name === name);
    if (existing) {
      existing.quantity += 1;
    } else {
      cart.push({ name, image, price, quantity: 1 });
    }
    localStorage.setItem('cart', JSON.stringify(cart));
    alert(`${name} wurde dem Warenkorb hinzugefügt.`);
  
// → zusätzlich für Slider: Zeigt das zuletzt hinzugefügte Produkt direkt im Warenkorb-Slider an
const itemHtml = `
  <div class="cart-item">
    <img src="${image}" alt="${name}" />  <!-- Produktbild -->
    <div>
      <strong>${name} (${size}g)</strong><br>         <!-- Produktname -->
      <span class="price-new">€${price}</span>  <!-- Preis mit zwei Nachkommastellen -->
    </div>
  </div>
`;

// Holt das div, in dem die Slider-Inhalte (Produkte) angezeigt werden
const cartItems = document.getElementById("cartItems");


  // Öffnet den Slider (führt openCart() aus)
  openCart();
  renderCartSlider();
}

// Lese den Warenkorb aus dem localStorage
function getCart() {
  return JSON.parse(localStorage.getItem('cart')) || [];
}

// Entferne ein Produkt aus dem Warenkorb (per Index)
function removeFromCart(index, isSlider) {
  let cart = getCart();
  cart.splice(index, 1);
  localStorage.setItem('cart', JSON.stringify(cart));
  if (isSlider) {
    renderCartSlider(); //nur auf cartslider.html nötig
  } else {
    renderCart(); // nur auf cart.html nötig
  }
}

// Ändere die Menge eines Produkts
function updateQuantity(index, newQuantity, isSlider) {
  let cart = getCart();
  if (newQuantity <= 0) {
    removeFromCart(index, isSlider);
    return;
  }
  cart[index].quantity = newQuantity;
  localStorage.setItem('cart', JSON.stringify(cart));
  if (isSlider) {
    renderCartSlider(); //nur auf cartslider nutzbar
  } else {
    renderCart(); // nur auf cart.html nötig
  }
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

//Renderfunktion für cartSlider.php
function renderCartSlider() {
  const cart = getCart();
  const cartItems = document.getElementById('cartItems');
  cartItems.innerHTML = '';

  let total = 0;

  cart.forEach((item, index) => {
    const itemTotal = item.price * item.quantity;
    total += itemTotal;
    cartItems.innerHTML += `
    <div class="cart-item">
      <div class="cart-item-image">
        <img src="${item.image}" alt="${item.name}" />
      </div>
      <div class="cart-item-main">
        <div class="cart-item-title">${item.name}</div>
        <div class="item-total" style="margin-bottom:0.4rem;">${itemTotal.toFixed(2)} €</div>
        <div class="cart-item-bottom-row">
          <div class="qty-row">
            <button class="qty-btn" onclick="updateQuantity(${index}, ${item.quantity - 1}, true)">
              <i class="fa-solid fa-minus"></i>
            </button>
            <span class="qty">${item.quantity}</span>
            <button class="qty-btn" onclick="updateQuantity(${index}, ${item.quantity + 1}, true)">
              <i class="fa-solid fa-plus"></i>
            </button>
          </div>
          <i class="fa-solid fa-trash remove-btn" onclick="removeFromCart(${index}, true)" title="Entfernen"></i>
        </div>
      </div>
    </div>
  `;
  });

  //Berechnet bzw. fügt neue Items vom Wert her zum SLider hinzu und aktualisiert damit den Gesamtbetrag
  const cartTotalSlider = document.getElementById('cartTotal');
  if (cartTotalSlider) {
    cartTotalSlider.textContent = total.toFixed(2) + " €";
  }
}

// Öffnet den Warenkorb-Slider, indem die CSS-Klasse "open" hinzugefügt wird
function openCart() {
  const slider = document.getElementById("cartSlider"); // Referenz auf das Slider-Element
  if (slider) slider.classList.add("open");             // fügt die Klasse "open" hinzu → macht den Slider sichtbar
}

// Schließt den Warenkorb-Slider, indem die CSS-Klasse "open" entfernt wird
function closeCart() {
  const slider = document.getElementById("cartSlider"); // Referenz auf das Slider-Element
  if (slider) slider.classList.remove("open");          // entfernt die Klasse "open" → versteckt den Slider
}


function intermediateStepAddToCart() {
  addToCart(product.name, product.pics.productPic1, getTotalPrice(product.priceWithoutTax));
=======
function intermediateStepAddToCart(){
 let selectedButton = document.querySelector('#VerpackungsgrößenButtons button.active');
    let buttonContent = selectedButton.textContent.slice(0, -1);
    let index;
    for (let i = 0; i < product.availableSizes.length; i++) {
        if (buttonContent == product.availableSizes[i]) {
            index = i;
            break;
        }
    }
 
  addToCart(product.name,  product.pics.productPic1, getTotalPrice(product.priceWithoutTax), product.availableSizes[index]);

}

//Bei geöffneten Warenkorbslider lässt sich der slider durch einen CLick auf Icon schließen
window.addEventListener("DOMContentLoaded", () => {
  closeCart();
  document.querySelector('.close-icon').addEventListener('click', closeCart);
});

