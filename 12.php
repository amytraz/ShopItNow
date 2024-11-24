<?php
include 'db.php';

$servername = "localhost";  
$username = "root";        
$password = "";             
$dbname = "project";     

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cartItems = json_decode($_POST['cartItems'], true);
    $totalAmount = $_POST['totalAmount'];

    foreach ($cartItems as $item) {
        $productName = $item['name'];
        $price = $item['price'];
        $quantity = $item['quantity'];
        $total = $price * $quantity;

        $sql = "INSERT INTO orders (product_name, price, quantity, total) VALUES ('$productName', '$price', '$quantity', '$total')";
        $conn->query($sql);
    }

    echo "<script>alert('Your order has been placed!');</script>";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>e-shop - Checkout</title>
    <link rel="stylesheet" href="12.css">
    <link rel="stylesheet" href="style.css">
    <style>
        .checkout-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .checkout-table th, .checkout-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        .checkout-table th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 10px;">
                <div class="logo">e-shop.</div>
                <div class="search-bar">
                    <input type="text" placeholder="Search Products...">
                    <button>🔍</button>
                </div>
                <div class="user-actions">
                    <div>🛒 Cart: <span id="cartTotal">$0.00</span></div>
                    <div>👤 <a href="8.php">Admin Account</a></div>
                    <button id="colorBlindModeToggle">Color Blind Mode</button>
                    <button id="voiceAssistantToggle" style="margin-left: 15px;">🎙️ Voice Assistant</button>
                </div>
            </div>
        </div>
    </header>
    <header>
        <div class="container-1">
            <h1>e-shop.</h1>
        </div>
    </header>
    <nav>
        <div class="container-1">
            <ul>
                <li><a href="1.html">All Categories</a></li>
                <li><a href="3.html">Products</a></li>
                <li><a href="4.html">LIMITED SALE</a></li>
                <li><a href="5.html">Best Seller</a></li>
            </ul>
        </div>
    </nav>
    <div class="container" style="margin-top: 30px">
        <h2>Checkout</h2>
        <div class="checkout-steps" style="margin-top: 30px">
            <div class="step active">Information</div>
        </div>
        <div class="checkout-content">
            <div class="billing-details">
                <h3>Billing Details</h3>
                <form id="checkoutForm" method="POST" action="12.php">
                    <input type="hidden" name="cartItems" id="cartItemsInput">
                    <input type="hidden" name="totalAmount" id="totalAmountInput">
                    <table class="checkout-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody id="checkoutItems"></tbody>
                    </table>
                </form>
            </div>
            <div class="order-summary">
                <h3>Order Summary</h3>
                <hr>
                <div class="total">
                    <h3>Total: <span id="totalAmount">$0.00</span></h3>
                </div>
                <button class="btn" id="placeOrderBtn">Order Now</button>
            </div>
        </div>
    </div>

    <script>
        function renderCheckout() {
            const checkoutItemsContainer = document.getElementById('checkoutItems');
            const cart = JSON.parse(localStorage.getItem('cart')) || {};
            let totalAmount = 0;
            const cartItemsArray = [];

            checkoutItemsContainer.innerHTML = ''; 

            for (const item in cart) {
                const { price, quantity } = cart[item];
                const total = price * quantity;
                const itemElement = document.createElement('tr');
                itemElement.innerHTML = `
                    <td>${item}</td>
                    <td>$${price.toFixed(2)}</td>
                    <td>${quantity}</td>
                    <td>$${total.toFixed(2)}</td>
                `;
                checkoutItemsContainer.appendChild(itemElement);
                totalAmount += total;

                cartItemsArray.push({
                    name: item,
                    price: price,
                    quantity: quantity,
                    total: total
                });
            }

            document.getElementById('totalAmount').innerText = `$${totalAmount.toFixed(2)}`;
            document.getElementById('cartTotal').innerText = `$${totalAmount.toFixed(2)}`;

            document.getElementById('cartItemsInput').value = JSON.stringify(cartItemsArray);
            document.getElementById('totalAmountInput').value = totalAmount.toFixed(2);
        }

        document.getElementById('placeOrderBtn').addEventListener('click', function() {
            document.getElementById('checkoutForm').submit(); 
            localStorage.clear(); 
        });

        document.addEventListener('DOMContentLoaded', renderCheckout);

        speak("Welcome to the Checkout page! Here, you'll enter your billing details and review your order summary. Ensure all information is correct before finalizing your purchase. With secure processing and transparent pricing, we aim to make your shopping experience seamless and stress-free. Order now and enjoy your new electronics!");
    </script>
    <script src="script.js"></script>
</body>
</html>