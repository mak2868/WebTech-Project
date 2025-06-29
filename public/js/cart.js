window.BASE_URL = window.BASE_URL || '/WebTech-Project/public';





/**********************************************
 *         Hilfsfunktionen (Utility)          *
 **********************************************/



/**
 * Liest den Warenkorb aus dem localStorage
 * @returns Warenkorb
 * @author Felix Bartel
 */
function getCart() {
  return JSON.parse(localStorage.getItem('cart')) || [];
}



/**
 * Sortiert den Warenkorb nach: Name und Größe des Produktes, zusätzlich stehen alle Größen des zuletzt hinzugefügten Produktes ganz vorne
 * @param {Warenkorb, der zuvor geladen wurde} cart 
 * @returns nach festgelegten Regeln sortierten Warenkorb
 * @author Marvin Kunz
 */
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





/**
 * Berechnet anhand der übergebene Parameter den Index im Warenkorb (möglich dank der einheitlichen Sortierung anhand von fest definierten Regeln)
 * @param {Name des Produktes} name 
 * @param {Angabe, der wie vielte Button eines Containers betätigt wurde (-> entspricht der Positionszeile in der Visualisierung = x-tes Auftreten des Produktes mit diesem Namen)} indexFromButtons 
 * @returns Index des Produktes im Warenkorb
 * @author Marvin Kunz
 */
function getIndexInCart(name, indexFromButtons) {
  let cart = localStorage.getItem('isLoggedIn') === 'true' ? [] : getCart();
  // Variable, die das Auftreten der unterschiedlichen Größen eines Produktes zählt
  let countItemWithName = 0;

  // Schleife, die den Warenkorb durchläuft
  for (let i = 0; i < cart.length; i++) {
    const item = cart[i];
    //Vergleich, ob der übergebene Name dem Namen des aktuellen Produktes entspricht
    if (item.name === name) {
      countItemWithName++; // Counter wird erhöht, sobald auf ein Produkt mit dem gleichen Namen getroffen wird
      // wenn Counter und Index gleich sind, ist das gewünschte Produkt gefunden
      if (countItemWithName - 1 === indexFromButtons) {
        return i; // Rückgabe des Indexes des gesuchten Produktes (entspricht dem Counter i der for-Schleife)
        ;
      }
    }
  }
}




/**********************************************
 *    Grundfunktionen - Datenmanipulation     *
 **********************************************/




/**
 * Entferne ein Produkt aus dem Warenkorb (per Index)
 * @param {Index an welcher Stelle im Warenkorb sich das zu entfernende Produkt befindet} index 
 * @param {boolsche Angabe, ob der Aufuruf der Funktion durch den Warenkorb-Slider geschieht oder nicht} isSlider 
 * @author Felix Bartel, Marvin Kunz (Anpassung: Timer)
 */
function removeFromCart(index, isSlider) {
  let cart = localStorage.getItem('isLoggedIn') === 'true' ? [] : getCart();
  cart.splice(index, 1); // entfernt das Produkt
  // stoppt den Timer, falls der Warenkorb leer ist
  if (cart.length === 0) {
    stopTimer();
  }

  // fügt den Warenkorb dem localStorage hinzu
  localStorage.setItem('cart', JSON.stringify(cart));

  // ruft entsprechend der aufrufenden Seite die passende Visualisierung auf 
  if (isSlider) {
    renderCartSlider(); // nur auf cartslider.php nötig
  } else {
    renderCart(); // nur auf cart.php nötig
  }

  updateCartIcon(); // Warenkorb-Icon aktualisieren
}




/**
 * Ruft die Funktion removeFromCart() mit geeigneten Parametern auf: Hierzu muss aus dem Namen des Produktes und des Index des Buttons der Index des Produktes im Warenkorb bestimmt werden.
 * @param {Name des Produkts} name 
 * @param {Angabe des Index der Positionszeile innerhalb einer Produktdarstellung im Warenkorb anhand des Buttons} indexFromButtons 
 * @param {boolsche Angabe, ob der Aufuruf der Funktion durch den Warenkorb-Slider geschieht oder nicht} isSlider 
 * @author Marvin Kunz
 */
