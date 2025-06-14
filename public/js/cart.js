// cart.js

/**
 * Funktion, die den Aufruf der Funktion addToCart() mit geeigneten Parametern vorbereitet
 * @author Marvin Kunz
 */
function intermediateStepAddToCart() {
  let selectedButton = document.querySelector('#VerpackungsgrößenButtons button.active'); // sucht den aktivierten Größenbutton auf der item.php Seite
  let buttonContent = selectedButton.textContent.slice(0, -1); // speichert die ausgewählte Größe ab
  // let index;


  // for (let i = 0; i < product.availableSizes.length; i++) {
  //   if (buttonContent == product.availableSizes[i]) {
  //     index = i;
  //     break;
  //   }
  // }

  // addToCart(product.name, product.pics.productPic1, getTotalPrice(product.priceWithoutTax), product.availableSizes[index]);
  addToCart(product.name, product.pics.productPic1, getTotalPrice(product.priceWithoutTax), buttonContent);
}

/**
 * Funktion, die ein Produkt zum Warenkorb hinzufügt, dabei wird in der Vorgehensweise unterschieden,
 * ob es eine neues Produkt im Warenkorb ist oder dieses bereits existiert.
 * @param {Name des Produkts} name 
 * @param {Das zum Produkt passende Bild, welches angezeigt werden soll} image 
 * @param {Preis des Produkts} price 
 * @param {Größe des Produktes} size
 * @author Felix Bartel, Marvin Kunz (Anpassung für verschiedene Größen)
 */
function addToCart(name, image, price, size) {
  let cart = JSON.parse(localStorage.getItem('cart')) || [];

  // Setze bei allen vorhandenen Produkten lastChanged auf false
  cart.forEach(item => item.lastAdded = false);

  // sucht, ob das Produkt in der gewünschten Größe bereits im Warenkorb liegt und speichert dieses ggf. ab
  let existing = cart.find(item => item.name === name && item.size === size);

  // Vorgehensweise abhängig davon, ob das Produkt bereits teil des Warenkorbs ist oder nicht
  if (existing) {
    existing.quantity += 1; // erhöht die Anzahl 
    existing.lastAdded = true;
    cart.forEach(item => {
      if (item.name == name) {
        item.lastAdded = true; // das zuletzt hinzugefügte Produkt wird markiert (unabhängig von deren Größe, geht auf den Namen zurück)
      }
    });
  } else {
    cart.push({ name, image, price, quantity: 1, size, lastAdded: true }); // fügt das Produkt dem Warenkorb hinzu
    cart.forEach(item => {
      if (item.name == name) {
        item.lastAdded = true; // das zuletzt hinzugefügte Produkt wird markiert (unabhängig von deren Größe, geht auf den Namen zurück)
      }
    });
  }

  // Warenkorb sortieren
  cart = sortCart(cart);

  localStorage.setItem('cart', JSON.stringify(cart));

  // Öffnet den Slider (führt openCart() aus) (kommt in /views)
  openCart();
  renderCartSlider();

  // startet den Timer, der runterzählt, wie lange das Produkt noch reserviert ist
  startTimer();
}

/**
 * Sortiert den Warenkorb nach: Name und Größe de Produktes, zsätzlich stehen alle Größen des zuletzt hinzugefügten Produktes ganz vorne
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
 * Liest den Warenkorb aus dem localStorage
 * @returns Warenkorb
 * @author Felix Bartel
 */
