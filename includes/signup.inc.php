<?php

if (isset($_POST["submit"])) {

    $name = $_POST["username"];
    $uid = $_POST["useruid"];
    $email = $_POST["email"];
    $cnpj = $_POST["cnpj"];
    $userclass = $_POST["userclass"];
    $uf = $_POST["uf"];
    $city = $_POST["city"];
    $systemcode = $_POST["systemcode"];
    $salesperson = $_POST["salesperson"];
    $password = $_POST["password"];
    $passwordRepeat = $_POST["passwordrepeat"];

    require_once './dbhc.inc.php';
    require_once './functions.inc.php';

    if (emptyInputSignup($name, $uid, $email, $cnpj, $userclass, $uf, $city, $systemcode, $password, $passwordRepeat) !== false) {
        header("location: ../signup.php?error=emptyinput");
        exit();
    }

    if (invalidUid($uid) !== false) {
        header("location: ../signup.php?error=invaliduid");
        exit();
    }
    
    if (invalidCnpj($cnpj) !== false) {
        header("location: ../signup.php?error=invalidcnpj");
        exit();
    }

    if (invalidEmail($email) !== false) {
        header("location: ../signup.php?error=invalidemail");
        exit();
    }
    
    if (invalidSystemCode($systemcode) !== false) {
        header("location: ../signup.php?error=invalidsyscode");
        exit();
    }

    if (passwordMatch($password, $passwordRepeat) !== false) {
        header("location: ../signup.php?error=pwdnomatch");
        exit();
    }

    if (uidExists($conn, $uid, $email) !== false) {
        header("location: ../signup.php?error=usernametaken");
        exit();
    }
    
    createUser($conn, $name, $email, $cnpj, $uid, $userclass, $uf, $city, $systemcode, $salesperson, $password);

    header("location: ../signup.php?error=none");
    exit();

} else {
    header("location: ../signup.php");
    exit();
}
