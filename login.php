<?php

session_start();

include "database.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    $sql = $conn->prepare(
        "SELECT id, name, email, password
         FROM users
         WHERE email = ?"
    );

    $sql->bind_param("s", $email);

    $sql->execute();

    $result = $sql->get_result();

    if ($result->num_rows == 1) {

        $user = $result->fetch_assoc();

        if (password_verify($password, $user["password"])) {

            $_SESSION["user_id"] = $user["id"];
            $_SESSION["user_name"] = $user["name"];
            $_SESSION["user_email"] = $user["email"];

            header("Location: products.php");
            exit();

        } else {

            $message = "Incorrect password.";

        }

    } else {

        $message = "Account not found.";

    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login | PinkyShop</title>

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
            WELCOME BACK
        </p>

        <h1>Login</h1>


        <?php if ($message != "") { ?>

            <div class="message">

                <?php echo htmlspecialchars($message); ?>

            </div>

        <?php } ?>


        <form method="POST">

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
                placeholder="Enter your password"
                required
            >


            <button type="submit">
                Login
            </button>

        </form>


        <p class="auth-link">

            Don't have an account?

            <a href="register.php">
                Create Account
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