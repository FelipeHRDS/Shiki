<?php

session_start();

if (!isset($_SESSION['useruid']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ./meus-produtos.php");
    exit;
}

$user = $_SESSION['useruid'];
$counter = $_SESSION['counter'];
$customerCode = isset($_SESSION['userReplacementPublicName']) ? $_SESSION['userReplacementSystemCode'] : $_SESSION['userSystemCode'];
$cnpj = $_SESSION['userCnpj'];
$date = date('d.m.y');

$file = fopen("./pedidos/{$counter}-{$date}-{$customerCode}.txt", "w");
if ($file === false) {
    die("Error opening file");
}

foreach ($_SESSION['shopping_cart'] as $item) {
    $systemcode = $item['codigosistema'];
    $code = $item['codigo'];
    $name = $item['nome'];
    $price = $item['preco unitario'];
    $quantity = $item['quantidade'];
    $total = $item['preco total'];

    $dataString = "{$systemcode}|{$code}|{$name}|{$price}|{$quantity}|{$total}\n";

    fwrite($file, $dataString);
}

fclose($file);

$_SESSION['shopping_cart'] = [];
$userReplacementPublicName = $_SESSION['userReplacementPublicName'] ?? "";

error_reporting(E_ALL);
ini_set('display_errors', 1);

require './PHPMailer-master/src/Exception.php';
require './PHPMailer-master/src/PHPMailer.php';
require './PHPMailer-master/src/SMTP.php';

require __DIR__ . "/dompdf/autoload.inc.php";

use Dompdf\Dompdf;
use Dompdf\Options;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

$options = new Options;
$options->setChroot(__DIR__);
$options->setIsRemoteEnabled(false);
$options->setIsPhpEnabled(true);

$dompdf = new Dompdf($options);

$html = '<head><link rel="stylesheet" href="gutenberg.css"><link rel="stylesheet" href="https://unpkg.com/gutenberg-css@0.7/dist/themes/oldstyle.min.css"><style>
			table {
				width: 100%;
				page-break-before: avoid;
				page-break-inside: always;
			}
			
			#header-container p {
			    margin: 0;
			}
		</style></head>';
$html .= $_POST['submit-html'];

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');

$dompdf->render();

$fileatt = $dompdf->output();
    if ($_SESSION['userType'] !== 'rep') {
        $directory = "./myshiki_historico-pedidos/{$user}/";
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }
        $myfile = file_put_contents(
            "{$directory}{$user}-{$counter}-{$date}.pdf", 
            $fileatt
        );
    } else {
        $directory = "./myshiki_historico-pedidos/{$userReplacementPublicName}/";
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }
        $myfile = file_put_contents(
            "{$directory}{$userReplacementPublicName}-{$counter}-{$date}.pdf", 
            $fileatt
        );
    }
    
$mail = new PHPMailer(true);
$mail->SMTPDebug = SMTP::DEBUG_SERVER;
$mail->isSMTP();

$mail->SMTPAuth = true;
$mail->Port       = 25;
$mail->Host       = "localhost";
$mail->Username   = 'mkt@shiki.com.br';
$mail->Password   = $_SERVER['MAIL_PASSWORD'];

$mail->setFrom('mkt@shiki.com.br');
//$mail->addAddress('shiki.pedido@gmail.com');
$mail->addAddress('adm@shiki.com.br');

    if ($_SESSION['userType'] !== 'rep') {
        $mail->addAttachment("./myshiki_historico-pedidos/{$user}/{$user}-{$counter}-{$date}.pdf");
    } else {
        $mail->addAttachment("./myshiki_historico-pedidos/{$userReplacementPublicName}/{$userReplacementPublicName}-{$counter}-{$date}.pdf");
    }

    $mail->isHTML(true);
    $mail->Subject = 'Novo Pedido MyShiki - ' . $counter;
    $mail->Body    = 'Boa tarde, segue novo pedido feito atrav&eacute;s do portal MyShiki em anexo. <br /> Cliente: ' . $customerCode .  '<br /> CNPJ: ' . $cnpj . '<br /> N&uacute;mero do pedido: ' . $counter . '<br /> Atente-se ao nome de usu&aacute;rio, CNPJ, observa&ccedil;&otilde;es e quantidades dos produtos. Obrigado. <br /> <p style="color: gray";>Este e-mail e anexo foram gerados automaticamente atrav&eacute;s do portal MyShiki. Por favor n&atilde;o responda a este e-mail. Em caso de d&uacute;vidas, refira-se ao profissional de TI da sua empresa.</p>';
    $mail->AltBody = 'Boa tarde, segue novo pedido feito atraves do portal MyShiki em anexo. Atente-se ao nome de usuario, CNPJ, observacoes e quantidades dos produtos. Obrigado. <br /> <p style="color: gray";>Este e-mail e anexo foram gerados automaticamente atraves do portal MyShiki. Por favor nao responda a este e-mail. Em caso de duvidas, refira-se ao profissional de TI da sua empresa.';
try {
    header("location: ./email-sent.php");
    $mail->send();
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}