function removeFromCartMultiRow(name, indexFromButtons, isSlider) {
  let indexInCart = getIndexInCart(name, indexFromButtons);
  removeFromCart(indexInCart, isSlider);
}



/**
 * entfernt alle Produkte aus dem Warenkorb
 * @author Marvin Kunz
 */
function removeAllItemsFromCart() {
  let cart = [];
  localStorage.setItem('cart', JSON.stringify(cart));
  renderCart();
  stopTimer();
  updateCartIcon();
}



/**
 * Ändere die Menge eines Produkts
 * @param {Index an welcher Stelle im Warenkorb sich das zu entfernende Produkt befindet} index 
 * @param {Neue Anzahl der zum Warenkorb hinzugefügten Produkte dieses Produkttyps} newQuantity 
 * @param {boolsche Angabe, ob der Aufuruf der Funktion durch den Warenkorb-Slider geschieht oder nicht} isSlider 
 * @author Felix Bartel
 */
function updateQuantity(index, newQuantity, isSlider) {
  let cart = localStorage.getItem('isLoggedIn') === 'true' ? [] : getCart();

  // entfernt das Produkt aus dem Warenkorb, falls die Anzahl = 0
  if (newQuantity <= 0) {
    cart.splice(index, 1);
    localStorage.setItem('cart', JSON.stringify(cart));
    if (isSlider) {
      renderCartSlider();
    } else {
      renderCart();
    }
    stopTimer();
    updateCartIcon();
    return;
  }

  // Anpassung der Anzahl
  cart[index].quantity = newQuantity;
  localStorage.setItem('cart', JSON.stringify(cart));

  if (isSlider) {
    renderCartSlider();
  } else {
    renderCart();
  }

  updateCartIcon();
}




/**
 * Ruft die Funktion updateQuantity() mit geeigneten Parametern auf: Hierzu muss aus dem Namen des Produktes und des Index des Buttons der Index des Produktes im Warenkorb bestimmt werden.
 * @param {Name des Produkts} name 
 * @param {Angabe des Index der Positionszeile innerhalb einer Produktdarstellung im Warenkorb anhand des Buttons} indexFromButtons 
 * @param {Neue Anzahl der zum Warenkorb hinzugefügten Produkte dieses Produkttyps} newQuantity 
 * @param {boolsche Angabe, ob der Aufuruf der Funktion durch den Warenkorb-Slider geschieht oder nicht} isSlider 
 * @author Marvin Kunz
 */
function updateQuantityMultiRow(name, indexFromButtons, newQuantity, isSlider) {
  const isLoggedIn = localStorage.getItem('isLoggedIn') === 'true';

  if (isLoggedIn) {
    // Suche die ID des Items im DOM (bei Server-Warenkorb)
    const cartItem = Array.from(document.querySelectorAll('.cart-item')).find(item =>
      item.querySelector('.cart-item-name')?.textContent === name
    );

    const inputs = cartItem?.querySelectorAll('.cart-item-quantity-input');
    const input = inputs?.[indexFromButtons];
    const itemId = input?.getAttribute('data-id');

    if (itemId) {
      updateServerQuantity(Number(itemId), newQuantity);
    }
  } else {
    const indexInCart = getIndexInCart(name, indexFromButtons);
    updateQuantity(indexInCart, newQuantity, isSlider);
  }
}






/**********************************************
 *         Clientseitige Darstellung          *
 **********************************************/




/**
 * zuständig für die Visualisierug der cart.php Seite (Render-Funktion)
 * @returns null (nur im Fehlerfall)
 * @author Marvin Kunz
 */
