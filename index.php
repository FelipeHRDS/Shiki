<?php

include './includes/profile_picture_mapping.php';
include './includes/Formatter.php';
session_start();
include_once './components/navbarContentMobile.php';
include_once './components/navbarContent.php';
include_once './components/settingsMenu.php';
include_once './includes/session_variables.inc.php';

$isLoggedIn = isset($userUid);

$profilePicturePath = isset($userCnpj) ? $profilePictureMapping[$userClass] ?? './media/atacado-icone.jpg' : '';
$formattedCnpj = isset($userCnpj) ? Formatter::formatCnpj($userCnpj) : '';

include_once './components/displayLinks.php';

?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Principal | MyShiki</title>

    <link rel="stylesheet" href="style-myshiki.css">
    <link rel="stylesheet" href="style-myshiki-meta.css">
    <link rel="stylesheet" href="style-myshiki-responsivo.css">
    <link rel="stylesheet" href="../styles/styles.css">
    <link rel="stylesheet" href="../styles/nav-bar.css">
    <link rel="stylesheet" href="../styles/responsivo.css">
    <link rel="stylesheet" href="./popup.css">
    <link rel="preload" fetchpriority="high" as="image" href="../media/Popup Turquesa v2.jpg" type="image/jpeg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="apple-touch-icon" sizes="180x180" href="./favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="./favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="./favicon/favicon-16x16.png">
    <link rel="manifest" href="./favicon/site.webmanifest">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    
    <style scoped>
        .wrapper {
            flex-flow: row nowrap;
            justify-content: space-evenly;
        }
        
        .welcome-title {
            color: white;
            text-align: center;
            margin: 2rem 0 0 0;
            font-size: 2rem;
            padding: 1rem 0 0 0;
        }
        @media screen and (max-width: 798px) {
            .welcome-title {
                color: white;
                text-align: center;
                margin: 6rem 0 0 0;
                font-size: 2rem;
                padding: 1rem 0 0 0;
            }

            .wrapper {
                flex-flow: column nowrap;
                justify-content: space-evenly;
            }
            
            .margin-top-to-fit {
                margin-top: 0rem;
            }
        }
    </style>
</head>
<body>
    <header class="nav-bar">
        <div class="nav-bar__mobile__hamburger-menu">
            <a aria-label="Abrir Menu" href="javascript:void(0)" class="hamburger-icon">
                <i class="fa fa-bars" style="font-size: x-large;"></i>
            </a>
        </div>
        <div class="nav-bar__logo">
            <a href="index.php">
                <img class="nav-bar__logo__image" src="../media/shiki-logo.jpg" alt="Logo Shiki">
            </a>
        </div>
        <div class="nav-bar__mobile__hamburger-menu__links __hide">
            <?= $navbarContentMobile ?>
        </div>
        <div class="nav-bar__menu">
            <a class="nav-bar__menu__link__myshiki" href="index.php">
                Página Principal
            </a>
            <?= $navbarContent ?>
        </div>
    </header>

    <!--
    <section>
            <div class="popup__screen-overlay __hide"></div>
            <div class="popup__body __hide">
                <div class="popup__close-button__wrapper">
                    <button id="popup__close-button" aria-label="Fechar Popup" style= "justify-content:end;"></button>
                </div>
                <div>
                    <a href="https://inscricaodeeventos.com.br/koelnmesse/anuga/2025/usuario/index.php" target="_blank" class="popup__go-button">VISITAR SITE &#8594;</a>
                <!--<a href="./meus-produtos.php?category=Turquesa#nosso-catalogo" class="popup__go-button">Confira as Novidades &#8594;</a>
                </div>
            </div>
    </section>
    -->

    <div style="display: flex; flex-flow: column nowrap; width: 100%" <?php if ($isLoggedIn) echo "class='margin-top-to-fit'"; ?>>
        <h1 class="welcome-title">Bem-Vindo à MyShiki</h1>
        <main style="width: 100%; margin: 0;">
            <div class="wrapper">
                <div class="login-container" style="gap: 0.5rem">
                    <?php
                    if ($isLoggedIn) {
                        displayUserInfo();
                        displayLinks();
                    } else {
                        echo "<button class='button-purple' onclick='window.location.href=\"login.php\"'>Login</button>";
                    }
                    ?>
                </div>
                <?= $settingsMenu; ?>
            </div>
        </main>
    </div>
    <script type="module" src="../script.js"></script>
    
    <script>
        function showCartAlert() {
            const logoutConfirmed = window.confirm('Ainda há itens no seu carrinho de compras. Tem certeza que deseja sair?');
            if (logoutConfirmed) {
                window.location.href="./includes/logout.inc.php";
            }
        }
    </script>
    <script type="module" src="./popuphandlers.js"></script>
</body>
</html>