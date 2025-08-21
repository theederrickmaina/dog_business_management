// Load cart items from localStorage
function loadCartItems() {
    const cartItems = JSON.parse(localStorage.getItem("cart")) || []; // Retrieve cart data from localStorage
    const cartContainer = document.getElementById("cart-items");
    const subtotalElement = document.getElementById("subtotal");
    const taxElement = document.getElementById("tax");
    const totalElement = document.getElementById("total");

    // Clear the cart container
    cartContainer.innerHTML = "";

    if (cartItems.length === 0) {
        // Display a message if the cart is empty
        cartContainer.innerHTML = "<p>Your cart is empty.</p>";
        subtotalElement.textContent = "Kshs. 0";
        taxElement.textContent = "Kshs. 0";
        totalElement.textContent = "Kshs. 0";
        return;
    }

    let subtotal = 0;

    cartItems.forEach((item) => {
        // Create a row for each cart item
        const cartRow = document.createElement("div");
        cartRow.classList.add("cart-row");
        cartRow.innerHTML = `
            <div class="cart-item">
                <img src="${item.image}" alt="${item.name}" class="cart-item-image">
                <div class="cart-item-details">
                    <p><strong>Name:</strong> ${item.name}</p>
                    <p><strong>Sex:</strong> ${item.sex}</p>
                    <p><strong>Price:</strong> Kshs. ${item.price}</p>
                </div>
            </div>
            <button class="remove-item" data-id="${item.id}">Remove</button>
        `;

        cartContainer.appendChild(cartRow);

        // Add to the subtotal
        subtotal += parseFloat(item.price);
    });

    // Calculate tax and total
    const tax = subtotal * 0.16;
    const total = subtotal + tax;

    // Update the cart summary
    subtotalElement.textContent = `Kshs. ${subtotal.toFixed(2)}`;
    taxElement.textContent = `Kshs. ${tax.toFixed(2)}`;
    totalElement.textContent = `Kshs. ${total.toFixed(2)}`;

    // Add event listeners to the "Remove" buttons
    document.querySelectorAll(".remove-item").forEach((button) => {
        button.addEventListener("click", function () {
            const itemId = this.getAttribute("data-id");
            removeCartItem(itemId);
        });
    });
}

// Remove an item from the cart
function removeCartItem(itemId) {
    let cartItems = JSON.parse(localStorage.getItem("cart")) || [];
    cartItems = cartItems.filter((item) => item.id !== itemId); // Filter out the item to be removed
    localStorage.setItem("cart", JSON.stringify(cartItems)); // Update localStorage
    loadCartItems(); // Reload the cart display
}

// Load cart items when the page loads
document.addEventListener("DOMContentLoaded", loadCartItems);






function removeItem(button) {
    const cartItem = button.parentElement.parentElement;
    cartItem.remove();
    updateCartSummary();
}

function updateCartSummary() {
    const cartItems = document.querySelectorAll('.cart-item');
    let subtotal = 0;

    cartItems.forEach(item => {
        const price = parseFloat(item.querySelector('p').textContent.replace('Price: Kshs. ', ''));
        const quantity = parseInt(item.querySelector('input').value);
        subtotal += price * quantity;
    });

    const tax = subtotal * 0.16; // 16% tax
    const total = subtotal + tax;

    document.getElementById('subtotal').textContent = `Kshs. ${subtotal.toFixed(2)}`;
    document.getElementById('tax').textContent = `Kshs. ${tax.toFixed(2)}`;
    document.getElementById('total').textContent = `Kshs. ${total.toFixed(2)}`;
}

// Update cart summary when quantity changes
document.querySelectorAll('.quantity input').forEach(input => {
    input.addEventListener('change', updateCartSummary);
});
