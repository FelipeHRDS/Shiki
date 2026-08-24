<?php

session_start();
include_once('./includes/session_variables.inc.php');

include_once './includes/auth.inc.php';
include_once './includes/Authentication.php';

$auth = new Authentication($privilegedClasses);

$auth->redirectIfNotAuthorized($userClass, './index.php');

include_once './components/navbarContent.php';
include_once './components/navbarContentMobile.php';

?>

<!DOCTYPE html>
<html lang="pt">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Gerar Catálogo | MyShiki</title>
        <link rel="stylesheet" href="style-myshiki.css">
        <link rel="stylesheet" href="style-myshiki-meta.css">
        <link rel="stylesheet" href="../styles/styles.css">
        <link rel="stylesheet" href="../styles/nav-bar.css">
        <link rel="stylesheet" href="../styles/responsivo.css">
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
            <?php echo $navbarContentMobile; ?>
        </div>
        <div class="nav-bar__menu">
            <a class="nav-bar__menu__link__myshiki" href="./index.php">
                Página Principal
            </a>
            <?php echo $navbarContent; ?>
        </div>
    </header>

        <form method="GET" action="./includes/get_tables.inc.php">
            <label for="tables">Selecionar tabela: </label>
            <select name="tables" id="tables">
                <option value="tab1">Tabela 1</option>
                <option value="tab3">Tabela 3</option>
                <option value="tab13">Tabela Mista</option>
            </select>
            <input type="submit" value="Buscar">
        </form>
    </body>
</html>