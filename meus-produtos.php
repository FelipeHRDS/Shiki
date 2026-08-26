<?php
    session_start();
    include_once './components/navbarContent.php';
    include_once './components/navbarContentMobile.php';
    include_once './components/sideBar.php';
    include_once './includes/session_variables.inc.php';
    
    if (!isset($userUid)) {
        header("location: ./login.php");
    }
    
    if (isset($userType) && $userType == 'rep' && !isset($_SESSION['userReplacementName'])) {
        header('location: ./escolher-cliente.php');
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style-myshiki.css">
    <link rel="stylesheet" href="style-myshiki-responsivo.css">
    <link rel="stylesheet" href="../styles/styles.css">
    <link rel="stylesheet" href="../styles/responsivo.css">
    <link rel="stylesheet" href="../styles/nav-bar.css">
    <link rel="stylesheet" href="meus-produtos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="apple-touch-icon" sizes="180x180" href="./favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="./favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="./favicon/favicon-16x16.png">
    <link rel="manifest" href="./favicon/site.webmanifest">
    <style>
         .filter-btn {
         background-color: #9f34db;
         color: #fff;
         padding: 0.5rem 1rem;
         border: none;
         border-radius: 1rem;
         cursor: pointer;
         font-size: 1rem;
         margin-top: 0.8rem;
         display: none;
         }
         .filter-btn:hover {
         opacity: 0.7;
         }
         .filter-popup {
         display: none;
         position: fixed;
         top: 0;
         left: 0;
         width: 100%;
         height: 100%;
         background-color: rgba(0, 0, 0, 0.5);
         justify-content: center;
         align-items: center;
         z-index: 1;
         overflow: scroll;
         opacity: 0;
         transition: opacity 0.3s ease-in-out;
         }
         .filter-content {
         background-color: #fff;
         padding: 1.25rem;
         border-radius: 10px;
         width: 80%;
         text-align: center;
         font-size: 0.8rem;
         width: 85%;
         display: flex;
         flex-flow: column nowrap;
         }
         #close-btn {
         background-color: #0b2646;
         color: #fff;
         padding: 10px 15px;
         border: none;
         border-radius: 5px;
         cursor: pointer;
         margin-top: 10px;
         align-self: center;
         }
         #close-btn:hover {
         opacity: 0.7;
         }
         #return-btn, #return-sub-btn {
         background-color: #174c8c;
         color: #fff;
         padding: 10px 15px;
         border: none;
         border-radius: 5px;
         cursor: pointer;
         margin-top: 10px;
         display: none;
         align-self: center;
         }
         #return-btn:hover, #return-sub-btn:hover {
         opacity: 0.7;
         }
         .filter-subcategory {
         display: none;
         width: auto;
         grid-template-columns: auto auto auto;
         }
         .filter-subsubcategory-menu {
         display: none;
         width: auto;
         grid-template-columns: auto;
         }
         #filter-content-button-list {
         display: flex;
         flex-flow: column nowrap;
         align-items: center;
         }
         button.filter-content-button {
         background: none;
         border: none;
         border-bottom: 1px solid #9f34db;
         cursor: pointer;
         width: 100%;
         padding: 0.6rem;
         padding-top: 1rem;
         color: #9f34db;
         font: var(--font-regular);
         justify-content: center;
         }
         button.filter-content-button:hover {
         background-color: #9f34db;
         color: #fff;
         }
      </style>
      <style scoped>
         @media screen and (max-width: 798px) {
         #catalog__side-bar__buttons__wrapper {
         display: none;
         }
         .filter-btn {
         display: flex;
         align-self: center;
         }
         .catalog__side-bar__search__wrapper {
         display: flex;
         flex-flow: column;
         }
         }
      </style>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <title>Meus Produtos | MyShiki</title>
