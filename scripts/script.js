function init_eventi() {
    var urlParams = new URLSearchParams(window.location.search);
    var filtro = urlParams.get('filtro');
    setFilter(filtro);
}

function setFilter(filtro) {
    if (filtro === 'data') {
        document.getElementById('data-container').classList.add('selected');
        document.getElementById('link-passati').classList.remove('selected');
        document.getElementById('link-tutti').classList.remove('selected');
        document.getElementById('link-prossimi').classList.remove('selected');
    } else {
        // Altrimenti, aggiungi la classe "selected" al link corrispondente
        if (filtro === 'passati') {
            document.getElementById('link-passati').classList.add('selected');
        } else if (filtro === 'tutti') {
            document.getElementById('link-tutti').classList.add('selected');
        } else {
            document.getElementById('link-prossimi').classList.add('selected');
        }
    }
}