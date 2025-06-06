// cart.js

// Füge ein Produkt zum Warenkorb hinzu
function addToCart(name, image, price, size) {
  console.error(size);
  let cart = JSON.parse(localStorage.getItem('cart')) || [];
  let existing = cart.find(item => item.name === name && item.size === size);
  if (existing) {
    existing.quantity += 1;
  } else {
    cart.push({ name, image, price, quantity: 1, size });
  }
  localStorage.setItem('cart', JSON.stringify(cart));

  // Öffnet den Slider (führt openCart() aus)
  // openCart();
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
  let arrSeveralSizes = [];

  cart.forEach((item, index) => {
    let single = true;
    for (let i = index + 1; i < cart.length; i++) {
      if ((item.name === cart[i].name) && !arrSeveralSizes.includes(item.name)) {
        single = false;
        arrSeveralSizes.push(item.name);
      }
    }
  })

  console.log(arrSeveralSizes);

  let arrSeveralProductSizeQuantity = [];
  arrSeveralSizes.forEach((_, index) => {
    // console.log("index " + index);
    let itemName = arrSeveralSizes[index];
    let newProduct = {};
    let firstLoop = true;

    console.log(cart);
    cart.forEach(item => {
      // console.log("ja die for each");
      // console.log("item.name " + item.name);
      // console.log("itemName " + itemName);
      if (item.name === itemName && firstLoop) {
        // console.log("1 if"); 
        firstLoop = false;

        newProduct = {
          [item.size]: {
            quantity: item.quantity,
            price: item.price
          }
        };
        arrSeveralProductSizeQuantity.push(newProduct);

      } else if (item.name === itemName && !firstLoop) {
        // console.log("2 if"); 
        newProduct[item.size] = {
          quantity: item.quantity,
          price: item.price
        }
      }
    });
    console.log(arrSeveralProductSizeQuantity);
  });


  let total = 0;
  let counterArrSeveralSizes = 0;

  cart.forEach((item, index) => {
    const itemTotal = item.price * item.quantity;
    total += itemTotal;

    let row;

    if (arrSeveralSizes.indexOf(item.name) == counterArrSeveralSizes) {
      // console.log("ja: " , arrSeveralProductSizeQuantity[counterArrSeveralSizes]); 
      row = createMultiItemRow(item, arrSeveralProductSizeQuantity[counterArrSeveralSizes]);
      counterArrSeveralSizes++;
    } else if (!arrSeveralSizes.includes(item.name)) {
      row = createSingleItemRow(item, index, itemTotal);
    }

    if (row != undefined) {
      container.appendChild(row);
    }

  })

  totalDisplay.textContent = `Gesamt: ${total.toFixed(2)} €`;

}

function createMultiItemRow(item, allItemsOfThisType) {

  let sumPriceOfProductType = 0;

  const productView = document.createElement('div');
  productView.className = 'cart-item-product-view';


  Object.entries(allItemsOfThisType).forEach((item) => {
    console.log("CT: ", item);
    let firstLoop = true;
    let secondLoop = true;

    let itemSize;
    let itemQuantity;
    let itemPrice;


    Object.entries(item).forEach(([_, details]) => {
      let singleSizeRow = document.createElement('div');
      if (firstLoop) {
        itemSize = item[0];
        console.log("Size: " + itemSize);
        firstLoop = false;
      } else if (secondLoop) {
        itemQuantity = details.quantity;
        console.log("Quantity: " + itemQuantity);
        itemPrice = details.price;
        console.log("Preis: " + itemPrice);

        sumPriceOfProductType = sumPriceOfProductType + itemQuantity * itemPrice;
        secondLoop = false;

      }

      if (!secondLoop) {

        singleSizeRow.textContent = `${itemQuantity} x ${itemSize}g: ${itemPrice * itemQuantity}€`;
        productView.appendChild(singleSizeRow);
      }


    });
  });


  const row = document.createElement('div');
  row.className = 'cart-item';
  row.id = item.name;
  row.style.display = "flex";
  row.style.alignItems = "center";
  row.style.flexWrap = "wrap";
  row.style.gap = "0.5rem";
  row.style.marginBottom = "0.5rem";

  const img = document.createElement('img');
  img.className = 'cart-item-img'
  img.src = item.image;
  img.alt = item.name;
  img.width = 60;

  const firstLineItemBlock = document.createElement('div');
  firstLineItemBlock.className = 'cart-item-first-line-item-block';

  const nameSpan = document.createElement('span');
  nameSpan.textContent = `${item.name}`;
  nameSpan.style.fontWeight = "bold";
  nameSpan.style.flex = "0 0 auto";

  const sumPriceOfProductTypeSpan = document.createElement('span');
  sumPriceOfProductTypeSpan.textContent = sumPriceOfProductType + "€";

  const removeAllButton = document.createElement('button');
  removeAllButton.textContent = "Alle Entfernen";
  // removeButton.onclick = () => removeFromCart(index);

  const rightSide = document.createElement('div');

  const removeButton = document.createElement('button');
  removeButton.textContent = "Entfernen";
  removeButton.onclick = () => removeFromCart(index);

  row.appendChild(img);
  row.appendChild(rightSide);

  rightSide.appendChild(firstLineItemBlock);
  rightSide.appendChild(productView);

  firstLineItemBlock.appendChild(nameSpan);
  firstLineItemBlock.appendChild(sumPriceOfProductTypeSpan);
  firstLineItemBlock.appendChild(removeAllButton);

  return row;
}


