<?php

    include './includes/Formatter.php';
    
    session_start();
    
    $formatted_cnpj = Formatter::formatCnpj($_SESSION['userCnpj']);
    
    if (!isset($_SESSION)) {
        header('location: ./index.php');
    }
    
    
    if (isset($_SESSION["userReplacementPublicName"])) {
        $userUid = $_SESSION["userReplacementPublicName"];
        include_once './includes/find_rep_users.php';
    } else {
        $userUid = $_SESSION["useruid"];
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Histórico de Pedidos | MyShiki</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="./style-myshiki.css">
        <link rel="stylesheet" href="../styles/styles.css">
        <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
        <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
        <style>
            table {
                border-collapse: collapse;
                width: 60%;
                background-color: #fff;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                margin-bottom: 5rem;
            }
    
            th, td {
                border: 1px solid #ddd;
                padding: 12px;
                text-align: left;
            }
    
            th {
                background-color: #f2f2f2;
            }
    
            tr:hover {
                background-color: #f5f5f5;
            }
    
            a {
                text-decoration: none;
                color: #156aa3;
            }
    
            a:hover {
                color: #e74c3c;
            }
        </style>
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
        <main style="margin: 0;">
            <div id="table-container__header">
                <p>
                    Meu histórico de pedidos
                </p>
                    <?php echo "<p>Pedidos de: {$_SESSION['useruid']} ($formatted_cnpj)</p>"?>
            </div>
            <div style="display: flex; width: 100%; justify-content: center; align-items: center;">
                <table id="userTable">
                    <thead>
                        <tr>
                            <th>Nome do Arquivo</th>
                            <th>Código do Pedido</th>
                            <th>Data</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        
                        if (isset($_SESSION['userType'], $_SESSION['usersClass']) && $_SESSION['userType'] == 'rep') {
                            $repUsers = find_rep_users($conn, $_SESSION['userRepNo']);
                            foreach ($repUsers as $user) {
                                $pdfFolderPath = "./myshiki_historico-pedidos/$user/";
                                $pdfFiles = glob("$pdfFolderPath*.pdf");
                                usort($pdfFiles, function ($a, $b) {
                                    return filemtime($b) - filemtime($a);
                                });
                                foreach ($pdfFiles as $file):
                                    preg_match('/-(\d+)-(\d{2}\.\d{2}\.\d{2})/', basename($file), $matches);
                                    if (!empty($matches)) {
                                        $number = $matches[1];
                                        $dateStr = $matches[2];
                                        $date = DateTime::createFromFormat('d.m.y', $dateStr);
                                    }
                                ?>
                                <tr>
                                    <td><?php echo "<a href='$file' target='_blank'>" . basename($file) . "</a>"; ?></td>
                                    <td><?php echo $number; ?></td>
                                    <td><?php echo $date->format('d.m.y'); ?></td>
                                </tr>
                                <?php endforeach;
                            }
                        } else {
                        
                        $pdfFolderPath = './myshiki_historico-pedidos/' . $userUid . '/';
                        $pdfFiles = glob($pdfFolderPath . '*.pdf');
                        
                        usort($pdfFiles, function ($a, $b) {
                            return filemtime($b) - filemtime($a);
                        });
                        
                        foreach ($pdfFiles as $file):
                            preg_match('/-(\d+)-(\d{2}\.\d{2}\.\d{2})/', basename($file), $matches);
                            $number = $matches[1];
                            $dateStr = $matches[2];
                            $date = DateTime::createFromFormat('d.m.y', $dateStr);
                        ?>
                            <tr>
                                <td><?php echo "<a href='$file' target='_blank'>" . basename($file) . "</a>"; ?></td>
                                <td><?php echo $number; ?></td>
                                <td><?php echo $date->format('d.m.y'); ?></td>
                            </tr>
                        <?php endforeach; } ?>
                    </tbody>
                </table>
            </div>
        </main>
        <script src="./includes/datatables-options.js"></script>
    </body>
</html>