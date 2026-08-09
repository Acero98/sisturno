<?php

require_once __DIR__ . '/../Models/ConsultaGeneralModel.php';
require_once __DIR__ . '/../../control/permisos.php';

class ConsultaGeneralController
{
    private $model;

    public function __construct($conexion)
    {
        $this->model = new ConsultaGeneralModel($conexion);
    }

    public function index()
    {
        permitirSolo(['Super Admin', 'Admin']);

        [$tipo, $fechaInicio, $fechaFin] = $this->resolverRango($_GET);
        $metricas = $this->model->obtenerMetricas($fechaInicio, $fechaFin);
        $servicios = $this->model->obtenerServicios();
        $operadores = $this->model->obtenerOperadores();

        require __DIR__ . '/../Views/Reportes/consulta_general.php';
    }

    public function datos()
    {
        permitirSolo(['Super Admin', 'Admin']);
        [$tipo, $fechaInicio, $fechaFin] = $this->resolverRango($_POST);
        $inicioRegistro = (int) ($_POST['start'] ?? 0);
        $cantidad = (int) ($_POST['length'] ?? 10);
        $filtros = [
            'estado' => $_POST['estado'] ?? '',
            'servicio' => $_POST['servicio'] ?? '',
            'operador' => $_POST['operador'] ?? '',
            'search' => $_POST['search']['value'] ?? '',
        ];
        [$columnaOrden, $direccionOrden] = $this->resolverOrden($_POST['order'][0] ?? []);

        $total = $this->model->contarTickets($fechaInicio, $fechaFin);
        $filtrados = $this->model->contarTickets($fechaInicio, $fechaFin, $filtros);
        $tickets = $this->model->obtenerTickets($fechaInicio, $fechaFin, $filtros, $inicioRegistro, $cantidad, $columnaOrden, $direccionOrden);
        $data = [];

        foreach ($tickets as $indice => $ticket) {
            $data[] = [
                $inicioRegistro + $indice + 1,
                $ticket['numero_tk'],
                $ticket['nombre_serv'] ?? '',
                $this->estadoHtml($ticket['estado_tk']),
                $ticket['nombre_user'] ?? '',
                $ticket['fecha_tk'],
                $ticket['creado_tk'],
                $ticket['hora_cita'],
                $ticket['hora_atencion'],
                $ticket['hora_finalizado'],
            ];
        }

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'draw' => (int) ($_POST['draw'] ?? 0),
            'recordsTotal' => $total,
            'recordsFiltered' => $filtrados,
            'data' => $data,
        ]);
    }

    private function estadoHtml($estado)
    {
        $clases = [
            'FINALIZADO' => 'bg-success',
            'PENDIENTE' => 'bg-warning text-dark',
            'CANCELADO' => 'bg-danger',
        ];
        $clase = $clases[$estado] ?? 'bg-primary';
        return '<span class="badge ' . $clase . '">' . htmlspecialchars($estado, ENT_QUOTES, 'UTF-8') . '</span>';
    }

    private function resolverOrden($orden)
    {
        $columnas = [
            0 => 't.id_tickets',
            1 => 't.numero_tk',
            2 => 's.nombre_serv',
            3 => 't.estado_tk',
            4 => 'u.nombre_user',
            5 => 't.fecha_tk',
            6 => 't.creado_tk',
            7 => 't.hora_cita',
            8 => 't.hora_atencion',
            9 => 't.hora_finalizado',
        ];
        $indice = (int) ($orden['column'] ?? 6);
        $direccion = strtolower($orden['dir'] ?? 'desc') === 'asc' ? 'ASC' : 'DESC';

        return [$columnas[$indice] ?? 't.creado_tk', $direccion];
    }

    private function resolverRango($parametros)
    {
        $hoy = date('Y-m-d');
        $tipo = $parametros['tipo'] ?? 'hoy';
        $rangos = [
            'hoy' => [$hoy, $hoy],
            'semana' => [date('Y-m-d', strtotime('-6 days')), $hoy],
            'mes' => [date('Y-m-01'), $hoy],
            'anio' => [date('Y-01-01'), $hoy],
        ];

        if ($tipo !== 'personalizado') {
            [$inicio, $fin] = $rangos[$tipo] ?? $rangos['hoy'];
            return [$tipo, $inicio, $fin];
        }

        $inicio = $parametros['inicio'] ?? '';
        $fin = $parametros['fin'] ?? '';
        $fechasValidas = preg_match('/^\d{4}-\d{2}-\d{2}$/', $inicio)
            && preg_match('/^\d{4}-\d{2}-\d{2}$/', $fin)
            && $inicio <= $fin;

        return $fechasValidas ? [$tipo, $inicio, $fin] : ['hoy', $hoy, $hoy];
    }
}
