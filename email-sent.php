<?php
session_start();
    if (!isset($_SESSION['useruid']) && $_GET['specs'] !== "oj2ha53e") {
        header("location: ./index.php");
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Email enviado com sucesso!</title>
        <link rel="stylesheet" href="style-myshiki.css">
        <link rel="stylesheet" href="style-myshiki-meta.css">
        <link rel="stylesheet" href="../styles/styles.css">
        <link rel="stylesheet" href="../styles/responsivo.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="apple-touch-icon" sizes="180x180" href="./favicon/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="./favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="./favicon/favicon-16x16.png">
        <link rel="manifest" href="./favicon/site.webmanifest">
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    </head>
    <body>
        <?php if (isset($_GET['specs']) && $_GET['specs'] !== "oj2ha53e") { echo
        '<div class="login-container">
            <img src="./media/check-circle.png" alt="success" style="width: 60px">
            <br />
            <h1>Seu pedido foi enviado com sucesso.</h1>
            <h2 style="font-weight: normal;">Recebemos seu pedido e o processaremos em breve!</h2>
            <h2 style="font-weight: normal;">Você pode fechar esta janela com segurança.</h2>
            <br />
            <a style="color: #9f34db;" href="./index.php">Ou clique aqui para voltar à tela principal.</a>
        </div>'; } else {
            echo '<div class="login-container">
            <img src="./media/check-circle.png" alt="success" style="width: 60px">
            <br />
            <h1>Sua solicitação foi enviada com sucesso.</h1>
            <br />
            <h2 style="font-weight: normal;">Você receberá um link no seu email cadastrado para redefinição de senha.</h2>
            <br />
            <a style="color: #9f34db;" href="./index.php">Clique aqui para voltar à tela principal.</a>
        </div>';
        } ?>
    </body>
</html>