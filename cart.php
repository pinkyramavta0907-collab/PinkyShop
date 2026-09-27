<?php

session_start();

include "database.php";

if (!isset($_SESSION["user_id"])) {

    header("Location: login.php");
    exit();

}

$user_id = $_SESSION["user_id"];


/* UPDATE QUANTITY */

if (isset($_POST["update_cart"])) {

    $cart_id = intval($_POST["cart_id"]);
    $quantity = intval($_POST["quantity"]);

    if ($quantity <= 0) {

        $delete = $conn->prepare(
            "DELETE FROM cart
             WHERE id = ? AND user_id = ?"
        );

        $delete->bind_param(
            "ii",
            $cart_id,
            $user_id
        );

        $delete->execute();

    } else {

        $update = $conn->prepare(
            "UPDATE cart
             SET quantity = ?
             WHERE id = ? AND user_id = ?"
        );

        $update->bind_param(
            "iii",
            $quantity,
            $cart_id,
            $user_id
        );

        $update->execute();

    }

    header("Location: cart.php");
    exit();

}


/* REMOVE PRODUCT */

if (isset($_POST["remove_item"])) {

    $cart_id = intval($_POST["cart_id"]);

    $delete = $conn->prepare(
        "DELETE FROM cart
         WHERE id = ? AND user_id = ?"
    );

    $delete->bind_param(
        "ii",
        $cart_id,
        $user_id
    );

    $delete->execute();

    header("Location: cart.php");
    exit();

}


/* GET CART */

$sql = "
SELECT 
    cart.id AS cart_id,
    products.name,
    products.price,
    products.image,
    cart.quantity
FROM cart
JOIN products
ON cart.product_id = products.id
WHERE cart.user_id = ?
";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "i",
    $user_id
);

$stmt->execute();

$result = $stmt->get_result();

$total = 0;

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Cart | PinkyShop</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>


<!-- NAVBAR -->

<nav class="navbar">

    <div class="logo">
        Pinky<span>Shop</span>
    </div>

    <div class="nav-links">

        <a href="index.php">Home</a>

        <a href="products.php">Shop</a>

        <a href="my-orders.php">📦 Orders</a>

        <a href="cart.php">🛒 Cart</a>

        <a href="logout.php">Logout</a>

    </div>

</nav>


<!-- CART -->

<section class="cart-section">

    <p class="section-title">
        YOUR SHOPPING CART
    </p>

    <h1>
        My Cart 🛒
    </h1>


    <?php if ($result->num_rows > 0) { ?>

        <div class="cart-container">


            <?php while ($item = $result->fetch_assoc()) { ?>

                <?php

                $subtotal =
                    $item["price"] * $item["quantity"];

                $total =
                    $total + $subtotal;

                ?>


                <div class="cart-item">


                    <!-- PRODUCT IMAGE -->

                    <div class="cart-image">

                        🛍️

                    </div>


                    <!-- PRODUCT DETAILS -->

                    <div class="cart-details">

                        <h2>

                            <?php

                            echo htmlspecialchars(
                                $item["name"]
                            );

                            ?>

                        </h2>


                        <p>

                            Price:
                            ₹<?php

                            echo number_format(
                                $item["price"],
                                2
                            );

                            ?>

                        </p>


                        <!-- QUANTITY -->

                        <div class="quantity-box">

                            <form method="POST">

                                <input
                                    type="hidden"
                                    name="cart_id"
                                    value="<?php
                                    echo $item["cart_id"];
                                    ?>"
                                >

                                <button
                                    type="submit"
                                    name="update_cart"
                                    value="1"
                                    class="quantity-btn"
                                    onclick="this.form.quantity.value--"
                                >
                                    −
                                </button>


                                <input
                                    type="number"
                                    name="quantity"
                                    value="<?php
                                    echo $item["quantity"];
                                    ?>"
                                    min="1"
                                    class="quantity-input"
                                >


                                <button
                                    type="submit"
                                    name="update_cart"
                                    value="1"
                                    class="quantity-btn"
                                    onclick="this.form.quantity.value++"
                                >
                                    +
                                </button>

                            </form>

                        </div>

                    </div>


                    <!-- PRICE -->

                    <div class="cart-price">

                        <h3>

                            ₹<?php

                            echo number_format(
                                $subtotal,
                                2
                            );

                            ?>

                        </h3>


                        <!-- REMOVE -->

                        <form method="POST">

                            <input
                                type="hidden"
                                name="cart_id"
                                value="<?php
                                echo $item["cart_id"];
                                ?>"
                            >

                            <button
                                type="submit"
                                name="remove_item"
                                class="remove-btn"
                            >
                                🗑️ Remove
                            </button>

                        </form>

                    </div>


                </div>


            <?php } ?>


            <!-- TOTAL -->

            <div class="cart-total">

                <h2>

                    Total:
                    ₹<?php

                    echo number_format(
                        $total,
                        2
                    );

                    ?>

                </h2>


                <a
                    href="products.php"
                    class="continue-btn"
                >
                    ← Continue Shopping
                </a>


                <a
                    href="checkout.php"
                    class="checkout-btn"
                >
                    Proceed to Checkout →
                </a>

            </div>


        </div>


    <?php } else { ?>


        <!-- EMPTY CART -->

        <div class="empty-cart">

            <div class="empty-icon">
                🛒
            </div>

            <h2>
                Your cart is empty
            </h2>

            <p>
                Add some products to your cart!
            </p>

            <a
                href="products.php"
                class="shop-btn"
            >
                Continue Shopping →
            </a>

        </div>


    <?php } ?>


</section>


<!-- FOOTER -->

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