function renderCart() {
  const cart = getCart();
  const container = document.getElementById('cart-items');
  const totalDisplay = document.getElementById('cart-total');

  if (!container || !totalDisplay) return;

  container.innerHTML = '';
  let arrSeveralSizes = [];

  cart.forEach((item, index) => {
    for (let i = index + 1; i < cart.length; i++) {
      if ((item.name === cart[i].name) && !arrSeveralSizes.includes(item.name)) {
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

  const netto = total * 0.81;

  totalDisplay.innerHTML = `Netto: ${netto.toFixed(2)} € <br> Gesamt: ${total.toFixed(2)} €`;
  checkCheckoutButtonState();
}



/**
 * Greift, und erstellt Ansicht, wenn für den selben Produkttyp nur eine Size im Warenkorb befindet
 * @param item, index, itemTotal
 * @author Felix Bartel, Marvin Kunz
 */


function createSingleItemRow(item, index, itemTotal) {
  const row = document.createElement('div');
  row.className = 'cart-item';

  const img = document.createElement('img');
  img.className = 'cart-item-img';
  img.src = item.image;

  img.alt = item.name;
  img.width = 60;

  const firstLineItemBlock = document.createElement('div');
  firstLineItemBlock.className = 'cart-item-first-line-item-block';

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
  minusButtonIcon.src = BASE_URL + '/images/minusBlack.svg';

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
  plusButtonIcon.src = BASE_URL + '/images/plusBlack.svg';

  const sumPriceOfProductTypeSpan = document.createElement('span');
  sumPriceOfProductTypeSpan.textContent = `${itemTotal.toFixed(2)} €`;

  const removeButton = document.createElement('button');
  removeButton.className = 'cart-item-remove-btn';
  removeButton.addEventListener("click", () => {
    removeFromCart(index, false);
  });
  const removeIcon = document.createElement('img');
  removeIcon.src = BASE_URL + '/images/removeIcon.svg';
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





/**
 * Greift, und erstellt Ansicht, wenn für den selben Produkttyp mehrere Sizes im Warenkorb befinden
 * @param item, allItemsOfThisType
 * @author Felix Bartel, Marvin Kunz
 */

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

    const sizeText = document.createElement('div');
    sizeText.textContent = `${rowStr}: ${rowPrice.toFixed(2)}€`;
    sizeText.style.gridColumn = '2 / 3';
    sizeText.style.gridRow = `${rowIndex} / ${rowIndex + 1}`;
    firstLineItemBlock.appendChild(sizeText);

    const quantityContainer = document.createElement('div');
    quantityContainer.className = 'cart-item-quantity-container';
    quantityContainer.style.gridColumn = '3 / 4';
    quantityContainer.style.gridRow = `${rowIndex} / ${rowIndex + 1}`;

    const minusButton = document.createElement('button');
    minusButton.classList.add('button', 'minus');

    minusButton.addEventListener("click", function (event) {
  const currentContainer = event.currentTarget.closest('.cart-item');
  const allQtyInputs = currentContainer.querySelectorAll('.cart-item-quantity-input');
  const clickedMinus = event.currentTarget;
  const allMinusButtons = currentContainer.querySelectorAll('.button.minus');

  const indexFromButtons = Array.from(allMinusButtons).indexOf(clickedMinus);
  const quantityInput = allQtyInputs[indexFromButtons];
  const currentQuantity = Number(quantityInput.value);

  const productName = currentContainer.querySelector('.cart-item-name')?.textContent;

  updateQuantityMultiRow(productName, indexFromButtons, currentQuantity - 1, false);
});


    const minusIcon = document.createElement('img');
    minusIcon.src = BASE_URL + '/images/minusBlack.svg';
    minusIcon.alt = '–';

    const quantityInput = document.createElement('input');
    quantityInput.type = 'number';
    quantityInput.min = 1;
    quantityInput.value = details.quantity;
    quantityInput.className = 'cart-item-quantity-input';
    quantityInput.setAttribute('data-id', details.id);

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
      updateQuantityMultiRow(currentContainerP.querySelector('.cart-item-name').textContent, iP, parseInt(quantityInputInContainerP[iP].value) + 1, false);
    });
    const plusIcon = document.createElement('img');
    plusIcon.src = BASE_URL + '/images/plusBlack.svg';
    plusIcon.alt = '+';

    minusButton.appendChild(minusIcon);
    plusButton.appendChild(plusIcon);
    quantityContainer.appendChild(minusButton);
    quantityContainer.appendChild(quantityInput);
    quantityContainer.appendChild(plusButton);
    firstLineItemBlock.appendChild(quantityContainer);

    const totalSpan = document.createElement('span');
    totalSpan.textContent = `${rowPrice.toFixed(2)}€`;
    totalSpan.style.gridColumn = '4 / 5';
    totalSpan.style.gridRow = `${rowIndex} / ${rowIndex + 1}`;
    firstLineItemBlock.appendChild(totalSpan);

    const removeBtn = document.createElement('button');
    removeBtn.className = 'cart-item-remove-btn';
    removeBtn.style.gridColumn = '5 / 6';
    removeBtn.style.gridRow = `${rowIndex} / ${rowIndex + 1}`;

    removeBtn.addEventListener("click", function (event) {
      const currentContainer = event.currentTarget.closest('.cart-item');
      const allRemoveButtons = currentContainer.querySelectorAll(".cart-item-remove-btn");
      const clickedRemoveButton = event.currentTarget;

      const indexFromButtons = Array.from(allRemoveButtons).indexOf(clickedRemoveButton);

      const productName = currentContainer.querySelector('.cart-item-name')?.textContent;

      removeFromCartMultiRow(productName, indexFromButtons, false);
    });


    const removeIcon = document.createElement('img');
    removeIcon.src = BASE_URL + '/images/removeIcon.svg';
    removeIcon.alt = 'Entfernen';
    removeBtn.appendChild(removeIcon);
    firstLineItemBlock.appendChild(removeBtn);

    rowIndex++;
  });

  row.appendChild(img);
  row.appendChild(firstLineItemBlock);
  return row;
}