function createSingleItemRow(item, index, itemTotal) {

  const row = document.createElement('div');
  row.className = 'cart-item';
  row.id = item.name;
  row.style.display = "flex";
  row.style.alignItems = "center";
  row.style.flexWrap = "nowrap";
  row.style.gap = "0.5rem";
  row.style.marginBottom = "0.5rem";

  const img = document.createElement('img');
  img.className = 'cart-item-img'
  img.src = item.image;
  img.alt = item.name;
  img.width = 60;

  const firstLineItemBlock = document.createElement('div');
  firstLineItemBlock.className = 'cart-item-first-line-item-block';

  const nameSpan = document.createElement('span');
  nameSpan.textContent = `${item.name}`;
  nameSpan.style.fontWeight = "bold";
  nameSpan.style.flex = "0 0 auto";

  const sizeSpan = document.createElement('span');
  sizeSpan.textContent = `(${item.size}g)`;

  const quantityContainer = document.createElement('div');
  quantityContainer.className = 'cart-item-quantity-container';

  const minusButton = document.createElement('button');
  // plusButton.className = 'cart-item-removeAll-btn';
  // removeButton.onclick = () => removeFromCart(index);

  const minusButtonIcon = document.createElement('img');
  minusButtonIcon.src = 'images/minusBlack.svg';
  minusButtonIcon.alt = 'Alle Entfernen';

  const quantitySpan = document.createElement('span');
  quantitySpan.textContent = `${item.quantity}`;

  const plusButton = document.createElement('button');
  // plusButton.className = 'cart-item-removeAll-btn';
  // removeButton.onclick = () => removeFromCart(index);

  const plusButtonIcon = document.createElement('img');
  plusButtonIcon.src = 'images/plusBlack.svg';
  plusButtonIcon.alt = 'Alle Entfernen';

  const sumPriceOfProductTypeSpan = document.createElement('span');
  sumPriceOfProductTypeSpan.textContent = `${itemTotal.toFixed(2)} €`;

  const removeAllButton = document.createElement('button');
  removeAllButton.className = 'cart-item-removeAll-btn';
  // removeButton.onclick = () => removeFromCart(index);

  const removeIcon = document.createElement('img');
  removeIcon.src = 'images/removeIcon.svg';
  removeIcon.alt = 'Alle Entfernen';

  row.appendChild(img);

  row.appendChild(img);
  row.appendChild(firstLineItemBlock);

  firstLineItemBlock.appendChild(nameSpan);
  firstLineItemBlock.appendChild(sizeSpan);

  quantityContainer.appendChild(minusButton);
  quantityContainer.appendChild(quantitySpan);
  quantityContainer.appendChild(plusButton);
  firstLineItemBlock.appendChild(quantityContainer);

  firstLineItemBlock.appendChild(sumPriceOfProductTypeSpan);
  firstLineItemBlock.appendChild(removeAllButton);

  removeAllButton.appendChild(removeIcon);

  plusButton.appendChild(plusButtonIcon);
  minusButton.appendChild(minusButtonIcon); 
  return row;
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
  let selectedButton = document.querySelector('#VerpackungsgrößenButtons button.active');
  let buttonContent = selectedButton.textContent.slice(0, -1);
  let index;
  for (let i = 0; i < product.availableSizes.length; i++) {
    if (buttonContent == product.availableSizes[i]) {
      index = i;
      break;
    }
  }

  console.log(product.availableSizes[index]);

  addToCart(product.name, product.pics.productPic1, getTotalPrice(product.priceWithoutTax), product.availableSizes[index]);
}

//Bei geöffneten Warenkorbslider lässt sich der slider durch einen CLick auf Icon schließen
window.addEventListener("DOMContentLoaded", () => {
  closeCart();
  // document.querySelector('.close-icon').addEventListener('click', closeCart);
});

