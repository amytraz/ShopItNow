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

$sql = "SELECT * FROM orders";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>e-shop User Dashboard</title>
    <link rel="stylesheet" href="10.css">
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
                    <div>🛒 Cart: $0.00</div>
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
    <main style="margin-top: 30px">
        <div class="container">
            <div class="dashboard">
                <div class="recent-orders">
                    <h3>All Orders</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Product Name</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $row['id'] . "</td>";
                                    echo "<td>" . $row['product_name'] . "</td>";
                                    echo "<td>$" . number_format($row['price'], 2) . "</td>";
                                    echo "<td>" . $row['quantity'] . "</td>";
                                    echo "<td>$" . number_format($row['total'], 2) . "</td>";
                                    echo "<td>" . $row['order_date'] . "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='6'>No orders found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
    <script src="script.js"></script>
</body>
</html>
