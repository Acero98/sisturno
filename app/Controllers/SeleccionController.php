<?php

require_once __DIR__ . '/../Models/SeleccionModel.php';
require_once __DIR__ . '/../../control/permisos.php';

class SeleccionController
{
    private $model;

    public function __construct($conexion)
    {
        $this->model = new SeleccionModel($conexion);
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
        $this->notificarSocket($notificacion);
        echo $resultado['ticket'];
    }

    private function notificarSocket($datos)
    {
        $curl = curl_init(SOCKETURL . '/notificar');
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($datos));
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($curl, CURLOPT_TIMEOUT, 3);
        curl_exec($curl);
        curl_close($curl);
    }
}
