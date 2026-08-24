import { generateProfile } from './generateCards.js';

const catalogGrid = document.getElementById("catalog__grid");
const crumbsWrapper = document.getElementById("crumbs__flowboxes__wrapper");
const noneFoundAlert = document.getElementById("catalog__none-found-alert");
const urlParams = new URLSearchParams(window.location.search);
const productCategoryQuery = urlParams.get("category");
const productCodeQuery = urlParams.get("code");

async function fetchCatalogData(url) {
    try {
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error(`Failed to fetch data: ${response.status}`);
        }
        const data = await response.json();
        return data;
    } catch (error) {
        console.error(`Error fetching catalog data: ${error.message}`);
        return [];
    }
};

window.productList = await fetchCatalogData("./catalogos/catalogo-fetch.php");

const indexableGalleryProducts = ["hashi-premium-5", "A-202", "CL-8629", "1243", "saco-roupa-color-p", "saco-roupa-color-m", "saco-roupa-color-g"];
const varietyLists = {"hashi-premium-5": ["./media/HASHI PREMIUM 5PARES.jpg", "../media/variedades/HASHI V1.jpg", "../media/variedades/HASHI V2.jpg", "../media/variedades/HASHI V3.jpg", "../media/variedades/HASHI N1.jpg", "../media/variedades/HASHI N2.jpg", "../media/variedades/HASHI N3.jpg", "../media/variedades/HASHI N4.jpg", "../media/variedades/HASHI N5.jpg", "../media/variedades/HASHI N6.jpg", "../media/variedades/HASHI F1.jpg", "../media/variedades/HASHI F2.jpg", "../media/variedades/HASHI F3.jpg", "../media/variedades/HASHI F4.jpg", "../media/variedades/HASHI F5.jpg", "../media/variedades/HASHI T1.jpg", "../media/variedades/HASHI T2.jpg", "../media/variedades/HASHI T3.jpg", "../media/variedades/HASHI T4.jpg"], "A-202": ["./media/A-202.jpg", "../media/variedades/A-202 COELHO AMARELO.jpg", "../media/variedades/A-202 DUENDE LARANJA.jpg", "../media/variedades/A-202 DUENDE VERMELHO.jpg", "../media/variedades/A-202 MENINA ROSA.jpg", "../media/variedades/A-202 MENINO AZUL.jpg", "../media/variedades/A-202 URSO VERDE.jpg"], "CL-8629": ["../media/variedades/CL-8629 AZUL.jpg", "../media/variedades/CL-8629 LARANJA.jpg", "../media/variedades/CL-8629 VERMELHO.jpg"], "1243": ["../media/1243.jpg", "../media/1243 ROSA.jpg", "../media/1243 VERDE.jpg"], "saco-roupa-color-p": ["../media/SACO ROUPA ROSAS.jpg", "../media/SACO ROUPA BOLHA.jpg"], "saco-roupa-color-m": ["../media/SACO ROUPA ROSAS.jpg", "../media/SACO ROUPA BOLHA.jpg"], "saco-roupa-color-g": ["../media/SACO ROUPA ROSAS.jpg", "../media/SACO ROUPA BOLHA.jpg"]};
const propertyMapping = {"PF-Hana": "PF-Hana", "PF-Sólido": "PF-Sólido", "Especiais": "Especiais", "DM": "DM", "BC": "BC", "F": "F", "N": "N", "RD": "RD", "Kitchen": "Kitchen", "BR": "BR", "Chá": "Chá", "Ecologia": "Ecologia", "Life": "Life", "Geral": "Geral", "Kids&Fun": "Kids & Fun", "Oceano": "Oceano", "Midori": "Midori", "Teishoku": "Teishoku", "Nuage": "Nuage", "Porcelana": "Porcelana", "Espetos": "Espetos", "Frigideira": "Frigideira", "Geral-Hashi": "Hashis Padrão", "Especiais-Hashi": "Hashis Especiais", "Embalagens": "Embalagens", "LinhaSK": "Descartáveis SK", "PoteMantimento": "Potes de Mantimento", "SushiAntiFog": "Embalagens Anti-Fog", "CoffeeCup": "Coffee Cup", "TigelaPapel": "Tigelas de Papel", "Canudo": "Canudos", "BoxPapel": "Box de Papel", "BowlBranco": "Bowl de Papel", "BowlKraft": "Bowl de Papel Kraft", "BowlPS": "Linha PS", "BowlPP": "Linha PP", "PoteMolho": "Potes para Molho e Sobremesa", "Eventos": "Eventos", "KraftBox": "Kraft Box", "Professionals": "Professionals\' Selection", "Alimento": "Alimentos", "customizable": "Personalizados", "Turquesa": "Turquesa"};