/**********************************************
 *         Serverseitige Darstellung          *
 **********************************************/


/**
 *  Lädt die serverseitige Warenkorb-Daten eines eingeloggten Users
 * @author Felix Bartel
 */

function loadServerCart() {
  fetch('index.php?page=get-cart')
    .then(res => res.json())
    .then(data => {
      const mapped = data.map(item => ({
        ...item,
        name: item.product_name
      }));
      renderServerCart(mapped);
    })
    .catch(err => console.error('Fehler beim Laden des Server-Warenkorbs:', err));
}


/**
 * @param name, image, price, size
 * @author Felix Bartel, Marvin Kunz
 */


function addToCart(name, image, price, size) {
  const isLoggedIn = localStorage.getItem('isLoggedIn') === 'true';

  const item = {
    name,
    image,
    price,
    size,
    quantity: 1,
    lastAdded: true
  };

  if (isLoggedIn) {
    fetch('index.php?page=add-cart-item', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(item)
    })
      .then(res => res.text())
      .then(text => {
        try {
          const json = JSON.parse(text);
          openCart();
          renderCartSlider();
          updateCartIcon();
        } catch (e) {
          console.error('Fehler beim Parsen der JSON-Antwort:', e, text);
        }
      })
      .catch(err => console.error('Serverfehler beim Hinzufügen:', err));
  } else {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    let existing = cart.find(i => i.name === name && i.size === size);
    if (existing) existing.quantity += 1;
    else cart.push(item);
    cart.forEach(item => {
      if(item.name === name){
        item.lastAdded = true;
      }
    });
    cart = sortCart(cart);
    localStorage.setItem('cart', JSON.stringify(cart));
    openCart();
    renderCartSlider();
    updateCartIcon();
  }
}



/**
 * Ändert serverseitig die Anzahl, löscht wenn Anzahl 0
 * Schreibt Anzahl in DB, aktualisiert entsprechend den Warenkorb mit dem Aufruf von loadServerCart()
 * @param itemId, quantity
 * @author Felix Bartel
 */

function updateServerQuantity(itemId, quantity) {
  if (quantity <= 0) {
    removeServerItem(itemId);
    return;
  }

  fetch('index.php?page=update-cart-item', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ item_id: itemId, quantity })
  })
    .then(() => {
      const page = new URLSearchParams(window.location.search).get('page');
      if (page === 'cart') {
        loadServerCart();
      } else {
        renderCartSlider();
      }

      updateCartIcon();
    });
}




/**
 * Löscht serverseitig das Item und aktualisiert den Warenkorb über Aufruf von loadServerCart()
 * @param itemId
 * @author Felix Bartel
 */



function removeServerItem(itemId) {
  fetch('index.php?page=remove-cart-item', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ item_id: itemId })
  }).then(() => {
    const page = new URLSearchParams(window.location.search).get('page');
    if (page === 'cart') {
      loadServerCart();
    } else {
      renderCartSlider();
    }

    updateCartIcon();
  });
}





/**
 * Löscht serverseitig den kompletten Warenkorb, 
 * aktualisiert entsprechend den Warenkorb mit Aufruf der Funktion loadServerCart()
 * @author Felix Bartel
 */

