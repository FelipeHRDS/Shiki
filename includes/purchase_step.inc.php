<?php
//arquivo responsavel por definir parametros de compra por item - minimo de itens, de quantos pode comprar, etc

include_once './includes/session_variables.inc.php';

$url_code = $_GET['code'] ?? '';
$userClass ??= '';

//listas estranhamente curtas pra quantidade de franquias que há
$purchaseStep1_5 = ['temakifry', 'sacho', 'iroha'];
$purchaseStep2 = ['meimei', 'manapoke', 'nakato'];
$seaweedPurchaseGroup = ['apollo', 'jun', 'matsuya', 'ragun'];


$productValues = getProductValues($url_code, $userClass, $purchaseStep1_5, $purchaseStep2);

function getProductValues($url_code, $userClass, $purchaseStep1_5, $purchaseStep2) {
    $defaultValues = [
        'PRODUCT_STEP' => '1',
        'PRODUCT_MIN' => '1',
        'PRODUCT_INIT_VALUE' => '1'
    ];

    if (isHashiAbertoPersonalizado($url_code)) {
        return setProductValues('3', $defaultValues);
    } elseif (isHashiPersonalizado($url_code)) {
        return [
            'PRODUCT_STEP' => '1.5', //alteração aqui, valor original '3'
            'PRODUCT_MIN' => '3',  // Mínimo de 3 unidades
            'PRODUCT_INIT_VALUE' => '3' // Valor inicial de 3 unidades
                ];
    } elseif (isRegularPurchase($url_code)) {
        return setProductValues('1', $defaultValues);
    } else {
        return getConditionalProductValues($userClass, $url_code, $purchaseStep1_5, $purchaseStep2, $defaultValues);
    }
}

function isHashiAbertoPersonalizado($url_code) {
    $productValues = [
        'PRODUCT_STEP' => '1.5', //alteração aqui, valor original '3'
        'PRODUCT_MIN' => '3',
        'PRODUCT_INIT_VALUE' => '3'
    ];

    return str_starts_with($url_code, 'hashi-aberto-personalizado');
}

function isRegularPurchase($url_code) {
    return !str_contains($url_code, 'personalizado') && !str_starts_with($url_code, 'hashi-granel');
}

function setProductValues($value, $defaultValues) {
    return array_fill_keys(array_keys($defaultValues), $value);
}

function getConditionalProductValues($userClass, $url_code, $purchaseStep1_5, $purchaseStep2, $defaultValues) {
    return match (true) {
        in_array($userClass, $purchaseStep1_5) => [
            'PRODUCT_STEP' => '1.5',
            'PRODUCT_MIN' => '3',
            'PRODUCT_INIT_VALUE' => '3'
        ],
        in_array($userClass, $purchaseStep2) && str_contains($url_code, 'kit-personalizado') => [
            'PRODUCT_STEP' => '1',
            'PRODUCT_MIN' => '2', // aqui eu alterei de 2 para 1 para que o cliente consiga selecionar de 1 em 1
            'PRODUCT_INIT_VALUE' => '2' // aqui eu alterei de 2 para 1 para que o cliente consiga selecionar de 1 em 1
        ],
        //mudei pra 1 pq estava deixando tudo que não era hashi-pers ou kit-pers com init e min de 3, sendo que outros produtos podem ser de 1 em 1 a compra
        default => setProductValues('1', $defaultValues)
    };
}

function hasAlgaSpecialProduct($userClass, $url_code, $seaweedPurchaseGroup) {
    return in_array($userClass, $seaweedPurchaseGroup) &&
           (str_contains($url_code, 'alga-blue-50') || str_contains($url_code, 'alga-gold-50'));
}

if (hasAlgaSpecialProduct($userClass, $url_code, $seaweedPurchaseGroup)) {
    $productValues = [
        'PRODUCT_STEP' => '10',
        'PRODUCT_MIN' => '20',
        'PRODUCT_INIT_VALUE' => '20'
    ];
}

function isHashiPersonalizado($url_code) {
    return str_contains($url_code, 'hashi-personalizado') || str_starts_with($url_code, 'hashi-granel');
}

//function to get if the product is a Cacau Finesse chopsticks
function isHashiFinesse($url_code) {
    return str_contains($url_code, 'rantyu-cacau-finesse-24') || str_starts_with($url_code, 'waribashi-cacau-finesse-24');
}
//

function isAlgaSpecial($url_code, $userClass, $seaweedPurchaseGroup) {
    return (str_contains($url_code, 'alga-blue') || str_contains($url_code, 'alga-gold')) &&
           in_array($userClass, $seaweedPurchaseGroup);
}