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


/*
BASI
*/
var autoNext=false;
function playerAudio(nomeBase) {
    percorso="assets/media/basi/";
    //settaggio title
    title=document.getElementsByTagName("h3")[0]
    title.innerHTML=nomeBase.slice(0,-4); 
    //settaggio audio
    audio = document.getElementById("audio");
    audio.setAttribute("autoplay", "true");
    audio.src = percorso + nomeBase;
    //cambio audio automatico finito il beat
    console.log(autoNext);
    if(autoNext){
        document.getElementById("audio").onended = function() {
            console.log(autoNext);
            nextAudio(nomeBase);
        }
    }
    
}

function autoPlay() {
    autoNext = !autoNext;
}

function nextAudio(nomeBase) {
    let basi = document.getElementsByClassName("base");   

    for (let i = 0; i < basi.length; i++) {
        if (basi[i].getAttribute("title") == nomeBase.slice(0,-4)) {
            let next = basi[i+1];
            if (next) {
                title=(next.getAttribute("title")+".mp3");
                playerAudio(title);
            }
            break    
        }
    }
}