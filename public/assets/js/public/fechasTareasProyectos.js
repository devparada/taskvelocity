moment.locale('es');
for (var i = 0; i < $(".fecha-limite").length; i++) {
    if (moment([$(".fecha-limite")[i].innerText], "YYYY-MM-DD").fromNow() !== "Fecha inválida") {
        $(".fecha-limite")[i].innerHTML = "<i class='fa-regular fa-clock'></i> " + moment([$(".fecha-limite")[i].innerText], "YYYY-MM-DD").fromNow();
    }
}
