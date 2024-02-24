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
    let video = document.getElementById("iframe_battle")
    video.contentWindow.postMessage( '{"event":"command", "func":"pauseVideo", "args":""}', '*');
    pressedButton.style.backgroundImage='url("../assets/icons/Play.png")';
    pressedButton.title="Riproduci " + newTitle;
}

function playIframe() {
    console.log("play");
    let video = document.getElementById("iframe_battle")
    video.contentWindow.postMessage('{"event":"command","func":"' + 'playVideo' + '","args":""}', '*');
    pressedButton.style.backgroundImage='url("../assets/icons/Pause.png")';
    pressedButton.title="Interrompi " + newTitle;
}

function newIframe(){

    //settaggio iframe
    link=thisBattle.getElementsByTagName("a")[0].href;
    actualTitle.innerHTML=newTitle;
    iframe.src=link;
    iframe.title=newTitle;

    //settaggio bottone pause nel caso di switch canzone
    for(i=0;i<descBattles.length;i++){
        buttonPP=descBattles[i].getElementsByTagName("button")[0];
        if(buttonPP.title.substr(0,10)=="Interrompi"){
            //console.log(buttonPP.title + "==Interrompi " + newTitle);
            buttonPP.style.backgroundImage='url("../assets/icons/Play.png")';
            buttonPP.title="Riproduci " + newTitle;
        }
    }
        pressedButton.style.backgroundImage='url("../assets/icons/Pause.png")';
        pressedButton.title="Interrompi " + newTitle;
}
 
function setIframe(battle){

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

    for(let i=0;i<beats.length;i++){
        buttonPP=beats[i].getElementsByTagName("button")[0];
        if(buttonPP.title.substr(0,10)=="Interrompi"){
            buttonPP.style.backgroundImage='url("../assets/icons/Play.png")';
            buttonPP.title="Riproduci " + newTitle;
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
    pressedButton=beats[0].getElementsByTagName("button")[0];
    
    for(let i=0;i<beats.length;i++){
        if(actualTitle.innerHTML==newTitle){
            pressedButton=beats[i].getElementsByTagName("button")[0];
            //console.log(pressedButton);
            break;
        }
    }

     //settaggio title
     actualTitle.innerHTML=newTitle; 

     //settaggio audio
     audio.setAttribute("autoplay", "true");
     audio.src = percorso + newTitle + ".mp3";

    //bottone play/pause //change beat
    if(pressedButton.title.substr(0,10)=="Interrompi"){
        console.log("pause");
        audio.pause();
        pressedButton.style.backgroundImage='url("../assets/icons/Play.png")';
        pressedButton.title="Riproduci " + newTitle;

    }else{
        
        console.log(actualTitle.innerHTML+"=="+newTitle);
        if(actualTitle.innerHTML==newTitle){
            console.log("play");
            audio.play();
            pressedButton.style.backgroundImage='url("../assets/icons/Pause.png")';
            pressedButton.title="Interrompi " + newTitle;
        }else{
            newBeat();
        }
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