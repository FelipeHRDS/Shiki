<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $p_code = $_POST["URLCodeParameter"] ?? '';
    $p_qtty = $_POST["p_qtty"] ?? '';
    $p_price = $_POST["p_price"] ?? '';
    $p_name = $_POST["p_name"] ?? '';
    $p_systemcode = $_POST["p_systemcode"] ?? '';
    $p_unit = $_POST["p_unit"] ?? '';
    $p_total = number_format(floatval($p_qtty) * floatval($p_price), 2, '.', '');

    require_once 'functions.inc.php';

    if (invalidProductQuantity($p_qtty)) {
        header("location: ../signup.php?error=emptyinput");
        exit();
    }

    if (!isset($_SESSION['shopping_cart'])) {
        $_SESSION['shopping_cart'] = [];
    }
    
    function &findProduct(&$cart, $code) {
        foreach($cart as &$product) {
            if ($product['codigo'] === $code) {
                return $product;
            }
        }
        return null;
    }
    
    $product = &findProduct($_SESSION['shopping_cart'], $p_code);
    
    if ($product !== null) {
        $product['quantidade'] += floatval($p_qtty);
        $product['preco total'] = number_format($product['quantidade'] * $product['preco unitario'], 2, '.', '');
    } else {
        $_SESSION['shopping_cart'][] = [
                'codigosistema' => $p_systemcode,
                'codigo' => $p_code,
                'nome' => $p_name,
                'quantidade' => $p_qtty,
                "unidade" => $p_unit,
                'preco unitario' => $p_price,
                'preco total' => $p_total
            ];
    }

    $productPurchaseInfo = [
        "codigosistema" => $p_systemcode,
        "codigo" => $p_code,
        "nome" => $p_name,
        "quantidade" => $p_qtty,
        "unidade" => $p_unit,
        "preco unitario" => $p_price,
        "preco total" => number_format((float) $p_total, 2, '.', '')
    ];

    header("location: ../meus-produtos.php?code=$p_code&status=itemadded");
    exit();
    
} else {
    header("location: ../product-details.php");
    exit();
}