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


function setIframe(battle){
    
    title=new Array(
        'Minuto',
        '4/4',
        'Cypher',
        '3/4',
        'KickBack',
        'Royal rumble',
        'Argomento',
        'Acappella',
        'Oggetti',

    );

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
        'Viene dato un argomento su cui i rapper dovranno cantare. Esiste la variante in cui i rapper cantano come se facessero parte di due fazioni opposte tra loro(es grassi <abbr title="versus">vs</abbr> magri): <a href="https://www.youtube.com/watch?v=OQd6xAAm9nU" target="_blank">Esempio fazioni</a>',
        'I rapper si sfidano senza alcun supporto musicale, concentrandosi solo sulle loro abilità vocali e liriche',
        'Prima dell\'inizio di ogni turno ai rapper verranno forniti degli oggetti che non conoscono a priori e dovranno rappare su quelli. Gli oggetti possono essere forniti dal pubblico o nascosti dentro un contenitore.',
    );

    document.getElementsByTagName("h3")[0].innerHTML="Esempio " + title[battle];

    iframe=document.getElementsByTagName("iframe")[0];
    iframe.src=link[battle];
    iframe.title=title[battle];
  
    document.getElementById("descrizione_battle").innerHTML=descrizione[battle];
}

function playerAudio(base) {
    percorso="assets/media/basi/";
    container = document.getElementById("audio_container");
    container.innerHTML ='<h3>' + base.slice(0,-4) + '</h3><audio controls id="audio"><source src="' + percorso + base + '" type="audio/mpeg"></audio>';
}