moment.locale('es');
for (var i = 0; i < document.querySelectorAll(".fecha-limite").length; i++) {
    if (moment([document.querySelectorAll(".fecha-limite")[i].innerText], "YYYY-MM-DD").fromNow() !== "Fecha inválida") {
        document.querySelectorAll(".fecha-limite")[i].innerHTML = "<i class='fa-regular fa-clock'></i> " + moment([document.querySelectorAll(".fecha-limite")[i].innerText], "YYYY-MM-DD").fromNow();
    }
}
