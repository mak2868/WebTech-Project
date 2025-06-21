window.addEventListener("DOMContentLoaded", () => {
  const isLoggedIn = window.IS_LOGGED_IN === true;
  const cartDataInput = document.getElementById('cart_data');
  const cartItemsContainer = document.getElementById('cartItems');
  const cartTotalEl = document.getElementById('cartTotal');

  if (!cartItemsContainer || !cartTotalEl) return;

  function renderItem({ name, product_name, size, quantity, price, image }) {
    const total = price * quantity;
    const wrapper = document.createElement("div");
    wrapper.className = "checkout-item";
    wrapper.innerHTML = `
      <div class="checkout-item-img">
        <img src="${window.BASE_URL + image}" alt="${product_name || name}" />
      </div>
      <div class="checkout-item-details">
        <div class="checkout-item-title"><strong>${product_name || name}</strong> (${size}g)</div>
        <div class="checkout-item-info">${quantity} × ${Number(price).toFixed(2)} € = <strong>${total.toFixed(2)} €</strong></div>
      </div>
    `;
    return wrapper;
  }

if (isLoggedIn) {
  fetch("index.php?page=get-cart")
    .then((res) => res.json())
    .then((data) => {
      cartItemsContainer.innerHTML = "";
      let total = 0;

      data.forEach((item) => {
        total += item.price * item.quantity;
        cartItemsContainer.appendChild(renderItem(item));
      });

      const coupon = window.SESSION_COUPON;

      let discount = 0;
      if (coupon) {
        if (coupon.type === "percent") {
          discount = total * coupon.value / 100;
        } else if (coupon.type === "amount") {
          discount = coupon.value;
        }
      }

      const finalTotal = Math.max(total - discount, 0);

      // Gesamtbetrag anzeigen
      cartTotalEl.textContent = finalTotal.toFixed(2) + " €";

      // Rabatttext anzeigen
      if (discount > 0) {
        const savingsEl = document.createElement("div");
        savingsEl.className = "summary-savings";
        savingsEl.innerHTML = `Du sparst: <strong>${discount.toFixed(2).replace('.', ',')} €</strong>`;
        cartTotalEl.parentNode.insertBefore(savingsEl, cartTotalEl.parentNode.firstChild);

      }

      cartDataInput.value = JSON.stringify(data);

      // Gesamtbetrag an PHP senden (nicht rabattiert!)
      fetch("index.php?page=set-cart-total", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ total })
      })
        .then(res => res.json())
        .then(json => {
          if (!json.success) {
            console.warn("Fehler bei setCartTotal:", json.message || json);
          }
        })
        .catch(err => console.error("setCartTotal failed:", err));
    });
}


  else {
    const cart = JSON.parse(localStorage.getItem("cart")) || [];
    cartItemsContainer.innerHTML = "";
    let total = 0;

    cart.forEach((item) => {
      total += item.price * item.quantity;
      cartItemsContainer.appendChild(renderItem(item));
    });

    cartTotalEl.textContent = total.toFixed(2) + " €";

    // Gesamtbetrag an PHP senden für spätere Rabattanzeige
    fetch("index.php?page=set-cart-total", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ total })
    })
      .then(res => res.json())
      .then(json => {
        if (!json.success) {
          console.warn("Fehler bei setCartTotal:", json.message || json);
        }
      })
      .catch(err => console.error("setCartTotal failed:", err));
    cartDataInput.value = JSON.stringify(cart);
  }
});
