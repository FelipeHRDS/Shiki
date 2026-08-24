import { setCookie, getCookie } from '../cookieFunctions.js';

const popup = document.querySelector(".popup__body");
const overlay = document.querySelector(".popup__screen-overlay");

window.addEventListener('DOMContentLoaded', function() {
    document.getElementsByClassName("nav-bar__mobile__hamburger-menu__links")[0].classList.add("__hide");

    const popupClosed = getCookie('sPCl');

    if (popupClosed === 'true') {
        popup.classList.add("__hide");
        overlay.classList.add("__hide");
    } else {
        popup.classList.remove("__hide");
        overlay.classList.remove('__hide');
    }
});


overlay.addEventListener("click", (event) => {
    if(event.target === overlay) { closePopup() }
});

document.getElementById("popup__close-button").addEventListener("click", () => { closePopup(); }
);

document.addEventListener("keydown", (event) => {
    if (event.key === "Escape") { closePopup(); }
});

function closePopup() {
    popup.classList.add("__hide");
    overlay.classList.add("__hide");
    setCookie('sPCl', 'true', 3);
}