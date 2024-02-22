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
function setIframe(battle){
{
    /*title=new Array(
        'Minuto',
        '4/4',
        'Cypher',
        '3/4',
        'KickBack',
        'Royal rumble',
        'Argomento',
        'Acappella',
        'Oggetti',

    );*/

    /*    
    link=new Array(
        'https://www.youtube.com/embed/RszfbKxb460?si=S66nOyYSoWWfIMRV&amp;start=98&amp;end=210&amp;autoplay=1',
        'https://www.youtube.com/embed/2ttgML437Ho?si=UpESmYDGIApC8Ykd&amp;start=370&amp;end=510&amp;autoplay=1',
        'https://www.youtube.com/embed/n2qPwcpdeeQ?si=TkMwig5ASAn-QDDp&amp;start=117&amp;end=364&amp;autoplay=1',
        'https://www.youtube.com/embed/n2qPwcpdeeQ?si=TkMwig5ASAn-QDDp&amp;start=779&amp;end=1014&amp;autoplay=1',
        'https://www.youtube.com/embed/n2qPwcpdeeQ?si=78l2svzMAuQgGem-&amp;start=1604&amp;end=3610&amp;autoplay=1',
        'https://www.youtube.com/embed/Czp97FOKaDM?si=1T1fmAYfknrT4rnP&amp;autoplay=1',
        'https://www.youtube.com/embed/95SZIlMiFfQ?si=tTn5cuhA1s2pQeTS&amp;start=4&amp;end=&amp;autoplay=1',
        'https://www.youtube.com/embed/OvVk892HzmE?si=zJjNgjiI8RlPKi7Y&amp;start=762&amp;autoplay=1',
        'https://www.youtube.com/embed/S8Ze0GCgo4k?si=-nNRKZxPbIgU_2uI&amp;autoplay=1',
    );
    
    descrizione=new Array(
        "I rapper fanno un minuto di <span lang=\"en\">freestyle</span> a testa, semplice</form> semplice.",
        "La modalità più classica tra tutte, a turno i rapper fanno 4/4 a testa!",
        "Un cypher è un cerchio di rapper che a turno rappano per una quantità di tempo che i rapper stessi scelgono; modalità spesso utilizzata come sorta di riscaldamento pre-<span lang=\"en\">battle</span>",
        "Un rapper canta per 3/4 e l'altro dovrà chiudere la rima occupando il 4/4 per poi continuare per 3/4 a cui l'avversario dovrà rispondere, e così via!",
        "Un rapper fa una domanda all'altro, l'altro dovrà rispondere e fare poi una domanda a sua volta, ovviamente tutto a tempo di musica!",
        'Qua i rapper cantano a rotazione, quello che meno è piaciuto tra i partecipanti viene eliminato, e gli altri continuano ad affrontarsi.',
        'Viene dato un argomento su cui i rapper dovranno cantare. Esiste la variante in cui i rapper cantano come se facessero parte di due fazioni opposte tra loro(es grassi <abbr title="versus">vs</abbr> magri): <a title="Variante fazioni" href="https://www.youtube.com/watch?v=OQd6xAAm9nU" target="_blank">Esempio fazioni</a>',
        'I rapper si sfidano senza alcun supporto musicale, concentrandosi solo sulle loro abilità vocali e liriche',
        'Prima dell\'inizio di ogni turno ai rapper verranno forniti degli oggetti che non conoscono a priori e dovranno rappare su quelli. Gli oggetti possono essere forniti dal pubblico o nascosti dentro un contenitore.',
    );
*/
}
    descBattle=document.getElementsByClassName("descrizioneBattle")[battle];

    //creazione variabili per battle cliccata
    titleModalità=descBattle.getElementsByTagName("a")[0].title;
    link=descBattle.getElementsByTagName("a")[0].href;
    /*settaggio descrizione SE NECESSARIA
    desc=descBattle.getElementsByTagName("dd")[0].innerHTML;
    */

    //settaggio iframe
    document.getElementsByTagName("h3")[0].innerHTML=titleModalità;
    iframe=document.getElementsByTagName("iframe")[0];
    iframe.src=link;
    iframe.title=titleModalità;
    /*settaggio descrizione SE NECESSARIA
    document.getElementById("descBattle").innerHTML=desc;
    */
}

function showPlayBattle(){
    battle=document.getElementsByClassName("descrizioneBattle")
    for (let i = 0; i < battle.length; i++) {
        battle[i].onmouseover = function() {
        battle[i].getElementsByTagName("img")[0].style.opacity=1;
        }

        battle[i].onmouseout = function() {
            battle[i].getElementsByTagName("img")[0].style.opacity=0;
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

{
    /*
    audio.onplaying = function(){
        for (let i = 0; i < basi.length; i++) {
            //document.getElementsByClassName("base")[playingBeat].getElementsByTagName("button")=i+1;
            bottone=document.getElementsByClassName("base")[i].getElementsByTagName("button")
            console.log(bottone[0].title.slice(10) + "==" + nomeBase.slice(0,-4));
            if(bottone[0].title.slice(10)==nomeBase.slice(0,-4)){

                playingBeat=i;
                bottone[0].innerHTML='<img src="..\\assets\\icons\\playArancionePieno.png"></img>';
                console.log(bottone[0].innerHTML);
                
                basi[i].onmouseover = function() {
                   // bottone=basi[i].getElementsByTagName("button");                      
                    //bottone[0].innerHTML='<img src="..\\assets\\icons\\playArancionePieno.png"></img>';
                }
                basi[i].onmouseout = function() {
                    //bottone=basi[i].getElementsByTagName("button");
                    //bottone[0].innerHTML=i+1;    
                }
            }
        }

    }*/
};

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

function showPlayBasi(){
    basi=document.getElementsByClassName("beat")
    for (let i = 0; i < basi.length; i++) {
        basi[i].onmouseover = function() {
            bottone=basi[i].getElementsByTagName("button");    
            bottone[0].style.opacity=1;                  
            bottone[0].innerHTML='<img src="..\\assets\\icons\\playArancionePieno.png"></img>';
        }
        basi[i].onmouseout = function() {
            bottone=basi[i].getElementsByTagName("button");
            bottone[0].style.opacity=0.6;
            bottone[0].innerHTML=i+1;
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