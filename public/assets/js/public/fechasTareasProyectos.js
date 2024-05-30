moment.locale('es');
for (var i = 0; i < document.getElementsByClassName("fecha-limite").length; i++) {
    if (moment([document.getElementsByClassName("fecha-limite")[i].innerText], "YYYY-MM-DD").fromNow() !== "Fecha inválida") {
        document.getElementsByClassName("fecha-limite")[i].innerHTML = "<i class='fa-regular fa-clock'></i> " + moment([document.getElementsByClassName("fecha-limite")[i].innerText], "YYYY-MM-DD").fromNow();
    }
}