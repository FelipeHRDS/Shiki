<?php

/*
TABELAS ESPECIAIS
-----------------------------------------------------
MIX (CLUB EMB):
    H1, M1, E3, U1, A1
    
MIX2 (MISTA):
    H1, M1, E3, U1, A3
    
MIX3 (ALIM TAB3):
    H1, M1, E1, U1, A3
*/

include_once '../includes/dbhc.inc.php';

if (isset($_SESSION['userReplacementName']) && isset($_SESSION['userReplacementClass'])) {
    $_SESSION['usersClass'] = $_SESSION['userReplacementClass'];
}

function isUserClass($userClass, $userClasses) {
    return in_array($userClass, $userClasses);
}

function getTableName($userClass, $userClassMappings) {
    $tableMap = [
        'notHomologado1' => 'tabela_1',
        'notHomologado3' => 'tabela_3',
        'notHomologadoMix2' => 'tabela_mista',
        'notHomologadoMix3' => 'tabela_mista3',
        'tab1' => ['tabela_1', true],
        'tab3' => ['tabela_3', true],
        'tabMix' => ['tabela_clubemb', true],
        'tabMix2' => ['tabela_mista', true],
        'tabMix3' => ['tabela_mista3', true]
    ];

    foreach ($userClassMappings as $key => $classes) {
        if (isUserClass($userClass, $classes)) {
            $table = $tableMap[$key] ?? 'tabela_1';
            return is_array($table) ? getQuery($userClass, $table[0], $table[1]) : getQuery($userClass, $table);
        }
    }

    $specialCases = [
        'atacado' => 'tabela_3',
        'vando' => 'catalogovando',
        'admin' => 'tabela_1',
        'tab1' => 'tabela_1',
        'tabmix3' => 'tabela_mista3',
        'tabela_mista' => 'tabela_mista'
    ];

    return $specialCases[$userClass] ?? getQuery($userClass, 'tabela_1');
}

function getHashiQuery() {
    return "SELECT h.productName, productCategory, productCode, productImage, productDimensions, productType, productMaterial, productPrice, productSystemCode, show_order, productSubCategory, active, is_promotion, correspondentProductName, correspondentProductCode, is_homologado, productUnit
        FROM users u
        JOIN hashi_assoc ha ON u.id = ha.user_id
        JOIN hashi_personalizado h ON ha.hashi_id = h.id
        WHERE u.id = " . $_SESSION['userId'] . " ";
}

function getQuery($userClass, $tableName, $includeHomologado = false) {
    $hashiQuery = getHashiQuery();
    $catalogPart = $includeHomologado ? "UNION SELECT productName, productCategory, productCode, productImage, productDimensions, productType, productMaterial, productPrice, productSystemCode, show_order, productSubCategory, active, is_promotion, correspondentProductName, correspondentProductCode, is_homologado, productUnit FROM $userClass " : "";
    
    if ($userClass == 'tab13') {

        return "(
            $hashiQuery
            $catalogPart
            UNION 
            SELECT productName, productCategory, productCode, productImage, productDimensions, productType, productMaterial, productPrice, productSystemCode, show_order, productSubCategory, active, is_promotion, correspondentProductName, correspondentProductCode, is_homologado, productUnit
            FROM restaurante
        ) AS combined
        GROUP BY productCode";
    }
    
    return "(
        $hashiQuery
        $catalogPart
        UNION 
        SELECT productName, productCategory, productCode, productImage, productDimensions, productType, productMaterial, productPrice, productSystemCode, show_order, productSubCategory, active, is_promotion, correspondentProductName, correspondentProductCode, is_homologado, productUnit
        FROM $tableName
    ) AS combined
    GROUP BY productCode";
}

function queryDatabase($sql) {
    global $stmt;
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $data = $result->fetch_all(MYSQLI_ASSOC);
        header('Content-Type: application/json');
        echo json_encode($data);
    } else {
        echo json_encode(['error' => 'No data found']);
    }
}
