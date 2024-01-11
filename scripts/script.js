var navOpened = false;

function toggleMenu() {
    var nav = document.getElementById("menu");
    var bcContainer = document.getElementById("breadcrumbs-container");
    navOpened = !navOpened;
    nav.setAttribute("data-menu-open", navOpened);
    bcContainer.setAttribute("data-menu-open", navOpened);
    document.body.setAttribute("data-menu-open", navOpened);
}