const catalogGrid = document.getElementById("catalog__grid");
const noneFoundAlert = document.getElementById("catalog__none-found-alert");
const catalogoFetch = fetch("./catalogos/catalogo-fetch.php");

const urlParams = new URLSearchParams(window.location.search);
let productCategoryQuery = urlParams.get("category");

if (catalogGrid) {
    catalogoFetch
        .then(response => response.json())
        .then(data => {
            if (data.length > 0) {
                data.forEach(product => {
                    profiles.push(generateProfile(product));
                });
                    profiles.forEach((product) => {
                        if (product.classList.contains("homologado") || product.classList.contains("novidade")) {
                            catalogGrid.appendChild(product);
                        }
                    })
                    if (productCategoryQuery) {
                        filterElements(productCategoryQuery);
                    }
            } else {
                console.error("Catalogo data is undefined.");
            }
        })
        .catch(error => {
            console.error("Error loading JSON file: " + error);
        });
}

/* function generateProfile(product) {
    const { productPrice, productCategory, productType, productImage, productName, productCode, productDimensions, productSubCategory, personalizado, is_homologado } = product;
    
    const homologado = (is_homologado == 1 ? "homologado" : null);
    const categories = productCategory.split(",").map(category => category.trim().toLowerCase()).join(" ");
    const productCard = document.createElement("a");
    productCard.className = `catalog__card catalog__card__link ${categories} ${productType.toLowerCase()} ${homologado} __hide`;
    
    productCard.innerHTML = `
        <div class="catalog__card__image__wrapper">
            <img src="${productImage}" alt="${productName}" class="catalog__card__image__content" loading="lazy" style="width: 6.25rem; height: 6.25rem;">
        </div>
        <div class="product-container">
            <h5 class="product-card__name" data-product-code="${productCode}" data-product-category="${productCategory}">${productName.toUpperCase()}</h5>
            <h5 class="product-card__dimensions">${(productDimensions)}</h5>
            <h6 class="product-card__code">${productCode}</h6>
            <h6 class="product-card__price" style="color: green;">R$${productPrice}</h6>
        </div>
    `;

    if (productSubCategory) {
        productCard.setAttribute("data-product-subcat", productSubCategory);
    }

    productCard.href = personalizado ? productCode : `./product-details.php?code=${productCode}`;
    productCard.id = productName;
    productCard.code = productCode;

    if (personalizado) {
        const dimensionsElem = productCard.querySelector(".product-card__dimensions");
        dimensionsElem.style.fontVariant = "small-caps";
        productCard.querySelector(".product-card__code").innerText = "";
    }

    productCard.classList.toggle("__hide", !productCard.classList.contains("homologado") && !productCard.classList.contains("novidade"));

    return productCard;
} */

function generateProfile(product) {
    const { productPrice, productCategory, productType, productImage, productName, productCode, productDimensions, productSubCategory, personalizado, is_homologado, active } = product;
    
    const homologado = (is_homologado == 1 ? "homologado" : null);
    const categories = productCategory.split(",").map(category => category.trim().toLowerCase()).join(" ");
    const productCard = document.createElement(active === "0" ? "div" : "a");
    
    productCard.className = `catalog__card catalog__card__link ${categories} ${productType.toLowerCase()} ${homologado} ${active === "0" ? "inactive" : ""} __hide`;
    
    productCard.innerHTML = `
        <div class="catalog__card__image__wrapper">
            <img src="${productImage}" alt="${productName}" class="catalog__card__image__content ${active === "0" ? "grayed-out" : ""}" loading="lazy" style="width: 6.25rem; height: 6.25rem;">
        </div>
        <div class="product-container">
            <h5 class="product-card__name" data-product-code="${productCode}" data-product-category="${productCategory}">${productName.toUpperCase()}</h5>
            <h5 class="product-card__dimensions">${(productDimensions)}</h5>
            <h6 class="product-card__code">${productCode}</h6>
            <h6 class="product-card__price" style="color: ${active === "0" ? "red" : "green"}; font-variant: ${active === "0" ? "small-caps" : ""}">${active === "0" ? "Indisponível" : `R$${productPrice}`}</h6>
        </div>
    `;

    if (productSubCategory) {
        productCard.setAttribute("data-product-subcat", productSubCategory);
    }

    if (personalizado) {
        const dimensionsElem = productCard.querySelector(".product-card__dimensions");
        dimensionsElem.style.fontVariant = "small-caps";
        productCard.querySelector(".product-card__code").innerText = "";
    }

    if (active === "0") {
        productCard.style.pointerEvents = "none"; // Makes the item unclickable
    } else {
        productCard.href = personalizado ? productCode : `./product-details.php?code=${productCode}`;
    }

    productCard.id = productName;
    productCard.code = productCode;

    productCard.classList.toggle("__hide", !productCard.classList.contains("homologado") && !productCard.classList.contains("novidade"));

    return productCard;
}

export { generateProfile };