const params = new URLSearchParams(window.location.search);
const error = params.get("error");

if(error === "noexiste"){
    Swal.fire({
        icon: 'error',
        title: 'Usuario no existe',
        confirmButtonColor: '#dc3545'
    });
}

if(error === "password"){
    Swal.fire({
        icon: 'error',
        title: 'Contraseña incorrecta',
        confirmButtonColor: '#dc3545'
    });
}

if(error === "desactivado"){
    Swal.fire({
        icon: 'warning',
        title: 'Usuario desactivado',
        text: 'Contacte al administrador',
        confirmButtonColor: '#ffc107'
    });
}

// Limpia la URL
window.history.replaceState({}, document.title, window.location.pathname);
