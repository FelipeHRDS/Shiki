<?php

session_start();

include_once './includes/dbhc.inc.php';
include_once './includes/auth.inc.php';
include_once './includes/Authentication.php';
include_once './includes/Filter.php';
include_once './includes/session_variables.inc.php';

// Permite admin, supervisor e vendedor nesta página.
$allowedClasses = ['admin', 'supervisor', 'vendedor'];

$auth = new Authentication($allowedClasses);
$auth->redirectIfNotAuthorized($userClass ?? null, './index.php');

// Identifica o tipo de acesso.
$isVendedor = ($userClass === 'vendedor');

if ($isVendedor) {
    // Vendedor visualiza somente seus clientes.
    $vendedorNumber = $_SESSION['userSystemCode'] ?? null;

    // Impede que um vendedor sem código consulte todos os clientes.
    if (
        $vendedorNumber === null ||
        !ctype_digit((string)$vendedorNumber) ||
        (int)$vendedorNumber <= 0
    ) {
        http_response_code(403);
        exit('Código do vendedor inválido ou não identificado.');
    }

    $vendedorNumber = (int)$vendedorNumber;

} else {
    // Mantém a regra anterior dos supervisores e administradores.
    $vendedorNumber = $_SESSION['vendedor'] ?? 0;
}

$users_data = Filter::find_customers(
    $vendedorNumber,
    "usersName,usersUid,usersCnpj,usersClass,usersUF,usersCity,vendedor"
);

if (isset($users_data['error'])) {
    error_log('Erro ao buscar clientes: ' . $users_data['error']);
    $users_data = [];
}

/**
 * Busca os PDFs do cliente e calcula:
 * - Quantidade de pedidos
 * - Data do último pedido
 * - Dias sem comprar
 */
function getCustomerOrderStats($userUid) {

    $pdfFolderPath = './myshiki_historico-pedidos/' . $userUid . '/';

    $pdfFiles = glob($pdfFolderPath . '*.pdf') ?: [];

    $totalPedidos = count($pdfFiles);
    $lastOrderDate = null;

    foreach ($pdfFiles as $file) {

        $fileName = basename($file);

        // Extrai o número e a data do nome do PDF.
        // Exemplo: pedido-12345-15.10.26.pdf
        if (preg_match(
            '/-(\d+)-(\d{2}\.\d{2}\.\d{2})\.pdf$/i',
            $fileName,
            $matches
        )) {

            $date = DateTimeImmutable::createFromFormat(
                '!d.m.y',
                $matches[2]
            );

            // Ignora datas inválidas.
            if (
                $date &&
                $date->format('d.m.y') === $matches[2]
            ) {

                if (
                    $lastOrderDate === null ||
                    $date > $lastOrderDate
                ) {
                    $lastOrderDate = $date;
                }
            }
        }
    }

    $daysWithoutBuying = null;

    if ($lastOrderDate !== null) {

        $today = new DateTimeImmutable('today');

        $daysWithoutBuying = (int) $lastOrderDate
            ->diff($today)
            ->format('%r%a');
    }

    return [
        'totalPedidos' => $totalPedidos,

        'lastOrderDate' => $lastOrderDate
            ? $lastOrderDate->format('d/m/Y')
            : 'Sem pedidos',

        'lastOrderTimestamp' => $lastOrderDate
            ? $lastOrderDate->format('Ymd')
            : '0',

        'daysWithoutBuying' => $daysWithoutBuying
    ];
}

/**
 * Monta a tabela de usuários.
 */
