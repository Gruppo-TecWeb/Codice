/*
 * TUTTE LE PAGINE
 */

var menu = null;
var bcContainer = null;
var menuOpened = false;

window.addEventListener("load", (event) => { // Quando questo script si avvia, aggiungo la classe "js" cosi' da abilitare il menu a scomparsa
    menu = document.getElementById("menu");
    bcContainer = document.getElementById("breadcrumbs-container");
    menuOpened = menu.getAttribute("data-menu-open") === "true" ? true : false;
    toggleMenu();

    menu.classList.add("js");
    bcContainer.classList.add("js");
    document.body.classList.add("js");

    document.addEventListener("click", (event) => {
        if (menu.getAttribute("data-menu-open") === "true" && !menu.contains(event.target) && !bcContainer.contains(event.target))
            toggleMenu();
    });
});

function toggleMenu() {
    menuOpened = !menuOpened;
    menu.setAttribute("data-menu-open", menuOpened);
    bcContainer.setAttribute("data-menu-open", menuOpened);
    document.body.setAttribute("data-menu-open", menuOpened);
}

// funzione che permette di tornare alla pagina precedente
function tornaIndietro() {
    window.history.back();
    return true;
}

/*
 * PAGINA BEATS
 */

function init_index() {
    const logo = document.querySelector('header a h1');
    const hero = document.querySelector('#hero h2');

    logo.classList.add('js');

    document.querySelector('header>a').setAttribute('href', '#content');

    window.addEventListener('scroll', function() {
        var position = hero.getBoundingClientRect();

        if (position.bottom < 0) {
            logo.classList.add('scrolled');
        } else {
            logo.classList.remove('scrolled');
        }
    });
}

/*
 * PAGINA EVENTO
 */

function init_evento() {
    linkIndietro = document.getElementById("indietro");
    if (document.referrer.includes("eventi.php"))
        linkIndietro.setAttribute('href', document.referrer);
}

/*
 * PAGINA BEATS
 */

function init_beats() {
    descrizioni = document.getElementsByClassName("descBeats");
    for (let i = 0; i < descrizioni.length; i++) {
        descrizioni[i].setAttribute("data-show", "false");
    }
    btnDescrizioni = document.getElementsByClassName("btnDesc");
    for (let i = 0; i < btnDescrizioni.length; i++) {
        btnDescrizioni[i].setAttribute("data-show", "false");
        btnDescrizioni[i].addEventListener("click", (event) => {
            showDescription(i);
        });
    }
}

function showDescription(index) {
    descrizione = document.getElementsByClassName("descBeats")[index];
    btnDescrizione = document.getElementsByClassName("btnDesc")[index];
    show = descrizione.getAttribute("data-show");
    descrizione.setAttribute("data-show", show === "true" ? "false" : "true");
    btnDescrizione.setAttribute("data-show", show === "true" ? "false" : "true");
    btnDescrizione.getElementsByTagName("span")[0].innerHTML = show === "true" ? "Audio descrizione" : "Nascondi";
}


function onJavaScript() {
    beats = document.getElementsByClassName("beat");

    for (let i = 0; i < beats.length; i++) {
        durata = document.getElementsByClassName("durata")[i];
        readDurata = document.getElementsByClassName("readDurata")[i];

        playerJump = beats[i].getElementsByTagName("a")[0].getElementsByTagName("span")[0];
        playerJump.setAttribute("aria-hidden", "true");

        audios = document.getElementsByClassName("audioBeats")[i];
        audios.setAttribute("tabindex", "-1");
        audios.setAttribute("data-java", "true");

        minuti = Math.floor(audios.duration / 60);
        secondi = Math.floor(audios.duration % 60);

        if (minuti == 1) {
            if (secondi < 10) {
                //durata.setAttribute("datatime","PT" + minuti + "M" + secondi + "S");
                durata.innerHTML = minuti + ":" + "0" + secondi;
                readDurata.innerHTML = minuti + " minuto e " + secondi + " secondi";
            } else {
                //durata.setAttribute("datatime","PT" + minuti + "M" + secondi + "S");
                durata.innerHTML = minuti + ":" + secondi;
                readDurata.innerHTML = minuti + " minuto e " + secondi + " secondi";
            }
        } else {
            if (secondi < 10) {
                //durata.setAttribute("datatime","PT" + minuti + "M" + secondi + "S");
                durata.innerHTML = minuti + ":" + "0" + secondi;
                readDurata.innerHTML = minuti + " minuti e " + secondi + " secondi";
            } else {
                //durata.setAttribute("datatime","PT" + minuti + "M" + secondi + "S");
                durata.innerHTML = minuti + ":" + secondi;
                readDurata.innerHTML = minuti + " minuti e " + secondi + " secondi";
            }
        }
    }
}


