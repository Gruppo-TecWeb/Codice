var navOpened = false;

function toggleMenu() {
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
    pressedButton.title="Riproduci " + titleModalità;
}

function playIframe() {
    console.log("play");
    let video = document.getElementById("iframe_battle")
    video.contentWindow.postMessage('{"event":"command","func":"' + 'playVideo' + '","args":""}', '*');
    pressedButton.style.backgroundImage='url("../assets/icons/Pause.png")';
    pressedButton.title="Interrompi " + titleModalità;
}

function newIframe(){

    //settaggio iframe
    link=thisBattle.getElementsByTagName("a")[0].href;
    actualTitle.innerHTML=titleModalità;
    iframe.src=link;
    iframe.title=titleModalità;

    //settaggio bottone pause nel caso di switch canzone
    for(i=0;i<descBattles.length;i++){
        buttonPP=descBattles[i].getElementsByTagName("button")[0];
        if(buttonPP.title.substr(0,10)=="Interrompi"){
            //console.log(buttonPP.title + "==Interrompi " + titleModalità);
            buttonPP.style.backgroundImage='url("../assets/icons/Play.png")';
            buttonPP.title="Riproduci " + titleModalità;
        }
    }
        pressedButton.style.backgroundImage='url("../assets/icons/Pause.png")';
        pressedButton.title="Interrompi " + titleModalità;
}
 
function setIframe(battle){

    descBattles=document.getElementsByClassName("descBattle");
    thisBattle=descBattles[battle];
    pressedButton=thisBattle.getElementsByTagName("button")[0];
    iframe=document.getElementById("iframe_battle");
    actualTitle=document.getElementsByTagName("h3")[0];
    titleModalità=thisBattle.getElementsByTagName("a")[0].title;
    
    if(pressedButton.title.substr(0,10)=="Interrompi"){
        pauseIframe();
    }else{
        if(actualTitle.innerHTML==titleModalità){
            playIframe();
        }else{
            newIframe();
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
                title=(nextButton[0].getAttribute("title").slice(10)+".mp3");
                playerAudio(title);
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