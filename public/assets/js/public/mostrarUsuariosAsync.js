$(document).ready(function () {
    $('#id_usuarios_asociados').select2({
        ajax: {
            url: "/async/buscarUsuarios",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term
                };
            },
            processResults: function (usuarios) {
                return {
                    results: usuarios.results
                };
            },
            cache: true
        },
        minimumInputLength: 3,
        placeholder: "Empieza a escribir un usuario"
    });
});