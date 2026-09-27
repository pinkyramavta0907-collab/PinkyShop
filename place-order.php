<?php

session_start();

include "database.php";

if (!isset($_SESSION["user_id"])) {

    header("Location: login.php");
    exit();

}

$user_id = $_SESSION["user_id"];


/* Get Cart */

$sql = "
SELECT
    cart.product_id,
    cart.quantity,
    products.price
FROM cart
JOIN products
ON cart.product_id = products.id
WHERE cart.user_id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();


if ($result->num_rows == 0) {

    die("Your cart is empty.");

}


/* Calculate Total */

$total = 0;

$items = [];

while ($item = $result->fetch_assoc()) {

    $subtotal =
        $item["price"] * $item["quantity"];

    $total += $subtotal;

    $items[] = $item;

}


/* Create Order */

$order = $conn->prepare(
    "INSERT INTO orders
    (user_id, total_amount, status)
    VALUES (?, ?, 'Pending')"
);

$order->bind_param(
    "id",
    $user_id,
    $total
);

$order->execute();

$order_id = $conn->insert_id;


/* Save Order Items */

$order_item = $conn->prepare(
    "INSERT INTO order_items
    (order_id, product_id, quantity, price)
    VALUES (?, ?, ?, ?)"
);

foreach ($items as $item) {

    $order_item->bind_param(
        "iiid",
        $order_id,
        $item["product_id"],
        $item["quantity"],
        $item["price"]
    );

    $order_item->execute();

}


/* Clear Cart */

$clear = $conn->prepare(
    "DELETE FROM cart WHERE user_id = ?"
);

$clear->bind_param("i", $user_id);

$clear->execute();


/* Success Page */

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Order Successful | PinkyShop</title>

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

        <a href="logout.php">Logout</a>

    </div>

</nav>


<section class="success-section">

    <div class="success-box">

        <div class="success-icon">
            🎉
        </div>

        <h1>
            Order Placed Successfully!
        </h1>

        <p>
            Thank you for shopping with PinkyShop.
        </p>

        <p class="order-number">
            Order ID:
            <strong>#<?php echo $order_id; ?></strong>
        </p>

        <p class="order-total">
            Total Amount:
            <strong>
                ₹<?php echo number_format($total, 2); ?>
            </strong>
        </p>

        <a
            href="products.php"
            class="shop-btn"
        >
            Continue Shopping →
        </a>

    </div>

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