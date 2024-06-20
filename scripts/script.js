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
 * PAGINA HOME
 */

function init_index() {
    const logo = document.querySelector('header h1.logo');
    const hero = document.querySelector('#hero h2');

    logo.classList.add('js');

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
        pressedButton.title = "Riproduci esempio " + newTitle;
    }
    if (event.data == YT.PlayerState.PLAYING) {
        pressedButton.setAttribute("data-isPlaying", "true");
        pressedButton.title = "Interrompi esempio " + newTitle;
    }
}
var descVideo = [];
var innerDescVideo = [];

function stripHTMLTags(str) {
    return str.replace(/<\/?[^>]+(>|$)/g, "");
}

function initIframe() {
    descBattles = document.getElementsByClassName("descBattle");
    actualTitle = document.getElementsByTagName("h3")[1];

    
    thisBattle = descBattles[0];
    newTitle = thisBattle.getElementsByTagName("dt")[0].getElementsByTagName("span")[0].innerHTML;
    pressedButton = thisBattle.getElementsByTagName("button")[0];
    
    var idDescVideo = [];
     
    
    for (var i = 0; i < descBattles.length; i++) {
        descVideo = descBattles[i].getElementsByTagName("p")[0];
        innerDescVideo[i]=stripHTMLTags(descVideo.innerHTML);
        console.log(innerDescVideo);
        descVideo.setAttribute("id","desc_"+i);
        idDescVideo[i]=descVideo.getAttribute("id");
    }
    
    iframe = document.getElementById("iframe_battle");
    iframe.setAttribute("aria-describedby",idDescVideo[0]);
    iframe.setAttribute("aria-label",innerDescVideo[0]);
    //console.log(descVideo[0]);

}

function setIframe(battle) {
    thisBattle = descBattles[battle];
    pressedButton = thisBattle.getElementsByTagName("button")[0];
    newTitle = thisBattle.getElementsByTagName("dt")[0].getElementsByTagName("span")[0].innerHTML;
    oldTitle=actualTitle.innerHTML;
    

    
    if (pressedButton.title.substr(0, 10) == "Interrompi") {
        player.pauseVideo();
        pressedButton.setAttribute("data-isPlaying", "false");
        pressedButton.title = "Riproduci esempio " + actualTitle.innerHTML;
    } else {
        if (actualTitle.innerHTML == "esempio "+ newTitle) {
            player.playVideo();
            pressedButton.setAttribute("data-isPlaying", "true");
            pressedButton.title = "Interrompi esempio " + newTitle;
        } else {

            iframe.setAttribute("aria-describedby","desc_"+battle);
            iframe.setAttribute("aria-label",innerDescVideo[battle]);
            pressedButton.setAttribute("title","Riproduci esempio "+actualTitle.innerHTML);
           // console.log(pressedButton.title);
            newIframe();
        }
    }
}

function newIframe() {
    var link = pressedButton.getAttribute("data-link");
    actualTitle.innerHTML = "esempio "+ newTitle;
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
    //console.log(pressedButton.title);
    for (var i = 0; i < descBattles.length; i++) {
        if(descBattles[i] != thisBattle){
            descBattles[i].getElementsByClassName("playerJump")[0].setAttribute("aria-hidden", "true");
            descBattles[i].getElementsByClassName("playerJump")[0].setAttribute("tabindex", "-1");
        }
        var buttonPP = descBattles[i].getElementsByTagName("button")[0];
        if (buttonPP.title.substr(0, 10) == "Interrompi") {
            buttonPP.setAttribute("data-isPlaying", "false");
            buttonPP.title = "Riproduci " + oldTitle;
        }
        
    }

    pressedButton.setAttribute("data-isPlaying", "true");
    pressedButton.title = "Interrompi esempio " + newTitle;
    console.log(pressedButton.title);
}


/*
 * PAGINA BEATS
 */

