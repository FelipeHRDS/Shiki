<?php

session_start();

$data = json_decode(
    file_get_contents("php://input"),
    true
);

header('Content-Type: application/json');

if (is_array($data)) {

    $_SESSION['shopping_cart'] = $data;

    echo json_encode([
        "success" => true
    ]);

} else {

    echo json_encode([
        "success" => false
    ]);
}