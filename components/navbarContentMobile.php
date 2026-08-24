<?php

function buildNavBarMobileContent() {
    if (!isset($_SESSION["useruid"])) {
        return renderGuestMobileNavBar();
    }

    $shoppingCartCount = isset($_SESSION['shopping_cart']) ? count($_SESSION['shopping_cart']) : 0;

    return renderUserMobileNavBar($shoppingCartCount);
}

function renderGuestMobileNavBar() {
    return <<<HTML
        <a href="../catalogo/">Produtos</a>
        <a href="./login.php">Login</a>
    HTML;
}

function renderUserMobileNavBar($shoppingCartCount) {
    return <<<HTML
        <a href="./meus-produtos.php">Produtos</a>
        <a href="./shopping-cart.php">Meu Carrinho</a>
        <div id="product-counter">$shoppingCartCount</div>
    HTML;
}

$navbarContentMobile = buildNavBarMobileContent();