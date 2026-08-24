<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

include_once '../includes/dbh.inc.php';

function getUserData($conn, $userName) {
    
    $stmt = $conn->prepare("SELECT usersName, usersUid, usersCnpj, usersSystemCode, usersUF, usersCity, usersClass FROM users WHERE usersName = ?");
    $stmt->bind_param("s", $userName);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    } else {
        echo "No results found";
    }

    $stmt->close();
    return $data;
}

if (isset($_POST["users"][0])) {
    $userReplacementName = htmlspecialchars($_POST["users"][0]);

    $_SESSION['userReplacementName'] = $userReplacementName;

    $data = getUserData($conn, $_SESSION['userReplacementName']);

    if (!empty($data)) {
        $_SESSION['userReplacementFormalName'] = $data[0]["usersName"];
        $_SESSION['userReplacementPublicName'] = $data[0]["usersUid"];
        $_SESSION['userReplacementCnpj'] = $data[0]["usersCnpj"];
        $_SESSION['userReplacementSystemCode'] = $data[0]["usersSystemCode"];
        $_SESSION['userReplacementUF'] = $data[0]["usersUF"];
        $_SESSION['userReplacementCity'] = $data[0]["usersCity"];
        $_SESSION['userReplacementClass'] = $data[0]["usersClass"];
    }
}

$conn->close();

header('Location: ../shopping-cart.php');
exit();