</head>
<body>
<!--Navigation menu on top of the page-->
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
            <?php echo $navbarContentMobile; ?>
        </div>
        <div class="nav-bar__menu">
            <a class="nav-bar__menu__link__myshiki" href="./index.php">
                Página Principal
            </a>
            <?php echo $navbarContent; ?>
        </div>
    </header>

    <!--Main content of the page-->
    <main>

        <!--Section containing the page title-->
        <section>
            <div class="banner__catalog" style="background-image: url('../media/shiki-background-cut.jpg'); filter: hue-rotate(270deg);">
                <div class="banner__catalog__text__wrapper">
                    <h1>NOSSOS PRODUTOS</h1>
                </div>
            </div>
        </section>

        <!--Catalog with search function and category selection buttons controlled by JS-->
        <section>
            <h3 class="article__title" id="nosso-catalogo">Nosso Catálogo</h3>
            <article class="catalog__section">
            <div class="catalog__wrapper">
                <?= $sideBar ?>
                    <button class="filter-btn" onclick="toggleFilterPopup()"><i class="fa fa-filter" style="margin-right: 0.4rem"></i> Filtrar produtos</button>
                    </div>
                </div>
                <div class="catalog-products" id="catalog__grid">
                    <p class="__hide" id="catalog__none-found-alert">Nenhum item encontrado.</p>
                </div>
                <div class="scroll-to-top__button" id="scroll-to-top__button" onclick="scrollToTop()">Voltar ao topo &#x25B2;</div>
            </div>
            <div id="filterPopup" class="filter-popup">
                        <div class="filter-content">
                           <div id="filter-content-button-list">
                              <button data-filter="Hashi" class="filter-content-button filter-content-button-main" onclick="toggleFilterSubcategory('Hashi');">Hashi</button>
                              <div class="filter-subcategory filter-subcategory-menu" id="filter-subcategory-Hashi">
                                 <button class="filter-content-button" onclick="filterElements('customizable'); toggleFilterSubcategory('Hashi'); toggleFilterPopup(); closeAllSubMenus();">Personalizado</button>
                                 <button class="filter-content-button" onclick="filterElements('Geral-Hashi'); toggleFilterSubcategory('Hashi'); toggleFilterPopup(); closeAllSubMenus();">Padrão</button>
                                 <button class="filter-content-button" onclick="filterElements('Especiais-Hashi'); toggleFilterSubcategory('Hashi'); toggleFilterPopup(); closeAllSubMenus();">Especiais</button>
                              </div>
                              <button class="filter-content-button filter-content-button-main" onclick="filterElements('Alimento'); toggleFilterPopup(); closeAllSubMenus();">Alimentos</button>
                              <button class="filter-content-button filter-content-button-main" onclick="toggleFilterSubcategory('Embalagem');">Embalagens</button>
                              <div class="filter-subcategory filter-subcategory-menu" id="filter-subcategory-Embalagem">
                                 <button class="filter-content-button" onclick="filterElements('LinhaSK'); toggleFilterSubcategory('Embalagem'); toggleFilterPopup(); closeAllSubMenus();">Linha SK</button>
                                 <button class="filter-content-button" onclick="filterElements('SushiAntiFog'); toggleFilterSubcategory('Embalagem'); toggleFilterPopup(); closeAllSubMenus();">Linha AntiFog</button>
                                 <button class="filter-content-button" onclick="filterElements('BoxPapel'); toggleFilterSubcategory('Embalagem'); toggleFilterPopup(); closeAllSubMenus();">Box Papel</button>
                                 <button class="filter-content-button" onclick="filterElements('Teishoku'); toggleFilterSubcategory('Embalagem'); toggleFilterPopup(); closeAllSubMenus();">Teishoku</button>
                                 <button class="filter-content-button" onclick="filterElements('PoteMolho'); toggleFilterSubcategory('Embalagem'); toggleFilterPopup(); closeAllSubMenus();">Potes para Molho e Sobremesa</button>
                                 <button class="filter-content-button" onclick="filterElements('BowlBranco'); toggleFilterSubcategory('Embalagem'); toggleFilterPopup(); closeAllSubMenus();">Bowl Branco</button>
                                 <button class="filter-content-button" onclick="filterElements('BowlKraft'); toggleFilterSubcategory('Embalagem'); toggleFilterPopup(); closeAllSubMenus();">Bowl Kraft</button>
                                 <button class="filter-content-button" onclick="filterElements('BowlPS'); toggleFilterSubcategory('Embalagem'); toggleFilterPopup(); closeAllSubMenus();">Bowl PS</button>
                                 <button class="filter-content-button" onclick="filterElements('BowlPP'); toggleFilterSubcategory('Embalagem'); toggleFilterPopup(); closeAllSubMenus();">Bowl PP</button>
                              </div>
                              <button class="filter-content-button filter-content-button-main" onclick="toggleFilterSubcategory('Melamina');">Melaminas</button>
                              <div class="filter-subcategory filter-subcategory-menu" id="filter-subcategory-Melamina">
                                 <button class="filter-content-button filter-content-button-has-sub" id="has-sub-Turquesa" onclick="toggleFilterSubsubcategory('Turquesa')">Turquesa</button>
                                 <div class="filter-subsubcategory-menu" id="filter-subsubcategory-menu-Turquesa">
                                    <button class="filter-content-button" onclick="filterElements('Turquesa'); toggleFilterPopup();closeAllSubSubMenus();">Todos</button>
                                    <button class="filter-content-button" onclick="filterSubCat('Turquesa', 'Pires'); toggleFilterPopup();closeAllSubSubMenus();">Pires</button>
                                    <button class="filter-content-button" onclick="filterSubCat('Turquesa', 'Prato'); toggleFilterPopup();closeAllSubSubMenus();">Pratos</button>
                                    <button class="filter-content-button" onclick="filterSubCat('Turquesa', 'Tigela'); toggleFilterPopup();closeAllSubSubMenus();">Tigelas</button>
                                 </div>
                                 <button class="filter-content-button filter-content-button-has-sub" id="has-sub-Oceano" onclick="toggleFilterSubsubcategory('Oceano')">Oceano</button>
                                 <div class="filter-subsubcategory-menu" id="filter-subsubcategory-menu-Oceano">
                                    <button class="filter-content-button" onclick="filterElements('Oceano'); toggleFilterPopup();closeAllSubSubMenus();">Todos</button>
                                    <button class="filter-content-button" onclick="filterSubCat('Oceano', 'Pires'); toggleFilterPopup();closeAllSubSubMenus();">Pires</button>
                                    <button class="filter-content-button" onclick="filterSubCat('Oceano', 'Prato'); toggleFilterPopup();closeAllSubSubMenus();">Pratos</button>
                                    <button class="filter-content-button" onclick="filterSubCat('Oceano', 'Tigela'); toggleFilterPopup();closeAllSubSubMenus();">Tigelas</button>
                                 </div>
                                 <button class="filter-content-button filter-content-button-has-sub" id="has-sub-Midori" onclick="toggleFilterSubsubcategory('Midori')">Midori</button>
                                 <div class="filter-subsubcategory-menu" id="filter-subsubcategory-menu-Midori">
                                    <button class="filter-content-button" onclick="filterElements('Midori'); toggleFilterPopup();closeAllSubSubMenus();">Todos</button>
                                    <button class="filter-content-button" onclick="filterSubCat('Midori', 'Pires'); toggleFilterPopup();closeAllSubSubMenus();">Pires</button>
                                    <button class="filter-content-button" onclick="filterSubCat('Midori', 'Prato'); toggleFilterPopup();closeAllSubSubMenus();">Pratos</button>
                                    <button class="filter-content-button" onclick="filterSubCat('Midori', 'Tigela'); toggleFilterPopup();closeAllSubSubMenus();">Tigelas</button>
                                 </div>
                                 <button class="filter-content-button filter-content-button-has-sub" id="has-sub-Nuage" onclick="toggleFilterSubsubcategory('Nuage')">Nuage</button>
                                 <div class="filter-subsubcategory-menu" id="filter-subsubcategory-menu-Nuage">
                                    <button class="filter-content-button" onclick="filterElements('Nuage'); toggleFilterPopup();closeAllSubSubMenus();">Todos</button>
                                    <button class="filter-content-button" onclick="filterSubCat('Nuage', 'Prato'); toggleFilterPopup();closeAllSubSubMenus();">Pratos</button>
                                    <button class="filter-content-button" onclick="filterSubCat('Nuage', 'Tigela'); toggleFilterPopup();closeAllSubSubMenus();">Tigelas</button>
                                 </div>
                                 <button class="filter-content-button filter-content-button-has-sub" id="has-sub-DM" onclick="toggleFilterSubsubcategory('DM')">DM</button>
                                 <div class="filter-subsubcategory-menu" id="filter-subsubcategory-menu-DM">
                                    <button class="filter-content-button" onclick="filterElements('DM'); toggleFilterPopup();closeAllSubSubMenus();">Todos</button>
                                    <button class="filter-content-button" onclick="filterSubCat('DM', 'Pires'); toggleFilterPopup();closeAllSubSubMenus();">Pires</button>
                                    <button class="filter-content-button" onclick="filterSubCat('DM', 'PratoDiv'); toggleFilterPopup();closeAllSubSubMenus();">Pratos com Divisória</button>
                                    <button class="filter-content-button" onclick="filterSubCat('DM', 'Prato'); toggleFilterPopup();closeAllSubSubMenus();">Pratos</button>
                                    <button class="filter-content-button" onclick="filterSubCat('DM', 'Barco'); toggleFilterPopup();closeAllSubSubMenus();">Barcos</button>
                                    <button class="filter-content-button" onclick="filterSubCat('DM', 'Tigela'); toggleFilterPopup();closeAllSubSubMenus();">Tigelas</button>
                                    <button class="filter-content-button" onclick="filterSubCat('DM', 'Outro'); toggleFilterPopup();closeAllSubSubMenus();">Outros</button>
                                 </div>
                                 <button class="filter-content-button filter-content-button-has-sub" id="has-sub-BC" onclick="toggleFilterSubsubcategory('BC')">BC</button>
                                 <div class="filter-subsubcategory-menu" id="filter-subsubcategory-menu-BC">
                                    <button class="filter-content-button" onclick="filterElements('BC'); toggleFilterPopup();closeAllSubSubMenus();">Todos</button>
                                    <button class="filter-content-button" onclick="filterSubCat('BC', 'Pires'); toggleFilterPopup();closeAllSubSubMenus();">Pires</button>
                                    <button class="filter-content-button" onclick="filterSubCat('BC', 'PratoDiv'); toggleFilterPopup();closeAllSubSubMenus();">Pratos com Divisória</button>
                                    <button class="filter-content-button" onclick="filterSubCat('BC', 'Prato'); toggleFilterPopup();closeAllSubSubMenus();">Pratos</button>
                                    <button class="filter-content-button" onclick="filterSubCat('BC', 'Barco'); toggleFilterPopup();closeAllSubSubMenus();">Barcos</button>
                                    <button class="filter-content-button" onclick="filterSubCat('BC', 'Tigela'); toggleFilterPopup();closeAllSubSubMenus();">Tigelas</button>
                                    <button class="filter-content-button" onclick="filterSubCat('BC', 'Outro'); toggleFilterPopup();closeAllSubSubMenus();">Outros</button>
                                 </div>
                                 <button class="filter-content-button filter-content-button-has-sub" id="has-sub-BR" onclick="toggleFilterSubsubcategory('BR')">BR</button>
                                 <div class="filter-subsubcategory-menu" id="filter-subsubcategory-menu-BR">
                                    <button class="filter-content-button" onclick="filterElements('BR'); toggleFilterPopup();closeAllSubSubMenus();">Todos</button>
                                    <button class="filter-content-button" onclick="filterSubCat('BR', 'Pires'); toggleFilterPopup();closeAllSubSubMenus();">Pires</button>
                                    <button class="filter-content-button" onclick="filterSubCat('BR', 'Prato'); toggleFilterPopup();closeAllSubSubMenus();">Pratos</button>
                                    <button class="filter-content-button" onclick="filterSubCat('BR', 'Tigela'); toggleFilterPopup();closeAllSubSubMenus();">Tigelas</button>
                                    <button class="filter-content-button" onclick="filterSubCat('BR', 'Outro'); toggleFilterPopup();closeAllSubSubMenus();">Outros</button>
                                 </div>
                                 <button class="filter-content-button filter-content-button-has-sub" id="has-sub-RD" onclick="toggleFilterSubsubcategory('RD')">RD</button>
                                 <div class="filter-subsubcategory-menu" id="filter-subsubcategory-menu-RD">
                                    <button class="filter-content-button" onclick="filterElements('RD'); toggleFilterPopup();closeAllSubSubMenus();">Todos</button>
                                    <button class="filter-content-button" onclick="filterSubCat('RD', 'Pires'); toggleFilterPopup();closeAllSubSubMenus();">Pires</button>
                                    <button class="filter-content-button" onclick="filterSubCat('RD', 'PratoDiv'); toggleFilterPopup();closeAllSubSubMenus();">Pratos com Divisória</button>
                                    <button class="filter-content-button" onclick="filterSubCat('RD', 'Prato'); toggleFilterPopup();closeAllSubSubMenus();">Pratos</button>
                                    <button class="filter-content-button" onclick="filterSubCat('RD', 'Barco'); toggleFilterPopup();closeAllSubSubMenus();">Barcos</button>
                                    <button class="filter-content-button" onclick="filterSubCat('RD', 'Tigela'); toggleFilterPopup();closeAllSubSubMenus();">Tigelas</button>
                                    <button class="filter-content-button" onclick="filterSubCat('RD', 'Outro'); toggleFilterPopup();closeAllSubSubMenus();">Outros</button>
                                 </div>
                                 <button class="filter-content-button filter-content-button-has-sub" id="has-sub-N" onclick="toggleFilterSubsubcategory('N')">N</button>
                                 <div class="filter-subsubcategory-menu" id="filter-subsubcategory-menu-N">
                                    <button class="filter-content-button" onclick="filterElements('N'); toggleFilterPopup();closeAllSubSubMenus();">Todos</button>
                                    <button class="filter-content-button" onclick="filterSubCat('N', 'Pires'); toggleFilterPopup();closeAllSubSubMenus();">Pires</button>
                                    <button class="filter-content-button" onclick="filterSubCat('N', 'PratoDiv'); toggleFilterPopup();closeAllSubSubMenus();">Pratos com Divisória</button>
                                    <button class="filter-content-button" onclick="filterSubCat('N', 'Prato'); toggleFilterPopup();closeAllSubSubMenus();">Pratos</button>
                                    <button class="filter-content-button" onclick="filterSubCat('N', 'Barco'); toggleFilterPopup();closeAllSubSubMenus();">Barcos</button>
                                    <button class="filter-content-button" onclick="filterSubCat('N', 'Tigela'); toggleFilterPopup();closeAllSubSubMenus();">Tigelas</button>
                                    <button class="filter-content-button" onclick="filterSubCat('N', 'Outro'); toggleFilterPopup();closeAllSubSubMenus();">Outros</button>
                                 </div>
                                 <button class="filter-content-button filter-content-button-has-sub" id="has-sub-F" onclick="toggleFilterSubsubcategory('F')">F</button>
                                 <div class="filter-subsubcategory-menu" id="filter-subsubcategory-menu-F">
                                    <button class="filter-content-button" onclick="filterElements('F'); toggleFilterPopup();closeAllSubSubMenus();">Todos</button>
                                    <button class="filter-content-button" onclick="filterSubCat('F', 'Pires'); toggleFilterPopup();closeAllSubSubMenus();">Pires</button>
                                    <button class="filter-content-button" onclick="filterSubCat('F', 'PratoDiv'); toggleFilterPopup();closeAllSubSubMenus();">Pratos com Divisória</button>
                                    <button class="filter-content-button" onclick="filterSubCat('F', 'Prato'); toggleFilterPopup();closeAllSubSubMenus();">Pratos</button>
                                    <button class="filter-content-button" onclick="filterSubCat('F', 'Barco'); toggleFilterPopup();closeAllSubSubMenus();">Barcos</button>
                                    <button class="filter-content-button" onclick="filterSubCat('F', 'Tigela'); toggleFilterPopup();closeAllSubSubMenus();">Tigelas</button>
                                    <button class="filter-content-button" onclick="filterSubCat('F', 'Outro'); toggleFilterPopup();closeAllSubSubMenus();">Outros</button>
                                 </div>
                                 <button class="filter-content-button filter-content-button-has-sub" id="has-sub-PF-Hana" onclick="toggleFilterSubsubcategory('PF-Hana')">PF-Hana</button>
                                 <div class="filter-subsubcategory-menu" id="filter-subsubcategory-menu-PF-Hana">
                                    <button class="filter-content-button" onclick="filterElements('PF-Hana'); toggleFilterPopup();closeAllSubSubMenus();">Todos</button>
                                    <button class="filter-content-button" onclick="filterSubCat('PF-Hana', 'Prato'); toggleFilterPopup();closeAllSubSubMenus();">Pratos</button>
                                    <button class="filter-content-button" onclick="filterSubCat('PF-Hana', 'PratoDiv'); toggleFilterPopup();closeAllSubSubMenus();">Pratos com Divisória</button>
                                    <button class="filter-content-button" onclick="filterSubCat('PF-Hana', 'Tigela'); toggleFilterPopup();closeAllSubSubMenus();">Tigelas</button>
                                 </div>
                                 <button class="filter-content-button filter-content-button-has-sub" id="has-sub-PF-Sólido" onclick="toggleFilterSubsubcategory('PF-Sólido')">PF-Sólido</button>
                                 <div class="filter-subsubcategory-menu" id="filter-subsubcategory-menu-PF-Sólido">
                                    <button class="filter-content-button" onclick="filterElements('PF-Sólido'); toggleFilterPopup();closeAllSubSubMenus();">Todos</button>
                                    <button class="filter-content-button" onclick="filterSubCat('PF-Sólido', 'Pires'); toggleFilterPopup();closeAllSubSubMenus();">Pires</button>
                                    <button class="filter-content-button" onclick="filterSubCat('PF-Sólido', 'PratoDiv'); toggleFilterPopup();closeAllSubSubMenus();">Pratos com Divisória</button>
                                    <button class="filter-content-button" onclick="filterSubCat('PF-Sólido', 'Prato'); toggleFilterPopup();closeAllSubSubMenus();">Pratos</button>
                                    <button class="filter-content-button" onclick="filterSubCat('PF-Sólido', 'Tigela'); toggleFilterPopup();closeAllSubSubMenus();">Tigelas</button>
                                    <button class="filter-content-button" onclick="filterSubCat('PF-Sólido', 'Outro'); toggleFilterPopup();closeAllSubSubMenus();">Outros</button>
                                 </div>
                                 <button class="filter-content-button filter-content-button-has-sub" id="has-sub-Especiais" onclick="toggleFilterSubsubcategory('Especiais')">Especiais</button>
                                 <div class="filter-subsubcategory-menu" id="filter-subsubcategory-menu-Especiais">
                                    <button class="filter-content-button" onclick="filterElements('Especiais'); toggleFilterPopup();closeAllSubSubMenus();">Todos</button>
                                    <button class="filter-content-button" onclick="filterSubCat('Especiais', 'Prato'); toggleFilterPopup();closeAllSubSubMenus();">Pratos</button>
                                    <button class="filter-content-button" onclick="filterSubCat('Especiais', 'Tigela'); toggleFilterPopup();closeAllSubSubMenus();">Tigelas</button>
                                 </div>
                              </div>
                              <button class="filter-content-button filter-content-button-main" onclick="toggleFilterSubcategory('Utilidade');">Utilidades</button>
                              <div class="filter-subcategory filter-subcategory-menu" id="filter-subcategory-Utilidade">
                                 <button class="filter-content-button" onclick="filterElements('Professionals'); toggleFilterSubcategory('Utilidade'); toggleFilterPopup(); closeAllSubMenus();">Professionals</button>
                                 <button class="filter-content-button" onclick="filterElements('Kitchen'); toggleFilterSubcategory('Utilidade'); toggleFilterPopup(); closeAllSubMenus();">Kitchen</button>
                                 <button class="filter-content-button" onclick="filterElements('Ecologia'); toggleFilterSubcategory('Utilidade'); toggleFilterPopup(); closeAllSubMenus();">Ecologia</button>
                                 <button class="filter-content-button" onclick="filterElements('Frigideira'); toggleFilterSubcategory('Utilidade'); toggleFilterPopup(); closeAllSubMenus();">Frigideiras</button>
                                 <button class="filter-content-button" onclick="filterElements('Chá'); toggleFilterSubcategory('Utilidade'); toggleFilterPopup(); closeAllSubMenus();">Chá</button>
                                 <button class="filter-content-button" onclick="filterElements('Kids&Fun'); toggleFilterSubcategory('Utilidade'); toggleFilterPopup(); closeAllSubMenus();">Kids &amp; Fun</button>
                                 <button class="filter-content-button" onclick="filterElements('Life'); toggleFilterSubcategory('Utilidade'); toggleFilterPopup(); closeAllSubMenus();">Life</button>
                                 <button class="filter-content-button" onclick="filterElements('PoteMantimento'); toggleFilterSubcategory('Utilidade'); toggleFilterPopup(); closeAllSubMenus();">Potes de Mantimento & Lunch Box</button>
                                 <button class="filter-content-button" onclick="filterElements('Garrafa'); toggleFilterSubcategory('Utilidade'); toggleFilterPopup(); closeAllSubMenus();">Garrafas Reutilizáveis</button>
                                 <button class="filter-content-button" onclick="filterElements('Canudo'); toggleFilterSubcategory('Utilidade'); toggleFilterPopup(); closeAllSubMenus();">Canudos</button>
                                 <button class="filter-content-button" onclick="filterElements('Hotelaria'); toggleFilterSubcategory('Utilidade'); toggleFilterPopup(); closeAllSubMenus();">Hotelaria</button>
                              </div>
                              <button class="filter-content-button filter-content-button-main" onclick="filterElements('Espeto'); toggleFilterPopup(); closeAllSubMenus();">Espetos</button>
                           </div>
                           <button id="close-btn"><i class="fa fa-close" style="margin-right: 0.4rem"></i> Fechar</button>
                           <button id="return-btn"><i class="fa fa-arrow-left" style="margin-right: 0.4rem"></i> Voltar</button>
                           <button id="return-sub-btn"><i class="fa fa-arrow-left" style="margin-right: 0.4rem"></i> Voltar</button>
                        </div>
                     </div>
                  </div>
            </article>
        </section>
        <section>
            <a class="whatsapp__bubble" href="https://wa.link/n89p0w" target="_blank">
                <img src="../media/whatsapp-bubble.png">
            </a>
        </section>
    </main>

    <!--Footer containing Instagram link-->
    <footer class="footer">
        <a class="footer__link" href="https://www.instagram.com/shikifoodservice/" target="_blank"><img class="footer__image" src="./media/instagram-icon.jpg" alt="Nosso Instagram"></a>
    </footer>

    <script type="module" src="../script.js"></script>
    
    <?php
        if (isset($_GET["status"])) {
            if ($_GET["status"] == "itemadded") {
                echo "<div class=\"notification notification-success\"><div class=\"notification__body\"><img src=\"./media/check-circle.png\" class=\"notification__icon\">Item adicionado ao carrinho!</div><div class=\"notification__progress notification__progress-success\"></div></div>";
            }}
    ?>
    <script>
        const profiles = [];
        const catalogGrid = document.getElementById("catalog__grid");
    </script>
    <script type="module" src="./catalogos/catalogo.js"></script>
    <script src="../catalogo/menuHandlers.js"></script>
    <script src="./catalogos/searchHandlers.js"></script>

