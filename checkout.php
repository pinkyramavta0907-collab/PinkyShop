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
    products.name,
    products.price,
    cart.quantity
FROM cart
JOIN products
ON cart.product_id = products.id
WHERE cart.user_id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();

$total = 0;

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Checkout | PinkyShop</title>

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


<section class="checkout-section">

    <p class="section-title">
        CHECKOUT
    </p>

    <h1>
        Order Summary 📦
    </h1>


    <div class="checkout-box">

        <?php if ($result->num_rows > 0) { ?>

            <?php while ($item = $result->fetch_assoc()) { ?>

                <?php

                $subtotal =
                    $item["price"] * $item["quantity"];

                $total =
                    $total + $subtotal;

                ?>

                <div class="checkout-item">

                    <div>

                        <h3>
                            <?php
                            echo htmlspecialchars(
                                $item["name"]
                            );
                            ?>
                        </h3>

                        <p>
                            ₹<?php
                            echo number_format(
                                $item["price"],
                                2
                            );
                            ?>
                            ×
                            <?php
                            echo $item["quantity"];
                            ?>
                        </p>

                    </div>

                    <strong>
                        ₹<?php
                        echo number_format(
                            $subtotal,
                            2
                        );
                        ?>
                    </strong>

                </div>

            <?php } ?>


            <div class="checkout-total">

                <h2>
                    Total Amount:
                    ₹<?php
                    echo number_format(
                        $total,
                        2
                    );
                    ?>
                </h2>

            </div>


            <form
                action="place-order.php"
                method="POST"
            >

                <input
                    type="hidden"
                    name="total"
                    value="<?php echo $total; ?>"
                >

                <button
                    type="submit"
                    class="checkout-btn"
                >
                    Place Order 📦
                </button>

            </form>


        <?php } else { ?>

            <div class="empty-cart">

                <div class="empty-icon">
                    🛒
                </div>

                <h2>
                    Your cart is empty
                </h2>

                <p>
                    Please add products before checkout.
                </p>

                <a
                    href="products.php"
                    class="shop-btn"
                >
                    Continue Shopping →
                </a>

            </div>

        <?php } ?>

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