function getCart() {
  return JSON.parse(localStorage.getItem('cart')) || [];
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
 * Entferne ein Produkt aus dem Warenkorb (per Index)
 * @param {Index an welcher Stelle im Warenkorb sich das zu entfernende Produkt befindet} index 
 * @param {boolsche Angabe, ob der Aufuruf der Funktion durch den Warenkorb-Slider geschieht oder nicht} isSlider 
 * @author Felix Bartel, Marvin Kunz (Anpassung: Timer)
 */
function removeFromCart(index, isSlider) {
  let cart = getCart();
  cart.splice(index, 1); // entfernt das Produkt

  // stoppt den Timer, falls der Warenkorb leer ist
  if (cart.length === 0) {
    stopTimer();
  }

  // fügt den Warenkorb dem localStorage hinzu
  localStorage.setItem('cart', JSON.stringify(cart));

  // ruft entsprechend der aufrufenden Seite die passende Visualisierung auf 
  if (isSlider) {
    renderCartSlider(); //nur auf cartslider.php nötig
  } else {
    renderCart(); // nur auf cart.php nötig
  }
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
  let indexInCart = getIndexInCart(name, indexFromButtons);
  updateQuantity(indexInCart, newQuantity, isSlider);
}

// Ändere die Menge eines Produkts (per Index)
/**
 * Ändere die Menge eines Produkts
 * @param {Index an welcher Stelle im Warenkorb sich das zu entfernende Produkt befindet} index 
 * @param {Neue Anzahl der zum Warenkorb hinzugefügten Produkte dieses Produkttyps} newQuantity 
 * @param {boolsche Angabe, ob der Aufuruf der Funktion durch den Warenkorb-Slider geschieht oder nicht} isSlider 
 * @author Felix Bartel
 */
function updateQuantity(index, newQuantity, isSlider) {
  let cart = getCart();

  // entfernt das Produkt aus dem Warenkorb, falls die Anzahl = 0
  if (newQuantity <= 0) {
    removeFromCartMultiRow(index, isSlider);
    return;
  }

  // Anpassung der Anzahl des übergebenen Produktes auf den übergebenen Wert
  cart[index].quantity = newQuantity;

  // fügt den Warenkorb dem localStorage hinzu
  localStorage.setItem('cart', JSON.stringify(cart));

  // ruft entsprechend der aufrufenden Seite die passende Visualisierung auf 
  if (isSlider) {
    renderCartSlider(); //nur auf cartslider.php nutzbar
  } else {
    renderCart(); // nur auf cart.php nötig
  }
}

/**
 * Berechnet anhand der übergebene Parameter den Index im Warenkorb (möglich dank der einheitlichen Sortierung anhand von fest definierten Regeln)
 * @param {Name des Produktes} name 
 * @param {Angabe, der wie vielte Button eines Containers betätigt wurde (-> entspricht der Positionszeile in der Visualisierung = x-tes Auftreten des Produktes mit diesem Namen)} indexFromButtons 
 * @returns Index des Produktes im Warenkorb
 * @author Marvin Kunz
 */
function getIndexInCart(name, indexFromButtons) {
  let cart = getCart();

  // Variable, die das Auftreten der unterschiedlichen Größen eines Produktes zählt
  let countItemWithName = 0;
  let returnValue;

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
  let arrSeveralSizes = []; // speichert Produktnamen aller Produkte, von welchem doppelte Größen im Warenkorb liegen

  // iteriert durch den Warenkorb
  cart.forEach((item, index) => {
    // sucht für jedes Item nach Items mit dem gleichen Namen (-> verschiedene Größen einer Sorte wurden in den Warenkorb gelegt)
    for (let i = index + 1; i < cart.length; i++) {
      if ((item.name === cart[i].name) && !arrSeveralSizes.includes(item.name)) { // legt den Namen nur dann ab, wenn er noch nicht Teil des Arrays ist
        arrSeveralSizes.push(item.name);
      }
    }
  });

  let arrSeveralProductSizeQuantity = []; // speichert für jedes Produkt aus arrSeveralSizes alle Größen mit ihrer Anzahl und dessen Stückpreis ab

  // durchläuft das Array, das alle Produktnamen beinhaltet
  arrSeveralSizes.forEach((_, index) => {
    let itemName = arrSeveralSizes[index];
    let newProduct = {};
    let firstLoop = true;

    // durchläuft den Warenkorb, um für die relevanten Produkte die Informationen abspeichern zu können
    cart.forEach(item => {
      if (item.name === itemName && firstLoop) {
        firstLoop = false;

        // bei erstmaligem Auftreten eines Produktes wird ein neues Produkt dem Array hinzugefügt + Informationen abgespeichert
        newProduct = {
          [item.size]: {
            quantity: item.quantity,
            price: item.price
          }
        };
        arrSeveralProductSizeQuantity.push(newProduct);

      } else if (item.name === itemName && !firstLoop) {
        // nachfolgend werden nur noch die neuen Informationen für verschiedene Größen hinterlegt
        newProduct[item.size] = {
          quantity: item.quantity,
          price: item.price
        };
      }
    });
  });

  let total = 0;
  let counterArrSeveralSizes = 0; // Variable die mitzählt, wie viele Produkte aus dem Array der Produkte mit mehreren hinzugefügten Größen bereits dargestellt wurden

  // Warenkorb wird durchlaufen, um die entsprechenden Visualisierungs-Funktionen für jedes Produkt aufzurufen
  cart.forEach((item, index) => {
    const itemTotal = item.price * item.quantity;
    total += itemTotal;

    let row;
    if (arrSeveralSizes.indexOf(item.name) == counterArrSeveralSizes) {
      row = createMultiItemRow(item, arrSeveralProductSizeQuantity[counterArrSeveralSizes]); // Aufruf der Darstellung mit Positionszeilen
      counterArrSeveralSizes++;
    } else if (!arrSeveralSizes.includes(item.name)) {
      row = createSingleItemRow(item, index, itemTotal); // Aufruf der Darstellung ohne Positionszeilen
    }

    if (row != undefined) {
      container.appendChild(row); // erstellte Visualisierung wird dem Container, der alle Produkte des Warenkorbs darstellt, übergeben
    }
  });

  totalDisplay.textContent = `Gesamt: ${total.toFixed(2)} €`;
}


/**
 * erstellt die Visualisierung der "zusammenfassenden Box" für alle Größen eines Produktes, wenn mehr als eine Größe eines Produktes im Warenkorb liegt
 * @param {übergibt das Produkt mit allen Werten, welche im Warenkorb gespeichert werden} item 
 * @param {übergibt alle Produkte mit dem gleichen Namen als Objekt mit dynamischem Key-Mapping, dabei wird jeweils nur die Größe mit Anzahl und Stückpreis übergeben} allItemsOfThisType 
 * @returns Box / Reihe (optische Darstellung des Produktes im Warenkorb)
 * @author Logik: Marvin Kunz, optische Darstellung / Anordnung: Felix Bartel
 */
function createMultiItemRow(item, allItemsOfThisType) {
  let totalSum = 0;

  // die Reihe, welche nachfolgend mit Produktinhalten befüllt wird
  const row = document.createElement('div');
  row.className = 'cart-item';

  // Produktbild
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

  // iteriert über das übergebene Objekt mit dynamischem Key-Mapping und erstellt dann für jede Größe eine Positionszeile
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

/**
 * erstellt die Visualisierung der "Box" für Produkte, die nur mit einer Größe eines Produktes im Warenkorb liegen
 * @param {übergibt das Produkt mit allen Werten, welche im Warenkorb gespeichert werden} item 
 * @param {Indes des Produktes im Warenkorb} index 
 * @param {Gesamtpreis für dieses Produkt} itemTotal 
 * @returns Box / Reihe (optische Darstellung des Produktes im Warenkorb)
 * @author Logik: Marvin Kunz, optische Darstellung / Anordnung: Felix Bartel
 */
function createSingleItemRow(item, index, itemTotal) {
  const row = document.createElement('div');
  row.className = 'cart-item';

  // Produktbild
  const img = document.createElement('img');
  img.className = 'cart-item-img';
  img.src = item.image;
  img.alt = item.name;
  img.width = 60;

  // Grid für den "firstLineItemBlock" anwenden
  const firstLineItemBlock = document.createElement('div');
  firstLineItemBlock.className = 'cart-item-first-line-item-block'; // Hier wird Grid verwendet

  // Produktname
  const nameSpan = document.createElement('span');
  nameSpan.textContent = `${item.name}`;
  nameSpan.style.fontWeight = 'bold';

  // Produktgröße
  const sizeSpan = document.createElement('span');
  sizeSpan.textContent = `(${item.size}g)`;

  // Anpassung + Anzeige der Anzahl
  const quantityContainer = document.createElement('div');
  quantityContainer.className = 'cart-item-quantity-container';

  const minusButton = document.createElement('button');
  minusButton.addEventListener("click", () => {
    updateQuantity(index, Number(item.quantity) - 1, false);
  });
  const minusButtonIcon = document.createElement('img');
  minusButtonIcon.src = 'images/minusBlack.svg';

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

  // Preis
  const sumPriceOfProductTypeSpan = document.createElement('span');
  sumPriceOfProductTypeSpan.textContent = `${itemTotal.toFixed(2)} €`;

  // Entfernen-Button
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

// Öffnet den Warenkorb-Slider, indem die CSS-Klasse "open" hinzugefügt wird (/controller)
function openCart() {
  const slider = document.getElementById("cartSlider"); // Referenz auf das Slider-Element
  if (slider) slider.classList.add("open");             // fügt die Klasse "open" hinzu → macht den Slider sichtbar
}

// Schließt den Warenkorb-Slider, indem die CSS-Klasse "open" entfernt wird (/controller)
function closeCart() {
  const slider = document.getElementById("cartSlider"); // Referenz auf das Slider-Element
  if (slider) slider.classList.remove("open");          // entfernt die Klasse "open" → versteckt den Slider
}

//Bei geöffneten Warenkorbslider lässt sich der slider durch einen CLick auf Icon schließen
window.addEventListener("DOMContentLoaded", () => {
  closeCart();
  // document.querySelector('.close-icon').addEventListener('click', closeCart);
});

var time = 0; // Timer-Variable (wird später aus localStorage geladen)
var runningTimer; // Referenz für den laufenden Timer

/**
 * Stellt den Timer otpisch auf der cart.php Seite dar
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


