<?php

session_start();

include "database.php";

if (!isset($_SESSION["user_id"])) {

    header("Location: login.php");
    exit();

}

$user_id = $_SESSION["user_id"];

$sql = "
SELECT
    id,
    total_amount,
    order_date,
    status
FROM orders
WHERE user_id = ?
ORDER BY order_date DESC
";

$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $user_id);

$stmt->execute();

$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>My Orders | PinkyShop</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

<nav class="navbar">

    <div class="logo">
        Pinky<span>Shop</span>
    </div>

    <div class="nav-links">

        <a href="index.php">Home</a>

        <a href="products.php">Shop</a>

        <a href="cart.php">🛒 Cart</a>

        <a href="my-orders.php">📦 My Orders</a>

        <a href="logout.php">Logout</a>

    </div>

</nav>


<section class="orders-section">

    <p class="section-title">
        ORDER HISTORY
    </p>

    <h1>
        My Orders 📦
    </h1>


    <?php if ($result->num_rows > 0) { ?>

        <div class="orders-container">

            <?php while ($order = $result->fetch_assoc()) { ?>

                <div class="order-card">

                    <div>

                        <h2>
                            Order #<?php echo $order["id"]; ?>
                        </h2>

                        <p>
                            Order Date:
                            <?php
                            echo date(
                                "d M Y, h:i A",
                                strtotime($order["order_date"])
                            );
                            ?>
                        </p>

                    </div>


                    <div class="order-info">

                        <h3>
                            ₹<?php
                            echo number_format(
                                $order["total_amount"],
                                2
                            );
                            ?>
                        </h3>

                        <span class="status">
                            <?php
                            echo htmlspecialchars(
                                $order["status"]
                            );
                            ?>
                        </span>

                    </div>

                </div>

            <?php } ?>

        </div>

    <?php } else { ?>

        <div class="empty-cart">

            <div class="empty-icon">
                📦
            </div>

            <h2>
                No Orders Yet
            </h2>

            <p>
                Your placed orders will appear here.
            </p>

            <a
                href="products.php"
                class="shop-btn"
            >
                Start Shopping →
            </a>

        </div>

    <?php } ?>

</section>


<footer>

    <h2>PinkyShop</h2>

    <p>
        Your style. Your choice.
    </p>

    <p class="copyright">
        © 2026 PinkyShop. All Rights Reserved.
    </p>

</footer>

</body>

</html>