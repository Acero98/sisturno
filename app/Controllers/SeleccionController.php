<?php

require_once __DIR__ . '/../Models/SeleccionModel.php';
require_once __DIR__ . '/../Services/NotificadorSocket.php';
require_once __DIR__ . '/../../control/permisos.php';

class SeleccionController
{
    private $model;
    private $notificadorSocket;

    public function __construct($conexion)
    {
        $this->model = new SeleccionModel($conexion);
        $this->notificadorSocket = new NotificadorSocket();
    }

    public function obtenerServiciosActivos()
    {
        permitirSolo(['Super Admin', 'Admin', 'Monitor', 'Turnos']);
        return $this->model->obtenerServiciosActivos();
    }

    public function index()
    {
        $servicios = $this->obtenerServiciosActivos();
        require __DIR__ . '/../Views/Seleccion/index.php';
    }

    public function contenido()
    {
        $servicios = $this->obtenerServiciosActivos();
        require __DIR__ . '/../Views/Seleccion/components/contenido.php';
    }

    public function generarTicket()
    {
        permitirSolo(['Super Admin', 'Admin', 'Monitor', 'Turnos']);
        $resultado = $this->model->generarTicket($_POST['id_servicios'] ?? 0);

        header('Content-Type: text/plain; charset=utf-8');
        if (!$resultado['ok']) {
            echo $resultado['error'];
            return;
        }

        $notificacion = ['accion' => 'nuevo_ticket'];
        if (($_POST['imprimir'] ?? '') === '1') {
            $notificacion['ticket'] = $resultado['ticket'];
            $notificacion['servicio'] = $resultado['servicio'];
            $notificacion['tipo'] = 'nuevo_ticket';
        }
        $this->notificadorSocket->notificar($notificacion);
        echo $resultado['ticket'];
    }

}