function html_table($data = []) {

    $rows = [];

    $columns = [
        'usersName',
        'usersUid',
        'usersCnpj',
        'usersClass',
        'usersUF',
        'usersCity',
        'vendedor'
    ];

    foreach ($data as $row) {

        if (!is_array($row) || !isset($row['usersUid'])) {
            continue;
        }

        $cells = [];

        // Identifica o cliente.
        $userUid = $row['usersUid'];

        // Busca as informações dos pedidos.
        $stats = getCustomerOrderStats($userUid);

        // Preenche as sete colunas originais.
        foreach ($columns as $column) {

            $value = htmlspecialchars(
                (string)($row[$column] ?? ''),
                ENT_QUOTES,
                'UTF-8'
            );

            $cells[] = "<td>{$value}</td>";
        }

        // Coluna 8: Quantidade de pedidos.
        $totalPedidos = $stats['totalPedidos'];

        $cells[] = "<td>{$totalPedidos}</td>";

        // Coluna 9: Último pedido.
        // data-order permite ordenar corretamente as datas.
        $lastOrderDate = $stats['lastOrderDate'];
        $lastOrderTimestamp = $stats['lastOrderTimestamp'];

        $cells[] = "<td data-order='{$lastOrderTimestamp}'>
            {$lastOrderDate}
        </td>";

        // Coluna 10: Dias sem comprar.
        $days = $stats['daysWithoutBuying'];

        if ($days === null) {

            $daysText = 'Nunca comprou';
            $daysOrder = -1;

        } elseif ($days < 0) {

            $daysText = 'Data futura';
            $daysOrder = -1;

        } else {

            $daysText = (string)$days;
            $daysOrder = $days;
        }

        $cells[] = "<td data-order='{$daysOrder}'>
            {$daysText}
        </td>";

        // Monta a linha completa.
        $rows[] = "<tr>" . implode('', $cells) . "</tr>";
    }

    return '
    <div style="display:flex; align-items:center; justify-content:center;">

        <table id="userTable" class="hover" style="width:100%;">

            <thead>
                <tr>
                    <th>Nome Empresarial</th>
                    <th>Nome de Usuário</th>
                    <th>CNPJ</th>
                    <th>Franquia</th>
                    <th>UF</th>
                    <th>Cidade</th>
                    <th>Vendedor</th>
                    <th>Quantidade de Pedidos</th>
                    <th>Último Pedido</th>
                    <th>Dias sem Comprar</th>
                </tr>
            </thead>

            <tbody>
                ' . implode('', $rows) . '
            </tbody>

        </table>

    </div>';
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <title>Usuários | MyShiki</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

    <link rel="stylesheet"
          href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">

    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

    <link rel="apple-touch-icon"
          sizes="180x180"
          href="./favicon/apple-touch-icon.png">

    <link rel="icon"
          type="image/png"
          sizes="32x32"
          href="./favicon/favicon-32x32.png">

    <link rel="icon"
          type="image/png"
          sizes="16x16"
          href="./favicon/favicon-16x16.png">

    <link rel="manifest" href="./favicon/site.webmanifest">

    <link rel="stylesheet" href="./style-myshiki.css">
    <link rel="stylesheet" href="../styles/styles.css">
    <link rel="stylesheet" href="../styles/responsivo.css">
    <link rel="stylesheet" href="./style-myshiki-responsivo.css">

</head>

<body>

<header style="display: flex;">

    <div class="nav-bar__mobile__hamburger-menu">
        <a aria-label="Abrir Menu"
           href="javascript:void(0)"
           class="hamburger-icon">
            <i class="fa fa-bars" style="font-size: x-large;"></i>
        </a>
    </div>

    <div class="nav-bar__logo">
        <a href="./index.php">
            <img class="nav-bar__logo__image"
                 src="../media/shiki-logo.jpg"
                 alt="Logo Shiki">
        </a>
    </div>

    <div class="nav-bar__mobile__hamburger-menu__links __hide">

        <?php
        if (isset($_SESSION["useruid"])) {

            echo '<a href="meus-produtos.php">Meus Produtos</a>';
            echo '<a href="profile.php">Perfil</a>';
            echo '<a href="./includes/logout.inc.php">Logout</a>';

        } else {

            echo '<a href="../catalogo/">Produtos</a>';
            echo '<a href="signup.php">Cadastro</a>';
            echo '<a href="index.php">Login</a>';
        }
        ?>

    </div>

    <div class="nav-bar__menu">

        <a class="nav-bar__menu__link__myshiki"
           href="./index.php">
            Página Principal
        </a>

        <?php

        if (isset($_SESSION["useruid"])) {

            echo '<a class="nav-bar__menu__link__myshiki"
                     href="./meus-produtos.php">Produtos</a>';

            echo '<a class="nav-bar__menu__link__myshiki myprofile-emp"
                     href="shopping-cart.php">Meu Carrinho</a>';

            if (isset($_SESSION['shopping_cart'])) {

                echo "<div id='product-counter'>" .
                    count($_SESSION['shopping_cart']) .
                    "</div>";
            }

            if (empty($_SESSION['shopping_cart'])) {

                echo '<a class="nav-bar__menu__link__myshiki"
                         href="./includes/logout.inc.php">Logout</a>';

            } else {

                echo '<a class="nav-bar__menu__link__myshiki"
                         href="javascript:showCartAlert();">Logout</a>';
            }

        } else {

            echo '<a class="nav-bar__menu__link__myshiki"
                     href="../catalogo/">Produtos</a>';

            echo '<a class="nav-bar__menu__link__myshiki"
                     href="./signup.php">Cadastro</a>';

            echo '<a class="nav-bar__menu__link__myshiki"
                     href="./index.php">Login</a>';
        }

        ?>

    </div>

</header>

<main>

    <!-- Filtros dos clientes -->
    <div style="margin:20px 265px; display:flex; gap:15px; align-items:center; flex-wrap:wrap;">

        <label for="filtroInatividade">
            Filtrar clientes:
        </label>

        <select id="filtroInatividade">

            <option value="todos">
                Todos os clientes
            </option>

            <option value="30">
                30 dias ou mais sem comprar
            </option>

            <option value="60">
                60 dias ou mais sem comprar
            </option>

            <option value="90">
                90 dias ou mais sem comprar
            </option>

            <option value="nunca">
                Nunca compraram
            </option>

        </select>

    </div>

    <!-- Tabela de usuários -->
    <?php echo html_table($users_data); ?>

</main>

<!-- Configurações originais do DataTables -->
<script src="./includes/datatables-options.js"></script>

<!-- Configurações exclusivas do users.php -->
<script>

$(document).ready(function () {

    // Recupera a tabela já inicializada.
    const table = $('#userTable').DataTable();

    // Ordena inicialmente pela quantidade de pedidos.
    table.order([7, 'desc']).draw();

    // Filtro personalizado de inatividade.
    $.fn.dataTable.ext.search.push(function(settings, data) {

        // Aplica somente nesta tabela.
        if (settings.nTable !== table.table().node()) {
            return true;
        }

        const filtro = $('#filtroInatividade').val();

        // Coluna 9: dias sem comprar.
        const diasTexto = String(data[9]).trim();

        const dias = parseInt(diasTexto, 10);

        if (filtro === 'todos') {
            return true;
        }

        if (filtro === 'nunca') {
            return diasTexto === 'Nunca comprou';
        }

        if (Number.isNaN(dias)) {
            return false;
        }

        return dias >= parseInt(filtro, 10);

    });

    // Atualiza a tabela quando o filtro muda.
    $('#filtroInatividade').on('change', function() {
        table.draw();
    });

});

</script>

</body>
</html>
