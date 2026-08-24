<?php

session_start();

include_once '../includes/dbhc.inc.php';
include_once '../includes/session_variables.inc.php';
include_once './userClassManager.php';

function getCatalogData($userClass) {
  global $conn;
  
  $userClassMappings = include './userClassMappings.php';

  $tableName = getTableName($userClass, $userClassMappings);
  $sql = "SELECT * FROM $tableName ORDER BY show_order";
  $stmt = null;

  try {
    $stmt = $conn->prepare($sql);
    if ($stmt->execute()) {
      $result = $stmt->get_result();
      if ($result->num_rows > 0) {
        $data = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode($data);
      } else {
        return ['error' => 'No data found'];
      }
    } else {
      throw new Exception('Unable to execute query');
    }
  } catch (Exception $e) {
    return ['error' => $e->getMessage()];
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