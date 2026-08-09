<?php

require_once __DIR__ . '/../Models/PantallaTurnosModel.php';
require_once __DIR__ . '/../../control/permisos.php';

class PantallaTurnosController
{
    private $model;

    public function __construct($conexion)
    {
        $this->model = new PantallaTurnosModel($conexion);
    }

    public function obtenerTurnosActivos()
    {
        permitirSolo(['Super Admin', 'Admin', 'Operador', 'Monitor', 'Turnos']);
        return $this->model->obtenerTurnosActivos();
    }

    public function index()
    {
        $turnos = $this->obtenerTurnosActivos();
        require __DIR__ . '/../Views/PantallaTurnos/index.php';
    }

    public function contenido()
    {
        $turnos = $this->obtenerTurnosActivos();
        require __DIR__ . '/../Views/PantallaTurnos/components/tabla_turnos.php';
    }
}
