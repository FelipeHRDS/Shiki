<?php

require_once './includes/dbh.inc.php';
require_once './includes/functions.inc.php';
include_once './includes/session_variables.inc.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    if (!validateResetToken($conn, $token)) {
        echo
        '<head><title>Token Inválido | MyShiki</title><link rel="stylesheet" href="style-myshiki.css">
        <link rel="stylesheet" href="../style-myshiki-meta.css">
        <link rel="stylesheet" href="../styles/styles.css">
        <link rel="stylesheet" href="../styles/responsivo.css"></head>';
        echo '<body><div class="login-container">
            <img src="./media/x-circle.svg" alt="Error" style="width: 60px">
            <br />
            <h1>Token inválido ou expirado.</h1>
            <h2 style="font-weight: normal;">O token que você tentou utilizar é inválido ou já expirou.</h2>
            <h2 style="font-weight: normal;">Tente novamente.</h2>
        </div></body>';
        exit();
    }
} else {
    header("location: ./index.php");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];

    if (empty($newPassword) || empty($confirmPassword)) {
        echo "Please enter both the new password and confirm password.";
        exit();
    }

    if ($newPassword !== $confirmPassword) {
        echo "Passwords do not match.";
        exit();
    }

    $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    $sql = "UPDATE users SET usersPwd = ? WHERE resetToken = ?";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo "SQL statement preparation failed.";
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ss", $hashedNewPassword, $token);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    echo '<head><title>Senha Redefinida | MyShiki</title><link rel="stylesheet" href="style-myshiki.css">
        <link rel="stylesheet" href="style-myshiki-meta.css">
        <link rel="stylesheet" href="../styles.css">
        <link rel="stylesheet" href="../responsivo.css"></head>';
        echo '<body><div class="login-container">
            <img src="./media/check-circle.png" alt="Success" style="width: 60px">
            <br />
            <h1>Senha redefinida com sucesso.</h1>
            <h2 style="font-weight: normal;">Sua senha foi redefinida com sucesso.</h2>
            <a style="color: #9f34db; href="./index.php">Clique aqui para voltar à tela de login.</h2>
        </div></body>';
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir Senha | MyShiki</title>
    <link rel="stylesheet" href="style-myshiki.css">
    <link rel="stylesheet" href="style-myshiki-meta.css">
    <link rel="stylesheet" href="../styles/styles.css">
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
    <?php
            if (isset($userUid)) {
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
            if (isset($userUid)) {
            echo "<a class=\"nav-bar__menu__link__myshiki myprofile-emp\" href=\"profile.php\">Meu Perfil</a>";
            echo "<a class=\"nav-bar__menu__link__myshiki\" href=\"./includes/logout.inc.php\">Logout</a>";
            } else {
            echo "<a class=\"nav-bar__menu__link__myshiki\" href=\"./index.php\">Login</a>";
            }
        ?>
    </div>
</header>
    <div class="wrapper">
        <div class="login-container__title__wrapper">
            <h2 class="login-container__title">Redefina sua senha</h2>
        </div>
        <div class="login-container" style="align-items: normal;">
            <form method="post" action="">
                <div class="input-group">
                    <label for="newPassword">Nova senha:</label>
                    <input type="password" id="newPassword" name="newPassword" required>
                </div>
                
                <div class="input-group">
                    <label for="confirmPassword">Confirme a senha:</label>
                    <input type="password" id="confirmPassword" name="confirmPassword" required>
                </div>
        
                <button type="submit" name="submit">Enviar</button>
            </form>
        </div>
    </div>
</body>
</html>