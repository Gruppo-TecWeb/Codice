var navOpened = false;

function toggleMenu() {
    var nav = document.getElementById("menu");
    var bcContainer = document.getElementById("breadcrumbs-container");
    navOpened = !navOpened;
    nav.setAttribute("data-opened", navOpened);
    bcContainer.style.minWidth = navOpened ? "90%" : "";
    document.body.style.overflowY = navOpened ? "hidden" : "unset";
}