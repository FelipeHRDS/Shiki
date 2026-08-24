<?php

if (isset($_POST["submit"])) {

    $uid = $_POST["useruid"];
    $password = $_POST["password"];

    require_once 'dbhc.inc.php';
    require_once 'functions.inc.php';

    if (emptyInputLogin($uid, $password) !== false) {
        header("location: ../login.php?error=emptyinput");
        exit();
    }

    loginUser($conn, $uid, $password);

} else {
    header("location: ../index.php");
    exit();
}
