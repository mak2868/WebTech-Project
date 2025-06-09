// cart.js

// Füge ein Produkt zum Warenkorb hinzu
function addToCart(name, image, price, size) {
  console.log("addtc");
  let cart = JSON.parse(localStorage.getItem('cart')) || [];

  // Setze bei allen vorhandenen Produkten lastChanged auf false
  cart.forEach(item => item.lastAdded = false);

  let existing = cart.find(item => item.name === name && item.size === size);

  if (existing) {
    existing.quantity += 1;
    existing.lastAdded = true;
    cart.forEach(item => {
      if (item.name == name) {
        console.log("ja1");
        item.lastAdded = true;
      }
    });
  } else {
    cart.push({ name, image, price, quantity: 1, size, lastAdded: true });
    cart.forEach(item => {
      if (item.name == name) {
        console.log("ja2");
        item.lastAdded = true;
      }
    });
  }

  cart = sortCart(cart); // Warenkorb sortieren
  console.log(cart);

  localStorage.setItem('cart', JSON.stringify(cart));

  // Öffnet den Slider (führt openCart() aus)
  // openCart();
  renderCartSlider();

  startTimer();
}


function sortCart(cart) {
  return cart.sort((a, b) => {
    // 0. Zuletzt hinzugefügtes Produkt soll ganz oben stehen
    if (a.lastAdded && !b.lastAdded) return -1;
    if (!a.lastAdded && b.lastAdded) return 1;

    // 1. Nach Name alphabetisch (deutsch, case-insensitive)
    const nameCompare = a.name.localeCompare(b.name, 'de', { sensitivity: 'base' });
    if (nameCompare !== 0) return nameCompare;

    // 2. Danach nach Size sortieren:
    const aHasX = a.size.toString().includes('x');
    const bHasX = b.size.toString().includes('x');

    if (aHasX && !bHasX) return 1;   // '12x45' nach '45'
    if (!aHasX && bHasX) return -1;

    // 3. Wenn beide gleichartig, numerisch vergleichen
    const sizeA = parseInt(a.size, 10);
    const sizeB = parseInt(b.size, 10);
    return sizeA - sizeB;
  });
}



// Lese den Warenkorb aus dem localStorage
function getCart() {
  return JSON.parse(localStorage.getItem('cart')) || [];
}

// Entferne ein Produkt aus dem Warenkorb (per Index)
function removeFromCartMultiRow(name, indexFromButtons, isSlider) {
  let indexInCart = getIndexInCart(name, indexFromButtons);
  removeFromCart(indexInCart, isSlider);
}

function removeFromCart(index, isSlider) {
  let cart = getCart();

  cart.splice(index, 1);
  if (cart.length === 0) {
    stopTimer();
  }
  localStorage.setItem('cart', JSON.stringify(cart));
  if (isSlider) {
    renderCartSlider(); //nur auf cartslider.html nötig
  } else {
    renderCart(); // nur auf cart.html nötig
  }
}

function removeAllItemsFromCart() {
  let cart = [];
  localStorage.setItem('cart', JSON.stringify(cart));
  renderCart();
  stopTimer();
}

// Ändere die Menge eines Produkts
function updateQuantityMultiRow(name, indexFromButtons, newQuantity, isSlider) {
  // console.log("name", name);
  console.log("iB", indexFromButtons);
  let indexInCart = getIndexInCart(name, indexFromButtons);
  console.log("indexincart", indexInCart);
  updateQuantity(indexInCart, newQuantity, isSlider);
  let cart = getCart();
  console.log(cart);
}


