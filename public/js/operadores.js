document.addEventListener("DOMContentLoaded", function () {

    // =========================
    // ELIMINAR
    // =========================
    document.querySelectorAll(".btnEliminar").forEach(btn => {

        btn.addEventListener("click", function(e){
            e.preventDefault();

            let id = this.dataset.id;

            Swal.fire({
                title: '¿Eliminar operador?',
                text: "Esta acción no se puede deshacer",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {

                if (result.isConfirmed) {
                    window.location.href = "lista_operadores.php?id=" + id;
                }

            });

        });

    });

    // DESACTIVAR
    document.addEventListener("click", function(e){

        if(e.target.closest(".btnDesactivarOpe")){

            const boton = e.target.closest(".btnDesactivarOpe");
            const id = boton.dataset.id;

            Swal.fire({
                title: '¿Desactivar operador?',
                text: 'El operador no podrá iniciar sesión',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, desactivar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {

                if (result.isConfirmed) {
                    window.location.href = "lista_operadores.php?accion=desactivar&id=" + id;
                }

            });

        }

    });

    // ACTIVAR
    document.addEventListener("click", function(e){

        if(e.target.closest(".btnActivarOpe")){

            const boton = e.target.closest(".btnActivarOpe");
            const id = boton.dataset.id;

            Swal.fire({
                title: '¿Activar operador?',
                text: 'El operador podrá iniciar sesión nuevamente.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, activar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {

                if (result.isConfirmed) {
                    window.location.href = "lista_operadores.php?accion=activar&id=" + id;
                }

            });

        }

    });

    // =========================
    // EDITAR USUARIO
    document.addEventListener("submit", function(e){

        if(!e.target.classList.contains("formEditarUsuario")) return;

        e.preventDefault();

        const form = e.target;

        Swal.fire({
            title: '¿Guardar cambios?',
            text: "Se actualizará la información del operador",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#ffc107',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {

            if(result.isConfirmed){
                form.submit();
            }

        });

    });

    // =========================
    // REGISTRAR USUARIO
    // =========================
    document.addEventListener("click", function(e){

        if(e.target.closest(".btnConfirmarRegistroOpe")){

            const boton = e.target.closest(".btnConfirmarRegistroOpe");
            const form = boton.closest("form");

            if(!form){
                console.log("No se encontró el formulario");
                return;
            }

            // Validar antes de mostrar SweetAlert
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            Swal.fire({
                title: '¿Registrar operador?',
                text: "Se creará un nuevo operador en el sistema",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0d6efd',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, registrar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {

                if (result.isConfirmed) {
                form.requestSubmit(); // AQUÍ ESTÁ LA CLAVE
                }

            });

        }

    });


    // =========================
    // MENSAJES DESPUÉS DE ACCIÓN
    // =========================
    const params = new URLSearchParams(window.location.search);
    const mensaje = params.get("mensaje");

    if (mensaje === "eliminado") {
        mostrarMensaje('¡Eliminado!', 'El registro fue eliminado correctamente.', 'success', '#198754');
    }

    if (mensaje === "registrado") {
        mostrarMensaje('¡Registrado!', 'El registro fue guardado correctamente.', 'success', '#0d6efd');
    }

    if (mensaje === "actualizado") {
        mostrarMensaje('¡Actualizado!', 'Los cambios se guardaron correctamente.', 'success', '#ffc107');
    }

    if (mensaje === "desactivado") {
    mostrarMensaje('Desactivado', 'El operador fue desactivado.', 'success', '#dc3545');
    }

    if (mensaje === "activado") {
        mostrarMensaje('Activado', 'El operador fue activado.', 'success', '#198754');
    }

    if (mensaje === "usuario_existe") {
    mostrarMensaje('Duplicado', 'El usurio ya existe.', 'warning', '#ffc107');
    }

    if (mensaje === "dni_existe") {
    mostrarMensaje('Duplicado', 'El dni ya existe.', 'warning', '#ffc107');
    }

    function mostrarMensaje(titulo, texto, icono, color){
        Swal.fire({
            title: titulo,
            text: texto,
            icon: icono,
            confirmButtonColor: color
        }).then(() => {
            window.history.replaceState({}, document.title, window.location.pathname);
        });
    }

});