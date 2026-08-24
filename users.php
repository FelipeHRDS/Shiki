<?php

include_once './includes/dbhc.inc.php';
include './includes/auth.inc.php';
include './includes/Authentication.php';
include_once './includes/Filter.php';

session_start();

include_once './includes/session_variables.inc.php';

$auth = new Authentication($privilegedClasses);
$auth->redirectIfNotAuthorized($userClass, './index.php');

$users_data = Filter::find_customers($_SESSION['vendedor'], "usersName,usersUid,usersCnpj,usersClass,usersUF,usersCity,vendedor");

?>

<html>
    <head>
        <title>Usuários | MyShiki</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
        <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
        <link rel="apple-touch-icon" sizes="180x180" href="./favicon/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="./favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="./favicon/favicon-16x16.png">
        <link rel="manifest" href="./favicon/site.webmanifest">
        <link rel="stylesheet" href="./style-myshiki.css">
        <link rel="stylesheet" href="../styles/styles.css">
        <link rel="stylesheet" href="../styles/responsivo.css">
        <link rel="stylesheet" href="./style-myshiki-responsivo.css">
    </head>
    <body>
        <header style="display: flex;">
            <div class="nav-bar__mobile__hamburger-menu">
                <a aria-label="Abrir Menu" href="javascript:void(0)" class="hamburger-icon">
                    <i class="fa fa-bars" style="font-size: x-large;"></i>
                </a>
            </div>
            <div class="nav-bar__logo">
                <a href="./index.php">
                    <img class="nav-bar__logo__image" src="../media/shiki-logo.jpg" alt="Logo Shiki">
                </a>
            </div>
            <div class="nav-bar__mobile__hamburger-menu__links __hide">
                <?php
                if (isset($_SESSION["useruid"])) {
                echo "<a href=\"meus-produtos.php\">Meus Produtos</a>";
                echo "<a href=\"profile.php\">Perfil</a>";
                echo "<a href=\"./includes/logout.inc.php\">Logout</a>";
                } else {
                echo "<a href=\"../catalogo/\">Produtos</a>";
                echo "<a href=\"signup.php\">Cadastro</a>";
                echo "<a href=\"index.php\">Login</a>";
                }
                ?>
            </div>
            <div class="nav-bar__menu">
                <a class="nav-bar__menu__link__myshiki" href="./index.php">
                    Página Principal
                </a>
                <?php
                    if (isset($_SESSION["useruid"])) {
                    echo "<a class=\"nav-bar__menu__link__myshiki\" href=\"./meus-produtos.php\">Produtos</a>";
                    echo "<a class=\"nav-bar__menu__link__myshiki myprofile-emp\" href=\"shopping-cart.php\">Meu Carrinho</a>";
                            if (isset($_SESSION['shopping_cart'])) {
                        echo "<div id='product-counter'>" . count($_SESSION['shopping_cart']) . "</div>";
                    }
                    
                    if (empty($_SESSION['shopping_cart'])) {
                    echo "<a class=\"nav-bar__menu__link__myshiki\" href=\"./includes/logout.inc.php\">Logout</a>";
                        } else {
                            echo "<a class=\"nav-bar__menu__link__myshiki\" href=\"javascript:showCartAlert();\">Logout</a>";
                        }
                        
                    } else {
                    echo "<a class=\"nav-bar__menu__link__myshiki\" href=\"../catalogo/\">Produtos</a>";
                    echo "<a class=\"nav-bar__menu__link__myshiki\" href=\"./signup.php\">Cadastro</a>";
                    echo "<a class=\"nav-bar__menu__link__myshiki\" href=\"./index.php\">Login</a>";
                    }
                ?>
            </div>
        </header>
        <?php
    function html_table($data = []){
    $rows = [];
    foreach ($data as $row) {
        $cells = [];
        foreach ($row as $cell) {
            $cells[] = "<td>{$cell}</td>";
        }
        $rows[] = "<tr>" . implode('', $cells) . "</tr>";
    }
    return "<div style=\"display:flex; align-items: center; justify-content: center;\"><table id=\"userTable\" class=\"hover\" style=\"width: 100%;\">
    <thead>
        <th>Nome Empresarial</th>
        <th>Nome de Usuário</th>
        <th>CNPJ</th>
        <th>Franquia</th>
        <th>UF</th>
        <th>Cidade</th>
        <th>Vendedor</th>
    </thead>
    <tbody>" . implode('', $rows) . "</tbody></table></div>";
}

echo html_table($users_data);
        ?>
        <script src="./includes/datatables-options.js"></script>
    </body>
</html>