function updateQuantity(index, newQuantity, isSlider) {
  console.log(newQuantity);
  console.log(index);
  let cart = getCart();

  if (newQuantity <= 0) {
    removeFromCartMultiRow(index, isSlider);
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

function getIndexInCart(name, indexFromButtons) {
  let cart = getCart();

  let countItemWithName = 0;
  let returnValue;

  for (let i = 0; i < cart.length; i++) {
    const item = cart[i];
    if (item.name === name) {
      countItemWithName++;
      if (countItemWithName - 1 === indexFromButtons) {
        returnValue = i;
        break;
      }
    }
  }

  return returnValue;
}

// Render-Funktion für cart.html
function renderCart() {
  const cart = getCart();
  console.log(cart);
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
  });

  let arrSeveralProductSizeQuantity = [];
  arrSeveralSizes.forEach((_, index) => {
    let itemName = arrSeveralSizes[index];
    let newProduct = {};
    let firstLoop = true;

    cart.forEach(item => {
      if (item.name === itemName && firstLoop) {
        firstLoop = false;

        newProduct = {
          [item.size]: {
            quantity: item.quantity,
            price: item.price
          }
        };
        arrSeveralProductSizeQuantity.push(newProduct);

      } else if (item.name === itemName && !firstLoop) {
        newProduct[item.size] = {
          quantity: item.quantity,
          price: item.price
        };
      }
    });
  });

  let total = 0;
  let counterArrSeveralSizes = 0;

  cart.forEach((item, index) => {
    const itemTotal = item.price * item.quantity;
    total += itemTotal;

    let row;
    if (arrSeveralSizes.indexOf(item.name) == counterArrSeveralSizes) {
      row = createMultiItemRow(item, arrSeveralProductSizeQuantity[counterArrSeveralSizes]);
      counterArrSeveralSizes++;
    } else if (!arrSeveralSizes.includes(item.name)) {
      row = createSingleItemRow(item, index, itemTotal);
    }

    if (row != undefined) {
      container.appendChild(row);
    }
  });

  totalDisplay.textContent = `Gesamt: ${total.toFixed(2)} €`;
}



function createMultiItemRow(item, allItemsOfThisType) {
  let totalSum = 0;

  const row = document.createElement('div');
  row.className = 'cart-item';

  const img = document.createElement('img');
  img.className = 'cart-item-img';
  img.src = item.image;
  img.alt = item.name;
  img.width = 60;

  const firstLineItemBlock = document.createElement('div');
  firstLineItemBlock.className = 'cart-item-first-line-item-block-multi';

  // Name (einmalig in Zeile 1, Spalte 1)
  const nameSpan = document.createElement('span');
  nameSpan.className = 'cart-item-name';
  nameSpan.textContent = item.name;
  nameSpan.style.fontWeight = 'bold';
  nameSpan.style.gridColumn = '1 / 2';
  nameSpan.style.gridRow = '1 / 2';
  firstLineItemBlock.appendChild(nameSpan);

  let rowIndex = 1;

  Object.entries(allItemsOfThisType).forEach(([size, details]) => {
    const rowStr = `${details.quantity} x ${size}g`;
    const rowPrice = details.quantity * details.price;
    totalSum += rowPrice;

    // Spalte 2: productView (Text)
    const sizeText = document.createElement('div');
    sizeText.textContent = `${rowStr}: ${rowPrice.toFixed(2)}€`;
    sizeText.style.gridColumn = '2 / 3';
    sizeText.style.gridRow = `${rowIndex} / ${rowIndex + 1}`;
    firstLineItemBlock.appendChild(sizeText);

    // Spalte 3: Quantity-Buttons
    const quantityContainer = document.createElement('div');
    quantityContainer.className = 'cart-item-quantity-container';
    quantityContainer.style.gridColumn = '3 / 4';
    quantityContainer.style.gridRow = `${rowIndex} / ${rowIndex + 1}`;

    const minusButton = document.createElement('button');
    minusButton.classList.add('button', 'minus');

    minusButton.addEventListener("click", function (event) {
      const currentContainerM = event.currentTarget.closest('.cart-item');
      const buttonsInContainerM = currentContainerM.querySelectorAll("button.minus");
      const clickedButtonM = event.currentTarget;

      const quantityInputInContainerM = currentContainerM.querySelectorAll('.cart-item-quantity-input');

      const iM = Array.from(buttonsInContainerM).indexOf(clickedButtonM);
      updateQuantityMultiRow(currentContainerM.querySelector('.cart-item-name').textContent, iM, Number(quantityInputInContainerM[iM].value) - 1, false);
    });

    const minusIcon = document.createElement('img');
    minusIcon.src = 'images/minusBlack.svg';
    minusIcon.alt = '–';

    const quantityInput = document.createElement('input');
    quantityInput.type = 'number';
    quantityInput.min = 1;
    quantityInput.value = details.quantity;
    quantityInput.className = 'cart-item-quantity-input';

    quantityInput.addEventListener('change', (event) => {
      const newQuantity = parseInt(event.target.value, 10);

      const currentContainerQS = event.currentTarget.closest('.cart-item');
      const buttonsInContainerQS = currentContainerQS.querySelectorAll(".cart-item-quantity-input");
      const clickedButtonQS = event.currentTarget;

      const iQS = Array.from(buttonsInContainerQS).indexOf(clickedButtonQS);

      if (newQuantity >= 1 && Number.isInteger(newQuantity)) {
        updateQuantityMultiRow(currentContainerQS.querySelector('.cart-item-name').textContent, iQS, newQuantity, false);
      } else {
        event.target.value = details.quantity;
      }
    });

    const plusButton = document.createElement('button');
    plusButton.classList.add('button', 'plus');

    plusButton.addEventListener("click", function (event) {
      const currentContainerP = event.currentTarget.closest('.cart-item');
      const buttonsInContainerP = currentContainerP.querySelectorAll("button.plus");
      const clickedButtonP = event.currentTarget;

      const quantityInputInContainerP = currentContainerP.querySelectorAll('.cart-item-quantity-input');

      const iP = Array.from(buttonsInContainerP).indexOf(clickedButtonP);
      console.log("hehe", parseInt(quantityInputInContainerP[iP].value), iP);
      updateQuantityMultiRow(currentContainerP.querySelector('.cart-item-name').textContent, iP, parseInt(quantityInputInContainerP[iP].value) + 1, false);
    });
    const plusIcon = document.createElement('img');
    plusIcon.src = 'images/plusBlack.svg';
    plusIcon.alt = '+';

    minusButton.appendChild(minusIcon);
    plusButton.appendChild(plusIcon);
    quantityContainer.appendChild(minusButton);
    quantityContainer.appendChild(quantityInput);
    quantityContainer.appendChild(plusButton);
    firstLineItemBlock.appendChild(quantityContainer);

    // Spalte 4: Einzelpreis nochmal (optional, falls gewünscht extra statt in Spalte 2)
    const totalSpan = document.createElement('span');
    totalSpan.textContent = `${rowPrice.toFixed(2)}€`;
    totalSpan.style.gridColumn = '4 / 5';
    totalSpan.style.gridRow = `${rowIndex} / ${rowIndex + 1}`;
    firstLineItemBlock.appendChild(totalSpan);

    // Spalte 5: Einzel-Entfernen Button
    const removeBtn = document.createElement('button');
    removeBtn.className = 'cart-item-remove-btn';
    removeBtn.style.gridColumn = '5 / 6';
    removeBtn.style.gridRow = `${rowIndex} / ${rowIndex + 1}`;

    removeBtn.addEventListener("click", function (event) {
      const currentContainerRB = event.currentTarget.closest('.cart-item');
      const buttonsInContainerRB = currentContainerRB.querySelectorAll("button.plus");
      const clickedButtonRB = event.currentTarget;

      const iRB = Array.from(buttonsInContainerRB).indexOf(clickedButtonRB);
      removeFromCartMultiRow(currentContainerRB.querySelector('.cart-item-name').textContent, iRB, false);
    });

    const removeIcon = document.createElement('img');
    removeIcon.src = 'images/removeIcon.svg';
    removeIcon.alt = 'Entfernen';
    removeBtn.appendChild(removeIcon);
    firstLineItemBlock.appendChild(removeBtn);

    rowIndex++;
  });

  row.appendChild(img);
  row.appendChild(firstLineItemBlock);
  return row;
}


