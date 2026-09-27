<?php

$conn = new mysqli(
    "sql313.infinityfree.com",
    "if0_42926082",
    "PinkyShop0907",
    "if0_42926082_pinkyshop",
    3306
);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

?>