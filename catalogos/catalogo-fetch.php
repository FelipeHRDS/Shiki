<?php

session_start();

include_once '../includes/dbhc.inc.php';
include_once '../includes/session_variables.inc.php';
include_once './userClassManager.php';

function getCatalogData($userClass) {
    global $conn;

    $userClassMappings = include './userClassMappings.php';
    $catalogQuery = getTableName($userClass, $userClassMappings);

    $userId = (int) ($_SESSION['userId'] ?? 0);

    if ($userId <= 0) {
        http_response_code(401);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Usuário não identificado']);
        return;
    }

    $sql = "
        SELECT
            catalog.*,
            COALESCE(pp.preco, catalog.productPrice) AS precoFinal
        FROM (
            SELECT *
            FROM $catalogQuery
        ) AS catalog

        LEFT JOIN preco_personalizado pp
            ON pp.productCode = catalog.productCode
            AND pp.user_id = ?

        ORDER BY catalog.show_order
    ";

    $stmt = null;

    try {
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            throw new Exception($conn->error);
        }

        $stmt->bind_param('i', $userId);

        if (!$stmt->execute()) {
            throw new Exception($stmt->error);
        }

        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);

        foreach ($data as &$product) {
            $product['productPrice'] = $product['precoFinal'];
            unset($product['precoFinal']);
        }
        unset($product);

        header('Content-Type: application/json');
        echo json_encode($data);

    } catch (Exception $e) {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode([
            'error' => 'Erro ao consultar catálogo'
        ]);

    } finally {
        if ($stmt !== null) {
            $stmt->close();
        }

        $conn->close();
    }
}

if (isset($userClass)) {
    $catalogData = getCatalogData($userClass);
}