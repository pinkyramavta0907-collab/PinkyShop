<?php

include "database.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    if ($name == "" || $email == "" || $password == "") {

        $message = "Please fill all fields.";

    } else {

        // Check if email already exists
        $check = $conn->prepare(
            "SELECT id FROM users WHERE email = ?"
        );

        $check->bind_param("s", $email);
        $check->execute();

        $result = $check->get_result();

        if ($result->num_rows > 0) {

            $message = "Email already registered.";

        } else {

            // Secure password
            $hashed_password = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            $sql = $conn->prepare(
                "INSERT INTO users (name, email, password)
                 VALUES (?, ?, ?)"
            );

            $sql->bind_param(
                "sss",
                $name,
                $email,
                $hashed_password
            );

            if ($sql->execute()) {

                $message = "Registration successful! You can login now.";

            } else {

                $message = "Registration failed.";

            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Register | PinkyShop</title>

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

        <a href="login.php">Login</a>

        <a href="cart.php">🛒 Cart</a>

    </div>

</nav>


<section class="auth-section">

    <div class="auth-box">

        <p class="section-title">
            JOIN PINKYSHOP
        </p>

        <h1>Create Account</h1>

        <?php if ($message != "") { ?>

            <div class="message">
                <?php echo htmlspecialchars($message); ?>
            </div>

        <?php } ?>


        <form method="POST">

            <label>Full Name</label>

            <input
                type="text"
                name="name"
                placeholder="Enter your name"
                required
            >


            <label>Email</label>

            <input
                type="email"
                name="email"
                placeholder="Enter your email"
                required
            >


            <label>Password</label>

            <input
                type="password"
                name="password"
                placeholder="Create a password"
                required
            >


            <button type="submit">
                Create Account
            </button>

        </form>


        <p class="auth-link">

            Already have an account?

            <a href="login.php">
                Login here
            </a>

        </p>

    </div>

</section>


<footer>

    <h2>PinkyShop</h2>

    <p>Your style. Your choice.</p>

    <p class="copyright">
        © 2026 PinkyShop. All Rights Reserved.
    </p>

</footer>

</body>

</html>