function clearServerCart() {
  fetch('index.php?page=clear-cart', { method: 'POST' })
    .then(() => {
      const page = new URLSearchParams(window.location.search).get('page');
      if (page === 'cart') {
        loadServerCart();
      } else {
        renderCartSlider();
      }

      updateCartIcon();
    });
}



/**
 * Greift, und erstellt Ansicht, wenn für den selben Produkttyp nur eine Size im Warenkorb befindet
 * @param item, itemTotal
 * @author Felix Bartel
 */

function createSingleItemRowServer(item, itemTotal) {
  const row = document.createElement('div');
  row.className = 'cart-item';

  const img = document.createElement('img');
  img.className = 'cart-item-img';
  img.src = item.image;
  img.alt = item.name;
  img.width = 60;

  const firstLineItemBlock = document.createElement('div');
  firstLineItemBlock.className = 'cart-item-first-line-item-block';

  const nameSpan = document.createElement('span');
  nameSpan.textContent = `${item.name}`;
  nameSpan.style.fontWeight = 'bold';

  const sizeSpan = document.createElement('span');
  sizeSpan.textContent = `(${item.size}g)`;

  const quantityContainer = document.createElement('div');
  quantityContainer.className = 'cart-item-quantity-container';

  const minusButton = document.createElement('button');
  minusButton.addEventListener("click", () => {
    updateServerQuantity(item.id, item.quantity - 1);
  });
  const minusIcon = document.createElement('img');
  minusIcon.src = BASE_URL + '/images/minusBlack.svg';
  minusButton.appendChild(minusIcon);

  const quantityInput = document.createElement('input');
  quantityInput.type = 'number';
  quantityInput.min = 1;
  quantityInput.value = item.quantity;
  quantityInput.className = 'cart-item-quantity-input';
  quantityInput.setAttribute('data-id', item.id);
  quantityInput.addEventListener('change', (event) => {
    const newQuantity = parseInt(event.target.value, 10);
    if (newQuantity >= 1 && Number.isInteger(newQuantity)) {
      updateServerQuantity(item.id, newQuantity);
    } else {
      event.target.value = item.quantity;
    }
  });

  const plusButton = document.createElement('button');
  plusButton.addEventListener("click", () => {
    updateServerQuantity(item.id, item.quantity + 1);
  });
  const plusIcon = document.createElement('img');
  plusIcon.src = BASE_URL + '/images/plusBlack.svg';
  plusButton.appendChild(plusIcon);

  quantityContainer.appendChild(minusButton);
  quantityContainer.appendChild(quantityInput);
  quantityContainer.appendChild(plusButton);

  const priceSpan = document.createElement('span');
  priceSpan.textContent = `${itemTotal.toFixed(2)} €`;

  const removeButton = document.createElement('button');
  removeButton.className = 'cart-item-remove-btn';
  removeButton.addEventListener("click", () => {
    removeServerItem(item.id);
  });
  const removeIcon = document.createElement('img');
  removeIcon.src = BASE_URL + '/images/removeIcon.svg';
  removeButton.appendChild(removeIcon);

  firstLineItemBlock.appendChild(nameSpan);
  firstLineItemBlock.appendChild(sizeSpan);
  firstLineItemBlock.appendChild(quantityContainer);
  firstLineItemBlock.appendChild(priceSpan);
  firstLineItemBlock.appendChild(removeButton);

  row.appendChild(img);
  row.appendChild(firstLineItemBlock);

  return row;
}





/**
 * Greift, und erstellt Ansicht, wenn für den selben Produkttyp mehrere Sizes im Warenkorb befinden
 * @param item, allItemsOfThisType
 * @author Felix Bartel
 */

