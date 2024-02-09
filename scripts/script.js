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

function playerAudio(nomeBase) {
    percorso="assets/media/basi/";
    container = document.getElementById("audio_container");
    container.innerHTML ='<h3>' + nomeBase.slice(0,-4) + '</h3><audio controls autoplay id="audio"><source src="' + percorso + nomeBase + '" type="audio/mpeg"></audio>';
    //document.getElementById("audio").onended = playerAudioNext(nomeBase); 
    //Non fa "onended", forse perchè la funzione viene eseguita da un onclick e quindi vanno in conflitto?

}

function playerAudioNext(nomeBase) { //forse devo include playerAudioNext()
    let basi = document.getElementsByClassName("base");
    for (let i = 0; i < basi.length; i++) {
        if (basi[i].getAttribute("title") == nomeBase.slice(0,-4)) {
            let next = basi[i+1];
            if (next) {
                title=(next.getAttribute("title")+".mp3");
                console.log(title);
                playerAudio(title);
            }
            break
            
        }
    }
}