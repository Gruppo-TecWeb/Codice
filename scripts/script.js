var navOpened = false;

function toggleMenu() {
    var nav = document.getElementById("menu");
    navOpened = !navOpened;
    nav.setAttribute("opened", navOpened);
    document.body.style.overflowY = navOpened ? "hidden" : "unset";
}