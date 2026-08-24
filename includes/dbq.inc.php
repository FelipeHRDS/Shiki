<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once "./dbhc.inc.php";

if (isset($_GET['jsonFilePath'])) {
    $jsonFilePath = $_GET['jsonFilePath'];

    $sql = "SELECT * FROM catalogo$jsonFilePath WHERE 1;";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        header('Content-Type: application/json');

        echo json_encode($data);
    } else {
        echo json_encode([]);
    }
} else {
    echo json_encode(['error' => 'jsonFilePath parameter not provided']);
}
