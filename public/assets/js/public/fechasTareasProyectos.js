moment.locale('es');
for (var i = 0; i < document.getElementsByClassName("fecha-limite").length; i++) {
    if (moment([document.getElementsByClassName("fecha-limite")[i].innerText], "YYYY-MM-DD").fromNow() !== "Fecha inválida") {
        document.getElementsByClassName("fecha-limite")[i].innerHTML = "Fecha límite: " + moment([document.getElementsByClassName("fecha-limite")[i].innerText], "YYYY-MM-DD").fromNow();
    } else {
        document.getElementsByClassName("fecha-limite")[i].innerHTML = "Fecha límite: No tiene";
    }
}