<script>
activateButton = (element) => {
    let buttons = document.querySelectorAll('.catalog__side-bar__buttons__item');
    buttons.forEach((button) => {
        button.classList.remove('catalog__side-bar__buttons__panel-active-item');
    })
    element.classList.add('catalog__side-bar__buttons__panel-active-item');
}

    activateSubcategoryButton = (element) => {
        let buttons = document.querySelectorAll('.catalog__side-bar__buttons__subcategory__toggle');
        buttons.forEach((button) => {
            button.classList.toggle('catalog__side-bar__buttons__subcategory__toggle-active');
        })
    }

        let scrollToTopButton = document.getElementById('scroll-to-top__button');
        
        function checkScroll() {
            if (window.scrollY >= 2000) {
                scrollToTopButton.style.display = "block";
            } else {
                scrollToTopButton.style.display = "none";
            }
        }
        
        function scrollToTop() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        window.addEventListener("scroll", checkScroll);
function showCartAlert() {
            const logoutConfirmed = window.confirm('Ainda há itens no seu carrinho de compras. Tem certeza que deseja sair?');
            if (logoutConfirmed) {
                window.location.href="./includes/logout.inc.php";
            }
        }
function toggleFilterPopup() {
             let filterPopup = document.getElementById("filterPopup");
             let mainSubcategoryButtons = document.querySelectorAll(".filter-content-button-main");
             mainSubcategoryButtons.forEach((button) => {
                button.style.display = "flex"; 
             });
             
             filterPopup.style.display = filterPopup.style.display === "flex" ? "none" : "flex";
             filterPopup.style.opacity = filterPopup.style.opacity === "1" ? "0" : "1";
             
             document.getElementById("return-btn").style.display = "none";
             document.getElementById("close-btn").style.display = "flex";
             document.getElementById("return-sub-btn").style.display = "none";
         }
         
         function toggleFilterSubcategory(supercategory) {
             let filterSubcategoryMenu = document.getElementById("filter-subcategory-" + supercategory);
             filterSubcategoryMenu.style.display = "grid";
             let mainSubcategoryButtons = document.querySelectorAll(".filter-content-button-main");
             mainSubcategoryButtons.forEach((button) => {
                button.style.display = "none";
             });
             
             if (supercategory === "Melamina") {
                 let filterSubcategoryMenuHasSub = document.querySelectorAll('[id^="has-sub-"]');
                 filterSubcategoryMenuHasSub.forEach((menu) => {
                     menu.style.display = "flex";
                 })
             }
             
             document.getElementById("close-btn").style.display = "none";
             document.getElementById("return-btn").style.display = "flex";
         }
         
         function toggleFilterSubsubcategory(supercategory) {
             let filterSubcategoryMenu = document.getElementById("filter-subsubcategory-menu-" + supercategory);
             filterSubcategoryMenu.style.display = "grid";
             let mainSubcategoryButtons = document.querySelectorAll(".filter-content-button-has-sub");
             mainSubcategoryButtons.forEach((button) => {
                button.style.display = "none";
             });
             
             document.getElementById("return-sub-btn").style.display = "flex";
             document.getElementById("return-btn").style.display = "none";
             document.getElementById("close-btn").style.display = "none";
         }
         
         function closeAllSubMenus() {
             let filterSubcategoryMenus = document.querySelectorAll('[id^="filter-subcategory-"]');
             filterSubcategoryMenus.forEach((menu) => {
                 console.log(menu);
                 menu.style.display = "none";
             });
         }
         
         function closeAllSubSubMenus() {
             let filterSubcategoryMenus = document.querySelectorAll('[id^="filter-subsubcategory-menu-"]');
             filterSubcategoryMenus.forEach((menu) => {
                 console.log(menu);
                 menu.style.display = "none";
             });
         }
         
         document.getElementById('close-btn').addEventListener('click', function() {
             toggleFilterPopup();
             closeAllSubMenus();
         });
         
         document.getElementById('return-btn').addEventListener('click', function() {
                 closeAllSubMenus();
                 let mainSubcategoryButtons = document.querySelectorAll(".filter-content-button-main");
             mainSubcategoryButtons.forEach((button) => {
                button.style.display = "flex";
             });
                 this.style.display = "none";
                 document.getElementById("close-btn").style.display = "flex";
         });
         
         document.getElementById('return-sub-btn').addEventListener('click', function() {
             let filterSubcategoryMenus = document.querySelectorAll('[id^="filter-subsubcategory-menu-"]');
             filterSubcategoryMenus.forEach((menu) => {
                 console.log(menu);
                 menu.style.display = "none";
             });
             
             let filterSubcategoryMenuHasSub = document.querySelectorAll('[id^="has-sub-"]');
                 filterSubcategoryMenuHasSub.forEach((menu) => {
                     menu.style.display = "flex";
                 });
             document.getElementById("return-sub-btn").style.display = "none";
             document.getElementById("return-btn").style.display = "flex";
             document.getElementById("close-btn").style.display = "none";
         })
         
        function filterHomologados () {
            let foundItems = [];
            
            profiles.forEach((item) => {
                if (item.classList.contains("homologado")) {
                    foundItems.push(item);
                }
            })
            
            while (catalogGrid.firstChild) { 
                catalogGrid.removeChild(catalogGrid.firstChild); 
            }
            foundItems.forEach((item) => {
                catalogGrid.appendChild(item);
                item.classList.remove("__hide");
            })
        }
