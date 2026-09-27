<?php
include "database.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>PinkyShop | Online Shopping</title>

    <link rel="stylesheet" href="style.css">
</head>

<body>

    <!-- Navigation -->
    <nav class="navbar">

        <div class="logo">
            Pinky<span>Shop</span>
        </div>

        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="products.php">Shop</a>
            <a href="login.php">Login</a>
            <a href="cart.php">🛒 Cart</a>
        </div>

    </nav>


    <!-- Hero Section -->

    <section class="hero">

        <div class="hero-content">

            <p class="small-title">
                ✨ WELCOME TO PINKYSHOP
            </p>

            <h1>
                Find Your
                <span>Perfect Style</span>
            </h1>

            <p class="hero-text">
                Discover fashion, footwear, accessories,
                electronics and more — all in one place.
            </p>

            <a href="products.php" class="shop-btn">
                Shop Now →
            </a>

        </div>

        <div class="hero-card">

            <div class="shopping-icon">
                🛍️
            </div>

            <h2>New Collection</h2>

            <p>Fresh styles are waiting for you!</p>

        </div>

    </section>


    <!-- Categories -->

    <section class="categories">

        <p class="section-title">
            EXPLORE
        </p>

        <h2>Shop By Category</h2>

        <div class="category-container">

            <div class="category-card">
                <div class="category-icon">👗</div>
                <h3>Fashion</h3>
                <p>Latest styles & outfits</p>
            </div>

            <div class="category-card">
                <div class="category-icon">👟</div>
                <h3>Footwear</h3>
                <p>Comfortable & stylish</p>
            </div>

            <div class="category-card">
                <div class="category-icon">⌚</div>
                <h3>Electronics</h3>
                <p>Smart gadgets & more</p>
            </div>

            <div class="category-card">
                <div class="category-icon">👜</div>
                <h3>Accessories</h3>
                <p>Complete your look</p>
            </div>

        </div>

    </section>


    <!-- Features -->

    <section class="features">

        <div>
            <h3>🚚 Fast Delivery</h3>
            <p>Quick & reliable delivery</p>
        </div>

        <div>
            <h3>🔒 Secure Shopping</h3>
            <p>Safe & secure experience</p>
        </div>

        <div>
            <h3>💖 Quality Products</h3>
            <p>Products selected with care</p>
        </div>

    </section>


    <!-- Footer -->

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