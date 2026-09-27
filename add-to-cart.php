<?php

session_start();

include "database.php";

/* Check Login */

if (!isset($_SESSION["user_id"])) {

    header("Location: login.php");
    exit();

}

$user_id = $_SESSION["user_id"];

$product_id = $_GET["id"];


/* Check Product */

$sql = $conn->prepare(
    "SELECT * FROM products WHERE id = ?"
);

$sql->bind_param("i", $product_id);
$sql->execute();

$result = $sql->get_result();

if ($result->num_rows == 0) {

    die("Product not found.");

}


/* Check Cart */

$check = $conn->prepare(
    "SELECT id FROM cart
     WHERE user_id = ? AND product_id = ?"
);

$check->bind_param(
    "ii",
    $user_id,
    $product_id
);

$check->execute();

$cart_result = $check->get_result();


/* Product Already in Cart */

if ($cart_result->num_rows > 0) {

    $update = $conn->prepare(
        "UPDATE cart
         SET quantity = quantity + 1
         WHERE user_id = ? AND product_id = ?"
    );

    $update->bind_param(
        "ii",
        $user_id,
        $product_id
    );

    $update->execute();

}


/* New Product */

else {

    $insert = $conn->prepare(
        "INSERT INTO cart
         (user_id, product_id, quantity)
         VALUES (?, ?, 1)"
    );

    $insert->bind_param(
        "ii",
        $user_id,
        $product_id
    );

    $insert->execute();

}


/* Go to Cart */

header("Location: cart.php");

exit();

?>