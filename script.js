const voiceAssistantToggle = document.getElementById('voiceAssistantToggle');
const cartButton = document.getElementById('cartBtn');
const checkoutButton = document.getElementById('checkoutBtn');
const colorBlindModeToggle = document.getElementById('colorBlindModeToggle');
const content = document.createElement('div');
content.className = 'content';
document.body.appendChild(content);

const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
const recognition = new SpeechRecognition();
let isListening = false;

// Event Listeners
document.addEventListener('DOMContentLoaded', function() {
    const categoryFilters = document.querySelectorAll('.category-filter');
    const brandFilters = document.querySelectorAll('.brand-filter');
    categoryFilters.forEach(filter => filter.addEventListener('change', filterProducts));
    brandFilters.forEach(filter => filter.addEventListener('change', filterProducts));

    const addToCartButtons = document.querySelectorAll('button');
    addToCartButtons.forEach(button => {
        if (button.textContent.trim().toLowerCase() === 'add to cart') {
            button.addEventListener('click', (event) => {
                const card = event.target.closest('.product-card');
                const productTitle = card.querySelector('.product-title').innerText;
                const productPrice = parseFloat(card.querySelector('.product-price').innerText.replace('$', ''));
                updateCart(productTitle, productPrice);
            });
        }
    });

    updateCartLink();

    if (voiceAssistantToggle) {
        voiceAssistantToggle.addEventListener('click', toggleVoiceAssistant);
    }
    if (colorBlindModeToggle) {
        colorBlindModeToggle.addEventListener('click', toggleColorBlindMode);
    }
    if (checkoutButton) {
        checkoutButton.addEventListener('click', () => {
            alert('Thank you for subscribing to our newsletter!');
            speak('Thank you for subscribing to our newsletter!');
        });
    }

});

recognition.onresult = (event) => {
    const currentIndex = event.resultIndex;
    const transcript = event.results[currentIndex][0].transcript;
    content.textContent = transcript;
    handleVoiceCommand(transcript.toLowerCase());
};

function toggleVoiceAssistant() {
    if (isListening) {
        content.textContent = "Voice assistant stopped.";
        recognition.stop();
        isListening = false;
        voiceAssistantToggle.textContent = "🎙️ Start Voice Assistant";
        speak("Voice assistant stopped.");
    } else {
        content.textContent = "Listening...";
        recognition.start();
        isListening = true;
        voiceAssistantToggle.textContent = "🛑 Stop Voice Assistant";
        speak("Voice assistant activated. How can I help you?");
    }
}

function handleVoiceCommand(command) {
    console.log('Received voice command:', command);

    const quantityMatch = command.match(/^10(\d+)$/);
    console.log('Quantity match result:', quantityMatch);

    if (quantityMatch && quantityMatch[1]) {
        console.log('Matched quantity string:', quantityMatch[1]);
        const quantity = parseInt(quantityMatch[1], 10);
        console.log('Parsed quantity:', quantity);

        if (!isNaN(quantity)) {
            console.log('Valid quantity recognized:', quantity);
            updateLastItemQuantity(quantity);
            speak(`Updating quantity to ${quantity}`);
            return;
        } else {
            console.log('Quantity parsed to NaN');
        }
    } else {
        console.log('No valid quantity match found');
    }

    const productIds = ['101', '201', '301', '401', '501', '601', '701', '801', '901', '1001', '1101', '1201', '1301', '1401', '1501'];
    for (const id of productIds) {
        if (command.includes(id.toLowerCase())) {
            console.log('Matched product ID:', id);
            addProductToCart(id);
            return;
        }
    }

    const checkboxIds = ['computer', 'mobile', 'tv', 'apple', 'samsung', 'asus'];
    for (const id of checkboxIds) {
        if (command.includes(id)) {
            const checkbox = document.getElementById(id);
            if (checkbox) {
                checkbox.checked = !checkbox.checked;
                const label = checkbox.nextSibling.textContent.trim();
                speak(`${checkbox.checked ? 'Checked' : 'Unchecked'} ${label}`);
                filterProducts();
                return;
            }
        }
    }

    if (command.includes('hello') || command.includes('hi')) {
        speak("Hello! How can I assist you with your shopping today?");
    } else if (command.includes('categories') || command.includes('all categories')) {
        navigateTo('1.html', "Opening All Categories page...");
    } else if (command.includes('products')) {
        navigateTo('3.html', "Opening Products page...");
    } else if (command.includes('sale') || command.includes('limited sale')) {
        navigateTo('4.html', "Opening Limited Sale page...");
    } else if (command.includes('best seller')) {
        navigateTo('5.html', "Opening Best Seller page...");
    } else if (command.includes('open cart')) {
        navigateTo('11.html', "Opening Cart page...");
    } else if (command.includes('check out')) {
        navigateTo('12.php', "Opening Checkout page...");
    } else if (command.includes('order')) {
        const orderButton = document.getElementById('placeOrderBtn');
        if (orderButton) {
            orderButton.click();
            speak("Placing your order now.");
        } else {
            speak("I'm sorry, but I couldn't find the order button. Please make sure you're on the checkout page.");
        }
    } else if (command.includes('shop now')) {
        navigateTo('3.html', "Opening Products page to start shopping...");
    } else if (command.includes('color blind mode')) {
        toggleColorBlindMode();
    } else {
        speak("I'm sorry, I didn't understand that command. How else can I assist you with your shopping?");
    }
}

