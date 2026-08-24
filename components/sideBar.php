<?php

$sideBar = '<div class="catalog__side-bar">
                    <div class="catalog__side-bar__search__wrapper">
                        <div id="catalog__side-bar__search__input-wrapper">
                            <input type="text" id="catalog__side-bar__search__input-field" placeholder="Pesquise nome ou código do produto..."/>
                            <button class="catalog__side-bar__search__search-button" id="search" aria-label = "Pesquisar"><i class="catalog__side-bar__search__search-icon fa fa-search"></i></button>
                        </div>
                        <div id="catalog__side-bar__buttons__wrapper">';
                        
                        if (isset($_SESSION['usersClass']) && 
                            $_SESSION['usersClass'] != 'atacado' && 
                            $_SESSION['usersClass'] != 'tab1' && 
                            $_SESSION['usersClass'] != 'fabiodarc' && 
                            $_SESSION['usersClass'] != 'tatianedasilva') {
                        $sideBar .= '<button id="catalog__side-bar__buttons__header-no-after-ms" class="catalog__side-bar__buttons__header" onclick="filterHomologados()">Itens Homologados</button>';
                        }
                        
                        $sideBar .= '<button id="catalog__side-bar__buttons__header-no-after-ms" class="catalog__side-bar__buttons__header sale-button" onclick="filterElements(\'Novidade\');">Novidades</button>
                        <button id="catalog__side-bar__buttons__header-no-after-ms" class="catalog__side-bar__buttons__header sale-button" onclick="filterElements(\'Professionals\');">Professionals\' Selection</button>
                        <button id="catalog__side-bar__buttons__header-no-after-ms" class="catalog__side-bar__buttons__header sale-button" onclick="filterElements(\'Alimento\');">Alimentos</button>
                        <button class="catalog__side-bar__buttons__header" onclick="filterElements(\'Hashi\')">Hashi</button>
                        <div class="catalog__side-bar__buttons__panel">
                            <button class="catalog__side-bar__buttons__item" onclick="filterElements(\'customizable\'); activateButton(this)">Personalizados</button>
                            <button class="catalog__side-bar__buttons__item" onclick="filterElements(\'Geral-Hashi\'); activateButton(this)">Padrão</button>
                            <button class="catalog__side-bar__buttons__item" onclick="filterElements(\'Especiais-Hashi\'); activateButton(this)">Especiais</button>
                        </div>
                        <button class="catalog__side-bar__buttons__header" onclick="filterElements(\'Embalagem\')">Embalagens</button>
                        <div class="catalog__side-bar__buttons__panel" id="panel-embalagens">
                            <button class="catalog__side-bar__buttons__item catalog__side-bar__buttons__subcategory__toggle" onclick="activateSubcategoryButton(this)">Sushi</button>
                                <div class="catalog__side-bar__buttons__subcategory__wrapper">
                                    <button class="catalog__side-bar__buttons__subcategory__item catalog__side-bar__buttons__item" onclick="filterElements(\'LinhaSK\'); activateButton(this)">Linha SK</button>
                                    <button class="catalog__side-bar__buttons__subcategory__item catalog__side-bar__buttons__item" onclick="filterElements(\'SushiAntiFog\'); activateButton(this)">Linha Anti-Fog</button>
                                    <button class="catalog__side-bar__buttons__subcategory__item catalog__side-bar__buttons__item" onclick="filterElements(\'BoxPapel\'); activateButton(this)">Box de Papel</button>
                                </div>
                            <button class="catalog__side-bar__buttons__item" onclick="filterElements(\'Teishoku\'); activateButton(this)">Teishoku</button>
                            <button class="catalog__side-bar__buttons__item" onclick="filterElements(\'PoteMolho\'); activateButton(this)">Potes para Molho e Sobremesa</button>
                            <button class="catalog__side-bar__buttons__item catalog__side-bar__buttons__subcategory__toggle" onclick="activateSubcategoryButton(this)">Bowls</button>
                            <div class="catalog__side-bar__buttons__subcategory__wrapper">
                                <button class="catalog__side-bar__buttons__subcategory__item catalog__side-bar__buttons__item" onclick="filterElements(\'BowlBranco\'); activateButton(this)">Papel Branco</button>
                                <button class="catalog__side-bar__buttons__subcategory__item catalog__side-bar__buttons__item" onclick="filterElements(\'BowlKraft\'); activateButton(this)">Papel Kraft</button>
                                <button class="catalog__side-bar__buttons__subcategory__item catalog__side-bar__buttons__item" onclick="filterElements(\'BowlPS\'); activateButton(this)">PS</button>
                                <button class="catalog__side-bar__buttons__subcategory__item catalog__side-bar__buttons__item" onclick="filterElements(\'BowlPP\'); activateButton(this)">PP</button>
                            </div>
                            <button class="catalog__side-bar__buttons__item" onclick="filterElements(\'KraftBox\'); activateButton(this)">Kraft Box</button>
                            <button class="catalog__side-bar__buttons__item" onclick="filterElements(\'Eventos\'); activateButton(this)">Eventos</button>
                            <button class="catalog__side-bar__buttons__item" onclick="filterElements(\'CoffeeCup\'); activateButton(this)">Coffee Cup</button>
                            <button class="catalog__side-bar__buttons__item" onclick="filterElements(\'Canudo\'); activateButton(this)">Canudos</button>
                        </div>
                        <button class="catalog__side-bar__buttons__header" onclick="filterElements(\'Melamina\')">Melaminas</button>
                        <div class="catalog__side-bar__buttons__panel">
                            <button class="catalog__side-bar__buttons__item catalog__side-bar__buttons__subcategory__toggle" onclick="filterElements(\'Turquesa\'); activateSubcategoryButton(this)">Turquesa　ターコイズ</button>
                            <div class="catalog__side-bar__buttons__subcategory__wrapper">
                                <button class="catalog__side-bar__buttons__subcategory__item catalog__side-bar__buttons__item" onclick="filterSubCat(\'Turquesa\', \'Pires\'); activateButton(this)">Pires</button>
                                <button class="catalog__side-bar__buttons__subcategory__item catalog__side-bar__buttons__item" onclick="filterSubCat(\'Turquesa\', \'Prato\'); activateButton(this)">Prato</button>
                                <button class="catalog__side-bar__buttons__subcategory__item catalog__side-bar__buttons__item" onclick="filterSubCat(\'Turquesa\', \'Tigela\'); activateButton(this)">Tigelas</button>
                            </div>
                            <button class="catalog__side-bar__buttons__item catalog__side-bar__buttons__subcategory__toggle" onclick="filterElements(\'Oceano\'); activateSubcategoryButton(this)">Oceano　海　オーシャン</button>
                            <div class="catalog__side-bar__buttons__subcategory__wrapper">
                                <button class="catalog__side-bar__buttons__subcategory__item catalog__side-bar__buttons__item" onclick="filterSubCat(\'Oceano\', \'Pires\'); activateButton(this)">Pires</button>
                                <button class="catalog__side-bar__buttons__subcategory__item catalog__side-bar__buttons__item" onclick="filterSubCat(\'Oceano\', \'Prato\'); activateButton(this)">Prato</button>
                                <button class="catalog__side-bar__buttons__subcategory__item catalog__side-bar__buttons__item" onclick="filterSubCat(\'Oceano\', \'Tigela\'); activateButton(this)">Tigelas</button>
                            </div>
                            <button class="catalog__side-bar__buttons__item catalog__side-bar__buttons__subcategory__toggle" onclick="filterElements(\'Midori\'); activateSubcategoryButton(this)">Midori　緑　みどり</button>
                            <div class="catalog__side-bar__buttons__subcategory__wrapper">
                                <button class="catalog__side-bar__buttons__subcategory__item catalog__side-bar__buttons__item" onclick="filterSubCat(\'Midori\', \'Pires\'); activateButton(this)">Pires</button>
                                <button class="catalog__side-bar__buttons__subcategory__item catalog__side-bar__buttons__item" onclick="filterSubCat(\'Midori\', \'Prato\'); activateButton(this)">Prato</button>
                                <button class="catalog__side-bar__buttons__subcategory__item catalog__side-bar__buttons__item" onclick="filterSubCat(\'Midori\', \'Tigela\'); activateButton(this)">Tigelas</button>
                            </div>
                            <button class="catalog__side-bar__buttons__item catalog__side-bar__buttons__subcategory__toggle" onclick="filterElements(\'Nuage\'); activateSubcategoryButton(this)">Nuage　雲　ヌアージュ</button>
                            <div class="catalog__side-bar__buttons__subcategory__wrapper">
                                <button class="catalog__side-bar__buttons__subcategory__item catalog__side-bar__buttons__item" onclick="filterSubCat(\'Nuage\', \'Prato\'); activateButton(this)">Prato</button>
                                <button class="catalog__side-bar__buttons__subcategory__item catalog__side-bar__buttons__item" onclick="filterSubCat(\'Nuage\', \'Tigela\'); activateButton(this)">Tigelas</button>
                            </div>

                            <button class="catalog__side-bar__buttons__item catalog__side-bar__buttons__subcategory__toggle" onclick="filterElements(\'Sepia\'); activateSubcategoryButton(this)">Sépia セピア</button>
                            <div class="catalog__side-bar__buttons__subcategory__wrapper">
                                <button class="catalog__side-bar__buttons__subcategory__item catalog__side-bar__buttons__item" onclick="filterSubCat(\'Sepia\', \'Prato\'); activateButton(this)">Prato</button>
                                <button class="catalog__side-bar__buttons__subcategory__item catalog__side-bar__buttons__item" onclick="filterSubCat(\'Sepia\', \'Pires\'); activateButton(this)">Pires</button>
                                <button class="catalog__side-bar__buttons__subcategory__item catalog__side-bar__buttons__item" onclick="filterSubCat(\'Sepia\', \'Tigela\'); activateButton(this)">Tigela</button>
                                <button class="catalog__side-bar__buttons__subcategory__item catalog__side-bar__buttons__item" onclick="filterSubCat(\'Sepia\', \'Copo\'); activateButton(this)">Copo</button>
                            </div>

                            <button class="catalog__side-bar__buttons__item catalog__side-bar__buttons__subcategory__toggle" onclick="filterElements(\'DM\'); activateSubcategoryButton(this)">Linha DM</button>
                            <div class="catalog__side-bar__buttons__subcategory__wrapper">
                                <button class="catalog__side-bar__buttons__subcategory__item catalog__side-bar__buttons__item" onclick="filterSubCat(\'DM\', \'Pires\'); activateButton(this)">Pires</button>
                                <button class="catalog__side-bar__buttons__subcategory__item catalog__side-bar__buttons__item" onclick="filterSubCat(\'DM\', \'PratoDiv\'); activateButton(this)">Prato com Divisória</button>
                                <button class="catalog__side-bar__buttons__subcategory__item catalog__side-bar__buttons__item" onclick="filterSubCat(\'DM\', \'Prato\'); activateButton(this)">Prato</button>
                                <button class="catalog__side-bar__buttons__subcategory__item catalog__side-bar__buttons__item" onclick="filterSubCat(\'DM\', \'Barco\'); activateButton(this)">Barcos</button>
                                <button class="catalog__side-bar__buttons__subcategory__item catalog__side-bar__buttons__item" onclick="filterSubCat(\'DM\', \'Tigela\'); activateButton(this)">Tigelas</button>
                                <button class="catalog__side-bar__buttons__subcategory__item catalog__side-bar__buttons__item" onclick="filterSubCat(\'DM\', \'Outro\'); activateButton(this)">Outros</button>
                            </div>
                            <button class="catalog__side-bar__buttons__item catalog__side-bar__buttons__subcategory__toggle" onclick="filterElements(\'BC\'); activateSubcategoryButton(this)">Linha BC</button>
                            <div class="catalog__side-bar__buttons__subcategory__wrapper">
                                <button class="catalog__side-bar__buttons__subcategory__item catalog__side-bar__buttons__item" onclick="filterSubCat(\'BC\', \'Pires\'); activateButton(this)">Pires</button>
                                <button class="catalog__side-bar__buttons__subcategory__item catalog__side-bar__buttons__item" onclick="filterSubCat(\'BC\', \'PratoDiv\'); activateButton(this)">Prato com Divisória</button>
                                <button class="catalog__side-bar__buttons__subcategory__item catalog__side-bar__buttons__item" onclick="filterSubCat(\'BC\', \'Prato\'); activateButton(this)">Prato</button>
                                <button class="catalog__side-bar__buttons__subcategory__item catalog__side-bar__buttons__item" onclick="filterSubCat(\'BC\', \'Barco\'); activateButton(this)">Barcos</button>
                                <button class="catalog__side-bar__buttons__subcategory__item catalog__side-bar__buttons__item" onclick="filterSubCat(\'BC\', \'Tigela\'); activateButton(this)">Tigelas</button>
                                <button class="catalog__side-bar__buttons__subcategory__item catalog__side-bar__buttons__item" onclick="filterSubCat(\'BC\', \'Outro\'); activateButton(this)">Outros</button>
                            </div>
                            <button class="catalog__side-bar__buttons__item catalog__side-bar__buttons__subcategory__toggle" onclick="filterElements(\'BR\'); activateSubcategoryButton(this)">Linha BR</button>
                            <div class="catalog__side-bar__buttons__subcategory__wrapper">
                                <button class="catalog__side-bar__buttons__subcategory__item catalog__side-bar__buttons__item" onclick="filterSubCat(\'BR\', \'Pires\'); activateButton(this)">Pires</button>
                                <button class="catalog__side-bar__buttons__subcategory__item catalog__side-bar__buttons__item" onclick="filterSubCat(\'BR\', \'Prato\'); activateButton(this)">Prato</button>
                                <button class="catalog__side-bar__buttons__subcategory__item catalog__side-bar__buttons__item" onclick="filterSubCat(\'BR\', \'Tigela\'); activateButton(this)">Tigelas</button>
                                <button class="catalog__side-bar__buttons__subcategory__item catalog__side-bar__buttons__item" onclick="filterSubCat(\'BR\', \'Outro\'); activateButton(this)">Outros</button>
                            </div>
                            <button class="catalog__side-bar__buttons__item catalog__side-bar__buttons__subcategory__toggle" onclick="filterElements(\'RD\'); activateSubcategoryButton(this)">Linha RD</button>
                            <div class="catalog__side-bar__buttons__subcategory__wrapper">
                                <button class="catalog__side-bar__buttons__subcategory__item catalog__side-bar__buttons__item" onclick="filterSubCat(\'RD\', \'Pires\'); activateButton(this)">Pires</button>
                                <button class="catalog__side-bar__buttons__subcategory__item catalog__side-bar__buttons__item" onclick="filterSubCat(\'RD\', \'PratoDiv\'); activateButton(this)">Prato com Divisória</button>
                                <button class="catalog__side-bar__buttons__subcategory__item catalog__side-bar__buttons__item" onclick="filterSubCat(\'RD\', \'Prato\'); activateButton(this)">Prato</button>
                                <button class="catalog__side-bar__buttons__subcategory__item catalog__side-bar__buttons__item" onclick="filterSubCat(\'RD\', \'Barco\'); activateButton(this)">Barcos</button>
                                <button class="catalog__side-bar__buttons__subcategory__item catalog__side-bar__buttons__item" onclick="filterSubCat(\'RD\', \'Tigela\'); activateButton(this)">Tigelas</button>
                                <button class="catalog__side-bar__buttons__subcategory__item catalog__side-bar__buttons__item" onclick="filterSubCat(\'RD\', \'Outro\'); activateButton(this)">Outros</button>
                            </div>
                            <button class="catalog__side-bar__buttons__item catalog__side-bar__buttons__subcategory__toggle" onclick="filterElements(\'N\'); activateSubcategoryButton(this)">Linha N</button>
                            <div class="catalog__side-bar__buttons__subcategory__wrapper">
                                <button class="catalog__side-bar__buttons__subcategory__item catalog__side-bar__buttons__item" onclick="filterSubCat(\'N\', \'Pires\'); activateButton(this)">Pires</button>
                                <button class="catalog__side-bar__buttons__subcategory__item catalog__side-bar__buttons__item" onclick="filterSubCat(\'N\', \'PratoDiv\'); activateButton(this)">Prato com Divisória</button>
                                <button class="catalog__side-bar__buttons__subcategory__item catalog__side-bar__buttons__item" onclick="filterSubCat(\'N\', \'Prato\'); activateButton(this)">Prato</button>
                                <button class="catalog__side-bar__buttons__subcategory__item catalog__side-bar__buttons__item" onclick="filterSubCat(\'N\', \'Barco\'); activateButton(this)">Barcos</button>
                                <button class="catalog__side-bar__buttons__subcategory__item catalog__side-bar__buttons__item" onclick="filterSubCat(\'N\', \'Tigela\'); activateButton(this)">Tigelas</button>
                                <button class="catalog__side-bar__buttons__subcategory__item catalog__side-bar__buttons__item" onclick="filterSubCat(\'N\', \'Outro\'); activateButton(this)">Outros</button>
                            </div>
                            <button class="catalog__side-bar__buttons__item catalog__side-bar__buttons__subcategory__toggle" onclick="filterElements(\'F\'); activateSubcategoryButton(this)">Linha F</button>
                            <div class="catalog__side-bar__buttons__subcategory__wrapper">
                                <button class="catalog__side-bar__buttons__subcategory__item catalog__side-bar__buttons__item" onclick="filterSubCat(\'F\', \'Pires\'); activateButton(this)">Pires</button>
                                <button class="catalog__side-bar__buttons__subcategory__item catalog__side-bar__buttons__item" onclick="filterSubCat(\'F\', \'PratoDiv\'); activateButton(this)">Prato com Divisória</button>
                                <button class="catalog__side-bar__buttons__subcategory__item catalog__side-bar__buttons__item" onclick="filterSubCat(\'F\', \'Prato\'); activateButton(this)">Prato</button>
                                <button class="catalog__side-bar__buttons__subcategory__item catalog__side-bar__buttons__item" onclick="filterSubCat(\'F\', \'Barco\'); activateButton(this)">Barcos</button>
                                <button class="catalog__side-bar__buttons__subcategory__item catalog__side-bar__buttons__item" onclick="filterSubCat(\'F\', \'Tigela\'); activateButton(this)">Tigelas</button>
                                <button class="catalog__side-bar__buttons__subcategory__item catalog__side-bar__buttons__item" onclick="filterSubCat(\'F\', \'Outro\'); activateButton(this)">Outros</button>
                            </div>
                            <button class="catalog__side-bar__buttons__item catalog__side-bar__buttons__subcategory__toggle" onclick="filterElements(\'PF-Hana\'); activateSubcategoryButton(this)">PF-Hana</button>
                            <div class="catalog__side-bar__buttons__subcategory__wrapper">
                                <button class="catalog__side-bar__buttons__subcategory__item catalog__side-bar__buttons__item" onclick="filterSubCat(\'PF-Hana\', \'Pires\'); activateButton(this)">Pires</button>
                                <button class="catalog__side-bar__buttons__subcategory__item catalog__side-bar__buttons__item" onclick="filterSubCat(\'PF-Hana\', \'PratoDiv\'); activateButton(this)">Prato com Divisória</button>
                                <button class="catalog__side-bar__buttons__subcategory__item catalog__side-bar__buttons__item" onclick="filterSubCat(\'PF-Hana\', \'Prato\'); activateButton(this)">Prato</button>
                            </div>
                            <button class="catalog__side-bar__buttons__item catalog__side-bar__buttons__subcategory__toggle" onclick="filterElements(\'PF-Sólido\'); activateSubcategoryButton(this)">PF-Sólido</button>
                            <div class="catalog__side-bar__buttons__subcategory__wrapper">
                                <button class="catalog__side-bar__buttons__subcategory__item catalog__side-bar__buttons__item" onclick="filterSubCat(\'PF-Sólido\', \'Pires\'); activateButton(this)">Pires</button>
                                <button class="catalog__side-bar__buttons__subcategory__item catalog__side-bar__buttons__item" onclick="filterSubCat(\'PF-Sólido\', \'PratoDiv\'); activateButton(this)">Prato com Divisória</button>
                                <button class="catalog__side-bar__buttons__subcategory__item catalog__side-bar__buttons__item" onclick="filterSubCat(\'PF-Sólido\', \'Prato\'); activateButton(this)">Prato</button>
                                <button class="catalog__side-bar__buttons__subcategory__item catalog__side-bar__buttons__item" onclick="filterSubCat(\'PF-Sólido\', \'Tigela\'); activateButton(this)">Tigelas</button>
                                <button class="catalog__side-bar__buttons__subcategory__item catalog__side-bar__buttons__item" onclick="filterSubCat(\'PF-Sólido\', \'Outro\'); activateButton(this)">Outros</button>
                            </div>
                            <button class="catalog__side-bar__buttons__item catalog__side-bar__buttons__subcategory__toggle" onclick="filterElements(\'Especiais\'); activateSubcategoryButton(this)">Especiais</button>
                            <div class="catalog__side-bar__buttons__subcategory__wrapper">
                                <button class="catalog__side-bar__buttons__subcategory__item catalog__side-bar__buttons__item" onclick="filterSubCat(\'Especiais\', \'Prato\'); activateButton(this)">Prato</button>
                                <button class="catalog__side-bar__buttons__subcategory__item catalog__side-bar__buttons__item" onclick="filterSubCat(\'Especiais\', \'Tigela\'); activateButton(this)">Tigelas</button>
                            </div>
                        </div>
                        <button class="catalog__side-bar__buttons__header" onclick="filterElements(\'Utilidade\')">Utilidades</button>
                        <div class="catalog__side-bar__buttons__panel">
                            <button class="catalog__side-bar__buttons__item" onclick="filterElements(\'Professionals\'); activateButton(this)">Professionals\' Selection</button>
                            <button class="catalog__side-bar__buttons__item" onclick="filterElements(\'ChapaTeppan\'); activateButton(this)">Chapa de Teppan</button>
                            <button class="catalog__side-bar__buttons__item" onclick="filterElements(\'Kitchen\'); activateButton(this)">Kitchen</button>
                            <button class="catalog__side-bar__buttons__item" onclick="filterElements(\'Ceramica\'); activateButton(this)">Cerâmica</button>
                            <button class="catalog__side-bar__buttons__item" onclick="filterElements(\'Ecologia\'); activateButton(this)">Ecologia</button>
                            <button class="catalog__side-bar__buttons__item" onclick="filterElements(\'Frigideira\'); activateButton(this)">Frigideiras</button>
                            <button class="catalog__side-bar__buttons__item" onclick="filterElements(\'Chá\'); activateButton(this)">Chá</button>
                            <button class="catalog__side-bar__buttons__item" onclick="filterElements(\'Kids&Fun\'); activateButton(this)">Kids &amp; Fun</button>
                            <button class="catalog__side-bar__buttons__item" onclick="filterElements(\'Life\'); activateButton(this)">Life</button>
                            <button class="catalog__side-bar__buttons__item" onclick="filterElements(\'PoteMantimento\'); activateButton(this)">Potes de Mantimento &amp; Lunch Box</button>
                            <button class="catalog__side-bar__buttons__item" onclick="filterElements(\'Garrafa\'); activateButton(this)">Garrafas Reutilizáveis</button>
                            <button class="catalog__side-bar__buttons__item" onclick="filterElements(\'Canudo\'); activateButton(this)">Canudos</button>
                            <button class="catalog__side-bar__buttons__item" onclick="filterElements(\'Hotelaria\'); activateButton(this)">Hotelaria</button>
                        </div>
                        <button id="catalog__side-bar__buttons__header-no-after-ms" class="catalog__side-bar__buttons__header" onclick="filterElements(\'Espetos\');">Espetos</button>
                    </div>'
;