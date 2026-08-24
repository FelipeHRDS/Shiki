<?php

function buildNavBarContent() {
    if (!isset($_SESSION['useruid'])) {
        return renderGuestNavBar();
    }

    $userClass = $_SESSION['usersClass'] ?? '';
    $shoppingCartCount = isset($_SESSION['shopping_cart']) ? count($_SESSION['shopping_cart']) : 0;
    $logoutLink = $shoppingCartCount > 0 ? 'javascript:showCartAlert();' : './includes/logout.inc.php';

    if ($userClass === 'supervisor') {
        return renderSupervisorNavBar();
    }

    return renderUserNavBar($shoppingCartCount, $logoutLink);
}

function renderGuestNavBar() {
    return <<<HTML
        <a class="nav-bar__menu__link__myshiki" href="../catalogo/">Produtos</a>
        <a class="nav-bar__menu__link__myshiki" href="./login.php">Login</a>
    HTML;
}

function renderSupervisorNavBar() {
    return <<<HTML
        <a class="nav-bar__menu__link__myshiki" href="./catalogos.php">Catálogos</a>
        <a class="nav-bar__menu__link__myshiki" href="./historico-meta--admin.php">Histórico</a>
        <a class="nav-bar__menu__link__myshiki" href="./includes/logout.inc.php">Logout</a>
    HTML;
}

function renderUserNavBar($shoppingCartCount, $logoutLink) {
    return <<<HTML
        <a class="nav-bar__menu__link__myshiki" href="./meus-produtos.php">Produtos</a>
        <ul style="list-style: none;">
            <li style="position: relative;">
                <div id="product-counter">$shoppingCartCount</div>
            </li>
            <li>
                <a style="position: relative;" class="nav-bar__menu__link__myshiki myprofile-emp" href="./shopping-cart.php">Meu Carrinho</a>
            </li>
        </ul>
        <a class="nav-bar__menu__link__myshiki" href="$logoutLink">Logout</a>
    HTML;
}

$navbarContent = buildNavBarContent();