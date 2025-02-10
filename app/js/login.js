import {header} from "/app/js/header.js";
import {getURL} from "/app/js/helpers.js";

let footer = null;

if (getURL() === "") {
    import("/app/js/index.js")

    .then(module => {
        footer = module.footer;

        checkForTabIndex();
    });
}

const login = document.querySelector(".login");

if (login) {
    document.documentElement.style.overflowY = "hidden";
    document.body.style.overflowY = "hidden";

    Array.from(document.body.children).forEach(child => {
        if (child !== login) {
            child.style.filter = "blur(20px)";
            child.style.pointerEvents = "none";
            child.style.transition = "none";

            const preventTabIndex = element => {
                Array.from(element.children).forEach(child => {
                    child.tabIndex = "-1";
                    preventTabIndex(child);
                });
            }
                
            preventTabIndex(child);
        }
    })
} 

document.querySelector(".login button:last-child").addEventListener("click", () => {
    const username = document.querySelector(".login input:first-of-type").value;
    const password = document.querySelector(".login input:last-of-type").value;

    fetch("/php/login.php", {
        method: "POST",
        body: JSON.stringify({
            username: username,
            password: password
        })
    })

    .then(response => response.json())
    .then(data => window.location.href = data);
});

const checkForTabIndex = () => {
    if (header) {
        header.headerTabIndex(-1, -1, -1);
    }

    if (footer && footer.lastButton) {
        footer.lastButton.removeEventListener("keydown", footer.lastButtonHandler);
    }
};

document.querySelector(".login button:first-child").addEventListener("click", () => {
    window.location.href = getURL();
});

document.addEventListener("DOMContentLoaded", () => {

    checkForTabIndex();
        
    window.addEventListener("resize", checkForTabIndex);
        
});