function updateLastItemQuantity(quantity) {
    const cart = JSON.parse(localStorage.getItem('cart')) || {};
    const items = Object.keys(cart);
    if (items.length > 0) {
        const lastItem = items[items.length - 1];
        cart[lastItem].quantity = quantity;
        localStorage.setItem('cart', JSON.stringify(cart));
        updateCartLink();
        speak(`Quantity for ${lastItem} set to ${quantity}`);
        
        const quantityInputs = document.querySelectorAll('.quantity-input');
        if (quantityInputs.length > 0) {
            quantityInputs[quantityInputs.length - 1].value = quantity;
            renderCart(); 
        }
    } else {
        speak("There are no items in your cart to update.");
    }
}

function navigateTo(page, message) {
    speak(message);
    window.location.href = page;
}

function addProductToCart(id) {
    console.log('Attempting to add product to cart:', id);
    const productCard = document.querySelector(`[id="${id}"]`);
    if (productCard) {
        console.log('Found product card:', productCard);
        const addToCartButton = productCard.querySelector('button');
        if (addToCartButton) {
            console.log('Found add to cart button:', addToCartButton);
            addToCartButton.click();
            const productTitle = productCard.querySelector('.product-title').textContent;
            const productPrice = parseFloat(productCard.querySelector('.product-price').textContent.replace('$', ''));
            updateCart(productTitle, productPrice);
            speak(`Added ${productTitle} to your cart.`);
        } else {
            console.log('Add to cart button not found');
            speak(`Sorry, I couldn't find the add to cart button for product ${id}.`);
        }
    } else {
        console.log('Product card not found');
        speak(`Sorry, I couldn't find a product with ID ${id}.`);
    }
}

function filterProducts() {
    const selectedCategories = Array.from(document.querySelectorAll('.category-filter:checked')).map(el => el.value);
    const selectedBrands = Array.from(document.querySelectorAll('.brand-filter:checked')).map(el => el.value);

    document.querySelectorAll('.product-card').forEach(card => {
        const productCategory = card.getAttribute('data-category');
        const productBrand = card.getAttribute('data-brand');
        const isCategoryMatched = selectedCategories.length === 0 || selectedCategories.includes(productCategory);
        const isBrandMatched = selectedBrands.length === 0 || selectedBrands.includes(productBrand);
        card.style.display = (isCategoryMatched && isBrandMatched) ? 'block' : 'none';
    });
}

function updateCart(productTitle, productPrice) {
    const cart = JSON.parse(localStorage.getItem('cart')) || {};
    if (cart[productTitle]) {
        cart[productTitle].quantity += 1;
    } else {
        cart[productTitle] = {
            price: productPrice,
            quantity: 1,
        };
    }
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartLink();
}

function updateCartLink() {
    const cart = JSON.parse(localStorage.getItem('cart')) || {};
    let totalAmount = 0;
    for (const item in cart) {
        totalAmount += cart[item].price * cart[item].quantity;
    }
    const cartLink = document.getElementById('cartLink');
    if (cartLink) {
        cartLink.innerText = `Cart: $${totalAmount.toFixed(2)}`;
    }
}

function toggleColorBlindMode() {
    document.body.classList.toggle('color-blind-mode');
    if (document.body.classList.contains('color-blind-mode')) {
        colorBlindModeToggle.textContent = 'Normal Mode';
        speak("Color blind mode activated.");
    } else {
        colorBlindModeToggle.textContent = 'Color Blind Mode';
        speak("Color blind mode deactivated.");
    }
}

function speak(text) {
    const utterance = new SpeechSynthesisUtterance(text);
    utterance.rate = 1;
    utterance.volume = 1;
    utterance.pitch = 1;
    window.speechSynthesis.speak(utterance);
}

function renderCart() {
    const cartItemsContainer = document.getElementById('cartItems');
    if (!cartItemsContainer) return;

    const cart = JSON.parse(localStorage.getItem('cart')) || {};
    let totalAmount = 0;

    cartItemsContainer.innerHTML = '';

    for (const item in cart) {
        const { price, quantity } = cart[item];
        const itemElement = document.createElement('tr');
        itemElement.innerHTML = `
            <td>${item}</td>
            <td>$${price.toFixed(2)}</td>
            <td>
                <input type="number" value="${quantity}" min="1" class="quantity-input" style="text-align: center;">
            </td>
            <td>
                <button class="btn remove">Remove</button>
            </td>
        `;
        cartItemsContainer.appendChild(itemElement);
        totalAmount += price * quantity;

        itemElement.querySelector('.quantity-input').addEventListener('change', (event) => {
            const newQuantity = parseInt(event.target.value);
            updateQuantity(item, newQuantity - quantity);
        });
        itemElement.querySelector('.remove').addEventListener('click', () => removeItem(item));
    }

    const totalElement = document.getElementById('totalAmount');
    if (totalElement) {
        totalElement.innerText = `$${totalAmount.toFixed(2)}`;
    }
    updateCartLink();
}

function updateQuantity(item, change) {
    const cart = JSON.parse(localStorage.getItem('cart')) || {};
    if (cart[item]) {
        cart[item].quantity += change;
        if (cart[item].quantity <= 0) {
            delete cart[item];
        }
        localStorage.setItem('cart', JSON.stringify(cart));
        renderCart();
    }
}

function removeItem(item) {
    const cart = JSON.parse(localStorage.getItem('cart')) || {};
    delete cart[item];
    localStorage.setItem('cart', JSON.stringify(cart));
    renderCart();
}

document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('cartItems')) {
        renderCart();
    }
});