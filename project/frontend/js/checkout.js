document.addEventListener("DOMContentLoaded", function () {
    const cart = JSON.parse(localStorage.getItem("cart")) || [];
    const checkoutContainer = document.getElementById("checkout-container");

    if (cart.length === 0) {
        checkoutContainer.innerHTML = "<p>Your cart is empty.</p>";
        return;
    }

    let totalPrice = 0;
    cart.forEach(product => {
        totalPrice += product.price * product.quantity;

        const item = document.createElement("div");
        item.classList.add("checkout-item");
        item.innerHTML = `
            <img src="http://localhost/project/backend/uploads/${product.image}" alt="${product.name}">
            <p>${product.name} - $${product.price} x ${product.quantity}</p>
        `;
        checkoutContainer.appendChild(item);
    });

    const total = document.createElement("p");
    total.innerHTML = `<strong>Total: $${totalPrice.toFixed(2)}</strong>`;
    checkoutContainer.appendChild(total);

    document.getElementById("checkout-form").addEventListener("submit", function (event) {
        event.preventDefault();

        const orderData = {
            name: document.getElementById("name").value,
            address: document.getElementById("address").value,
            payment: document.getElementById("payment").value,
            cart: cart
        };

        fetch("http://localhost/project/backend/php/process_checkout.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(orderData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                alert("Order placed successfully!");
                localStorage.removeItem("cart");
                window.location.href = "index.html";
            } else {
                alert("Error: " + data.message);
            }
        })
        .catch(error => console.error("Error:", error));
    });
});
function checkout() {
    const cart = JSON.parse(localStorage.getItem("cart")) || [];

    if (cart.length === 0) {
        alert("Your cart is empty!");
        return;
    }

    fetch("http://localhost/project/backend/php/checkout.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ cart: cart })
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        if (data.status === "success") {
            localStorage.removeItem("cart");
            window.location.href = "order_success.html";
        }
    })
    .catch(error => console.error("Checkout error:", error));
}
