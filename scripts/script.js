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
});

function toggleMenu() {
    menuOpened = !menuOpened;
    menu.setAttribute("data-menu-open", menuOpened);
    bcContainer.setAttribute("data-menu-open", menuOpened);
    document.body.setAttribute("data-menu-open", menuOpened);
}

/*
BATTLE
*/
function pauseIframe() {
    console.log("pause");
    iframe.contentWindow.postMessage( '{"event":"command", "func":"pauseVideo", "args":""}', '*');
    pressedButton.setAttribute("data-isPlaying", "false");
    pressedButton.title="Riproduci " + newTitle;
}

function playIframe() {
    console.log("play");
    iframe.contentWindow.postMessage('{"event":"command","func":"' + 'playVideo' + '","args":""}', '*');
    pressedButton.setAttribute("data-isPlaying", "true");
    pressedButton.title="Interrompi " + newTitle;
}

function newIframe(){
    //settaggio iframe
    link=thisBattle.getElementsByTagName("a")[0].href;
    actualTitle.innerHTML=newTitle;
    iframe.src=link;
    iframe.title=newTitle;
    

    //settaggio bottone della canzone precedentemente in riproduzione
    for(i=0;i<descBattles.length;i++){
        buttonPP=descBattles[i].getElementsByTagName("button")[0];
        if(buttonPP.title.substr(0,10)=="Interrompi"){
            //console.log(buttonPP.title + "==Interrompi " + newTitle);
            buttonPP.setAttribute("data-isPlaying", "false");
            buttonPP.title="Riproduci " + newTitle;
        }
    }

    //settaggio bottone nuova canzone
    pressedButton.setAttribute("data-isPlaying", "true");
    pressedButton.title="Interrompi " + newTitle;   
}
 
function setIframe(battle){
    //variabili varie
    descBattles=document.getElementsByClassName("descBattle");
    thisBattle=descBattles[battle];
    pressedButton=thisBattle.getElementsByTagName("button")[0];
    iframe=document.getElementById("iframe_battle");
    actualTitle=document.getElementsByTagName("h3")[0];
    newTitle=thisBattle.getElementsByTagName("a")[0].title;

    
    if(pressedButton.title.substr(0,10)=="Interrompi"){
        pauseIframe();
    }else{
        if(actualTitle.innerHTML==newTitle){
            playIframe();
        }else{
            newIframe();
        }
    }
}


/*
BASI
*/

function newBeat(){
    //cambia gli statement dell'audio che era in riproduzione precedentemente
    for(let i=0;i<beats.length;i++){
        buttonPP=beats[i].getElementsByTagName("button")[0];
        if(buttonPP.title.substr(0,10)=="Interrompi"){
            buttonPP.setAttribute("data-isPlaying","false")
            buttonPP.title="Riproduci " + buttonPP.title.substr(10);
        } 
    }
    //settaggio title
    pressedButton.setAttribute("data-isPlaying","true")
    actualTitle.innerHTML=newTitle; 
    pressedButton.title="Interrompi " + newTitle;
    
    //settaggio audio
    audio.setAttribute("autoplay", "true");
    audio.src = percorso + newTitle + ".mp3"; 
}

function setAudioDuration(){
    span=document.getElementsByClassName("durata");
    audios=document.getElementsByClassName("audioBeats");
    for(let i=0;i<span.length;i++){
        audios[i].setAttribute("data-java","true");
        durata=Math.floor(audios[i].duration/60) + ":" + Math.floor(audios[i].duration%60);
        minuti=durata.slice(0,durata.indexOf(":"));
        secondi=durata.slice(durata.indexOf(":")+1);
        if(secondi.length==1){
            span[i].innerHTML = minuti + ":0" + secondi; 
        }else{
            span[i].innerHTML = durata; 
        }
    }
}


var autoNext=false;
function playerAudio(nomeBase) {
    
    //variabili varie
    percorso="assets/media/basi/";
    audio = document.getElementById("audio");
    audioContainer=document.getElementById("audio_container");
    actualTitle = audioContainer.getElementsByTagName("h3")[0];
    newTitle = nomeBase.slice(0,-4);
    beats = document.getElementsByClassName("beat")
    for(let i=0;i<beats.length;i++){
        if(beats[i].getElementsByTagName("button")[0].title.slice(10)==newTitle || beats[i].getElementsByTagName("button")[0].title.slice(11)==newTitle){
            pressedButton=beats[i].getElementsByTagName("button")[0];
        }
    }
    
    if(actualTitle.innerHTML==newTitle){
        if(pressedButton.title.slice(0,10)=="Interrompi"){
            console.log("pause");
            audio.pause();
            pressedButton.setAttribute("data-isPlaying","false")
            pressedButton.title="Riproduci " + newTitle;

        }else{
            console.log("play");
            audio.play();
            pressedButton.setAttribute("data-isPlaying","true")
            pressedButton.title="Interrompi " + newTitle;
        }    
    }else{
        newBeat();
    }
    
    //bottone riproduzione automatica
    document.getElementById("autoNext").onclick = function() {
        autoNext = !autoNext;
        console.log(autoNext);
        autoPlay(nomeBase);
    }
}


function autoPlay(nomeBase){
   
    
    if(autoNext){
        audio.onended = function() {
            audio.setAttribute("autoplay", "true");
            console.log("nomeBase");
            nextAudio(nomeBase);

        }
    }else{
        audio.onended = function() {
            audio.setAttribute("autoplay", "false");
        }
    } 
    
}

function nextAudio(nomeBase) {  
    console.log("dentro nextAudio");
    for (let i = 0; i < beats.length; i++) {
       
        bottone = beats[i].getElementsByTagName("button")[0]
        //console.log(bottone.getAttribute("title").slice(10));
        console.log(bottone.getAttribute("title").slice(10)+"=="+nomeBase.slice(0,-4));    
        if (bottone.getAttribute("title").slice(10) == nomeBase.slice(0,-4)) {
            let next = beats[i+1];
            if (next) {
                let nextButton = next.getElementsByTagName("button");
                console.log(nextButton[0].getAttribute("title").slice(10));
                actualTitle=(nextButton[0].getAttribute("title").slice(10)+".mp3");
                playerAudio(actualTitle);
            }
            break;  
        }else{
            console.log(bottone.getAttribute("title").slice(11)+ "==" +nomeBase.slice(0,-4))
            if(bottone.getAttribute("title").slice(11) == nomeBase.slice(0,-4)){
                let next = beats[i+1];
                if (next) {
                    let nextButton = next.getElementsByTagName("button");
                    console.log(nextButton[0].getAttribute("title").slice(11)+".mp3");
                    actualTitle=(nextButton[0].getAttribute("title").slice(11)+".mp3");

                    playerAudio(actualTitle);
                }
                break;  
            }
        }
    }
}




/*
CLASSIFICHE
*/

function hideSubmitButtons() {
    var submitButtons = document.getElementsByClassName("hidden-by-js");
    for (let i = 0; i < submitButtons.length; i++) {
        submitButtons[i].classList.add("no-script");
    }
}