moment.locale('es');
if (moment([document.getElementById("fecha-limite").innerText], "YYYY-MM-DD").fromNow() !== "Fecha inválida") {
    document.getElementById("fecha-limite").innerHTML = "Fecha límite: " + moment([document.getElementById("fecha-limite").innerText], "YYYY-MM-DD").fromNow();
} else {
    document.getElementById("fecha-limite").innerHTML = "Fecha límite: No tiene fecha límite";
}