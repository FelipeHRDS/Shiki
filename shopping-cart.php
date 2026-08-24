<?php

session_start();
include './includes/Formatter.php';
include_once './includes/session_variables.inc.php';
include_once './includes/auth.inc.php';
include_once './includes/Authentication.php';

if (!isset($userUid)) {
   header("location: ./login.php");
}
if ($userType == 'rep' && !isset($_SESSION['userReplacementName'])) {
    header("location: ./escolher-cliente.php");
}

include_once './components/navbarContent.php';
include_once './components/navbarContentMobile.php';
include_once './components/shoppingCart.php';

$shopping_cart = $_SESSION['shopping_cart'] ?? [];
$preco_total_pedido = 0;
foreach ($shopping_cart as $item) {
    $preco_total_pedido += floatval($item['preco total']);
}

include_once "./includes/functions.inc.php";

$formatted_cnpj = Formatter::formatCnpj($userCnpj);

if (isset($_SESSION['userReplacementCnpj'])) {
    $formattedCnpj = Formatter::formatCnpj($_SESSION['userReplacementCnpj']);
}

$auth = new Authentication($noLowerLimitClasses);

//adicionar condição igual a "restki" para classe matsuri com R$690
if (isset($userClass)) {
    if ($auth->allowNoLowerLimit($userClass)) {
        define("MINIMUM_VALUE", "100");
    }

    switch ($userClass) {
        case ("restki"):
            define("MINIMUM_VALUE", "1200");
            break;
        case ("meimei"):
            define("MINIMUM_VALUE", "500");
            break;
        default:
            define("MINIMUM_VALUE", "900");
    }

    /*if ($userClass === "restki") {
        define("MINIMUM_VALUE", "1200");
    } else if ($auth->allowNoLowerLimit($userClass)) {
        define("MINIMUM_VALUE", "100");
    } else {
        define("MINIMUM_VALUE", "900");
    }*/

}


?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Meu Carrinho | MyShiki</title>
        <link rel="stylesheet" href="./style-myshiki.css">
        <link rel="stylesheet" href="../styles/styles.css">
        <link rel="stylesheet" href="../styles/responsivo.css">
        <link rel="stylesheet" href="./style-myshiki-responsivo.css">
        <link rel="stylesheet" href="../styles/nav-bar.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="apple-touch-icon" sizes="180x180" href="./favicon/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="./favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="./favicon/favicon-16x16.png">
        <link rel="manifest" href="./favicon/site.webmanifest">
        <style>
            body {
                overflow: visible;
            }
            
            main {
                display: flex;
                flex-flow: column nowrap;
            }

            table {
                width: 100%;
                page-break-inside: avoid;
                border-bottom: 1px solid black;
                border-collapse: collapse;
                margin-bottom: 1.5rem;
                margin-top: 0;
            }
            
            th, td {
                border-bottom: 1px solid black;
                padding: 8px 16px;
                page-break-inside: avoid;
                text-align: center;
            }
            
            #additional-details, label {
                align-self: center;
                width: 80%;
                margin-bottom: 0.8rem;
            }
        </style>
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    </head>
    <body>
        <header class="nav-bar">
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
            <?php echo $navbarContentMobile ?>
        </div>
        <div class="nav-bar__menu">
            <a class="nav-bar__menu__link__myshiki" href="./index.php">
                Página Principal
            </a>
            <?php echo $navbarContent; ?>
        </div>
    </header>
        <main>
            <div id="table-container__header">
                <p>
                    Meu carrinho de compras
                </p>
                    <?php
                    if ($_SESSION['userType'] !== 'rep') {
                    echo "<p>Pedido de: {$_SESSION['useruid']} ($formatted_cnpj)</p>";
                    } else {
                        echo "<p>Pedido de: {$_SESSION['userReplacementFormalName']} ({$formattedCnpj})</p>";
                    }
                    ?>
            </div>
            <?php
            if (!empty($_SESSION['shopping_cart'])) {
            echo '<div id="table-container">
                </div>
            <label for="additional-details">Detalhes adicionais (opcional):</label>
            <form style="display: flex; justify-content: center; flex-flow: column nowrap" method="post" action="pdf-template.php">
            <textarea id="additional-details" name="additional-details" rows="3" columns="20"></textarea>';
            
            

            //se valor total pedido for > que R$1.000, ou for classe matsuri e > que R$690, não finaliza pedido
            if ($preco_total_pedido >= MINIMUM_VALUE /*|| ($_SESSION['usersClass'] == "matsuri" && $preco_total_pedido >= 690.00)*/) {
                echo '<button type="submit" name="finalizar_compra" value="1">Finalizar Compra</button>';
                //echo '<input type="submit" class="shopping-cart-button" value="Ver prévia do pedido">';
            } else {
                echo '<p style="text-align: center; color: red;">O pedido não atinge o valor mínimo de R$' . MINIMUM_VALUE . ',00.</p>';
            }
             echo '</form>';

        } else {
            echo '<div style="display: flex; flex-flow: column nowrap; background-color: #eee;"><p class="empty-cart__alert">Você ainda não tem itens no seu carrinho de compras.</p><a class="shopping-cart-button" href="meus-produtos.php">Conferir produtos disponíveis</a></div>';
        }
?>
        </main>
        <?php echo '<script>
            document.addEventListener("DOMContentLoaded", () => {
                shoppingCart = ' . json_encode($shopping_cart) . ';
                createTable();
            })';
        echo $shoppingCartScript; ?>
        <script>
            function showCartAlert() {
                const logoutConfirmed = window.confirm('Ainda há itens no seu carrinho de compras. Tem certeza que deseja sair?');
                if (logoutConfirmed) {
                    window.location.href="./includes/logout.inc.php";
                }
            }
        </script>
        <script type="module" src="../script.js"></script>
    </body>
</html>