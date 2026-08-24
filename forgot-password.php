<?php
    session_start();
    if (isset($_SESSION['useruid'])) {
        header('location: ./index.php');
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Esqueci Minha Senha | Shiki</title>
    <link rel="stylesheet" href="style-myshiki.css">
    <link rel="stylesheet" href="style-myshiki-meta.css">
    <link rel="stylesheet" href="style-myshiki-responsivo.css">
    <link rel="stylesheet" href="../styles/styles.css">
    <link rel="stylesheet" href="../styles/responsivo.css">
    <link rel="stylesheet" href="../styles/nav-bar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="apple-touch-icon" sizes="180x180" href="./favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="./favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="./favicon/favicon-16x16.png">
    <link rel="manifest" href="./favicon/site.webmanifest">
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
    <?php
            if (isset($_SESSION["useruid"])) {
            echo "<a href=\"meus-produtos.php\">Meus Produtos</a>";
            echo "<a href=\"profile.php\">Perfil</a>";
            echo "<a href=\"./includes/logout.inc.php\">Logout</a>";
            } else {
            echo "<a href=\"../catalogo/\">Produtos</a>";
            echo "<a href=\"index.php\">Login</a>";
            }
        ?>
        </div>
    <div class="nav-bar__menu">
        <a class="nav-bar__menu__link__myshiki" href="./index.php">
            Página Principal
        </a>
        <a class="nav-bar__menu__link__myshiki" href="../catalogo/">
            Produtos
        </a>
        <?php
            if (isset($_SESSION["useruid"])) {
            echo "<a class=\"nav-bar__menu__link__myshiki myprofile-emp\" href=\"profile.php\">Meu Perfil</a>";
            echo "<a class=\"nav-bar__menu__link__myshiki\" href=\"./includes/logout.inc.php\">Logout</a>";
            } else {
            echo "<a class=\"nav-bar__menu__link__myshiki\" href=\"./index.php\">Login</a>";
            }
            
        ?>
    </div>
</header>

<main>
<div class="wrapper">
    <div class="login-container__title__wrapper">
        <h2 class="login-container__title">Esqueci Minha Senha</h2>
    </div>
    <div class="login-container">
        <form id="loginForm" action="./includes/password_recovery_handler.php" method="post">
            <div class="input-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="input-group">
                <label for="cnpj">CNPJ cadastrado:</label>
                <input type="text" id="cnpj" name="cnpj" required>
            </div>

            <button type="submit" name="submit">Recuperar senha</button>
        </form>
        </div>
    </div>

    <?php
        if (isset($_GET["error"])) {
            if ($_GET["error"] == "emptyinput") {
                echo "<div class=\"notification notification-error\"><div class=\"notification__body\"><img src=\"./media/x-circle.svg\" class=\"notification__icon\">Preencha todos os campos!</div><div class=\"notification__progress notification__progress-error\"></div></div>";
            } else if ($_GET["error"] == "wronglogin") {
                echo "<div class=\"notification notification-error\"><div class=\"notification__body\"><img src=\"./media/x-circle.svg\" class=\"notification__icon\">Credenciais inválidas</div><div class=\"notification__progress notification__progress-error\"></div></div>";
            } else if ($_GET["error"] == "usernotfound") {
                echo "<div class=\"notification notification-error\"><div class=\"notification__body\"><img src=\"./media/x-circle.svg\" class=\"notification__icon\">Credenciais inválidas</div><div class=\"notification__progress notification__progress-error\"></div></div>";
            } else if ($_GET["error"] == "none") {
                header("location: ./email-sent.php?specs=oj2ha53e");
            }
        }
    ?>
</div>
</main>
<script type="module" src="../script.js"></script>
</body>
</html>
