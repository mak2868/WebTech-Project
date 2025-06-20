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

        cartTotalEl.textContent = total.toFixed(2) + " €";
        cartDataInput.value = JSON.stringify(data);
      });
  } else {
    const cart = JSON.parse(localStorage.getItem("cart")) || [];
    cartItemsContainer.innerHTML = "";
    let total = 0;

    cart.forEach((item) => {
      total += item.price * item.quantity;
      cartItemsContainer.appendChild(renderItem(item));
    });

    cartTotalEl.textContent = total.toFixed(2) + " €";
    cartDataInput.value = JSON.stringify(cart);
  }
});
