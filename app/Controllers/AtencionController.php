<?php

require_once __DIR__ . '/../Models/AtencionModel.php';
require_once __DIR__ . '/../Services/NotificadorSocket.php';
require_once __DIR__ . '/../../control/permisos.php';

class AtencionController
{
    private $model;
    private $notificadorSocket;

    public function __construct($conexion)
    {
        $this->model = new AtencionModel($conexion);
        $this->notificadorSocket = new NotificadorSocket();
    }

    public function obtenerContextoActual()
    {
        permitirSolo(['Super Admin', 'Admin', 'Operador']);
        return $this->model->obtenerContexto((int) $_SESSION['id_usuario']);
    }

    public function index()
    {
        $contexto = $this->obtenerContextoActual();
        extract($contexto);
        require __DIR__ . '/../Views/Atencion/index.php';
    }

    public function contenido()
    {
        $contexto = $this->obtenerContextoActual();
        extract($contexto);
        require __DIR__ . '/../Views/Atencion/components/contenido.php';
    }

    public function llamar()
    {
        $resultado = $this->model->llamarSiguiente($this->idUsuarioAutorizado());
        if (is_array($resultado)) {
            $this->responderAccion($resultado['estado'], ['accion' => 'ticket_llamado']);
            return;
        }
        $this->responderAccion($resultado);
    }

    public function comenzar()
    {
        $this->responderAccion($this->model->comenzarAtencion($this->idUsuarioAutorizado()));
    }

    public function finalizar()
    {
        $this->responderAccion($this->model->finalizarAtencion($this->idUsuarioAutorizado()));
    }

    public function cancelar()
    {
        $this->responderAccion($this->model->cancelarAtencion($this->idUsuarioAutorizado()));
    }

    private function idUsuarioAutorizado()
    {
        permitirSolo(['Super Admin', 'Admin', 'Operador']);
        return (int) $_SESSION['id_usuario'];
    }

    private function responderAccion($resultado, $notificacion = null)
    {
        if ($resultado === 'OK') {
            $this->notificadorSocket->notificar($notificacion ?? ['accion' => 'ticket_actualizado']);
        }
        header('Content-Type: text/plain; charset=utf-8');
        echo $resultado;
    }

}
