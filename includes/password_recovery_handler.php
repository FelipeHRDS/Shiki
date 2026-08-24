<?php

require_once 'dbh.inc.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

function forgotPassword($conn, $email, $cnpj) {
    
    require '../PHPMailer-master/src/Exception.php';
    require '../PHPMailer-master/src/PHPMailer.php';
    require '../PHPMailer-master/src/SMTP.php';
    
    if (empty($email) || empty($cnpj)) {
        header("location: ./forgot-password.php?error=emptyinput");
        exit();
    }

    $sql = "SELECT * FROM users WHERE usersEmail = ? AND usersCnpj = ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ./forgot-password.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ss", $email, $cnpj);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)) {
        $resetToken = bin2hex(random_bytes(32));

        $resetExpiration = date('Y-m-d H:i:s', strtotime('+1 hour'));
        $updateSql = "UPDATE users SET resetToken = ?, resetExpiration = ? WHERE usersEmail = ?";
        $updateStmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($updateStmt, $updateSql)) {
            header("location: ./forgot-password.php?error=stmtfailed");
            exit();
        }

        mysqli_stmt_bind_param($updateStmt, "sss", $resetToken, $resetExpiration, $email);
        mysqli_stmt_execute($updateStmt);

        $resetLink = "http://shiki.com.br/MyShiki/reset-password.php?token=$resetToken";

        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->SMTPDebug = true;

        $mail->SMTPAuth   = false;
        $mail->Port       = '25';
        $mail->Host       = "localhost";
        $mail->Username   = 'mkt@shiki.com.br';
        $mail->Password   = $_SERVER["MAIL_PASSWORD"];

        $mail->setFrom('mkt@shiki.com.br');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'Redefinir Senha';
            $mail->Body = "Clique aqui para redefinir sua senha: <a href=\"$resetLink\">$resetLink</a>";
        $mail->send();
        header("location: ../forgot-password.php?error=none");
            
    } else {
        header("location: ../forgot-password.php?error=usernotfound");
    }

    mysqli_stmt_close($stmt);
    mysqli_stmt_close($updateStmt);
}

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $cnpj = $_POST['cnpj'];

    forgotPassword($conn, $email, $cnpj);
}