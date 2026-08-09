document.addEventListener('DOMContentLoaded', () => {
    const checks = [...document.querySelectorAll('.service-checkbox')];
    const actualizar = (check) => check.closest('.service-selector').querySelector('.service-card').classList.toggle('is-selected', check.checked);
    const contador = document.getElementById('contadorServicios');
    const actualizarContador = () => { if (contador) contador.textContent = `${checks.filter(c => c.checked).length} servicio(s) seleccionado(s)`; };
    checks.forEach(check => { actualizar(check); check.addEventListener('change', () => { actualizar(check); actualizarContador(); }); });
    actualizarContador();
    document.getElementById('btnSeleccionarTodo')?.addEventListener('click', () => { checks.forEach(c => { c.checked = true; actualizar(c); }); actualizarContador(); });
    document.getElementById('btnDeseleccionarTodo')?.addEventListener('click', () => { checks.forEach(c => { c.checked = false; actualizar(c); }); actualizarContador(); });
    document.querySelector('.formGuardarAsignacionesMvc')?.addEventListener('submit', (event) => { if (event.target.dataset.confirmado === '1') return; event.preventDefault(); Swal.fire({ title: '¿Guardar asignaciones?', text: 'Se actualizarán los servicios disponibles para este operador.', icon: 'question', showCancelButton: true, confirmButtonColor: '#0f766e', cancelButtonColor: '#6c757d', confirmButtonText: 'Sí, guardar', cancelButtonText: 'Cancelar' }).then(r => { if (r.isConfirmed) { event.target.dataset.confirmado = '1'; event.target.requestSubmit(); } }); });
    const mensaje = new URLSearchParams(window.location.search).get('mensaje'); if (mensaje === 'asignaciones_guardadas') Swal.fire({ title: '¡Guardado!', text: 'Las asignaciones se actualizaron correctamente.', icon: 'success', confirmButtonColor: '#0f766e' }).then(() => window.history.replaceState({}, document.title, window.location.pathname + window.location.search.replace(/&mensaje=[^&]*/, '')));
});