function createSingleItemRow(item, index, itemTotal) {
  const row = document.createElement('div');
  row.className = 'cart-item';

  const img = document.createElement('img');
  img.className = 'cart-item-img';
  img.src = item.image;
  img.alt = item.name;
  img.width = 60;

  // Grid für den "firstLineItemBlock" anwenden
  const firstLineItemBlock = document.createElement('div');
  firstLineItemBlock.className = 'cart-item-first-line-item-block'; // Hier wird Grid verwendet

  const nameSpan = document.createElement('span');
  nameSpan.textContent = `${item.name}`;
  nameSpan.style.fontWeight = 'bold';

  const sizeSpan = document.createElement('span');
  sizeSpan.textContent = `(${item.size}g)`;

  const quantityContainer = document.createElement('div');
  quantityContainer.className = 'cart-item-quantity-container';

  const minusButton = document.createElement('button');
  minusButton.addEventListener("click", () => {
    updateQuantity(index, Number(item.quantity) - 1, false);
  });
  const minusButtonIcon = document.createElement('img');
  minusButtonIcon.src = 'images/minusBlack.svg';

  // const quantitySpan = document.createElement('span');
  // quantitySpan.textContent = `${item.quantity}`;
  const quantityInput = document.createElement('input');
  quantityInput.type = 'number';
  quantityInput.min = 1;
  quantityInput.value = item.quantity;
  quantityInput.className = 'cart-item-quantity-input';

  quantityInput.addEventListener('change', (event) => {
    const newQuantity = parseInt(event.target.value, 10);
    if (newQuantity >= 1 && Number.isInteger(newQuantity)) {
      updateQuantity(index, newQuantity);
    } else {
      event.target.value = item.quantity;
    }
  });


  const plusButton = document.createElement('button');
  plusButton.addEventListener("click", () => {
    updateQuantity(index, parseInt(item.quantity) + 1, false);
  });
  const plusButtonIcon = document.createElement('img');
  plusButtonIcon.src = 'images/plusBlack.svg';

  const sumPriceOfProductTypeSpan = document.createElement('span');
  sumPriceOfProductTypeSpan.textContent = `${itemTotal.toFixed(2)} €`;

  const removeButton = document.createElement('button');
  removeButton.className = 'cart-item-remove-btn';
  removeButton.addEventListener("click", () => {
    removeFromCart(index, false);
  });
  const removeIcon = document.createElement('img');
  removeIcon.src = 'images/removeIcon.svg';
  removeButton.appendChild(removeIcon);

  row.appendChild(img);
  row.appendChild(firstLineItemBlock);

  firstLineItemBlock.appendChild(nameSpan);
  firstLineItemBlock.appendChild(sizeSpan);
  firstLineItemBlock.appendChild(quantityContainer);
  firstLineItemBlock.appendChild(sumPriceOfProductTypeSpan);
  firstLineItemBlock.appendChild(removeButton);

  quantityContainer.appendChild(minusButton);
  quantityContainer.appendChild(quantityInput);
  quantityContainer.appendChild(plusButton);

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

var time = 0; // Timer-Variable (wird später aus localStorage geladen)
var runningTimer; // Referenz für den laufenden Timer

// Diese Funktion zeigt den Timer im HTML-Element an
function updateTimerDisplay() {
  console.log("display");

  if(document.querySelector(".timerText") != null){
    document.querySelector(".timerText").style.visibility = 'visible';
    document.querySelector(".time").style.visibility = 'visible';
  }

  var m = Math.floor(time / 60); // Minuten berechnen
  var s = time % 60; // Sekunden berechnen
  if (m < 10) m = "0" + m;
  if (s < 10) s = "0" + s;

  // Nur auf der cart.php-Seite den Timer anzeigen
  if (window.location.pathname.endsWith("/cart.php")) {
    var timerElement = document.querySelector(".time");
    if (timerElement) {
      timerElement.innerText = m + ":" + s; // Anzeige des Timers
    }
  }
}

// Beim Laden der Seite die gespeicherte Zeit im localStorage holen
window.addEventListener('load', function () {
  if (window.location.pathname.endsWith("/cart.php")) {
    // Wenn Zeit im localStorage gespeichert ist, benutze diese
    if (localStorage.getItem('timerTime')) {
      time = parseInt(localStorage.getItem('timerTime'), 10);
      updateTimerDisplay();  // Timer anzeigen
      startTimer(); // Timer fortsetzen
    } else {
      // Wenn keine Zeit im localStorage ist, zeige 00:00
      time = 0;
      updateTimerDisplay();
    }
  }
});

// Timer-Funktion in regelmäßigen Abständen aufrufen
function timer() {
  console.log("timer");

  if (time > 0) {
    time--; // Timer dekrementieren
    updateTimerDisplay(); // Timer im HTML-Element aktualisieren
    // Speichere die verbleibende Zeit im localStorage
    localStorage.setItem('timerTime', time);
  } else {
    stopTimer(); // Stoppe den Timer, wenn er abgelaufen ist
  }
}

// Stoppen des Timers
function stopTimer() {
  console.log("stopp");
  if (runningTimer) {
    clearInterval(runningTimer); // Timer stoppen
    runningTimer = null;
    localStorage.removeItem('timerTime'); // Lösche die gespeicherte Zeit im localStorage
  }
  let cart = getCart();
  if (cart.length === 0) {
    document.querySelector(".timerText").style.visibility = 'hidden';
    document.querySelector(".time").style.visibility = 'hidden';
  } else {
    document.querySelector(".timerText").innerHTML = "Produktreservierung ist abgelaufen";
    document.querySelector(".time").style.visibility = 'hidden';
  }
}

// Funktion zum Starten des Timers
function startTimer() {
  console.log("starten");
  if (!runningTimer) {  // Stelle sicher, dass der Timer nicht doppelt läuft
    // Hier definierst du die Startzeit des Timers

    time = 20; // Wenn keine Zeit im localStorage ist, setze 20 Minuten als Startzeit


    runningTimer = setInterval(timer, 1000); // Intervall für Timer
    timer(); // Direkt beim Start den Timer aufrufen
  }
}


