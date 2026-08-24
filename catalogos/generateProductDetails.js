const crumbsWrapper = document.getElementById("crumbs__flowboxes__wrapper");
const catalogoFetch = fetch("./catalogos/catalogo-fetch.php");

function generateProductDetails(selectedProduct) {
    const productImage = document.getElementById("product-details__image__content");
    const productDetailsName = document.getElementById("product-details-name");
    const productDetailsMenuCode = document.getElementById("product-details-menu-code");
    const productDetailsLine = document.getElementById("product-details-line");
    const productPrice = document.getElementById("product-price");
    const pPrice = document.getElementById("p_price");
    const pName = document.getElementById("p_name");
    const pSystemCode = document.getElementById("p_systemcode");
    const materialItem = document.getElementById("material-item");
    const dimensionsItem = document.getElementById("dimensions-item");
    const productDetailsMaterial = document.getElementById("product-details-material");
    const productDetailsDimensions = document.getElementById("product-details-dimensions");
    const productDetailsDescriptionList = document.getElementById("product-details__description-list");

    document.title = `${selectedProduct.productCode} | Detalhes do Produto`;
    productImage.src = selectedProduct.productImage;
    productImage.classList.add("__zoomable-image");
    productImage.addEventListener("click", zoomIn);
    productImage.alt = selectedProduct.productName;

    if (productDetailsDimensions) {
        productDetailsDimensions.textContent = selectedProduct.productDimensions;
    }
    
    selectedProduct.productCategory = selectedProduct.productCategory.split(",")[0]
    
    const mappedObject = {productCategory: selectedProduct.productCategory};
    const displayString = propertyMapping[mappedObject.productCategory];

    productDetailsName.textContent = selectedProduct.productName.toUpperCase();
    productDetailsMenuCode.textContent = selectedProduct.productCode;

    if (productDetailsLine) {
        productDetailsLine.textContent = displayString;
    }

    productPrice.textContent = selectedProduct.productPrice;
    pPrice.value = selectedProduct.productPrice;
    pName.value = selectedProduct.productName;
    pSystemCode.value = selectedProduct.productSystemCode;

    if (!selectedProduct.productMaterial) {
        materialItem.classList.add("__hide");
    } else {
        materialItem.style.display = "block";
        if (productDetailsMaterial) {
            productDetailsMaterial.textContent = selectedProduct.productMaterial;
        }
    }

    if (!selectedProduct.productDimensions) {
        dimensionsItem.classList.add("__hide");
    } else {
        dimensionsItem.style.display = "block";
    }
    
    if (selectedProduct.correspondentProductCode) {
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

    document.querySelectorAll(".product-details__image__wrapper").forEach(function(wrapper) {
        wrapper.addEventListener('click', function(event) {
        if (event.target.id === 'next-arrow' || event.target.id === 'prev-arrow') {
                let direction = event.target.id === 'next-arrow' ? 1 : -1;
                currentIndex += direction;
                if (currentIndex >= images.length) {
                    currentIndex = 0;
                } else if (currentIndex < 0) {
                    currentIndex = images.length - 1;
                }
                document.getElementById("product-details__image__content").src = images[currentIndex];
            }
        });
    });
}

export { generateProductDetails };