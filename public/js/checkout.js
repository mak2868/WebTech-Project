window.addEventListener("DOMContentLoaded", () => {
    const isLoggedIn = window.IS_LOGGED_IN === true;
    const cartDataInput = document.getElementById('cart_data');
    const cartItemsContainer = document.getElementById('cartItems');
    const cartTotalEl = document.getElementById('cartTotal');

    if (isLoggedIn) {
        fetch("index.php?page=get-cart")
            .then(res => res.json())
            .then(data => {
                let total = 0;
                cartItemsContainer.innerHTML = "";
                data.forEach(item => {
                    const itemTotal = item.price * item.quantity;
                    total += itemTotal;

                    const itemDiv = document.createElement('div');
                    itemDiv.innerHTML = `
            <div style="margin-bottom: 1rem;">
              <strong>${item.product_name}</strong> (${item.size}g)<br>
              ${item.quantity} x ${Number(item.price).toFixed(2)
                        } € = ${itemTotal.toFixed(2)} €
            </div>`;
                    cartItemsContainer.appendChild(itemDiv);
                });

                cartTotalEl.textContent = total.toFixed(2) + ' €';
                cartDataInput.value = JSON.stringify(data);
            });
    } else {
        const cart = JSON.parse(localStorage.getItem('cart')) || [];
        let total = 0;
        cartItemsContainer.innerHTML = "";
        cart.forEach(item => {
            const itemTotal = Number(item.price) * Number(item.quantity);
            total += itemTotal;

            const itemDiv = document.createElement('div');
            itemDiv.innerHTML = `
        <div style="margin-bottom: 1rem;">
          <strong>${item.name}</strong> (${item.size}g)<br>
          ${item.quantity} x ${item.price.toFixed(2)} € = ${itemTotal.toFixed(2)} €
        </div>`;
            cartItemsContainer.appendChild(itemDiv);
        });

        cartTotalEl.textContent = total.toFixed(2) + ' €';
        cartDataInput.value = JSON.stringify(cart);
    }
});
