// Script wird ausgeführt, sobald das DOM vollständig geladen ist
window.addEventListener("DOMContentLoaded", () => {
  // Prüft, ob der Benutzer eingeloggt ist (vom Server gesetzt)
  const isLoggedIn = window.IS_LOGGED_IN === true;

  /**
   *@author Merzan
   */

  // DOM-Elemente
  const cartDataInput = document.getElementById('cart_data'); // verstecktes Input-Feld für Warenkorb-Daten
  const cartItemsContainer = document.getElementById('cartItems'); // Container für Produktdarstellung
  const cartTotalEl = document.getElementById('cartTotal'); // Gesamtpreis-Anzeige

  // Falls kein Warenkorb angezeigt werden soll, Abbruch 
  if (!cartItemsContainer || !cartTotalEl) return;

  /**
   * Erstellt ein DOM-Element für ein Produkt im Checkout
   */
function renderItem({ name, product_name, size, quantity, price, image }) {
  const total = price * quantity;
  const wrapper = document.createElement("div");
  wrapper.className = "checkout-item";

  wrapper.innerHTML = `
    <div class="checkout-item-img">
      <img src="${image}" alt="${product_name || name}" />
    </div>
    <div class="checkout-item-details">
      <div class="checkout-item-title"><strong>${product_name || name}</strong> (${size}g)</div>
      <div class="checkout-item-info">${quantity} × ${Number(price).toFixed(2)} € = <strong>${total.toFixed(2)} €</strong></div>
    </div>
  `;

  return wrapper;
}
  /**
   * Sendet den Gesamtpreis serverseitig in die Session (optional)
   */
  function sendCartTotal(total) {
    fetch("index.php?page=set-cart-total", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ total })
    }).catch(err => console.error("setCartTotal failed:", err));
  }

  /**
   * Warenkorb anzeigen, wenn Benutzer eingeloggt ist
   */
  if (isLoggedIn) {
    fetch("index.php?page=get-cart")
      .then(res => res.json())
      .then((data) => {
        cartItemsContainer.innerHTML = ""; // vorherige Inhalte löschen
        let total = 0;

        // Einzelne Warenkorbpositionen rendern
        data.forEach((item) => {
          total += item.price * item.quantity;
          cartItemsContainer.appendChild(renderItem(item));
        });

        // Rabatt anwenden, falls Gutschein vorhanden
        let discount = 0;
        const coupon = window.SESSION_COUPON;

        if (coupon) {
          if (coupon.type === "percent") {
            discount = total * coupon.value / 100;
          } else if (coupon.type === "amount") {
            discount = coupon.value;
          }
        }

        // Gesamtpreis nach Rabatt anzeigen
        const finalTotal = Math.max(total - discount, 0);
        cartTotalEl.textContent = finalTotal.toFixed(2) + " €";

        // Spare-Betrag anzeigen (optional)
        const savingsEl = document.getElementById("cartSavings");
        if (discount > 0 && savingsEl) {
          savingsEl.innerHTML = `Du sparst: <strong>${discount.toFixed(2).replace('.', ',')} €</strong>`;
        }

        // Warenkorb-Daten für den Checkout bereitstellen
        cartDataInput.value = JSON.stringify(data);
        sendCartTotal(total); // serverseitig speichern (optional)
      });
  }

  /**
   * Absenden des Formulars „Jetzt bestellen“
   */
  const form = document.getElementById('checkoutForm');
  if (form) {
    form.addEventListener('submit', async (e) => {
      e.preventDefault();

      const cart = JSON.parse(cartDataInput.value || '[]');
      if (!cart.length) {
        alert("Dein Warenkorb ist leer.");
        return;
      }

      try {
        const response = await fetch("index.php?page=place-order", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(cart)
        });

        // Body nur einmal lesen, danach nicht nochmal zugreifen
        const text = await response.text();
        let result;

        try {
          result = JSON.parse(text);
        } catch (jsonErr) {
          console.error("Antwort war kein gültiges JSON:", text);
          alert("Serverfehler bei der Bestellung. Siehe Konsole.");
          return;
        }

        if (result.success) {
          localStorage.removeItem("cart"); // clientseitiger Warenkorb leeren
          window.location.href = "index.php?page=thankyou"; // Weiterleitung nach Bestellung
        } else {
          alert("Fehler bei der Bestellung: " + (result.message || "Unbekannter Fehler"));
        }

      } catch (fetchErr) {
        console.error("Fetch fehlgeschlagen:", fetchErr);
        alert("Netzwerkfehler. Bitte später erneut versuchen.");
      }
    });
  }
});
