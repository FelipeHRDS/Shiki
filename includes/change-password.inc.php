<?php

require_once 'session_variables.inc.php';

if (isset($_POST["submit"])) {
    
    $uid = $userUid;
    
    echo $uid;
    var_dump($_SESSION);
    print_r($_SESSION);
    
    
    //variaveis locais <= variaveis do form (POST)
    $currentpassword = $_POST["currentpassword"];
    $newpassword = $_POST["newpassword"];
    $newpasswordrepeat = $_POST["newpasswordrepeat"];

    require_once 'dbhc.inc.php';
    require_once 'functions.inc.php';
    changePassword($conn, $uid, $currentpassword, $newpassword, $newpasswordrepeat);

} else {
    header("location: ../index.php");
}