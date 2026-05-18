document.addEventListener("DOMContentLoaded", function () {
    const checkboxes = document.querySelectorAll(".servicio-checkbox");

    function actualizarTarjeta(checkbox) {
        const card = checkbox.closest("label").querySelector(".servicio-card");

        if (checkbox.checked) {
            card.classList.add("bg-primary", "text-white", "border-primary");
            card.classList.remove("border-secondary");
        } else {
            card.classList.remove("bg-primary", "text-white", "border-primary");
            card.classList.add("border-secondary");
        }
    }

    checkboxes.forEach(checkbox => {
        actualizarTarjeta(checkbox);

        checkbox.addEventListener("change", function () {
            actualizarTarjeta(this);
        });
    });
});

document.addEventListener("DOMContentLoaded", function () {

    const formulario = document.querySelector(".formGuardarAsignaciones");

    if (formulario) {
        formulario.addEventListener("submit", function (e) {
            e.preventDefault();

            Swal.fire({
                title: '¿Guardar cambios?',
                text: 'Se actualizarán los servicios asignados al usuario.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0d6efd',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, guardar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {

                    // Mensaje de éxito
                    Swal.fire({
                        title: 'Actualizado correctamente',
                        text: 'Las asignaciones se guardaron con éxito.',
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        // Enviar el formulario después del mensaje
                        formulario.submit();
                    });

                }
            });
        });
    }

});