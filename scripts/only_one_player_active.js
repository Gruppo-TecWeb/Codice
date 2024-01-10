function onlyOnePlayer(container) {
    container.addEventListener("play", function(event) {
    basi = container.getElementsByTagName("audio")
      for(i=0; i < basi.length; i++) {
        base = basi[i];
        if (base !== event.target) {
          base.pause();
        }
      }
    }, true);
  }
  
  document.addEventListener("DOMContentLoaded", function() {
    onlyOnePlayer(document.body);
  });