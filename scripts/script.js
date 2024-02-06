var navOpened = false;

function toggleMenu() {
    var nav = document.getElementById("menu");
    var bcContainer = document.getElementById("breadcrumbs-container");
    navOpened = !navOpened;
    nav.setAttribute("data-menu-open", navOpened);
    bcContainer.setAttribute("data-menu-open", navOpened);
    document.body.setAttribute("data-menu-open", navOpened);
}

function init_eventi() {
    var urlParams = new URLSearchParams(window.location.search);
    var filtro = urlParams.get('filtro');
    if (filtro === 'data') {
        document.getElementById('data').classList.add('selected');
        document.getElementById('link-passati').classList.remove('selected');
        document.getElementById('link-tutti').classList.remove('selected');
        document.getElementById('link-prossimi').classList.remove('selected');
    } else {
        // Altrimenti, aggiungi la classe "selected" al link corrispondente
        document.getElementById('data-container').classList.remove('selected');
        if (filtro === 'passati') {
            document.getElementById('link-passati').classList.add('selected');
        } else if (filtro === 'tutti') {
            document.getElementById('link-tutti').classList.add('selected');
        } else {
            document.getElementById('link-prossimi').classList.add('selected');
        }
    }
}

function playerAudio(base) {
    percorso = "assets/media/basi/";
    container = document.getElementById("audio_container");
    container.innerHTML = '<h3>' + base.slice(0, -4) + '</h3><audio controls autoplay id="audio"><source src="' + percorso + base + '" type="audio/mpeg"></audio>';
}

function setFissaPlayerBase(fissato) {
    var bcContainer = document.getElementById("breadcrumbs-container");
    var playerBasi = document.getElementById("audio_container");
    bcContainer.setAttribute("data-player-fixed", fissato);
    playerBasi.setAttribute("data-player-fixed", fissato);
}

function observePlayerPinned() {
    var observer = new IntersectionObserver(function(entries) {

        alert(entries[0].intersectionRatio);
        if (entries[0].intersectionRatio === 1) {
            setFissaPlayerBase(true);
        } else {
            // alert(entries[0].intersectionRatio);
            setFissaPlayerBase(false);

        }

    }, { threshold: [0, 0.5, 1], rootMargin: "0px 0px 0px 0px", root: document.getElementById("basi_container") });

    observer.observe(document.querySelector("#audio_container"));
}