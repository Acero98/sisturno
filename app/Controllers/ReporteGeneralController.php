<?php

require_once __DIR__ . '/../Models/ReporteGeneralModel.php';
require_once __DIR__ . '/../../control/permisos.php';

class ReporteGeneralController
{
    private $model;
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
        $this->model = new ReporteGeneralModel($conexion);
    }

    public function index()
    {
        permitirSolo(['Super Admin', 'Admin']);
        [$tipo, $fechaInicio, $fechaFin] = $this->resolverRango();
        $metricas = $this->model->obtenerMetricas($fechaInicio, $fechaFin);
        $promedios = $this->model->obtenerPromedios($fechaInicio, $fechaFin);
        $estados = $this->model->obtenerEstados($fechaInicio, $fechaFin);
        $servicios = $this->model->obtenerServicios($fechaInicio, $fechaFin);
        $rankingOperadores = $this->model->obtenerRankingOperadores($fechaInicio, $fechaFin);
        $tiempoPorServicio = $this->model->obtenerTiempoPorServicio($fechaInicio, $fechaFin);
        $tendenciaResultado = $this->model->obtenerTendencia($fechaInicio, $fechaFin);
        $tendencia = $tendenciaResultado['datos'];
        $agrupacionTendencia = $tendenciaResultado['agrupacion'];
        $horasPico = $this->model->obtenerHorasPico($fechaInicio, $fechaFin);
        $graficas = [
            'estados' => $estados,
            'servicios' => $servicios,
            'tendencia' => $tendencia,
            'agrupacionTendencia' => $agrupacionTendencia,
            'horasPico' => $horasPico,
        ];
        require __DIR__ . '/../Views/Reportes/reporte_general.php';
    }

    private function resolverRango()
    {
        $tipo = $_GET['tipo'] ?? 'hoy';
        $hoy = date('Y-m-d');
        $rangos = ['hoy' => [$hoy, $hoy], 'semana' => [date('Y-m-d', strtotime('-6 days')), $hoy], 'mes' => [date('Y-m-01'), $hoy], 'anio' => [date('Y-01-01'), $hoy]];
        if ($tipo !== 'personalizado') {
            [$inicio, $fin] = $rangos[$tipo] ?? $rangos['hoy'];
            return [$tipo, $inicio, $fin];
        }
        $inicio = $_GET['inicio'] ?? '';
        $fin = $_GET['fin'] ?? '';
        $validas = preg_match('/^\d{4}-\d{2}-\d{2}$/', $inicio) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $fin) && $inicio <= $fin;
        return $validas ? [$tipo, $inicio, $fin] : ['hoy', $hoy, $hoy];
    }
}