function createMultiItemRowServer(item, allItemsOfThisType) {
  const row = document.createElement('div');
  row.className = 'cart-item';

  const img = document.createElement('img');
  img.className = 'cart-item-img';
  img.src = item.image;
  img.alt = item.name;
  img.width = 60;

  const block = document.createElement('div');
  block.className = 'cart-item-first-line-item-block-multi';

  const name = document.createElement('span');
  name.textContent = item.name;
  name.className = 'cart-item-name';
  name.style.fontWeight = 'bold';
  name.style.gridColumn = '1 / 2';
  name.style.gridRow = '1 / 2';
  block.appendChild(name);

  let i = 0;
  for (const [size, data] of Object.entries(allItemsOfThisType)) {
    const rowPrice = data.quantity * data.price;

    const sizeText = document.createElement('div');
    sizeText.textContent = `${data.quantity} x ${size}g: ${rowPrice.toFixed(2)}€`;
    sizeText.style.gridColumn = '2 / 3';
    sizeText.style.gridRow = `${i + 1} / ${i + 2}`;
    block.appendChild(sizeText);

    const qty = document.createElement('div');
    qty.className = 'cart-item-quantity-container';
    qty.style.gridColumn = '3 / 4';
    qty.style.gridRow = `${i + 1} / ${i + 2}`;
    qty.innerHTML = `
      <button onclick="updateServerQuantity(${data.id}, ${data.quantity - 1})"><img src="${BASE_URL}/images/minusBlack.svg"></button>
      <input type="number" class="cart-item-quantity-input" value="${data.quantity}" data-id="${data.id}" onchange="updateServerQuantity(${data.id}, this.value)">
      <button onclick="updateServerQuantity(${data.id}, ${data.quantity + 1})"><img src="${BASE_URL}/images/plusBlack.svg"></button>
    `;
    block.appendChild(qty);

    const totalSpan = document.createElement('span');
    totalSpan.textContent = `${rowPrice.toFixed(2)}€`;
    totalSpan.style.gridColumn = '4 / 5';
    totalSpan.style.gridRow = `${i + 1} / ${i + 2}`;
    block.appendChild(totalSpan);

    const remove = document.createElement('button');
    remove.className = 'cart-item-remove-btn';
    remove.style.gridColumn = '5 / 6';
    remove.style.gridRow = `${i + 1} / ${i + 2}`;
    remove.innerHTML = `<img src="${BASE_URL}/images/removeIcon.svg" alt="Entfernen">`;
    remove.onclick = () => removeServerItem(data.id);
    block.appendChild(remove);

    i++;
  }

  row.appendChild(img);
  row.appendChild(block);
  return row;
}






/**
 * zeigt alle serverseitigen Warenkorb-Artikel an, 
 * berechnet den Gesamtpreis und gruppiert Produkte mit mehreren Größen zu einer gemeinsamen Darstellung.
 * @param cartItems
 * @author Felix Bartel
 */


function renderServerCart(cartItems) {
  const container = document.getElementById('cart-items');
  const totalDisplay = document.getElementById('cart-total');

  if (!container || !totalDisplay) return;

  while (container.firstChild) {
    container.removeChild(container.firstChild);
  }

  cartItems = sortCart(cartItems);

  const multiSizeNames = [...new Set(cartItems.map(i => i.name))]
    .filter(name => cartItems.filter(i => i.name === name).length > 1);

  const grouped = {};
  cartItems.forEach(item => {
    if (multiSizeNames.includes(item.name)) {
      if (!grouped[item.name]) grouped[item.name] = {};
      grouped[item.name][item.size] = {
        quantity: item.quantity,
        price: item.price,
        id: item.id,
        image: item.image
      };
    }
  });

  let total = 0;
  const rendered = new Set();

  cartItems.forEach((item) => {
    const itemTotal = item.price * item.quantity;
    total += itemTotal;

    let row;
    if (multiSizeNames.includes(item.name) && !rendered.has(item.name)) {
      row = createMultiItemRowServer(item, grouped[item.name]);
      rendered.add(item.name);
    } else if (!multiSizeNames.includes(item.name)) {
      row = createSingleItemRowServer(item, itemTotal);
    }

    if (row) container.appendChild(row);
  });

   const netto = total * 0.81;

  totalDisplay.innerHTML = `Netto: ${netto.toFixed(2)} € <br> Gesamt: ${total.toFixed(2)} €`;
  checkCheckoutButtonState();
}










/**********************************************
 *            Slider-Funktionen               *
 **********************************************/

/**
 * zuständig für die Visualisierug der cartslider.php Seite (Render-Funktion)
 * @author Merzan Köse
 */

