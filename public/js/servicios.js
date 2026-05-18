document.addEventListener("DOMContentLoaded", function () {

    // =========================
    // ELIMINAR
    // =========================
    document.querySelectorAll(".btnEliminar").forEach(btn => {

        btn.addEventListener("click", function (e) {
            e.preventDefault();

            let id = this.dataset.id;

            Swal.fire({
                title: '¿Eliminar usuario?',
                text: "Esta acción no se puede deshacer",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {

                if (result.isConfirmed) {
                    window.location.href = "registrar_usuario.php?id=" + id;
                }

            });

        });

    });

    // DESACTIVAR
    document.addEventListener("click", function (e) {

        if (e.target.closest(".btnDesactivar")) {

            const boton = e.target.closest(".btnDesactivar");
            const id = boton.dataset.id;

            Swal.fire({
                title: '¿Desactivar servicio?',
                text: 'El servicio dejará de mostrarse en la pantalla de selección.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, desactivar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {

                if (result.isConfirmed) {
                    window.location.href = "index.php?accion=desactivar&id=" + id;
                }

            });

        }

    });

    // ACTIVAR
    document.addEventListener("click", function (e) {

        if (e.target.closest(".btnActivar")) {

            const boton = e.target.closest(".btnActivar");
            const id = boton.dataset.id;

            Swal.fire({
                title: '¿Activar servicio?',
                text: 'El servicio volverá a mostrarse en la pantalla de selección.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, activar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {

                if (result.isConfirmed) {
                    window.location.href = "index.php?accion=activar&id=" + id;
                }

            });

        }

    });

    // =========================
    // EDITAR SERVICIOS
    document.addEventListener("submit", function (e) {

        if (!e.target.classList.contains("formEditarServicio")) return;

        e.preventDefault();

        const form = e.target;

        Swal.fire({
            title: '¿Guardar cambios?',
            text: "Se actualizará la información del servicio",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#ffc107',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {

            if (result.isConfirmed) {
                form.submit();
            }

        });

    });

    // =========================
    // REGISTRAR SERVICIOS
    // =========================
    document.addEventListener("click", function (e) {

        if (e.target.closest(".btnConfirmarRegistro")) {

            const boton = e.target.closest(".btnConfirmarRegistro");
            const form = boton.closest("form");

            if (!form) {
                console.log("No se encontró el formulario");
                return;
            }

            // Validar antes de mostrar SweetAlert
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            Swal.fire({
                title: '¿Registrar servicio?',
                text: "Se creará un nuevo servicio en el sistema",
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

    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".btnEditar").forEach(btn => {
            btn.addEventListener("click", function () {
                document.getElementById("edit_id").value =
                    this.dataset.id;

                document.getElementById("edit_nombre").value =
                    this.dataset.nombre;

                document.getElementById("edit_codigo").value =
                    this.dataset.codigo;
            });
        });
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
        mostrarMensaje('Desactivado', 'El servicio fue desactivado.', 'success', '#dc3545');
    }

    if (mensaje === "activado") {
        mostrarMensaje('Activado', 'El servicio fue activado.', 'success', '#198754');
    }

    if (mensaje === "existe") {
        mostrarMensaje('Duplicado', 'El código ya existe.', 'warning', '#ffc107');
    }

    function mostrarMensaje(titulo, texto, icono, color) {
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