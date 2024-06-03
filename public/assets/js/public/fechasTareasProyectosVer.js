moment.locale('es');
if (moment([document.querySelector("#fecha-limite").innerText], "YYYY-MM-DD").fromNow() !== "Fecha inválida") {
    document.querySelector("#fecha-limite").innerHTML = "Fecha límite: " + moment([document.querySelector("#fecha-limite").innerText], "YYYY-MM-DD").fromNow();
} else {
    document.querySelector("#fecha-limite").innerHTML = "Fecha límite: No tiene fecha límite";
}
