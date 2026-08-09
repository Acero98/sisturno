<?php

require_once __DIR__ . '/../Models/AtencionModel.php';
require_once __DIR__ . '/../../control/permisos.php';

class AtencionController
{
    private $model;

    public function __construct($conexion)
    {
        $this->model = new AtencionModel($conexion);
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
            $this->notificarSocket($notificacion ?? ['accion' => 'ticket_actualizado']);
        }
        header('Content-Type: text/plain; charset=utf-8');
        echo $resultado;
    }

    private function notificarSocket($datos)
    {
        $datos['fecha'] = date('Y-m-d H:i:s');
        $payload = json_encode($datos);
        $curl = curl_init(SOCKETURL . '/notificar');
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($curl, CURLOPT_TIMEOUT, 3);
        curl_exec($curl);
        curl_close($curl);
    }
}
