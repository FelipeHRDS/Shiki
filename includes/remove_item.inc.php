<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['actionSubmit'])) {
    $index = $_GET['actionSubmit'];

    if (isset($_SESSION['shopping_cart'][$index])) {
        unset($_SESSION['shopping_cart'][$index]);

        $_SESSION['shopping_cart'] = array_values($_SESSION['shopping_cart']);
    }

    header("Location: ../shopping-cart.php");
    exit();
} else {
    header("Location: ../shopping-cart.php");
    exit();
}
