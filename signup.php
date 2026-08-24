<?php
    session_start();
    include_once('./includes/session_variables.inc.php');

    include './includes/auth.inc.php';
    include './includes/Authentication.php';
    
    $auth = new Authentication($privilegedClasses);
    
    $auth->redirectIfNotAuthorized($userClass, './index.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuário | MyShiki</title>
    <link rel="stylesheet" href="style-myshiki.css">
    <link rel="stylesheet" href="style-myshiki-meta.css">
    <link rel="stylesheet" href="../styles/styles.css">
    <link rel="stylesheet" href="../styles/nav-bar.css">
    <link rel="stylesheet" href="../styles/responsivo.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="apple-touch-icon" sizes="180x180" href="./favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="./favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="./favicon/favicon-16x16.png">
    <link rel="manifest" href="./favicon/site.webmanifest">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>
<body>

<header class="nav-bar">
        <div class="nav-bar__mobile__hamburger-menu">
            <a aria-label="Abrir Menu" href="javascript:void(0)" class="hamburger-icon">
                <i class="fa fa-bars" style="font-size: x-large;"></i>
            </a>
        </div>
        <div class="nav-bar__logo">
            <a href="./index.php">
                <img class="nav-bar__logo__image" src="../media/shiki-logo.jpg" alt="Logo Shiki">
            </a>
        </div>
        <div class="nav-bar__mobile__hamburger-menu__links __hide">
        <?php
            if (isset($_SESSION["useruid"])) {
            echo "<a href=\"meus-produtos.php\">Meus Produtos</a>";
            echo "<a href=\"profile.php\">Perfil</a>";
            echo "<a href=\"./includes/logout.inc.php\">Logout</a>";
            } else {
            echo "<a href=\"../catalogo/\">Produtos</a>";
            echo "<a href=\"signup.php\">Cadastro</a>";
            echo "<a href=\"login.php\">Login</a>";
            }
            ?>
        </div>
        <div class="nav-bar__menu">
            <a class="nav-bar__menu__link__myshiki" href="./index.php">
                Página Principal
            </a>
            <a class="nav-bar__menu__link__myshiki" href="../catalogo/">
                Produtos
            </a>
            <?php
                if (isset($_SESSION["useruid"])) {
                echo "<a class=\"nav-bar__menu__link__myshiki myprofile-emp\" href=\"profile.php\">Meu Perfil</a>";
                echo "<a class=\"nav-bar__menu__link__myshiki\" href=\"./includes/logout.inc.php\">Logout</a>";
                } else {
                echo "<a class=\"nav-bar__menu__link__myshiki\" href=\"./signup.php\">Cadastro</a>";
                echo "<a class=\"nav-bar__menu__link__myshiki\" href=\"./login.php\">Login</a>";
                }
            ?>
        </div>
    </header>

    <main>
    <div class="wrapper">
        <div class="login-container__title__wrapper">
            <h2 class="login-container__title">Cadastro MyShiki</h2>
        </div>
        <div class="login-container">
            <form id="loginForm" action="./includes/signup.inc.php" method="post">
                <div class="signup-grid-container">
                    <div class="signup-grid-item">
                        <div class="input-group">
                            <label class="label" for="username">Razão Social:</label>
                            <input type="text" id="username" name="username" autocomplete="off">
                        </div>
        
                        <div class="input-group">
                            <label class="label" for="useruid">Nome de Usuário:</label>
                            <input type="text" id="useruid" name="useruid" autocomplete="off">
                        </div>
        
                        <div class="input-group">
                            <label class="label" for="email">E-mail:</label>
                            <input type="text" id="email" name="email" autocomplete="off">
                        </div>
                        
                        <div class="input-group">
                            <label class="label" for="cnpj">CNPJ:</label>
                            <input type="text" id="cnpj" name="cnpj" autocomplete="off">
                        </div>
                        
                        <div class="input-group">
                            <label class="label" for="userclass">Classe:</label>
                            <select id="userclass" name="userclass">
                                <option value="akiryo">Akiryo</option>
                                <option value="atacado">Atacado</option>
                                <option value="apollo">Apollo</option>
                                <option value="chinainbox">China In Box</option>
                                <option value="choes">Choes</option>
                                <option value="cibjcr">Cibjcr</option>
                                <option value="dacho">Dacho</option>
                                <option value="gaya">Gaya</option>
                                <option value="gendaiairport">Gendai Airport</option>
                                <option value="gendaipremium">Gendai Premium</option>
                                <option value="hashiexpress">Hashi Express</option>
                                <option value="japesca">Japesca</option>
                                <option value="jojo">Jojo</option>
                                <option value="jun">Jun</option>
                                <option value="kenjinkai">Kenren</option>
                                <option value="koala">Koala</option>
                                <option value="kyotto">Kyotto</option>
                                <option value="lffoods">LF Foods</option>
                                <option value="liberdadestore">Liberdade Store</option>
                                <option value="maeda">Maeda</option>
                                <option value="mam">Mam</option>
                                <option value="manapoke">Manapoke</option>
                                <option value="maru">Maru</option>
                                <option value="matsuri">Matsuri</option>
                                <option value="matsuya">Matsuya</option>
                                <option value="meiji">Meiji</option>
                                <option value="meimei">Mei Mei</option>
                                <option value="minimok">Minimok</option>
                                <option value="mirai">Mirai</option>
                                <option value="mlymisse">MLY Misse</option>
                                <option value="nagairo">Nagairo</option>
                                <option value="nakato">Nakato</option>
                                <option value="nippongourmet">Nippon Gourmet</option>
                                <option value="ojapo">O Japo</option>
                                <option value="pabu">Pabu</option>
                                <option value="parada10">Parada 10</option>
                                <option value="resthana">Restaurante Hana</option>
                                <option value="restmatsuri">Restaurante Matsuri</option>
                                <option value="sacho">Sacho</option>
                                <option value="saka">Saka</option>
                                <option value="shark">Shark</option>
                                <option value="skamoto">S. Kamoto</option>
                                <option value="soho">Soho</option>
                                <option value="sushiaki">Sushiaki</option>
                                <option value="sushiemcasa">Sushi Em Casa</option>
                                <option value="sushiemcasadistro">Sushi Em Casa Distro</option>
                                <option value="sushito">Sushito</option>
                                <option value="tab1">Tabela 1</option>
                                <option value="tab13">Tabela 1 + 3</option>
                                <option value="taisho">Taisho</option>
                                <option value="temakeriaecia">Temakeria e Cia</option>
                                <option value="temakifry">Temaki Fry</option>
                                <option value="tillovando">Tillo/Vando</option>
                                <option value="tor">Tor</option>
                                <option value="tora">Tora</option>
                                <option value="vip">VIP</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="signup-grid-item">
                        <div class="input-group">
                            <label class="label" for="uf">UF:</label>
                            <select id="uf" name="uf">
                                <option value="AC">AC</option>
                                <option value="AL">AL</option>
                                <option value="AP">AP</option>
                                <option value="AM">AM</option>
                                <option value="BA">BA</option>
                                <option value="CE">CE</option>
                                <option value="DF">DF</option>
                                <option value="ES">ES</option>
                                <option value="GO">GO</option>
                                <option value="MA">MA</option>
                                <option value="MT">MT</option>
                                <option value="MS">MS</option>
                                <option value="MG">MG</option>
                                <option value="PA">PA</option>
                                <option value="PB">PB</option>
                                <option value="PR">PR</option>
                                <option value="PE">PE</option>
                                <option value="PI">PI</option>
                                <option value="RJ">RJ</option>
                                <option value="RN">RN</option>
                                <option value="RS">RS</option>
                                <option value="RO">RO</option>
                                <option value="RR">RR</option>
                                <option value="SC">SC</option>
                                <option value="SP">SP</option>
                                <option value="SE">SE</option>
                                <option value="TO">TO</option>
                            </select>
                        </div>
                        
                        <div class="input-group">
                            <label class="label" for="city">Cidade:</label>
                            <input type="text" id="city" name="city">
                        </div>
                        
                        <div class="input-group">
                            <label class="label" for="systemcode">Código:</label>
                            <input type="text" id="systemcode" name="systemcode">
                        </div>
                        
                        <div class="input-group">
                            <label class="label" for="salesperson">Vendedor:</label>
                            <input type="number" min="1" max="50" step="1" id="salesperson" name="salesperson">
                        </div>

                        <div class="input-group">
                            <label class="label" for="password">Senha:</label>
                            <input type="password" id="password" name="password" required>
                        </div>
        
                        <div class="input-group">
                            <label class="label" for="passwordrepeat">Repita a senha:</label>
                            <input type="password" id="passwordrepeat" name="passwordrepeat" required>
                        </div>
                    </div>
                </div>
                
                <button type="submit" name="submit">Enviar</button>
            </form>
        </div>
        
        <?php if (isset($_GET["error"])): ?>
            <?php
            $error = htmlspecialchars($_GET["error"]);
            $errorMessages = [
                "emptyinput" => "Preencha todos os campos",
                "invaliduid" => "Nome de usuário inválido",
                "invalidemail" => "Email inválido!",
                "invalidcnpj" => "CNPJ inválido!",
                "pwdnomatch" => "Senhas não conferem!",
                "stmtfailed" => "Algo deu errado!",
                "usernametaken" => "Esse nome de usuário já existe!",
                "none" => "Cadastro realizado com sucesso!"
            ];
            $errorMessage = $errorMessages[$error] ?? "Erro desconhecido";
            $errorClass = $error === "none" ? "success" : "error";
            $iconPath = $error === "none" ? "./media/check-circle.png" : "./media/x-circle.png";
            ?>
            <div class="notification notification-<?= $errorClass ?>">
                <div class="notification__body">
                    <img src="<?= $iconPath ?>" class="notification__icon">
                    <?= $errorMessage ?>
                </div>
                <div class="notification__progress notification__progress-<?= $errorClass ?>">
                </div>
            </div>
        <?php endif; ?>
</main>
<script type="module" src="../script.js"></script>
</body>
</html>