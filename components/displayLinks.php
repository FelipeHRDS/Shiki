<?php

function displayLinks() {
    $buttons = [
        'supervisor' => [
            'meus-produtos.php' => 'Produtos',
            'historico.php' => 'Histórico de Pedidos',
            'users.php' => 'Usuários'
        ],
        'vendedor' => [
            'meus-produtos.php' => 'Produtos',
            'historico.php' => 'Histórico de Pedidos'
        ],
        'rep' => [
            'escolher-cliente.php' => 'Fazer pedido',
            'includes/logout.inc.php' => 'Logout'
        ],
        'default' => [
            'meus-produtos.php' => 'Produtos',
            'shopping-cart.php' => 'Meu Carrinho',
            'includes/logout.inc.php' => 'Logout'
        ]
    ];

    $userClass = $_SESSION['usersClass'] ?? 'default';
    $userType = $_SESSION['userType'] ?? 'default';

    $links = $buttons[$userClass] ?? $buttons[$userType] ?? $buttons['default'];

    foreach ($links as $href => $label) {
        echo createButton($href, $label);
    }
}

function createButton($href, $label) {
    return "<button class='button-purple' onclick='window.location.href=\"$href\"'>$label</button>";
}

function displayUserInfo() {
    if (!isset($_SESSION['name'], $_SESSION['useruid'])) {
        echo "User information is not available.";
        return;
    }

    global $profilePicturePath, $formattedCnpj;

    $userName = htmlspecialchars($_SESSION['name'], ENT_QUOTES, 'UTF-8');
    $userUid = htmlspecialchars($_SESSION['useruid'], ENT_QUOTES, 'UTF-8');
    $profilePicturePath = htmlspecialchars($profilePicturePath, ENT_QUOTES, 'UTF-8');

    ?>

    <div style="display: flex; flex-flow: row nowrap; gap: 1rem; font-size: 1.125rem;">
        <img src="<?= $profilePicturePath ?>" 
             alt="User Profile Picture" 
             style="width: 5rem; aspect-ratio: 1; margin-right: 0.5rem; border-radius: 50%;">
        <div style="display: flex; flex-flow: column nowrap; justify-content: center; gap: 0.25rem;">
            <?= $userName ?> (<?= $formattedCnpj ?>)
        </div>
    </div>
    <p style="font-size: 1.125rem;"><?= $userUid ?></p>

    <?php
}