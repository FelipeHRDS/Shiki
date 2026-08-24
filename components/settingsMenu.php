<?php

function buildSettingsMenu() {
    if (!isset($_SESSION['useruid'])) {
        return '';
    }

    $buttons = [
        'admin' => [
            './historico.php' => 'Histórico de Pedidos',
            './users.php' => 'Usuários',
            './signup.php' => 'Cadastrar Usuário'
        ],
        'default' => [
            './historico-pedidos.php' => 'Histórico de Pedidos',
            './change-password.php' => 'Alterar Senha'
        ]
    ];

    $userClass = $_SESSION['usersClass'];

    if ($userClass == 'supervisor' || $userClass == 'vendedor') {
        return '';
    }

    $links = ($userClass == 'admin') ? $buttons['admin'] : $buttons['default'];

    $menu = '<div class="login-container"><h2>Configurações</h2>';
    foreach ($links as $href => $label) {
        $menu .= "<button class='button-purple' onclick='window.location.href=\"$href\"'>$label</button>";
    }
    $menu .= '</div>';

    return $menu;
}

$settingsMenu = buildSettingsMenu();
