<?php session_start();
include_once './components/navbarContentMobile.php';
include_once './components/navbarContent.php';
include_once './includes/session_variables.inc.php';
include_once './includes/purchase_step.inc.php';
foreach($productValues as $key=>$value){define($key,$value);} 
?>

<!doctypehtml>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta content="width=device-width,initial-scale=1"name="viewport">
        <title>Detalhes do Produto</title>
        <link href="style-myshiki.css"rel="stylesheet">
        <link href="style-myshiki-responsivo.css"rel="stylesheet">
        <link href="../styles/styles.css"rel="stylesheet">
        <link href="../styles/responsivo.css"rel="stylesheet">
        <link href="../styles/nav-bar.css"rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"rel="stylesheet">
        <link href="./favicon/apple-touch-icon.png"rel="apple-touch-icon"sizes="180x180">
        <link href="./favicon/favicon-32x32.png"rel="icon"sizes="32x32"type="image/png">
        <link href="./favicon/favicon-16x16.png"rel="icon"sizes="16x16"type="image/png">
        <link href="./favicon/site.webmanifest"rel="manifest">
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    </head>
    <body>
        <header class="nav-bar">
            <div class="nav-bar__mobile__hamburger-menu">
                <a href="javascript:void(0)"class="hamburger-icon"aria-label="Abrir Menu">
                    <i class="fa fa-bars"style="font-size:x-large"></i>
                </a>
            </div>
            <div class="nav-bar__logo">
                <a href="./index.php">
                    <img alt="Logo Shiki"class="nav-bar__logo__image"src="../media/shiki-logo.jpg">
                </a>
            </div>
            <div class="__hide nav-bar__mobile__hamburger-menu__links">
                <?=$navbarContentMobile?>
            </div>
            <div class="nav-bar__menu">
                <a href="./index.php"class="nav-bar__menu__link__myshiki">
                    Página Principal
                </a><?=$navbarContent?>
            </div>
        </header>
        <main>
            <div class="crumbs__wrapper">
                <div id="crumbs__flowboxes__wrapper">
                    <a href="index.php">
                        <div class="crumbs__flowboxes__container-right-indentation">
                            Página Principal
                        </div>
                    </a>
                    <a href="meus-produtos.php">
                        <div class="crumbs__flowboxes__container-left-indentation crumbs__flowboxes__container-right-indentation">
                            Catálogo
                        </div>
                    </a>
                    <div class="crumbs__flowboxes__container-left-indentation crumbs__flowboxes__container-active"style="">
                        <span id="product-details-menu-code"style="">
                        </span>
                    </div>
                </div>
            </div>
            <div class="product-details__wrapper">
                <div class="product-details__title">
                    <h1 class="product-details__title__content">
                        <span id="product-details-name">
                        </span>
                            <?php if(isHashiFinesse($url_code)): ?>
                                <hr style="width:90%; height:5px; background-color:red; border:none; border-radious: 2px; margin:0 auto; margin-top:20px; margin-bottom:10px;">
                            <?php endif; ?>
                    </h1>
                </div>
                <div class="product-details__content">
                    <div class="product-details__image__wrapper">
                        <img data-action="zoom"id="product-details__image__content">
                    </div>
                    <div id="product-details__description-list__wrapper">
                        <ul id="product-details__description-list">
                            <li id="material-item">Material: 
                                <span id="product-details-material"></span>
                            </li>
                            <li>Linha: 
                                <span id="product-details-line"></span>
                            </li>
                            <li id="dimensions-item">Dimensões: 
                                <span id="product-details-dimensions"></span>
                            </li>
                            <?php if(isHashiPersonalizado($url_code)): ?>
                                <li style="font-size:1rem;color:light-gray">
                                    Este produto está disponível apenas em múltiplos de 1500 pares, sendo 3000 pares o mínimo.
                                </li>
                            <?php endif; ?>
                            <?php if(isAlgaSpecial($url_code,$userClass,$seaweedPurchaseGroup)): ?>
                                <li style="font-size:1rem;color:light-gray">
                                    Este produto está disponível apenas em múltiplos de 20 pacotes.
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                    <div class="product-details__purchase-info__wrapper">
                        <div id="purchase-info__wrapper">
                            <div class="purchase-info__price__wrapper">
                                <div>
                                    <span id="product-price"></span>
                                </div>
                            </div>
                            <div class="purchase-info__quantity__wrapper">
                                <form action="./includes/add_to_cart.inc.php"id="purchase-form"method="post">
                                    <input id="p_systemcode"name="p_systemcode"type="hidden">
                                    <input id="p_price"name="p_price"type="hidden">
                                    <input id="p_name"name="p_name"type="hidden">
                                    <input id="URLCodeParameter"name="URLCodeParameter"type="hidden"value="<?=$url_code?>"> 
                                    <input id="p_unit"name="p_unit"type="hidden">
                                    
                                    <label for="p_qtty">Quantidade:</label>
                                    <input id="p_qtty"name="p_qtty"type="number"value="<?=$productValues['PRODUCT_INIT_VALUE']?>"min="<?=$productValues['PRODUCT_MIN']?>"step="<?=$productValues['PRODUCT_STEP']?>"> 
                                    <button disabled id="submit-form-button"name="submit"type="submit">
                                        Adicionar ao Carrinho
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
            
        <footer class="footer">
            <a href="https://www.instagram.com/shikifoodservice/"class="footer__link"target="_blank">
                <img alt="Nosso Instagram"class="footer__image"src="./media/instagram-icon.jpg">
            </a>
        </footer>
            
        <script src="./catalogos/catalogo.js"type="module"></script>
        <script src="../script.js"type="module"></script>
        <script defer>
            const submitFormButton = document.getElementById('submit-form-button');
            submitFormButton.innerText = 'Aguarde...';
            document.getElementById("product-details__image__content").addEventListener('load', function() {
            submitFormButton.removeAttribute('disabled');
            submitFormButton.innerText = 'Adicionar ao carrinho';
            });
        </script>
        <script>function showCartAlert() {
            const logoutConfirmed = window.confirm('Ainda há itens no seu carrinho de compras. Tem certeza que deseja sair?');
            if (logoutConfirmed) {
                window.location.href="./includes/logout.inc.php";
            }
        }
        </script>
    
    </body>
</html>