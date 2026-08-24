<?php
session_start();

$shoppingCartJSON = file_get_contents('php://input');
$shoppingCart = json_decode($shoppingCartJSON, true);

echo json_encode($_SESSION['shopping_cart']);
$_SESSION['shopping_cart'] = $shoppingCart;