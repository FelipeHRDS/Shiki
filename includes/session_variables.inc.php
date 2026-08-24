<?php

if (isset($_SESSION) && !empty($_SESSION)) {

    $userId = $_SESSION["userId"];
    $userUid = $_SESSION["useruid"];
    $userName = $_SESSION["name"];
    $userClass = $_SESSION["usersClass"];
    $userCnpj = $_SESSION["userCnpj"];
    $userUf = $_SESSION["userUF"];
    $userCity = $_SESSION["userCity"];
    $userSystemCode = $_SESSION["userSystemCode"];
    $userRepNo = $_SESSION["userRepNo"];
    $userType = $_SESSION["userType"];
    $userDispensaTransp = $_SESSION["dispensaTransp"];

    var_dump($_SESSION);

}