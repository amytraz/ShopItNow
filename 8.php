<?php
include 'db.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root";  
$password = "";     
$dbname = "project";  

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $username = $_POST['username'];
    $newsletter = isset($_POST['newsletter']) ? 1 : 0;

    $stmt = $conn->prepare("INSERT INTO users (email, password, username, newsletter) VALUES (?, ?, ?, ?)");
    
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    if (!$stmt->bind_param("sssi", $email, $password, $username, $newsletter)) {
        die("Error binding parameters: " . $stmt->error);
    }

    if ($stmt->execute()) {
        echo "New account created successfully";
    } else {
        echo "Error executing statement: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>e-shop Registration</title>
    <link rel="stylesheet" href="8.css">
    <link rel="stylesheet" href="style.css">
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
                    <div>🛒 <a href="11.html" id="cartLink">Cart: $0.00</a></div>
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
    <main>
        <div class="container">
            <h2>Register</h2>
            <form class="register-form" action="8.php" method="POST">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="newsletter" style="display: inline;" class="a1">Subscribe to Newsletter</label>
                    <input type="checkbox" id="newsletter" style="margin-left: 15px; margin-top: 5px; width: 13px" name="newsletter">
                </div>
                <button type="submit" class="btn" id="cartBtn">Create Account</button>
                <p style="text-align: center; margin-top: 15px;">
                    Already had an account? <a href="9.php">Login here</a>
                </p>
            </form>
        </div>
    </main>

    <script>
        document.getElementById('cartBtn').addEventListener('click', function() {
            alert('Account created successfully...');
        });
    </script>
    <script src="script.js"></script>
</body>
</html>
