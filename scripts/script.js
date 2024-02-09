var navOpened = false;

function toggleMenu() {
    var nav = document.getElementById("menu");
    var bcContainer = document.getElementById("breadcrumbs-container");
    navOpened = !navOpened;
    nav.setAttribute("data-menu-open", navOpened);
    bcContainer.setAttribute("data-menu-open", navOpened);
    document.body.setAttribute("data-menu-open", navOpened);
}

function onlyOnePlayer() {
    container = document.getElementById("lista_basi")
    container.addEventListener("play", function(event) {
        basi = container.getElementsByTagName("audio")
        for (i = 0; i < basi.length; i++) {
            base = basi[i];
            if (base !== event.target) {
                base.pause();
            }
        }
    }, true);
}