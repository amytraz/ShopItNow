<?php
include 'db.php';

session_start();

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
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, email, password FROM users WHERE email = ?");
    
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            
            header("Location: 10.php");
            exit();
        } else {
            $error = "Invalid email or password";
        }
    } else {
        $error = "Invalid email or password";
    }

    $stmt->close();
}

$conn->close();

if (isset($error)) {
    echo $error;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>e-shop Login</title>
    <link rel="stylesheet" href="9.css">
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
    <main style="margin-top: 20px">
        <div class="container">
            <h2>Login</h2>
            <form class="login-form" action="9.php" method="POST" style="margin-top: 30px">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" class="btn" id="loginBtn">Login</button>
                <p style="text-align: center; margin-top: 15px;">
                    Don't have an account? <a href="8.php">Register here</a>
                </p>
            </form>
        </div>
    </main>
    <script src="script.js"></script>
</body>
</html>
