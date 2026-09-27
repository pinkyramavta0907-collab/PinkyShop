<?php

include "database.php";

$search = "";
$category = "";


/* GET SEARCH */

if (isset($_GET["search"])) {

    $search = trim($_GET["search"]);

}


/* GET CATEGORY */

if (isset($_GET["category"])) {

    $category = trim($_GET["category"]);

}


/* PRODUCT QUERY */

$sql = "SELECT * FROM products WHERE 1=1";

$params = [];
$types = "";


/* SEARCH FILTER */

if ($search != "") {

    $sql .= " AND (name LIKE ? OR category LIKE ?)";

    $search_value = "%" . $search . "%";

    $params[] = $search_value;
    $params[] = $search_value;

    $types .= "ss";

}


/* CATEGORY FILTER */

if ($category != "") {

    $sql .= " AND category = ?";

    $params[] = $category;

    $types .= "s";

}


/* NEWEST PRODUCTS FIRST */

$sql .= " ORDER BY id DESC";


/* PREPARE */

$stmt = $conn->prepare($sql);


/* BIND PARAMETERS */

if (count($params) > 0) {

    $stmt->bind_param(
        $types,
        ...$params
    );

}


/* EXECUTE */

$stmt->execute();

$result = $stmt->get_result();

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Shop | PinkyShop</title>

    <link
        rel="stylesheet"
        href="style.css"
    >

</head>


<body>


<!-- ================= NAVBAR ================= -->

<nav class="navbar">

    <div class="logo">

        Pinky<span>Shop</span>

    </div>


    <div class="nav-links">

        <a href="index.php">
            Home
        </a>

        <a href="products.php">
            Shop
        </a>

        <a href="my-orders.php">
            📦 Orders
        </a>

        <a href="cart.php">
            🛒 Cart
        </a>

        <a href="logout.php">
            Logout
        </a>

    </div>

</nav>



<!-- ================= PRODUCTS SECTION ================= -->

<section class="products">


    <p class="section-title">

        OUR COLLECTION

    </p>


    <h1>

        Discover Our Products

    </h1>



    <!-- ================= SEARCH ================= -->

    <form
        method="GET"
        class="search-box"
    >


        <!-- SEARCH INPUT -->

        <input
            type="text"
            name="search"
            placeholder="🔎 Search products..."
            value="<?php
                echo htmlspecialchars($search);
            ?>"
        >



        <!-- CATEGORY -->

        <select name="category">


            <option value="">

                All Categories

            </option>


            <option
                value="Fashion"
                <?php
                    if ($category == "Fashion") {
                        echo "selected";
                    }
                ?>
            >

                Fashion

            </option>


            <option
                value="Footwear"
                <?php
                    if ($category == "Footwear") {
                        echo "selected";
                    }
                ?>
            >

                Footwear

            </option>


            <option
                value="Electronics"
                <?php
                    if ($category == "Electronics") {
                        echo "selected";
                    }
                ?>
            >

                Electronics

            </option>


            <option
                value="Accessories"
                <?php
                    if ($category == "Accessories") {
                        echo "selected";
                    }
                ?>
            >

                Accessories

            </option>


        </select>



        <!-- SEARCH BUTTON -->

        <button type="submit">

            Search

        </button>



        <!-- CLEAR BUTTON -->

        <a
            href="products.php"
            class="clear-btn"
        >

            Clear

        </a>


    </form>



    <!-- ================= PRODUCT GRID ================= -->

    <div class="product-grid">


        <?php

        if ($result->num_rows > 0) {

        ?>


            <?php

            while ($product = $result->fetch_assoc()) {

            ?>


                <!-- ================= PRODUCT CARD ================= -->

                <div class="product-card">


                    <!-- PRODUCT IMAGE -->

                    <div class="product-image">

                        <img
                            src="images/<?php
                                echo htmlspecialchars(
                                    $product["image"]
                                );
                            ?>"
                            alt="<?php
                                echo htmlspecialchars(
                                    $product["name"]
                                );
                            ?>"
                        >

                    </div>



                    <!-- PRODUCT NAME -->

                    <h2>

                        <?php

                        echo htmlspecialchars(
                            $product["name"]
                        );

                        ?>

                    </h2>



                    <!-- CATEGORY -->

                    <p>

                        <?php

                        echo htmlspecialchars(
                            $product["category"]
                        );

                        ?>

                    </p>



                    <!-- PRICE -->

                    <h3>

                        ₹<?php

                        echo number_format(
                            $product["price"],
                            2
                        );

                        ?>

                    </h3>



                    <!-- ADD TO CART -->

                    <a
                        class="shop-btn"
                        href="add-to-cart.php?id=<?php
                            echo $product["id"];
                        ?>"
                    >

                        Add to Cart 🛒

                    </a>


                </div>


            <?php

            }

            ?>


        <?php

        } else {

        ?>


            <!-- ================= NO PRODUCTS ================= -->

            <div class="no-products">


                <div>

                    🔍

                </div>


                <h2>

                    No products found

                </h2>


                <p>

                    Try another search or category.

                </p>


            </div>


        <?php

        }

        ?>


    </div>


</section>



<!-- ================= FOOTER ================= -->

<footer>


    <h2>

        PinkyShop

    </h2>


    <p>

        Your style. Your choice.

    </p>


    <p class="copyright">

        © 2026 PinkyShop. All Rights Reserved.

    </p>


</footer>



</body>

</html>