function renderCartSlider() {
  const isLoggedIn = localStorage.getItem('isLoggedIn') === 'true';

  if (isLoggedIn) {
    fetch('index.php?page=get-cart')
      .then(res => res.json())
      .then(data => {
        const mapped = data.map(item => ({
          ...item,
          name: item.product_name
        }));
        renderServerCartSlider(mapped);
      })
      .catch(err => console.error('Fehler beim Laden des Server-Warenkorbs (Slider):', err));
  } else {
    renderClientCartSlider();
  }
}

function renderClientCartSlider() {
  const cart = getCart();
  const cartItemsContainer = document.getElementById('cartItems');
  cartItemsContainer.innerHTML = '';
  let total = 0;

  cart.forEach((item, index) => {
    const itemTotal = item.price * item.quantity;
    total += itemTotal;

    const imagePath = item.image;

    cartItemsContainer.innerHTML += `
      <div class="cart-item">
        <div class="cart-item-image">
          <img src="${imagePath}" alt="${item.name}" />
        </div>
        <div class="cart-item-main">
          <div class="cart-item-title">${item.name} (${item.size}g)</div>
          <div class="item-total" style="margin-bottom:0.4rem;">${itemTotal.toFixed(2)} €</div>
          <div class="cart-item-bottom-row">
            <div class="qty-row">
              <button class="qty-btn" onclick="updateQuantity(${index}, ${item.quantity - 1}, true)">
                <img src="${BASE_URL}/images/minusBlack.svg" alt="Minus">
              </button>
              <span class="qty">${item.quantity}</span>
              <button class="qty-btn" onclick="updateQuantity(${index}, ${item.quantity + 1}, true)">
                <img src="${BASE_URL}/images/plusBlack.svg" alt="Plus">
              </button>
            </div>
            <img src="${BASE_URL}/images/removeIcon.svg" alt="Entfernen" class="remove-btn" onclick="removeFromCart(${index}, true)">
          </div>
        </div>
      </div>
    `;
  });

  const cartTotalSlider = document.getElementById('cartTotal');
  if (cartTotalSlider) {
    cartTotalSlider.textContent = total.toFixed(2) + " €";
  }
}






/**
 * zeigt alle serverseitigen Warenkorb-Artikel im Slider an, 
 * @param cartItems
 * @author Felix Bartel
 */

function renderServerCartSlider(cartItems) {
  const cartItemsContainer =
    document.getElementById('cartItems') ||
    document.getElementById('cart-items');

  if (!cartItemsContainer) return;
  cartItemsContainer.innerHTML = '';
  let total = 0;

  cartItems.forEach(item => {
    const itemTotal = item.price * item.quantity;
    total += itemTotal;

    const imagePath = item.image;

    cartItemsContainer.innerHTML += `
      <div class="cart-item">
        <div class="cart-item-image">
          <img src="${imagePath}" alt="${item.name}" />
        </div>
        <div class="cart-item-main">
          <div class="cart-item-title">${item.name} (${item.size}g)</div>
          <div class="item-total" style="margin-bottom:0.4rem;">${itemTotal.toFixed(2)} €</div>
          <div class="cart-item-bottom-row">
            <div class="qty-row">
              <button class="qty-btn" onclick="${item.quantity > 1 ? `updateServerQuantity(${item.id}, ${item.quantity - 1})` : `removeServerItem(${item.id})`}">
               <img src="${BASE_URL}/images/minusBlack.svg" alt="Minnus">
              </button>
              <span class="qty">${item.quantity}</span>
              <button class="qty-btn" onclick="updateServerQuantity(${item.id}, ${item.quantity + 1})">
               <img src="${BASE_URL}/images/plusBlack.svg" alt="Plus">
              </button>
            </div>
           <img src="${BASE_URL}/images/removeIcon.svg" alt="Entfernen" class="remove-btn" onclick="removeServerItem(${item.id})">
          </div>
        </div>
      </div>
    `;
  });

  const cartTotalSlider =
    document.getElementById('cartTotal') ||
    document.getElementById('cart-total');

  if (cartTotalSlider) {
    cartTotalSlider.textContent = total.toFixed(2) + " €";
  }
}







function openCart() {
  const slider = document.getElementById("cartSlider");
  if (slider) slider.classList.add("open");
}

