$(function () {
    $('#id_usuarios_asociados').select2({
        ajax: {
            url: "/async/buscarUsuarios",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    usuarios: params.term
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

    if ($("#usuarios_selecionados").val() !== undefined) {
        // Obtener los usuarios selecionados del input oculto
        var usuariosPreseleccionados = JSON.parse($("#usuarios_selecionados").val());

        usuariosPreseleccionados.forEach(function (usuario) {
            var idUsuario = usuario.id_usuario;
            var username = usuario.username;
            // Verificar si el usuario ya está selecionado en el select2
            if ($("#id_usuarios_asociados").find('option[value="' + idUsuario + '"]').length === 0) {
                // Agregar el usuario como opción selecionada
                $("#id_usuarios_asociados").append('<option value="' + idUsuario + '" selected>' + username + '</option>').trigger('change');
            }
        });
    }
});
