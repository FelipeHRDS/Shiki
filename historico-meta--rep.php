<?php

include_once './includes/dbh.inc.php';

session_start();

$sql = "SELECT usersName, usersCnpj, usersUF, usersCity FROM users WHERE usersUid NOT LIKE '%admin%' AND userType = 'User' AND repNumber = ?";
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
        <title>Históricos de Pedidos - MyShiki</title>
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
        <link rel="stylesheet" href="../styles/nav-bar.css">
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
        foreach ($row as $key => $cell) {
            $encodedCell = urlencode($cell);
            $cells[] = ($key === 'usersName') ? "<td><a style='text-decoration: none; color: black;' href='./historico-meta--rep.php?user={$encodedCell}'>{$cell}</a></td>" : "<td>{$cell}</td>";
        }
        $rows[] = "<tr>" . implode('', $cells) . "</tr>";
    }
    return "<div style=\"display:flex; align-items: center; justify-content: center;\"><table id=\"userTable\" class=\"hover\" style=\"width: 100%;\">
    <thead>
        <th>Nome Empresarial</th>
        <th>CNPJ</th>
        <th>UF</th>
        <th>Cidade</th>
    </thead>
    <tbody>" . implode('', $rows) . "</tbody></table></div>";
}

echo html_table($data);
        ?>
        
        <script src="./includes/datatables-options.js"></script>
    </body>
</html>