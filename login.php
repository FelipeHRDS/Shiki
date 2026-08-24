<?php
    session_start();
    include_once './includes/session_variables.inc.php';
    if (isset($userUid)) {
        header('location: ./index.php');
    }
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acesso de Usuário | MyShiki</title>
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
        if (isset($userUid)) {
            echo "<a href=\"meus-produtos.php\">Meus Produtos</a>
            <a href=\"profile.php\">Perfil</a>
            <a href=\"./includes/logout.inc.php\">Logout</a>";
        } else {
            echo "<a href=\"../catalogo/\">Produtos</a>
            <a href=\"login.php\">Login</a>";
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
        <?php if (isset($userUid)): ?>
            <a class=\"nav-bar__menu__link__myshiki myprofile-emp\" href=\"profile.php\">Meu Perfil</a>
            <a class=\"nav-bar__menu__link__myshiki\" href=\"./includes/logout.inc.php\">Logout</a>
        <?php else: ?>
        <?php endif ?>
    </div>
</header>

<main>
<div class="wrapper">
    <div class="login-container__title__wrapper">
        <h2 class="login-container__title">Login MyShiki</h2>
    </div>
    <div class="login-container">
        <form id="loginForm" action="./includes/login.inc.php" method="post">
            <div class="input-group">
                <label class="label" for="useruid">Nome de Usuário ou CNPJ:</label>
                <input type="text" id="useruid" name="useruid" autocomplete="on">
            </div>  

            <div class="input-group">
                <label class="label" for="password">Senha:</label>
                <input type="password" id="password" name="password" required autocomplete="current-password" style="display: inline-block;">
            </div>
            
            <input type="checkbox" value="lsRememberMe" id="rememberMe"> <label for="rememberMe">Lembrar-se de mim</label>
            
            <button type="submit" name="submit" onclick="lsRememberMe()">Entrar</button>
        </form>
        <div>
            <a href='./forgot-password.php' style='color: #000;'>Esqueci minha senha</a>
        </div>
    </div>

    <?php
        if (isset($_GET["error"])) {
            if ($_GET["error"] == "emptyinput") {
                echo "<div class=\"notification notification-error\"><div class=\"notification__body\"><img src=\"./media/x-circle.svg\" class=\"notification__icon\">Preencha todos os campos!</div><div class=\"notification__progress notification__progress-error\"></div></div>";
            } else if ($_GET["error"] == "wronglogin") {
                echo "<div class=\"notification notification-error\"><div class=\"notification__body\"><img src=\"./media/x-circle.svg\" class=\"notification__icon\">Credenciais inválidas</div><div class=\"notification__progress notification__progress-error\"></div></div>";
            }
        }
    ?>
</div>
</main>
<script type="module" src="../script.js"></script>
<script>
    const cnpjInput = document.getElementById("useruid");
    let isCnpj = false;
    
    const rmCheck = document.getElementById("rememberMe");
    const userUidInput = document.getElementById("useruid");
    
    if (userUidInput.length > 2 && !isNaN(userUidInput.substring(0,2))) {
        isCnpj = true;
    }
    
    if (localStorage.checkbox && localStorage.checkbox !== "") {
      rmCheck.setAttribute("checked", "checked");
      userUidInput.value = localStorage.username;
    } else {
      rmCheck.removeAttribute("checked");
      userUidInput.value = "";
    }
    
    function lsRememberMe() {
      if (rmCheck.checked && userUidInput.value !== "") {
        localStorage.username = userUidInput.value.replace(/[.,\/#!$%\^&\*;:{}=\-_`~()]/g,"");
        localStorage.checkbox = rmCheck.value;
      } else {
        localStorage.username = "";
        localStorage.checkbox = "";
      }
    }
    
    cnpjInput.addEventListener("input", function() {
        const value = this.value;
        const formattedValue = value.replace(/\D/g, '') // Remove non-digit characters
                             .replace(/^(\d{2})(\d{3})(\d{3})(\d{4})(\d{1})/, '$1.$2.$3/$4-$5'); // Format CNPJ
        
        if (value.length > 2 && !isNaN(value.substring(0, 2))) {
            this.value = formattedValue;
            isCnpj = true;
        } else {
            this.value = value;
        }
    });
    
    const loginForm = document.getElementById("loginForm");
    loginForm.addEventListener("submit", function(event) {
        if (isCnpj) {
          cnpjInput.value = cnpjInput.value.replace(/\D/g, '');
        }
    });
</script>
</body>
</html>
