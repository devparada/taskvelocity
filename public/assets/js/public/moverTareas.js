const divTareas = document.querySelector("#tareas-grid");

var inicialX, offsetX;

function moverTareas(evento) {
    var distanciaX = evento.clientX - inicialX;
    var nuevaPosicionX = offsetX - distanciaX;

    // Establece la nueva posición del elemento
    divTareas.scrollLeft = nuevaPosicionX;
}

divTareas.addEventListener("mousedown", function (evento) {
    // Guarda la posición inicial del ratón y la posición inicial del elemento
    inicialX = evento.clientX;
    offsetX = divTareas.scrollLeft;

    divTareas.addEventListener("mousemove", moverTareas);
});

document.addEventListener("mouseup", function () {
    divTareas.removeEventListener("mousemove", moverTareas);
});