function closeCart() {
  const slider = document.getElementById("cartSlider");
  if (slider) slider.classList.remove("open");
}

document.addEventListener('DOMContentLoaded', () => {
  const closeBtn = document.getElementById('closeCartBtn');
  if (closeBtn) {
    closeBtn.addEventListener('click', () => {
      const slider = document.getElementById('cartSlider');
      if (slider) slider.classList.remove('open');
    });
  }
});


















/**********************************************
 *            Timer-Funktionen               *
 **********************************************/




var time = 0; // Timer-Variable (wird später aus localStorage geladen)
var runningTimer; // Referenz für den laufenden Timer

/**
 * Stellt den Timer optisch auf der cart.php Seite dar
 * @author Marvin Kunz
 */
function updateTimerDisplay() {
  if (document.querySelector(".timerText") != null) {
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




/**
 * Beim Laden der cart.php Seite die gespeicherte Zeit im localStorage holen
 * @author Marvin Kunz
 */
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

/**
 * Funktion, die sekündlich aufgerufen wrd, um die Restzeit des Timer anzupassen (-> Implementierung der Timer-Funktionalität)
 * @author Marvin Kunz
 */
function timer() {
  if (time > 0) {
    time--; // Timer dekrementieren
    updateTimerDisplay(); // Timer im HTML-Element aktualisieren
    // Speichere die verbleibende Zeit im localStorage
    localStorage.setItem('timerTime', time);
  } else {
    stopTimer(); // Stoppe den Timer, wenn er abgelaufen ist
  }
}

/**
 * Funktion zum Stoppen des Timers
 * @author Marvin Kunz
 */
function stopTimer() {
  if (runningTimer) {
    clearInterval(runningTimer);
    runningTimer = null;
    localStorage.removeItem('timerTime');
  }
  let cart = getCart();
  const timerTextEl = document.querySelector(".timerText");
  const timeEl = document.querySelector(".time");

  if (cart.length === 0) {
    if (timerTextEl) timerTextEl.style.visibility = 'hidden';
    if (timeEl) timeEl.style.visibility = 'hidden';
  } else {
    if (timerTextEl) timerTextEl.innerHTML = "Produktreservierung ist abgelaufen";
    if (timeEl) timeEl.style.visibility = 'hidden';
  }
}

/**
 * Funktion zum Starten des Timers
 * @author Marvin Kunz
 */
function startTimer() {
  if (!runningTimer) {  // Stelle sicher, dass der Timer nicht doppelt läuft
    // Hier definierst du die Startzeit des Timers

    time = 20; // Wenn keine Zeit im localStorage ist, setze 20 Minuten als Startzeit


    runningTimer = setInterval(timer, 1000); // Intervall für Timer
    timer(); // Direkt beim Start den Timer aufrufen
  }
}






/**
 * Funktion, die den Aufruf der Funktion addToCart() mit geeigneten Parametern vorbereitet
 */
function intermediateStepAddToCart() {
  let selectedButton = document.querySelector('#VerpackungsgrößenButtons button.active');
  let buttonContent = selectedButton.textContent.slice(0, -1);

  addToCart(product.name, product.product_pic1, getTotalPrice(product.priceWithoutTax), buttonContent);
}

/**
 * Funktion die den CheckoutBTTN blockiert wenn man kein
 * Produkt im Warenkobr hat aber in den checkout möchte
 */
function checkCheckoutButtonState() {
  const btn = document.getElementById('checkoutBtn');
  if (!btn) return;

  const isLoggedIn = localStorage.getItem('isLoggedIn') === 'true';

  if (isLoggedIn) {
    fetch('index.php?page=get-cart')
      .then(res => res.json())
      .then(cart => {
        if (!Array.isArray(cart) || cart.length === 0) {
          disableCheckoutBtn(btn);
        }
      })
      .catch(() => disableCheckoutBtn(btn));
  } else {
    const cart = JSON.parse(localStorage.getItem('cart') || '[]');
    if (cart.length === 0) {
      disableCheckoutBtn(btn);
    }
  }
}

function disableCheckoutBtn(button) {
  button.disabled = true;
  button.title = 'Warenkorb ist leer';
  button.style.opacity = 0.5;
  button.style.cursor = 'not-allowed';
}

