const CATALOG_CARD_SELECTOR = ".catalog__card";
const SEARCH_INPUT_SELECTOR = "catalog__side-bar__search__input-field";
const SEARCH_BUTTON_SELECTOR = ".catalog__side-bar__search__search-button";
let oldCategory = "";
const melamina_subcategories = ["Nuage", "Oceano", "Midori", "DM", "BC", "RD", "BR", "N", "F", "PF-Hana", "PF-Sólido", "Especiais", "Porcelana", "Turquesa"];
const embalagem_subcategories = ["LinhaSK", "SushiAntiFog", "BoxPapel", "Teishoku", "PoteMolho", "BowlBranco", "BowlKraft", "BowlPS", "BowlPP", "KraftBox", "Eventos", "CoffeeCup", "Canudo"];
const noneFoundAlert = document.getElementById("catalog__none-found-alert");

function getCategoryFromURL() {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get('category');
}

const category = getCategoryFromURL();
if (category) {
    filterElements(category);
}


function filterSubCat(category, subcategory) {
   const foundItems = [];
   category = category.toLowerCase();
   //subcategory = subcategory.toLowerCase();
   
   profiles.forEach((product) => {
       if (product.classList.contains(category) && product.getAttribute("data-product-subcat") == subcategory) {
           foundItems.push(product)
       }
   });
   console.log(foundItems);
   
    while (catalogGrid.firstChild) {
        catalogGrid.removeChild(catalogGrid.firstChild); 
    }
    foundItems.forEach((item) => {
        catalogGrid.appendChild(item);
        item.classList.remove("__hide");
    }
    );
}

async function repaintArticle(newCategory) {
    if (oldCategory !== newCategory) {
        oldCategory = newCategory;
        const repaintedImageContent = document.getElementsByClassName("article__image__content")[0];
        const repaintedTitle = document.getElementsByClassName("article__title")[0];
        const repaintedTexts = document.getElementsByClassName("article__text__content");
        const repaintedButton = document.getElementsByClassName("form__cta-button")[0];
        
        newContent = findNewContent(newCategory);
        
        repaintedImageContent.setAttribute("src", newContent[0]);
        repaintedTitle.innerHTML = newContent[1];
        for (let i = 0; i < repaintedTexts.length && i < 2; i++) {
            repaintedTexts[i].innerHTML = newContent[i + 2];
        }
        
        if (["Melamina", "Nuage", "Midori", "Oceano", "Embalagem", "Utilidade", "Espetos"].includes(newCategory) || embalagem_subcategories.includes(newCategory) || melamina_subcategories.includes(newCategory)) {
            repaintedButton.classList.add("__hide");
        } else {
            repaintedButton.classList.remove("__hide");
        } 
    }
}

