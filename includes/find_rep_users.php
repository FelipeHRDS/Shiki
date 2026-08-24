<?php

include_once 'dbhc.inc.php';

function find_rep_users($conn, $repNumber) {
    $stmt = $conn->prepare("SELECT usersUid FROM users WHERE repNumber = ? AND userType = 'User'");
    $stmt->bind_param("i", $repNumber);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $users = [];
    while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
        $users[] = $row['usersUid'];
    }
    
    return $users;
}
