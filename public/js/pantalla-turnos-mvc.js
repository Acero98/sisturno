(function () {
    const config = window.PANTALLA_TURNOS_CONFIG;
    const player = document.getElementById('videoPlayer');
    const audio = document.getElementById('audioNotificacion');
    const videos = config.videos;
    let indiceVideo = parseInt(localStorage.getItem('videoIndex'), 10) || 0;
    let tiempoGuardado = parseFloat(localStorage.getItem('videoTime')) || 0;

    function reproducirVideoActual() {
        if (!player || videos.length === 0) return;
        player.src = videos[indiceVideo];
        player.addEventListener('loadedmetadata', function restaurarTiempo() {
            if (tiempoGuardado > 0 && tiempoGuardado < player.duration) player.currentTime = tiempoGuardado;
            player.removeEventListener('loadedmetadata', restaurarTiempo);
            player.play().catch(function (error) { console.warn('El navegador requiere interacción para reproducir el video.', error); });
        });
    }

    function siguienteVideo() {
        indiceVideo = (indiceVideo + 1) % videos.length;
        tiempoGuardado = 0;
        localStorage.setItem('videoIndex', indiceVideo);
        localStorage.setItem('videoTime', 0);
        reproducirVideoActual();
    }

    if (player) {
        player.addEventListener('ended', siguienteVideo);
        player.addEventListener('error', function () {
            console.warn('No se pudo cargar el video:', player.src);
            if (videos.length > 1) siguienteVideo();
        });
        setInterval(function () {
            if (!player.paused && Number.isFinite(player.currentTime)) {
                localStorage.setItem('videoIndex', indiceVideo);
                localStorage.setItem('videoTime', player.currentTime);
            }
        }, 1000);
        reproducirVideoActual();
    }

    document.addEventListener('pointerdown', function activarMultimedia() {
        if (player) {
            player.muted = false;
            player.play().catch(function () {});
        }
        document.removeEventListener('pointerdown', activarMultimedia);
    });

    function actualizarFechaHora() {
        const ahora = new Date();
        document.getElementById('horaActual').textContent = ahora.toLocaleTimeString('es-PE', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
        document.getElementById('fechaActual').textContent = ahora.toLocaleDateString('es-PE', { weekday: 'long', day: '2-digit', month: 'long', year: 'numeric' });
    }
    actualizarFechaHora();
    setInterval(actualizarFechaHora, 1000);

    function actualizarTablaTurnos() {
        fetch(config.baseUrl + 'index.php?ruta=pantalla-turnos&action=contenido', { credentials: 'same-origin' })
            .then(function (respuesta) {
                if (!respuesta.ok) throw new Error('No se pudo actualizar la tabla');
                return respuesta.text();
            })
            .then(function (html) { document.getElementById('contenedorTurnos').innerHTML = html; })
            .catch(function (error) { console.error('Error al actualizar la tabla:', error); });
    }

    const socket = io(SOCKET_URL);
    socket.on('connect', function () { console.log('Pantalla de turnos MVC conectada:', socket.id); });
    socket.on('actualizar_pantalla', function (data) {
        actualizarTablaTurnos();
        if (data && data.accion === 'ticket_llamado' && audio) {
            audio.currentTime = 0;
            audio.play().catch(function (error) { console.warn('No se pudo reproducir la notificación.', error); });
        }
    });
}());