function findNewContent(category) {
    switch (category) {
        case "Hashi":
            return ["../media/hashi-personalizado.jpg", "HASHI PERSONALIZADO", "Deixe uma impressão duradoura nos seus clientes com <span style=\"font-weight: bold; color:#b90000;\">hashis personalizados</span> que apresentam o logotipo e a identidade visual da sua marca. Crie uma conexão instantânea com seu público.", "Nossos serviços de personalização de embalagens de hashi são a maneira perfeita de diferenciar seu negócio e encantar seus clientes. Entre em contato conosco hoje mesmo para iniciar o processo de personalização e elevar sua marca a um novo nível."];
        case "Nuage":
        case "Oceano":
        case "Midori":
        case "DM":
        case "BC":
        case "RD":
        case "BR":
        case "N":
        case "F":
        case "PF-Hana":
        case "PF-Sólido":
        case "Especiais":
        case "Porcelana":
        case "Melamina":
            return ["../media/poster-melaminas.jpg", "MELAMINAS", "Conheça nossa linha de <span style=\"font-weight: bold; color:#6082b6;\">melaminas</span>, perfeita para realçar a sua experiência culinária, especialmente em pratos orientais. Incluindo pires, pratos, pratos com divisória, tigelas e muito mais, nossos produtos são fabricados com materiais de alta qualidade que garantem <span style=\"font-weight: bold; color:#6082b6;\">resistência e durabilidade</span> excepcionais, ideais para suportar o uso diário e o preparo de refeições típicas da culinária oriental.", "Além da funcionalidade, nossas melaminas se destacam pelas <span style=\"font-weight: bold; color:#6082b6;\">cores vibrantes e design elegante</span>, complementando perfeitamente a apresentação de pratos orientais. Com uma variedade de opções, você pode combinar e harmonizar suas peças para criar a mesa perfeita."];
        case "LinhaSK":
        case "SushiAntiFog":
        case "BoxPapel":
        case "Teishoku":
        case "PoteMolho":
        case "BowlBranco":
        case "BowlKraft":
        case "BowlPS":
        case "BowlPP":
        case "KraftBox":
        case "Eventos":
        case "CoffeeCup":
        case "Canudo":
        case "Embalagem":
            return ["../media/poster-embalagens.jpg", "EMBALAGENS", "Descubra a nossa linha de <span style=\"font-weight: bold; color:#228b22;\">embalagens sustentáveis</span>, pensada para atender todas as suas necessidades com responsabilidade ambiental. Oferecemos uma ampla variedade de produtos, desde potes para molho e sobremesa, salad bowls e tigelas de papel branco e papel kraft, até itens para eventos, como barquinhos de papel e canudos ecológicos.", "Com nossas <span style=\"font-weight: bold; color:#228b22;\">embalagens para sushi</span>, garantimos a proteção e frescor dos seus produtos, utilizando materiais que minimizam o impacto ambiental. Priorizamos o uso de <span style=\"font-weight: bold; color:#228b22;\">materiais recicláveis e biodegradáveis</span>, contribuindo para um futuro mais verde e sustentável. Escolha nossas embalagens e faça parte dessa mudança positiva para o planeta."];
        default:
            return ["../media/hashi-personalizado.jpg", "HASHI PERSONALIZADO", "Deixe uma impressão duradoura nos seus clientes com <span style=\"font-weight: bold; color:#b90000;\">hashis personalizados</span> que apresentam o logotipo e a identidade visual da sua marca. Crie uma conexão instantânea com seu público.", "Nossos serviços de personalização de embalagens de hashi são a maneira perfeita de diferenciar seu negócio e encantar seus clientes. Entre em contato conosco hoje mesmo para iniciar o processo de personalização e elevar sua marca a um novo nível."];
    }
}

document.querySelector(SEARCH_BUTTON_SELECTOR).addEventListener("click", async () => {
    let searchInput = document.getElementById(SEARCH_INPUT_SELECTOR).value.trim();
    searchProduct(searchInput);
});

document.getElementById(SEARCH_INPUT_SELECTOR).addEventListener("keydown", async (event) => {
    if (event.key === "Enter") {
        let searchInput = document.getElementById(SEARCH_INPUT_SELECTOR).value.trim();
        searchProduct(searchInput);
    }
});

function filterElements(value) {
    const foundItems = [];
    value = value.toLowerCase();
    profiles.forEach((product) => {
        if (product.classList.contains(value)) {
            foundItems.push(product);
        }
    })
    while (catalogGrid.firstChild) {
        catalogGrid.removeChild(catalogGrid.firstChild); 
    }
    foundItems.forEach((item) => {
        catalogGrid.appendChild(item);
        item.classList.remove("__hide");
    }
    );
}

function normalizeText(text) {
    return text.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase();
}

function searchProduct(userInput) {
    const foundItems = [];
    const searchWords = normalizeText(userInput).split(" ");

    profiles.forEach((item) => {
        const idText = normalizeText(item.id);
        const classText = normalizeText(item.classList.value);
        const codeText = normalizeText(item.code);

        const allWordsMatch = searchWords.every(word =>
            idText.includes(word) ||
            classText.includes(word) ||
            codeText.includes(word)
        );

        if (allWordsMatch) {
            foundItems.push(item);
        }
    });

    while (catalogGrid.firstChild) {
        catalogGrid.removeChild(catalogGrid.firstChild);
    }

    foundItems.forEach((item) => {
        catalogGrid.appendChild(item);
        item.classList.remove("__hide");
    });

    localStorage.setItem("lastSearch", userInput);
}

let buttons = document.querySelectorAll("button");

buttons.forEach(function(button) {
    button.addEventListener('click', function() {
    try {
        var filters = this.getAttribute('data-filter').split(',');
        if (filters.length > 1) {
            filterSubCat(filters[0], filters[1]);
        } else {
            filterElements(filters[0]);
            repaintArticle(filters[0]);
        }
    } catch {

    }
    }, false);
  });