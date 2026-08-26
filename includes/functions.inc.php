<?php

function emptyInputSignup(...$args) {
    foreach ($args as $arg) {
        if (empty($arg)) {
            return true;
        }
    }
    return false;
}

function emptyInputLogin($uid, $password) {
    $result = (empty($uid) || empty($password)) ? true : false;
    return $result;
}

function invalidUid($uid) {
    $result = !preg_match("/^[a-zA-Z0-9]*$/", $uid) ? true : false;
    return $result;
}

function invalidCnpj($cnpj) {
    return !validateCNPJ($cnpj);
}

function invalidSystemCode($systemcode) {
    $result = (!preg_match("/^\d{2,6}$/", $systemcode)) ? true : false;
    return $result;
}

function invalidEmail($email) {
    $result = (!filter_var($email, FILTER_VALIDATE_EMAIL)) ? true : false;
    return $result;
}

function passwordMatch($password, $passwordRepeat) {
    $result = ($password !== $passwordRepeat) ? true : false;
    return $result;
}

function invalidProductQuantity($p_qtty) {
    $result = (empty($p_qtty) || $p_qtty < 1 || gettype($p_qtty) === 'integer' || gettype($p_qtty) === 'double') ? true : false;
    return $result;
}

//função userExiste?
function uidExists($conn, $uid, $cnpj) {
    $sql = "SELECT * FROM users WHERE usersUid = ? OR usersCnpj = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../signup.php?error=stmtfailed");
        exit();
    }
        mysqli_stmt_bind_param($stmt, "ss", $uid, $cnpj);
        mysqli_stmt_execute($stmt); //consulta é feita

        $resultData = mysqli_stmt_get_result($stmt); //resultado da consulta

        //se linha for encontrada com SELECT, retorna a linha, senão retorna falso
        if ($row = mysqli_fetch_assoc($resultData)) {
            return $row;
        } else {
            $result = false;
            return $result;
        }

        //fecha a declaração SQL, liberando recursos
        mysqli_stmt_close($stmt);
    }

function createUser($conn, $name, $email, $cnpj, $uid, $userclass, $uf, $city, $systemcode, $salesperson, $password) {
    $sql = "INSERT INTO users (usersName, usersEmail, usersCnpj, usersUid, usersClass, usersUF, usersCity, usersSystemCode, vendedor, usersPwd) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../signup.php?error=stmtfailed");
        exit();
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    mysqli_stmt_bind_param($stmt, "ssssssssss", $name, $email, $cnpj, $uid, $userclass, $uf, $city, $systemcode, $salesperson, $hashedPassword);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("location: ../signup.php?error=none");
}

function loginUser($conn, $uid, $password) {
    $uidExists = uidExists($conn, $uid, $uid);

    //função uidExists() funciona
    if ($uidExists === false) {
        header("location: ../login.php?error=wronglogin");
        exit();
    }

    $passwordHashed = $uidExists["usersPwd"];
    $checkPwd = password_verify($password, $passwordHashed);

    if ($checkPwd === false) {
        header("location: ../login.php?error=wronglogin");
        exit();
    } else if ($checkPwd === true) {
        session_start();
        $_SESSION["userId"] = $uidExists["id"];
        $_SESSION["useruid"] = $uidExists["usersUid"];
        $_SESSION["name"] = $uidExists["usersName"];
        $_SESSION["usersClass"] = $uidExists["usersClass"];
        $_SESSION["userCnpj"] = $uidExists["usersCnpj"];
        $_SESSION["userUF"] = $uidExists["usersUF"];
        $_SESSION["userCity"] = $uidExists["usersCity"];
        $_SESSION["userSystemCode"] = $uidExists["usersSystemCode"];
        $_SESSION["userRepNo"] = $uidExists["repNumber"];
        $_SESSION["userType"] = $uidExists["userType"];
        $_SESSION["dispensaTransp"] = $uidExists["dispensa_transp"];
        $_SESSION["vendedor"] = $uidExists["vendedor"];
        header("location: ../restore-session.php");
    }
}

function addToLoginLogs($loggeduser) {
                date_default_timezone_set('America/Sao_Paulo');
                $logFile = '../myshiki_historico-acessos/' . $loggeduser . '.txt';
                $logintime = date('Y-m-d H:i:s');
                file_put_contents($logFile, $logintime . PHP_EOL, FILE_APPEND);
}

function changePassword($conn, $uid, $oldPassword, $newPassword, $newPasswordRepeat) {
    //Error
    if (/*empty($uid) ||*/ empty($oldPassword) || empty($newPassword) || empty($newPasswordRepeat)) {
        header("location: ../change-password.php?error=emptyinput");
        exit();
    }

    //verificação feita
    if ($newPassword !== $newPasswordRepeat) {
        header("location: ../change-password.php?error=passwordsdontmatch");
        exit();
    }

    $uidExists = uidExists($conn, $uid, $uid);

    //Error
    if ($uidExists === false) {
        header("location: ../change-password.php?error=usernotfound");
        exit();
    }

    $passwordHashed = $uidExists["usersPwd"];
    $checkOldPwd = password_verify($oldPassword, $passwordHashed);

    //da errado aqui
    if ($checkOldPwd === false) {
        header("location: ../change-password.php?error=wronglogin");
        exit();
    }

    $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    $sql = "UPDATE users SET usersPwd = ? WHERE usersUid = ?";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../change-password.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ss", $hashedNewPassword, $uid);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("location: ../change-password.php?error=none");
}

function validateResetToken($conn, $token) {
    $currentDateTime = date('Y-m-d H:i:s');
    
    $sql = "SELECT * FROM users WHERE resetToken = ? AND resetExpiration > ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        return false;
    }

    mysqli_stmt_bind_param($stmt, "ss", $token, $currentDateTime);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)) {
        return true;
    } else {
        return false;
    }
}

# --------------------------------------------------
# VALIDATE CNPJ

function validateCnpj($cnpj) {
    if (strlen($cnpj) != 14) {
        return false;
    }

    $inscricao = substr($cnpj, 0, 12);
    $validacao = substr($cnpj, 12, 2);

    $pesos_primeiro_digito = [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
    $pesos_segundo_digito = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];

    $primeiro_digito_verificador = calcular_digito_verificador($inscricao, $pesos_primeiro_digito);
    $segundo_digito_verificador = calcular_digito_verificador($inscricao . $primeiro_digito_verificador, $pesos_segundo_digito);

    return $validacao === "{$primeiro_digito_verificador}{$segundo_digito_verificador}";
}

function calcular_digito_verificador($inscricao, $pesos) {
    $soma = 0;
    foreach (str_split($inscricao) as $i => $digito) {
        $soma += (int)$digito * $pesos[$i];
    }

    $resto = $soma % 11;
    return ($resto < 2) ? 0 : 11 - $resto;
}