</script>
<script>
/*
  Código para persistir filtro no sessionStorage + restaurar ao voltar.
  Coloque antes de </body> ou em um arquivo JS incluído na página.
*/

(function(){
  const STORAGE_KEY = 'catalogo-filtro-atual';
  const SCROLL_KEY = 'catalogo-scroll';

  // Adaptar essa função ao teu mecanismo atual de mostrar/ocultar itens
  // Se já existe filterElements, vamos sobrescrevê-la de forma segura:
  const realFilterElements = window.filterElements || function(category){
    // fallback mínimo caso não exista
    console.warn('filterElements original não encontrada. Recebeu:', category);
  };

  // Nova função que salva o estado e chama a real
  window.filterElements = function(category, options = {}) {
    // options: { pushState: boolean } -> controle se atualiza a URL com history
    // salva no sessionStorage
    try {
      sessionStorage.setItem(STORAGE_KEY, JSON.stringify({
        categoria: category,
        timestamp: Date.now()
      }));
    } catch(e){ console.warn('Erro salvando filtro:', e); }

    // Salva scroll atual (opcional)
    try { sessionStorage.setItem(SCROLL_KEY, String(window.scrollY || 0)); } catch(e){}

    // chama a implementação real (a tua que faz show/hide)
    realFilterElements(category);

    // se tu quiser refletir na url (opcional), usa replaceState para não poluir histórico
    try {
      const qs = category ? '?f=' + encodeURIComponent(category) : location.pathname + location.search;
      history.replaceState({categoria: category}, '', category ? ('?' + new URLSearchParams({f: category}).toString()) : location.pathname);
    } catch(e){}
  };

  // Restaura o filtro salvo (chama filterElements sem empurrar novo estado)
  function restoreFilterFromStorage() {
    try {
      const raw = sessionStorage.getItem(STORAGE_KEY);
      if (!raw) return;
      const state = JSON.parse(raw);
      if (state && state.categoria) {
        // aplica o filtro
        // passamos uma flag para não salvar novamente desnecessariamente (mas a função salva por padrão; isso é OK)
        realFilterElements(state.categoria);
        // Atualiza UI: marca botão ativo (se tiver activateButton)
        try {
          // procura botão com onclick contendo a categoria (simplista) - adaptar se necessário
          const btn = document.querySelector(`[onclick*="${state.categoria.replace(/'/g,'\\\'')}"]`);
          if (btn && typeof activateButton === 'function') activateButton(btn);
        } catch(e){}
      }
    } catch(e){ console.warn('Erro restaurando filtro:', e); }
  }

  // Restaura scroll salvo
  function restoreScrollFromStorage(){
    try {
      const y = sessionStorage.getItem(SCROLL_KEY);
      if (y !== null) {
        // usa setTimeout para garantir que renderização já ocorreu
        setTimeout(()=> window.scrollTo(0, parseInt(y,10) || 0), 50);
        // opcional: remover após restaurar pra não reaplicar sempre
        sessionStorage.removeItem(SCROLL_KEY);
      }
    } catch(e){}
  }

  // Se o navegador usa bfcache (voltar rápido), pageshow.persisted é true -> re-aplica
  window.addEventListener('pageshow', function(e){
    restoreFilterFromStorage();
    restoreScrollFromStorage();
  }, false);

  // Ao carregar pela primeira vez (ou reload)
  document.addEventListener('DOMContentLoaded', function(){
    // Se há query param ?f=xxx (opcional), priorize isso e salve
    const params = new URLSearchParams(location.search);
    const f = params.get('f');
    if (f) {
      // aplica e salva via filterElements
      window.filterElements(f);
    } else {
      // restaura do storage se não houver query param
      restoreFilterFromStorage();
    }
    // restaura scroll
    restoreScrollFromStorage();
  });

  // Quando o usuário clicar em um link para a página de produto,
  // é útil salvar o scroll antes de navegar (se seus produtos abrem com target _self)
  // Para garantir isso mesmo em links dinamicamente criados, delegamos:
  document.addEventListener('click', function(e){
    const a = e.target.closest && e.target.closest('a');
    if (!a) return;
    // ajusta a condição abaixo para os links que vão para produto.php (ex.: /produto.php?id=)
    if (a.href && a.href.indexOf('produto.php') !== -1) {
      try { sessionStorage.setItem(SCROLL_KEY, String(window.scrollY || 0)); } catch(e){}
      // opcionalmente salvar filtro também (já gravamos sempre ao filtrar)
    }
  }, true);

  // Popstate: quando usuário usa back/forward, re-aplica o estado baseado em history.state ou storage
  window.addEventListener('popstate', function(e){
    const s = (e.state && e.state.categoria) ? e.state.categoria : null;
    if (s) {
      try { realFilterElements(s); } catch(e){}
    } else {
      restoreFilterFromStorage();
    }
    restoreScrollFromStorage();
  });
})();
</script>

<script>
    const sessionCart = <?php echo json_encode($_SESSION['shopping_cart'] ?? []); ?>;

    if (sessionCart.length > 0) {
        localStorage.setItem(
            "shopping_cart",
            JSON.stringify(sessionCart)
        );
    }
</script>

</body>
</html>