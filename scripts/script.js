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