var autoNext = false;

function playerAudio(nomeBase) {

    //variabili varie
    percorso = "assets/media/basi/";
    audio = document.getElementById("audio");
    audioContainer = document.getElementById("audio_container");
    h3 = audioContainer.getElementsByTagName("h3")[0];
    newTitle = nomeBase.slice(0, -4).replaceAll("-", " ");
    beats = document.getElementsByClassName("beat")
    for (let i = 0; i < beats.length; i++) {
        if (beats[i].getElementsByTagName("button")[0].getAttribute("data-title-beat") == nomeBase.slice(0, -4)) {
            pressedButton = beats[i].getElementsByTagName("button")[0];
            audioJump = beats[i].getElementsByTagName("a")[0];
            audioJump.setAttribute("tabindex", "0");

            playerJump = beats[i].getElementsByTagName("a")[0].getElementsByTagName("span")[0];
            playerJump.setAttribute("aria-hidden", "false");
        }
    }


    if (h3.innerHTML == newTitle) {
        if (pressedButton.title.slice(0, 10) == "Interrompi") {
            audio.pause();
            pressedButton.setAttribute("data-isPlaying", "false")
            pressedButton.title = "Riproduci " + newTitle;
        } else {
            audio.play();
            pressedButton.setAttribute("data-isPlaying", "true")
            pressedButton.title = "Interrompi " + newTitle;
        }
    } else {
        newBeat(nomeBase);
    }

    //bottone riproduzione automatica
    autoPlay(nomeBase);
}

function newBeat(nomeBase) {
    //cambia gli statement dell'audio che era in riproduzione precedentemente
    for (let i = 0; i < beats.length; i++) {
        buttonPP = beats[i].getElementsByTagName("button")[0];
        audioJump = beats[i].getElementsByTagName("a")[0];
        playerJump.setAttribute("aria-hidden", "false");

        if (buttonPP.title.substr(0, 10) == "Interrompi") {
            audioJump.setAttribute("tabindex", "-1");

            buttonPP.setAttribute("data-isPlaying", "false")
            buttonPP.title = "Riproduci " + buttonPP.getAttribute("data-title-beat");
        }
    }
    //settaggio title bottone e player audio
    pressedButton.setAttribute("data-isPlaying", "true")
    h3.innerHTML = newTitle;
    pressedButton.title = "Interrompi " + newTitle;

    //settaggio audio
    audio.setAttribute("autoplay", "true");
    audio.src = percorso + nomeBase;
}


function autoPlay(nomeBase) {
    document.getElementById("autoNext").onclick = function() {
        autoNext = !autoNext;
        autoRip=document.getElementById("autoNext");
        autoRip.setAttribute("aria-pressed", autoNext);
    }
    audio.onended = function() {
        if (autoNext) {
            //autoplay.setAttribute("", "true");
            audio.setAttribute("autoplay", "true");
            nextAudio(nomeBase);
        } else {

            audio.setAttribute("autoplay", "false");

        }
    }
}

function nextAudio(nomeBase) {
    newTitle = nomeBase.slice(0, -4).replaceAll("-", " ");
    beats = document.getElementsByClassName("beat")
    for (let i = 0; i < beats.length; i++) {
        if (beats[i].getElementsByTagName("button")[0].getAttribute("data-title-beat") == nomeBase.slice(0, -4)) {
            pressedButton = beats[i].getElementsByTagName("button")[0];
        }
    }

    for (let i = 0; i < beats.length; i++) {
        bottone = beats[i].getElementsByTagName("button")[0]
        if (bottone.getAttribute("data-title-beat") == nomeBase.slice(0, -4)) {
            let next = beats[i + 1];
            if (next) {
                let nextButton = next.getElementsByTagName("button");
                newTitle = nextButton[0].getAttribute("data-title-beat") + ".mp3";
                playerAudio(newTitle);
            }
            break;
        }
    }
}