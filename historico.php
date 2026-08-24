<?php

    include './includes/Formatter.php';
    
    session_start();
    include_once './components/navbarContentMobile.php';
    include_once './components/navbarContent.php';
    include_once './includes/dbhc.inc.php';
    include_once './includes/Filter.php';
    
    if (isset($_SESSION)) {
        $formatted_cnpj = Formatter::formatCnpj($_SESSION['userCnpj']);
    }
    
    if (!isset($_SESSION) || !isset($_SESSION['usersClass']) || 
        ($_SESSION['usersClass'] != "admin" && $_SESSION['usersClass'] != "vendedor" && $_SESSION['usersClass'] != "supervisor")) {
        header('location: ../index.html');
        exit();
    }

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Histórico de Pedidos | MyShiki</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="./style-myshiki.css">
        <link rel="stylesheet" href="../styles/styles.css">
        <link rel="stylesheet" href="../styles/responsivo.css">
        <link rel="stylesheet" href="../styles/nav-bar.css">
        <link rel="stylesheet" href="./style-myshiki-responsivo.css">
        <link rel="apple-touch-icon" sizes="180x180" href="./favicon/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="./favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="./favicon/favicon-16x16.png">
        <link rel="manifest" href="./favicon/site.webmanifest">
        <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
        <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
        
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <style>
            html, body {
                height: 110%;
            }
            
            table {
                border-collapse: collapse;
                max-width: 100%;
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
        <main style="margin: 0;">
            <div id="table-container__header">
                <p>
                    Meu histórico de pedidos
                </p>
                    <?php echo "<p>Pedidos de: {$_SESSION['useruid']} ($formatted_cnpj)</p>"?>
            </div>
            <div style="display: flex; width: 100%; justify-content: center; align-items: center; margin-top: 4rem;">
                <table id="userTable" class="hover" style="width:90%">
                    <thead>
                        <tr>
                            <th>Nome do Arquivo</th>
                            <th>Código do Pedido</th>
                            <th>Data</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        $pdfBaseFolderPath = './myshiki_historico-pedidos/';
                        $customers = Filter::find_customers($_SESSION['vendedor'], "usersUid");
                        $targetDate = $_SESSION['usersClass'] == 'admin' || $_SESSION['usersClass'] == 'supervisor' ? DateTime::createFromFormat('Y-m-d', '2023-09-01') : DateTime::createFromFormat('Y-m-d', '2024-10-07');

                        $filteredPdfFiles = Filter::filter_pdf_files_by_date($pdfBaseFolderPath, $customers, $targetDate);

                        foreach ($filteredPdfFiles as $fileInfo) {
                            ?>
                            <tr>
                                <td><?php echo "<a href='{$fileInfo['file']}' target='_blank'>" . basename($fileInfo['file']) . "</a>"; ?></td>
                                <td><?php echo $fileInfo['number']; ?></td>
                                <td><?php echo $fileInfo['date']->format('Y.m.d'); ?></td>
                            </tr>
                            <?php
                        }
                    ?>

                    </tbody>
                </table>
            </div>
        </main>
        <script src="./includes/datatables-options.js"></script>
    </body>
</html>