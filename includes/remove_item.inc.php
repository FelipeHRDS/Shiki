<?php

session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['actionSubmit'])) {

    $index = $_GET['actionSubmit'];

    if (isset($_SESSION['shopping_cart'][$index])) {

        unset($_SESSION['shopping_cart'][$index]);

        $_SESSION['shopping_cart'] = array_values(
            $_SESSION['shopping_cart']
        );
    }

    header('Content-Type: application/json');

    echo json_encode([
        "success" => true,
        "shopping_cart" => $_SESSION['shopping_cart']
    ]);

    exit();

}

header('Content-Type: application/json');

echo json_encode([
    "success" => false
]);