if (crumbsWrapper) {
    if (productCodeQuery) {
        const selectedProduct = productList.find(product => 
            product.productCode === productCodeQuery && product.active !== "0"
        );

        if (selectedProduct) {
            generateProductDetails(selectedProduct);
        } else {
            alertAndGoBack("Produto não encontrado.");
        }
    }
}

function alertAndGoBack(message) {
    alert(message);
    window.history.back();
}

function shuffleArray(array) {
    for (let i = array.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i+1));
        [array[i],array[j]] = [array[j],array[i]];
    }
}

function generateProductDetails(selectedProduct) {
    console.log(selectedProduct.productSystemCode);
    const productImage = document.getElementById("product-details__image__content");
    document.title = `${selectedProduct.productCode} | Detalhes do Produto`;
    productImage.src = selectedProduct.productImage;
    productImage.classList.add("__zoomable-image");
    productImage.setAttribute("onclick", "zoomIn(this)");
    productImage.setAttribute("alt", selectedProduct.productName);
    if (document.getElementById("product-details-dimensions")) {
        document.getElementById("product-details-dimensions").textContent = selectedProduct.productDimensions;
    }
    const mappedObject = {productCategory: selectedProduct.productCategory.split(",")[0]};
    const displayString = propertyMapping[mappedObject.productCategory];
    document.getElementById("product-details-name").textContent = selectedProduct.productName.toUpperCase();
    document.getElementById("product-details-menu-code").textContent = selectedProduct.productCode;
    if (document.getElementById("product-details-line")) {
        document.getElementById("product-details-line").textContent = displayString;
    }
    document.getElementById("product-price").textContent = selectedProduct.productPrice;
    document.getElementById("p_price").value = selectedProduct.productPrice;
    document.getElementById("p_name").value = selectedProduct.productName;
    document.getElementById("p_systemcode").value = selectedProduct.productSystemCode;
    document.getElementById("p_unit").value = selectedProduct.productUnit;
    
    if (!selectedProduct.productMaterial) {
        document.getElementById("material-item").classList.add("__hide");
    } else {
        document.getElementById("material-item").setAttribute("display", "block");
        if (document.getElementById("product-details-material")) {
        document.getElementById("product-details-material").textContent = selectedProduct.productMaterial;
        }
    }

    if (!selectedProduct.productDimensions) {
        document.getElementById("dimensions-item").classList.add("__hide");
    } else {
        document.getElementById("dimensions-item").setAttribute("display", "block");
    }
    
    if (selectedProduct.correspondentProductCode) {
        let correspondentProductIntro;
        const correspondentProductLink = document.createElement("a");
        correspondentProductLink.href = `./product-details.php?code=${selectedProduct.correspondentProductCode}`;
        correspondentProductLink.classList.add("correspondent-product-link");
        if (selectedProduct.correspondentProductName.startsWith("Tampa")) {
            correspondentProductIntro = "Tampa: ";
        } else {
            correspondentProductIntro = "Pote: ";
        }
        correspondentProductLink.textContent = correspondentProductIntro + `${selectedProduct.correspondentProductName}`;
        document.getElementById("product-details__description-list").appendChild(correspondentProductLink);
    }

    if (indexableGalleryProducts.includes(selectedProduct.productCode)) {
        const prevArrow = document.createElement("button");
        prevArrow.textContent = "‹";
        prevArrow.classList.add("arrow");
        prevArrow.id = "prev-arrow";
        document.querySelector(".product-details__image__wrapper").insertBefore(prevArrow, productImage);

        const nextArrow = document.createElement("button");
        nextArrow.textContent = "›";
        nextArrow.classList.add("arrow");
        nextArrow.id = "next-arrow";
        document.querySelector(".product-details__image__wrapper").appendChild(nextArrow);
    }

    let images = varietyLists[selectedProduct.productCode];
    let currentIndex = 0;

    $("#next-arrow").click(function() {
        currentIndex++;
        if (currentIndex >= images.length) {
            currentIndex = 0;
        }
        $("#product-details__image__content").attr("src", images[currentIndex]);
    });

    $("#prev-arrow").click(function() {
        currentIndex--;
        if (currentIndex < 0) {
            currentIndex = images.length - 1;
        }
        $("#product-details__image__content").attr("src", images[currentIndex]);
    });
}