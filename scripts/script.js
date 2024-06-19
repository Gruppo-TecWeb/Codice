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

    menu.classList.add("js");
    bcContainer.classList.add("js");
    document.body.classList.add("js");

    document.addEventListener("click", (event) => { // Quando clicco fuori dal menù, lo nascondo
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
 * PAGINA EVENTO
 */

function init_evento() {
    linkIndietro = document.getElementById("indietro");
    if (document.referrer.includes("eventi.php"))
        linkIndietro.setAttribute('href', document.referrer);
}

/*
 * PAGINA INDEX
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
 * PAGINA MODALITA'
 */

var player;
var descBattles;
var pressedButton;
var actualTitle;
var newTitle;
var thisBattle;

function onYouTubeIframeAPIReady() {
    player = new YT.Player('iframe_battle', {
        events: {
            'onStateChange': onPlayerStateChange
        }
    });
}

function onPlayerStateChange(event) {
    if (event.data == YT.PlayerState.PAUSED) {
        pressedButton.setAttribute("data-isPlaying", "false");
        pressedButton.title = "Riproduci " + newTitle;
    }
    if (event.data == YT.PlayerState.PLAYING) {
        pressedButton.setAttribute("data-isPlaying", "true");
        pressedButton.title = "Interrompi " + newTitle;
    }
}

function initIframe() {
    descBattles = document.getElementsByClassName("descBattle");
    actualTitle = document.getElementsByTagName("h3")[1];
    var idDescVideo = []; 

    for (var i = 0; i < descBattles.length; i++) {
        descVideo = descBattles[i].getElementsByTagName("p")[0];
        descVideo.setAttribute("id","desc_"+i);
        
        idDescVideo[i]=descVideo.getAttribute("id");
        console.log(idDescVideo[i]);
        console.log(descVideo);
    }

    
    
    iframe = document.getElementById("iframe_battle");
    iframe.setAttribute("aria-describedby",idDescVideo[0]);

    thisBattle = descBattles[0];
    newTitle = thisBattle.getElementsByTagName("strong")[0].innerHTML;;
    pressedButton = thisBattle.getElementsByTagName("button")[0];
}

function setIframe(battle) {
    thisBattle = descBattles[battle];
    pressedButton = thisBattle.getElementsByTagName("button")[0];
    newTitle = thisBattle.getElementsByTagName("strong")[0].innerHTML;

    if (pressedButton.title.substr(0, 10) == "Interrompi") {
        player.pauseVideo();
        pressedButton.setAttribute("data-isPlaying", "false");
        pressedButton.title = "Riproduci " + newTitle;
    } else {
        if (actualTitle.innerHTML == newTitle) {
            player.playVideo();
            pressedButton.setAttribute("data-isPlaying", "true");
            pressedButton.title = "Interrompi " + newTitle;
        } else {
            iframe.setAttribute("aria-describedby","desc_"+battle);
            newIframe();
        }
    }
}

function newIframe() {
    var link = pressedButton.getAttribute("data-link");
    actualTitle.innerHTML = "Esempio "+ newTitle;
    thisBattle.getElementsByClassName("playerJump")[0].setAttribute("aria-hidden", "false");
    thisBattle.getElementsByClassName("playerJump")[0].setAttribute("tabindex", "0");
    var videoId = link.split('embed/')[1].split('?')[0];
    var start = pressedButton.getAttribute("data-start");
    var end = pressedButton.getAttribute("data-end");

    player.loadVideoById({
        videoId: videoId,
        startSeconds: start,
        endSeconds: end
    });

    for (var i = 0; i < descBattles.length; i++) {
        if(descBattles[i] != thisBattle){
            descBattles[i].getElementsByClassName("playerJump")[0].setAttribute("aria-hidden", "true");
            descBattles[i].getElementsByClassName("playerJump")[0].setAttribute("tabindex", "-1");
        }
        var buttonPP = descBattles[i].getElementsByTagName("button")[0];
        if (buttonPP.title.substr(0, 10) == "Interrompi") {
            buttonPP.setAttribute("data-isPlaying", "false");
            buttonPP.title = "Riproduci " + newTitle;
        }
        
    }

    pressedButton.setAttribute("data-isPlaying", "true");
    pressedButton.title = "Interrompi " + newTitle;
}







/*
 * PAGINA BEATS
 */

function init_beats() {
    setDurationBeats();
    pressedButton = document.getElementsByClassName("beat")[0].getElementsByTagName("button")[0];
    document.getElementById("audio").addEventListener("play", function() {
        pressedButton.setAttribute("data-isPlaying", "true")
        pressedButton.title = "Interrompi " + newTitle;
        pressedButton.setAttribute("aria-label","Interrompi " + newTitle);

    });

    document.getElementById("audio").addEventListener("pause", function() {
        pressedButton.setAttribute("data-isPlaying", "false")
        pressedButton.title = "Riproduci " + newTitle;
        pressedButton.setAttribute("aria-label","Riproduci " + newTitle);
    });

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
    descrizione.setAttribute("aria-hidden", show === "true" ? "true" : "false");

    btnDescrizione.setAttribute("data-show", show === "true" ? "false" : "true");
    btnDescrizione.setAttribute("aria-expanded", show === "true" ? "false" : "true");  
    btnDescrizione.getElementsByTagName("span")[0].innerHTML = show === "true" ? "Audio descrizione" : "Nascondi";
}


function setDurationBeats() {
    const beats = document.getElementsByClassName("beat");
    for (let i = 0; i < beats.length; i++) {
        const time = document.getElementsByTagName("time");
        const durata = document.getElementsByClassName("durata")[i];
        const readDurata = document.getElementsByClassName("readDurata")[i];

        audiosTitle = document.getElementsByClassName("btnPlay")[i].getAttribute("data-title-beat");
        const audio = new Audio("assets/media/basi/" + audiosTitle + ".mp3");

        audio.addEventListener('loadedmetadata', () => {
            const minuti = Math.floor(audio.duration / 60);
            const secondi = Math.floor(audio.duration % 60);
            const datatime = "PT" + minuti + "M" + secondi + "S";
            time[i].setAttribute("datetime",datatime);
            if (minuti == 1) {
                if (secondi < 10) {
                    durata.innerHTML = minuti + ":" + "0" + secondi;
                    readDurata.innerHTML = minuti + " minuto e " + secondi + " secondi";
                } else {
                    durata.innerHTML = minuti + ":" + secondi;
                    readDurata.innerHTML = minuti + " minuto e " + secondi + " secondi";
                }
            } else {
                if (secondi < 10) {
                    durata.innerHTML = minuti + ":" + "0" + secondi;
                    readDurata.innerHTML = minuti + " minuti e " + secondi + " secondi";
                } else {
                    durata.innerHTML = minuti + ":" + secondi;
                    readDurata.innerHTML = minuti + " minuti e " + secondi + " secondi";
                }
            }
        });
    }
}


var autoNext = false;

function playerAudio(nomeBase) {
    document.getElementById("audio").addEventListener("play", function() {
        pressedButton.setAttribute("data-isPlaying", "true")
        pressedButton.title = "Interrompi " + newTitle;
    });

    document.getElementById("audio").addEventListener("pause", function() {
        pressedButton.setAttribute("data-isPlaying", "false")
        pressedButton.title = "Riproduci " + newTitle;
    });
    //variabili varie
    percorso = "assets/media/basi/";
    audio = document.getElementById("audio");
    audioContainer = document.getElementById("audio_container");
    h3 = audioContainer.getElementsByTagName("h3")[0];
    newTitle = nomeBase.slice(0, -4);
    beats = document.getElementsByClassName("beat")

    for (let i = 0; i < beats.length; i++) {
        if (beats[i].getElementsByTagName("button")[0].getAttribute("data-title-beat") == nomeBase.slice(0, -4)) {
            pressedButton = beats[i].getElementsByTagName("button")[0];
            audioJump = beats[i].getElementsByTagName("a")[0];
            audioJump.setAttribute("tabindex", "0");
            audioJump.setAttribute("aria-hidden", "false");

            /*playerJump = beats[i].getElementsByTagName("a")[0].getElementsByTagName("span")[0];
            playerJump.setAttribute("aria-hidden", "false");*/
        }else{
            audioJump = beats[i].getElementsByTagName("a")[0];
            audioJump.setAttribute("tabindex", "-1");
            audioJump.setAttribute("aria-hidden", "true");
        }
    }


    if (h3.innerHTML == newTitle) {
        if (pressedButton.title.slice(0, 10) == "Interrompi") {
            audio.pause();

        } else {
            audio.play();

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
        //audioJump.setAttribute("tabindex", "0");
        //audioJump.setAttribute("aria-hidden", "false");
        //playerJump.setAttribute("aria-hidden", "false");
        

        if (buttonPP.title.substr(0, 10) == "Interrompi") {
            audioJump.setAttribute("tabindex", "-1");
            audioJump.setAttribute("aria-hidden", "true");

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
        autoRip = document.getElementById("autoNext");
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
    newTitle = nomeBase.slice(0, -4);
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

/*
 * PAGINE ADMIN
 */

// per ogni button con name=elimina, aggiungo un event listener che chiede conferma prima di eliminare l'elemento; se viene premuto annulla, interrompo l'invio del form
document.addEventListener('DOMContentLoaded', function() {
    var deleteButtons = document.querySelectorAll('button[name="elimina"]');
    for (let i = 0; i < deleteButtons.length; i++) {
        deleteButtons[i].addEventListener('click', function(event) {
            var confirmDelete = confirm('Sei sicuro di voler eliminare questo elemento?');
            if (!confirmDelete) {
                event.preventDefault();
            }
        });
    }
});