function init_beats() {
    pressedButton = document.getElementsByClassName("beat")[0].getElementsByTagName("button")[0];
    document.getElementById("audio").addEventListener("play", function() {
        pressedButton.setAttribute("data-isPlaying", "true");
        pressedButton.title = "Interrompi " + newTitle;
        pressedButton.setAttribute("aria-label", "Interrompi " + newTitle);

    });

    document.getElementById("audio").addEventListener("pause", function() {
        pressedButton.setAttribute("data-isPlaying", "false");
        pressedButton.title = "Riproduci " + newTitle;
        pressedButton.setAttribute("aria-label", "Riproduci " + newTitle);
    });

    btnDescrizioni = document.getElementsByClassName("btnDesc");
    descBeats = document.getElementsByClassName("descBeats");
    for (let i = 0; i < btnDescrizioni.length; i++) {
        btnDescrizioni[i].setAttribute("data-show", "false");
        btnDescrizioni[i].setAttribute("aria-controls", "desc_" + i);
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
    descrizione.hasAttribute("hidden") ? descrizione.removeAttribute("hidden") : descrizione.setAttribute("hidden","");

    btnDescrizione.setAttribute("data-show", show === "true" ? "false" : "true");
    btnDescrizione.setAttribute("aria-expanded", show === "true" ? "false" : "true");  
    btnDescrizione.getElementsByTagName("span")[0].innerHTML = show === "true" ? "Audio descrizione" : "Nascondi";
}

var autoNext = false;

function playerAudio(nomeBase, index) {
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
    audio.setAttribute("aria-describedby", "desc_" + index);
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
        } else {
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
    autoPlay(nomeBase, index);
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

function autoPlay(nomeBase, index) {
    document.getElementById("autoNext").onclick = function() {
        autoNext = !autoNext;
        autoRip = document.getElementById("autoNext");
        autoRip.setAttribute("aria-pressed", autoNext);
    }
    audio.onended = function() {
        if (autoNext) {
            //autoplay.setAttribute("", "true");
            audio.setAttribute("autoplay", "true");
            nextAudio(nomeBase, index);
        } else {

            audio.setAttribute("autoplay", "false");

        }
    }
}

function nextAudio(nomeBase, index) {
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
                playerAudio(newTitle, index+1);
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

// funzione che controlla i dati nei form
document.addEventListener('DOMContentLoaded', function() {
    const formLogin = document.getElementById('form-login');
    const formProfilo = document.getElementById('form-profilo');
    const formEvento = document.getElementById('form-evento');
    const formClassifica = document.getElementById('form-classifica');
    const formTipoEvento = document.getElementById('form-tipo-evento');
    const formRapper = document.getElementById('form-rapper');
    const formAmministratore = document.getElementById('form-amministratore');

    let clickedButton = null;
    const urlParams = new URLSearchParams(window.location.search);
    const form = formProfilo || formEvento || formClassifica || formTipoEvento || formRapper || formAmministratore;

    if (form) {
        form.addEventListener('click', function(event) {
            if (event.target.type === 'submit') {
                clickedButton = event.target;
            }
        });
    }

    // Funzioni di validazione generiche
    function validateField(inputElement, errorElement, validationFn) {
        inputElement.addEventListener('input', function() {
            validationFn();
        });
    }

    function checkUniqueField(tipo, value, elementToEdit = null) {
        const requestBody = { tipo: tipo, [tipo]: value };
        if (elementToEdit !== null) {
            requestBody.elementToEdit = elementToEdit;
        }

        return fetch('../utilities/verifica-dati-form.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(requestBody)
            })
            .then(response => response.json())
            .catch(error => {
                console.error('Errore:', error);
                return { error: 'Si è verificato un errore.' };
            });
    }

    function validateData(dataEventoInput, dataEventoError) {
        const data = dataEventoInput.value.trim();
        if (data === "") {
            dataEventoError.textContent = "Data obbligatoria";
            dataEventoInput.classList.add('inputError');
            dataEventoError.classList.add('inputError');
            return false;
        } else {
            const date = new Date(data);
            if (isNaN(date.getTime())) {
                dataEventoError.textContent = "Data non valida";
                dataEventoInput.classList.add('inputError');
                dataEventoError.classList.add('inputError');
                return false;
            } else {
                dataEventoError.textContent = "";
                dataEventoInput.classList.remove('inputError');
                dataEventoError.classList.remove('inputError');
                return true;
            }
        }
    }

    function validateOra(oraEventoInput, oraEventoError) {
        const ora = oraEventoInput.value.trim();
        if (ora === "") {
            oraEventoError.textContent = "Ora obbligatoria";
            oraEventoInput.classList.add('inputError');
            oraEventoError.classList.add('inputError');
            return false;
        } else {
            const timeRegex = /^(\d{1,2}):(\d{1,2})(?::(\d{1,2}))?$/;
            if (!timeRegex.test(ora)) {
                oraEventoError.textContent = "Ora non valida";
                oraEventoInput.classList.add('inputError');
                oraEventoError.classList.add('inputError');
                return false;
            } else {
                oraEventoError.textContent = "";
                oraEventoInput.classList.remove('inputError');
                oraEventoError.classList.remove('inputError');
                return true;
            }
        }
    }


    // Validazione per il form di login
    if (formLogin) {
        const usernameInput = document.getElementById('username');
        const passwordInput = document.getElementById('password');
        const usernameError = document.getElementById('username-error');
        const passwordError = document.getElementById('password-error');

        // Validazione dell'username durante la digitazione
        usernameInput.addEventListener('input', function() {
            validateField(usernameInput, usernameError, validateUsername);
        });

        // Validazione dell'username quando si perde il focus dal campo username
        usernameInput.addEventListener('blur', validateUsername);

        // Validazione della password durante la digitazione
        passwordInput.addEventListener('input', function() {
            validateField(passwordInput, passwordError, validatePassword);
        });

        // Validazione della password quando si perde il focus dal campo password
        passwordInput.addEventListener('blur', validatePassword);

        formLogin.addEventListener('submit', function(event) {
            const isUsernameValid = validateUsername();
            const isPasswordValid = validatePassword();

            if (!isUsernameValid || !isPasswordValid) {
                event.preventDefault();
            }
        });

        // Controlla che l'username non sia vuoto
        function validateUsername() {
            const username = usernameInput.value.trim();
            if (username === "") {
                usernameError.textContent = "Username obbligatorio.";
                usernameInput.classList.add('inputError');
                usernameError.classList.add('inputError');
                return false;
            } else {
                usernameError.textContent = "";
                usernameInput.classList.remove('inputError');
                usernameError.classList.remove('inputError');
                return true;
            }
        }

        // Controlla che la password non sia vuota
        function validatePassword() {
            const password = passwordInput.value.trim();
            if (password === "") {
                passwordError.textContent = "Password obbligatoria.";
                passwordInput.classList.add('inputError');
                passwordError.classList.add('inputError');
                return false;
            } else {
                passwordError.textContent = "";
                passwordInput.classList.remove('inputError');
                passwordError.classList.remove('inputError');
                return true;
            }
        }
    }

    // Validazione per il form profilo
    if (formProfilo) {
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('confermaPassword');
        const emailError = document.getElementById('email-error');
        const passwordError = document.getElementById('password-error');

        // Validazione dell'email durante la digitazione
        emailInput.addEventListener('input', function() {
            validateField(emailInput, emailError, validateEmail);
        });

        // Validazione dell'email quando si perde il focus dal campo email
        emailInput.addEventListener('blur', validateEmail);

        // Controllo password quando si perde il focus dal campo password
        passwordInput.addEventListener('blur', function() {
            if (confirmPasswordInput.value.trim() !== "") {
                validatePasswords();
            }
        });

        // Validazione e controllo che non sia vuota la conferma password quando si perde il focus dal campo conferma password
        confirmPasswordInput.addEventListener('blur', function() {
            validatePasswords();
        });

        // Controllo conferma password durante la digitazione se la password è già stata inserita
        confirmPasswordInput.addEventListener('input', function() {
            if (passwordInput.value.trim() !== "") {
                validatePasswords();
            }
        });

        function validateEmail() {
            return new Promise((resolve, reject) => {
                const email = emailInput.value.trim();
                // Espressione regolare per la validazione dell'email
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (email === "") {
                    emailError.textContent = "Email obbligatoria.";
                    emailInput.classList.add('inputError');
                    emailError.classList.add('inputError');
                    resolve(false);
                } else if (!emailRegex.test(email)) {
                    emailError.textContent = "Email non valida.";
                    emailInput.classList.add('inputError');
                    emailError.classList.add('inputError');
                    resolve(false);
                } else {
                    elementToEdit = urlParams.has('username') ? urlParams.get('username') : null;
                    checkUniqueField('email-profilo', email, elementToEdit)
                        .then(data => {
                            if (data.exists) {
                                emailError.textContent = "Email già registrata.";
                                emailInput.classList.add('inputError');
                                emailError.classList.add('inputError');
                                resolve(false);
                            } else {
                                emailError.textContent = "";
                                emailInput.classList.remove('inputError');
                                emailError.classList.remove('inputError');
                                resolve(true);
                            }
                        })
                        .catch(error => {
                            console.error('Errore:', error);
                            emailError.textContent = "Si è verificato un errore. Riprova.";
                            emailInput.classList.add('inputError');
                            emailError.classList.add('inputError');
                            resolve(false);
                        });
                }
            });
        }

        function validatePasswords() {
            const password = passwordInput.value.trim();
            const confirmPassword = confirmPasswordInput.value.trim();
            if (password !== "" && password !== confirmPassword) {
                passwordError.textContent = "Le password non coincidono.";
                passwordInput.classList.add('inputError');
                confirmPasswordInput.classList.add('inputError');
                passwordError.classList.add('inputError');
                return false;
            } else {
                passwordError.textContent = "";
                passwordInput.classList.remove('inputError');
                confirmPasswordInput.classList.remove('inputError');
                passwordError.classList.remove('inputError');
                return true;
            }
        }

        formProfilo.addEventListener('submit', async function(event) {
            event.preventDefault(); // Blocca l'invio del modulo inizialmente

            const isEmailValid = await validateEmail();
            const arePasswordsValid = validatePasswords();

            if (isEmailValid && arePasswordsValid) {
                const confermaInput = document.createElement('input');
                confermaInput.type = 'hidden';
                confermaInput.name = 'conferma';
                confermaInput.value = 'true';
                formProfilo.appendChild(confermaInput);

                if (clickedButton) {
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = clickedButton.name;
                    hiddenInput.value = clickedButton.value;
                    form.appendChild(hiddenInput);
                }

                formProfilo.submit();
            }
        });
    }

    // Validazione per il form eventi
    if (formEvento) {
        const titoloEventoInput = document.getElementById('titolo-evento');
        const titoloEventoError = document.getElementById('titolo-error');
        const dataEventoInput = document.getElementById('data');
        const dataEventoError = document.getElementById('data-error');
        const oraEventoInput = document.getElementById('ora');
        const oraEventoError = document.getElementById('ora-error');
        const luogoEventoInput = document.getElementById('luogo');
        const luogoEventoError = document.getElementById('luogo-error');
        const locandinaInput = document.getElementById('locandina');
        const locandinaError = document.getElementById('locandina-error');

        // Controlla durante la digitazione che il titolo sia stato inserito
        titoloEventoInput.addEventListener('input', function() {
            validateField(titoloEventoInput, titoloEventoError, validateTitolo);
        });

        // Controlla durante la digitazione che la data sia stata inserita e sia valida
        dataEventoInput.addEventListener('input', function() {
            validateField(dataEventoInput, dataEventoError, validateData);
        });

        // Controlla durante la digitazione che l'ora sia stata inserita e sia valida
        oraEventoInput.addEventListener('input', function() {
            validateField(oraEventoInput, oraEventoError, validateOra);
        });

        // Controlla durante la digitazione che il luogo sia stato inserito
        luogoEventoInput.addEventListener('input', function() {
            validateField(luogoEventoInput, luogoEventoError, validateLuogo);
        });

        locandinaInput.addEventListener('change', function(event) {
            const file = locandinaInput.files[0];

            // Se non è stato selezionato alcun file, non eseguire la validazione
            if (!file) {
                locandinaError.textContent = "";
                locandinaInput.classList.remove('inputError');
                locandinaError.classList.remove('inputError');
                return;
            }

            // Verifica se il file è un'immagine
            if (!file.type.startsWith('image/')) {
                locandinaError.textContent = "Il file deve essere un'immagine.";
                locandinaInput.classList.add('inputError');
                locandinaError.classList.add('inputError');
                locandinaInput.value = ""; // Resetta il valore dell'input per consentire all'utente di selezionare un nuovo file
                return;
            }

            // Verifica se il file supera i 10 MB
            const maxSize = 10 * 1024 * 1024; // 10 MB in byte
            if (file.size > maxSize) {
                locandinaError.textContent = "Il file non può superare i 10 MB.";
                locandinaInput.classList.add('inputError');
                locandinaError.classList.add('inputError');
                locandinaInput.value = ""; // Resetta il valore dell'input per consentire all'utente di selezionare un nuovo file
                return;
            }

            // Se tutti i controlli passano, rimuovi eventuali messaggi di errore
            locandinaError.textContent = "";
            locandinaInput.classList.remove('inputError');
            locandinaError.classList.remove('inputError');
        });

        function validateTitolo() {
            const titolo = titoloEventoInput.value.trim();
            if (titolo === "") {
                titoloEventoError.textContent = "Titolo obbligatorio";
                titoloEventoInput.classList.add('inputError');
                titoloEventoError.classList.add('inputError');
                return false;
            } else {
                titoloEventoError.textContent = "";
                titoloEventoInput.classList.remove('inputError');
                titoloEventoError.classList.remove('inputError');
                return true;
            }
        }

        function validateLuogo() {
            const luogo = luogoEventoInput.value.trim();
            if (luogo === "") {
                luogoEventoError.textContent = "Luogo obbligatorio";
                luogoEventoInput.classList.add('inputError');
                luogoEventoError.classList.add('inputError');
                return false;
            } else {
                luogoEventoError.textContent = "";
                luogoEventoInput.classList.remove('inputError');
                luogoEventoError.classList.remove('inputError');
                return true;
            }
        }

        formEvento.addEventListener('submit', async function(event) {
            event.preventDefault(); // Blocca l'invio del modulo inizialmente

            const isTitoloValid = validateTitolo();
            const isDataValid = validateData(dataEventoInput, dataEventoError);
            const isOraValid = validateOra(oraEventoInput, oraEventoError);
            const isLuogoValid = validateLuogo();

            if (isTitoloValid && isDataValid && isOraValid && isLuogoValid) {
                const confermaInput = document.createElement('input');
                confermaInput.type = 'hidden';
                confermaInput.name = 'conferma';
                confermaInput.value = 'true';
                formEvento.appendChild(confermaInput);

                if (clickedButton) {
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = clickedButton.name;
                    hiddenInput.value = clickedButton.value;
                    form.appendChild(hiddenInput);
                }

                formEvento.submit();
            }
        });
    }

    // Validazione per il form classifiche
    if (formClassifica) {
        const titoloClassificaInput = document.getElementById('titolo-classifica');
        const titoloClassificaError = document.getElementById('titolo-error');
        const dataInizioInput = document.getElementById('data-inizio');
        const dataInizioError = document.getElementById('data-inizio-error');
        const dataFineInput = document.getElementById('data-fine');
        const dataFineError = document.getElementById('data-fine-error');

        // Validazione del titolo durante la digitazione
        titoloClassificaInput.addEventListener('input', function() {
            validateField(titoloClassificaInput, titoloClassificaError, validateTitolo);
        });
        
        // Validazione della data di inizio durante la digitazione
        dataInizioInput.addEventListener('input', function() {
            validateField(dataInizioInput, dataInizioError, validateData);
        });

        // Validazione della data di fine durante la digitazione
        dataFineInput.addEventListener('input', function() {
            validateField(dataFineInput, dataFineError, validateData);
        });

        // Controlla che il titolo sia stato inserito e sia unico
        function validateTitolo() {
            return new Promise((resolve, reject) => {
                const titolo = titoloClassificaInput.value.trim();
                if (titolo === "") {
                    titoloClassificaError.textContent = "Titolo obbligatorio.";
                    titoloClassificaInput.classList.add('inputError');
                    titoloClassificaError.classList.add('inputError');
                    resolve(false);
                } else {
                    elementToEdit = urlParams.has('idClassifica') ? urlParams.get('idClassifica') : null;
                    console.log(elementToEdit);
                    checkUniqueField('titolo-classifica', titolo, elementToEdit)
                        .then(data => {
                            if (data.exists) {
                                titoloClassificaError.textContent = "Esiste già una classifica con questo titolo.";
                                titoloClassificaInput.classList.add('inputError');
                                titoloClassificaError.classList.add('inputError');
                                resolve(false);
                            } else {
                                titoloClassificaError.textContent = "";
                                titoloClassificaInput.classList.remove('inputError');
                                titoloClassificaError.classList.remove('inputError');
                                resolve(true);
                            }
                        })
                        .catch(error => {
                            console.error('Errore:', error);
                            titoloClassificaError.textContent = "Si è verificato un errore. Riprova.";
                            titoloClassificaInput.classList.add('inputError');
                            titoloClassificaError.classList.add('inputError');
                            resolve(false);
                        });
                }
            });
        }

        formClassifica.addEventListener('submit', async function(event) {
            event.preventDefault(); // Blocca l'invio del modulo inizialmente

            const isTitoloValid = await validateTitolo();
            const isDataInizioValid = validateData(dataInizioInput, dataInizioError);
            const isDataFineValid = validateData(dataFineInput, dataFineError);

            if (isTitoloValid && isDataInizioValid && isDataFineValid) {
                const confermaInput = document.createElement('input');
                confermaInput.type = 'hidden';
                confermaInput.name = 'conferma';
                confermaInput.value = 'true';
                formClassifica.appendChild(confermaInput);

                if (clickedButton) {
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = clickedButton.name;
                    hiddenInput.value = clickedButton.value;
                    form.appendChild(hiddenInput);
                }

                formClassifica.submit();
            }
        });
    }

    // Validazione per il form tipi evento
    if (formTipoEvento) {
        const titoloTipoEventoInput = document.getElementById('titolo-tipo-evento');
        const titoloTipoEventoError = document.getElementById('titolo-error');

        // Validazione del titolo durante la digitazione
        titoloTipoEventoInput.addEventListener('input', function() {
            validateField(titoloTipoEventoInput, titoloTipoEventoError, validateTitolo);
        });

        // controlla che il titolo sia stato inserito e sia unico
        function validateTitolo() {
            return new Promise((resolve, reject) => {
                const titolo = titoloTipoEventoInput.value.trim();
                if (titolo === "") {
                    titoloTipoEventoError.textContent = "Titolo obbligatorio.";
                    titoloTipoEventoInput.classList.add('inputError');
                    titoloTipoEventoError.classList.add('inputError');
                    resolve(false);
                } else {
                    elementToEdit = urlParams.has('titolo') ? urlParams.get('titolo') : null;
                    checkUniqueField('titolo-tipo-evento', titolo, elementToEdit)
                        .then(data => {
                            if (data.exists) {
                                titoloTipoEventoError.textContent = "Esiste già un tipo evento con questo titolo.";
                                titoloTipoEventoInput.classList.add('inputError');
                                titoloTipoEventoError.classList.add('inputError');
                                resolve(false);
                            } else {
                                titoloTipoEventoError.textContent = "";
                                titoloTipoEventoInput.classList.remove('inputError');
                                titoloTipoEventoError.classList.remove('inputError');
                                resolve(true);
                            }
                        })
                        .catch(error => {
                            console.error('Errore:', error);
                            titoloTipoEventoError.textContent = "Si è verificato un errore. Riprova.";
                            titoloTipoEventoInput.classList.add('inputError');
                            titoloTipoEventoError.classList.add('inputError');
                            resolve(false);
                        });
                }
            });
        }

        formTipoEvento.addEventListener('submit', async function(event) {
            event.preventDefault(); // Blocca l'invio del modulo inizialmente

            const isTitoloValid = await validateTitolo();

            if (isTitoloValid) {
                const confermaInput = document.createElement('input');
                confermaInput.type = 'hidden';
                confermaInput.name = 'conferma';
                confermaInput.value = 'true';
                formTipoEvento.appendChild(confermaInput);

                if (clickedButton) {
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = clickedButton.name;
                    hiddenInput.value = clickedButton.value;
                    form.appendChild(hiddenInput);
                }

                formTipoEvento.submit();
            }
        });
    }

    // Validazione per il form rappers e amministratori
    if (formRapper || formAmministratore) {
        const emailInput = document.getElementById('email');
        const usernameInput = document.getElementById('username');
        const emailError = document.getElementById('email-error');
        const usernameError = document.getElementById('username-error');

        const form = formRapper ? formRapper : formAmministratore;

        // Validazione dell'email durante la digitazione
        emailInput.addEventListener('input', function() {
            validateField(emailInput, emailError, validateEmail);
        });

        // Validazione dell'email quando si perde il focus dal campo email
        emailInput.addEventListener('blur', validateEmail);

        // Validazione dell'username durante la digitazione
        usernameInput.addEventListener('input', function() {
            validateField(usernameInput, usernameError, validateUsername);
        });

        // Validazione dell'username quando si perde il focus dal campo username
        usernameInput.addEventListener('blur', validateUsername);

        // controlla che l'email sia stata inserita e sia unica
        function validateEmail() {
            return new Promise((resolve, reject) => {
                const email = emailInput.value.trim();
                // Espressione regolare per la validazione dell'email
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (email === "") {
                    emailError.textContent = "Email obbligatoria.";
                    emailInput.classList.add('inputError');
                    emailError.classList.add('inputError');
                    resolve(false);
                } else if (!emailRegex.test(email)) {
                    emailError.textContent = "Email non valida.";
                    emailInput.classList.add('inputError');
                    emailError.classList.add('inputError');
                    resolve(false);
                } else {
                    elementToEdit = urlParams.has('username') ? urlParams.get('username') : null;
                    checkUniqueField('email', email, elementToEdit)
                        .then(data => {
                            if (data.exists) {
                                emailError.textContent = "Email già registrata.";
                                emailInput.classList.add('inputError');
                                emailError.classList.add('inputError');
                                resolve(false);
                            } else {
                                emailError.textContent = "";
                                emailInput.classList.remove('inputError');
                                emailError.classList.remove('inputError');
                                resolve(true);
                            }
                        })
                        .catch(error => {
                            console.error('Errore:', error);
                            emailError.textContent = "Si è verificato un errore. Riprova.";
                            emailInput.classList.add('inputError');
                            emailError.classList.add('inputError');
                            resolve(false);
                        });
                }
            });
        }

        // controlla che l'username sia stato inserito e sia unico
        function validateUsername() {
            return new Promise((resolve, reject) => {
                const username = usernameInput.value.trim();
                if (username === "") {
                    usernameError.textContent = "Username obbligatorio.";
                    usernameInput.classList.add('inputError');
                    usernameError.classList.add('inputError');
                    resolve(false);
                } else {
                    elementToEdit = urlParams.has('username') ? urlParams.get('username') : null;
                    checkUniqueField('username', username, elementToEdit)
                        .then(data => {
                            if (data.exists) {
                                usernameError.textContent = "Username già utilizzato.";
                                usernameInput.classList.add('inputError');
                                usernameError.classList.add('inputError');
                                resolve(false);
                            } else {
                                usernameError.textContent = "";
                                usernameInput.classList.remove('inputError');
                                usernameError.classList.remove('inputError');
                                resolve(true);
                            }
                        })
                        .catch(error => {
                            console.error('Errore:', error);
                            usernameError.textContent = "Si è verificato un errore. Riprova.";
                            usernameInput.classList.add('inputError');
                            usernameError.classList.add('inputError');
                            resolve(false);
                        });
                }
            });
        }

        form.addEventListener('submit', async function(event) {
            event.preventDefault(); // Blocca l'invio del modulo inizialmente

            const isEmailValid = await validateEmail();
            const isUsernameValid = await validateUsername();

            if (isEmailValid && isUsernameValid) {
                const confermaInput = document.createElement('input');
                confermaInput.type = 'hidden';
                confermaInput.name = 'conferma';
                confermaInput.value = 'true';
                form.appendChild(confermaInput);

                if (clickedButton) {
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = clickedButton.name;
                    hiddenInput.value = clickedButton.value;
                    form.appendChild(hiddenInput);
                }

                form.submit();
            }
        });
    }
});