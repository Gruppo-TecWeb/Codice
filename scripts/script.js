var navOpened = false;

function toggleMenu() {http://localhost/index.php
    var nav = document.getElementById("menu");
    var bcContainer = document.getElementById("breadcrumbs-container");
    navOpened = !navOpened;
    nav.setAttribute("data-menu-open", navOpened);
    bcContainer.setAttribute("data-menu-open", navOpened);
    document.body.setAttribute("data-menu-open", navOpened);
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

function getDuration() {
    audio.onloadedmetadata( function(){
        alert(audio.duration);
    });
}

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
   console.log(audio.duration)

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
        autoPlay(nomeBase);
    }
        autoPlay(nomeBase);
      
}


function autoPlay(nomeBase){
    if(autoNext){
        audio.onended = function() {
            nextAudio(nomeBase);  
        }
    }else{
        audio.onended = function() {
            audio.setAttribute("autoplay", "false");
        }
    } 
}

function nextAudio(nomeBase) {
    let basi = document.getElementsByClassName("beat");   
    
    for (let i = 0; i < basi.length; i++) {


        let bottone = basi[i].getElementsByTagName("button");    
        if (bottone[0].getAttribute("title").slice(10) == nomeBase.slice(0,-4)) {
            let next = basi[i+1];
            if (next) {
                let nextButton = next.getElementsByTagName("button");
                actualTitle=(nextButton[0].getAttribute("title").slice(10)+".mp3");
                playerAudio(actualTitle);
            }
            break;  
        }
    }
}





/*
CLASSIFICHE
*/

function hideSubmitButtons(){
    var submitButtons = document.getElementsByClassName("hidden-by-js");
    for (let i = 0; i < submitButtons.length; i++) {
        submitButtons[i].classList.add("no-script");
    }
}