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
BEATS
*/



function setAudioDuration(){
    span=document.getElementsByClassName("durata");
    audios=document.getElementsByClassName("audioBeats");
    
    
    for(let i=0;i<span.length;i++){
        playerJump=document.getElementsByClassName("beat")[i].getElementsByTagName("a")[0].getElementsByTagName("span")[0];
        playerJump.setAttribute("aria-hidden","true");
        
        audios[i].setAttribute("tabindex","-1");
        audios[i].setAttribute("data-java","true");
        durata=Math.floor(audios[i].duration/60) + ":" + Math.floor(audios[i].duration%60);
        minuti=durata.slice(0,durata.indexOf(":"));
        secondi=durata.slice(durata.indexOf(":")+1);
        if(minuti==1){
            if(secondi.length==1){
                span[i].innerHTML ="<time aria-hidden='true' datatime=PT" + minuti + "M" + secondi + "S>" + minuti + ":" + "0" + secondi + "</time>"+ "<span class='navigationHelp'>"+minuti+"minuto e "+secondi+"secondi"+"</span>"; 
            }else{
                span[i].innerHTML = "<time aria-hidden='true' datatime=PT" + minuti + "M" + secondi + "S>" + minuti + ":" + secondi + "</time>"+ "<span class='navigationHelp'>"+minuti+" minuto e "+secondi+" secondi"+"</span>"; 
            }
        }else{
            if(secondi.length==1){
                span[i].innerHTML ="<time aria-hidden='true' datatime=PT" + minuti + "M" + secondi + "S>" + minuti + ":" + "0" + secondi + "</time>"+ "<span class='navigationHelp'>"+minuti+"minuti e "+secondi+"secondi"+"</span>"; 
            }else{
                span[i].innerHTML = "<time aria-hidden='true' datatime=PT" + minuti + "M" + secondi + "S>" + minuti + ":" + secondi + "</time>"+ "<span class='navigationHelp'>"+minuti+" minuti e "+secondi+" secondi"+"</span>"; 
            }
        }
    }
}


var autoNext=false;
function playerAudio(nomeBase){
    
    //variabili varie
    percorso="assets/media/basi/";
    audio = document.getElementById("audio");
    audioContainer=document.getElementById("audio_container");
    h3 = audioContainer.getElementsByTagName("h3")[0];
    newTitle = nomeBase.slice(0,-4).replaceAll("-"," ");
    beats = document.getElementsByClassName("beat")
    for(let i=0;i<beats.length;i++){
        console.log(playerJump);
        if(beats[i].getElementsByTagName("button")[0].getAttribute("data-title-beat")==nomeBase.slice(0,-4)){
            pressedButton=beats[i].getElementsByTagName("button")[0];
            audioJump=beats[i].getElementsByTagName("a")[0];
            audioJump.setAttribute("tabindex","0");

            playerJump=beats[i].getElementsByTagName("a")[0].getElementsByTagName("span")[0];
            playerJump.setAttribute("aria-hidden","false");
        console.log(i + playerJump);
        }
    }
    

    if(h3.innerHTML==newTitle){
        if(pressedButton.title.slice(0,10)=="Interrompi"){
            //console.log("pause");
            audio.pause();
            pressedButton.setAttribute("data-isPlaying","false")
            pressedButton.title="Riproduci " + newTitle;

           

        }else{
            audio.play();
            pressedButton.setAttribute("data-isPlaying","true")
            pressedButton.title="Interrompi " + newTitle;
            
            
        }    
    }else{
        newBeat(nomeBase);
        
    }
    
    //bottone riproduzione automatica
    autoPlay(nomeBase);
}

function newBeat(nomeBase){
    //cambia gli statement dell'audio che era in riproduzione precedentemente
    for(let i=0;i<beats.length;i++){
        buttonPP=beats[i].getElementsByTagName("button")[0];
        audioJump=beats[i].getElementsByTagName("a")[0];
        playerJump.setAttribute("aria-hidden","false");
        
        if(buttonPP.title.substr(0,10)=="Interrompi"){
            audioJump.setAttribute("tabindex","-1");
            
            buttonPP.setAttribute("data-isPlaying","false")
            buttonPP.title="Riproduci " + buttonPP.getAttribute("data-title-beat");
        } 
    }
    //settaggio title bottone e player audio
    pressedButton.setAttribute("data-isPlaying","true")
    h3.innerHTML=newTitle; 
    pressedButton.title="Interrompi " + newTitle;
    
    //settaggio audio
    audio.setAttribute("autoplay", "true");
    audio.src = percorso + nomeBase; 
}


function autoPlay(nomeBase){
    document.getElementById("autoNext").onclick = function() {
        autoNext = !autoNext;
        console.log(autoNext);
        document.getElementById("autoNext").setAttribute("aria-pressed", autoNext);
    }
    audio.onended = function() {
        if(autoNext){
            
            audio.setAttribute("autoplay", "true");
                nextAudio(nomeBase);
        }else{
            
            audio.setAttribute("autoplay", "false");

            }
        }
}

function nextAudio(nomeBase) {  
    newTitle = nomeBase.slice(0,-4).replaceAll("-"," ");
    beats = document.getElementsByClassName("beat")
    for(let i=0;i<beats.length;i++){
        if(beats[i].getElementsByTagName("button")[0].getAttribute("data-title-beat")==nomeBase.slice(0,-4)){
            pressedButton=beats[i].getElementsByTagName("button")[0];
        }
    }

    for (let i = 0; i < beats.length; i++) {
        bottone = beats[i].getElementsByTagName("button")[0]
        if(bottone.getAttribute("data-title-beat") == nomeBase.slice(0,-4)){
            let next = beats[i+1];
            if (next) {
                let nextButton = next.getElementsByTagName("button");
                newTitle=nextButton[0].getAttribute("data-title-beat")+".mp3";
                playerAudio(newTitle);
            }
            break;  
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