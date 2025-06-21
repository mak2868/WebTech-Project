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

  function sendCartTotal(total) {
    fetch("index.php?page=set-cart-total", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ total })
    }).catch(err => console.error("setCartTotal failed:", err));
  }

  if (isLoggedIn) {
    fetch("index.php?page=get-cart")
      .then(res => res.json())
      .then((data) => {
        cartItemsContainer.innerHTML = "";
        let total = 0;

        data.forEach((item) => {
          total += item.price * item.quantity;
          cartItemsContainer.appendChild(renderItem(item));
        });

        let discount = 0;
        const coupon = window.SESSION_COUPON;
        console.log("SESSION_COUPON:", coupon);

        if (coupon) {
          if (coupon.type === "percent") {
            discount = total * coupon.value / 100;
          } else if (coupon.type === "amount") {
            discount = coupon.value;
          }
        }

        const finalTotal = Math.max(total - discount, 0);
        cartTotalEl.textContent = finalTotal.toFixed(2) + " €";

        console.log("Discount:", discount);

        const savingsEl = document.getElementById("cartSavings");
        if (discount > 0 && savingsEl) {
          savingsEl.innerHTML = `Du sparst: <strong>${discount.toFixed(2).replace('.', ',')} €</strong>`;
        }



        cartDataInput.value = JSON.stringify(data);
        sendCartTotal(total);
      });
  }

  const form = document.getElementById('checkoutForm');
  if (form) {
    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      const cart = JSON.parse(cartDataInput.value || '[]');
      if (!cart.length) {
        alert("Dein Warenkorb ist leer.");
        return;
      }

      const response = await fetch("index.php?page=place-order", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(cart)
      });

      const result = await response.json();
      if (result.success) {
        alert("Bestellung erfolgreich! Bestellnummer: " + result.order_id);
        window.location.href = "index.php";
      } else {
        alert("Fehler bei der Bestellung: " + result.message);
      }
    });
  }
});
