<?php

include_once './includes/dbh.inc.php';

session_start();

var_dump($_SESSION['userRepNo']);
echo($_SESSION['userRepNo']);

include_once './components/navbarContentMobile.php';
include_once './components/navbarContent.php';
include_once './includes/auth.inc.php';
include_once './includes/Authentication.php';
include_once './includes/session_variables.inc.php';

$auth = new Authentication($repUsers);
$auth->redirectIfNotAuthorized($userType, './index.php');

$sql = "SELECT SQL_NO_CACHE usersName FROM users WHERE usersUid NOT LIKE '%admin%' AND userType = 'User' AND repNumber = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $_SESSION['userRepNo']);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}
$conn->close();

?>

<html>
    <head>
        <title>Usuários - MyShiki</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
        <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
        <link rel="apple-touch-icon" sizes="180x180" href="./favicon/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="./favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="./favicon/favicon-16x16.png">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="manifest" href="./favicon/site.webmanifest">
        <link rel="stylesheet" href="./style-myshiki.css">
        <link rel="stylesheet" href="../styles/styles.css">
        <link rel="stylesheet" href="../styles/responsivo.css">
        <link rel="stylesheet" href="./style-myshiki-responsivo.css">
        <link rel="stylesheet" href="../styles/nav-bar.css">
        <style>
            #choose-customer-form {
                display: flex;
                flex-direction: column;
                width: 50%;
                align-items: center;
                justify-content: center;
            }
            .select-user-bar {
                display: flex;
                padding: 0.5rem 2rem;
                border-radius: 50px;
            }
            .choose-customer-container {
                display: flex;
                align-items: center;
                justify-content: center;
                background: #ebebeb;
                margin-top: 120px;
                padding-top: 20px;
                flex-direction: column;
                gap: 0.5rem;}
        </style>
        <style scoped>
            @media screen and (max-width: 798px) {
                .choose-customer-container {
                    width: 100%;
                    margin-top: 80px;
                }
                .select-user-bar {
                    width: 150%;
                    background-color: white;
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
            <a href="./index.php">
                <img class="nav-bar__logo__image" src="../media/shiki-logo.jpg" alt="Logo Shiki">
            </a>
        </div>
        <div class="nav-bar__mobile__hamburger-menu__links __hide">
            <?= $navbarContentMobile ?>
        </div>
        <div class="nav-bar__menu">
            <a class="nav-bar__menu__link__myshiki" href="./index.php">
                Página Principal
            </a>
            <?= $navbarContent;?>
        </div>
    </header>
        <div class="choose-customer-container">
            <h2>Selecione uma unidade: </h2>
        <?php
            echo "<form id='choose-customer-form' action='./components/userReplacement.php' method='post'>";
            $selectOptions = [];
            foreach ($data as $row) {
                foreach ($row as $cell) {
                    $selectOptions[] = "<option value=\"{$cell}\">{$cell}</option>";
                }
            }
            echo "<select class='select-user-bar' id='users' name='users[]'>" . implode('', $selectOptions) . "</select><input type='submit' onclick='return confirm(\"Deseja selecionar este CNPJ?\")' class='shopping-cart-button' value='Confirmar'></form>";
        ?>
        </div>

        <script>
        $(document).ready(function() {

    var table = $('#userTable').DataTable({
        retrieve: true,
        language: {
            "sProcessing":    "Processando...",
            "sLengthMenu":    "Mostrar _MENU_ registros",
            "sZeroRecords":   "Nenhum registro encontrado",
            "sEmptyTable":    "Nenhum dado disponível nesta tabela",
            "sInfo":          "Mostrando registros de _START_ a _END_ de um total de _TOTAL_ registros",
            "sInfoEmpty":     "Mostrando registros de 0 a 0 de um total de 0 registros",
            "sInfoFiltered": "(filtrado de um total de _MAX_ registros)",
            "sInfoPostFix":   "",
            "sSearch":        "Pesquisar:",
            "sUrl":           "",
            "sInfoThousands": ".",
            "sLoadingRecords": "Carregando...",
            "oPaginate": {
                "sFirst":    "Primeiro",
                "sLast":    "Último",
                "sNext":    "Próximo",
                "sPrevious": "Anterior"
            },
            "oAria": {
                "sSortAscending": ": Ativar para classificar a coluna em ordem crescente",
                "sSortDescending": ": Ativar para classificar a coluna em ordem decrescente"
            }
        }
    });
});
        </script>
        <script type="module" src="../script.js"></script>
    </body>
</html>