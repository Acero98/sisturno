<?php

require_once __DIR__ . '/../Models/InicioModel.php';
require_once __DIR__ . '/../../control/permisos.php';

class InicioController
{
    private $model;


    public function __construct($conexion)
    {
        $this->model = new InicioModel($conexion);
    }


    public function index()
    {
        permitirSolo(['Super Admin', 'Admin']);
        // =================================================
        // MÉTRICAS
        // =================================================

        $total = $this->model->obtenerTotal();

        $pendientes = $this->model->obtenerPendientes();

        $llamados = $this->model->obtenerLlamados();

        $en_atencion = $this->model->obtenerEnAtencion();

        $atendidos = $this->model->obtenerAtendidos();

        $cancelados = $this->model->obtenerCancelados();


        // =================================================
        // TIEMPOS
        // =================================================

        $promedioEspera =
            $this->model->obtenerPromedioEspera();

        $promedioAtencion =
            $this->model->obtenerPromedioAtencion();


        // =================================================
        // GRÁFICOS
        // =================================================

        $topServicios =
            $this->model->obtenerTopServicios();

        $horasPico =
            $this->model->obtenerHorasPico();

        $tendencia =
            $this->model->obtenerTendencia();


        // =================================================
        // RANKING
        // =================================================

        $rankingOperadores =
            $this->model->obtenerRankingOperadores();

        $usuarioData = $this->model->obtenerUsuario(
            $_SESSION['id_usuario']
        );


        // =================================================
        // FECHA Y HORA
        // =================================================

        $fechaActual = date('d/m/Y');

        $horaActual = date('h:i A');


        // =================================================
        // VARIABLES PARA LOS GRÁFICOS
        // =================================================

        $serviciosLabels = [];
        $serviciosData = [];

        foreach ($topServicios as $servicio) {

            $serviciosLabels[] =
                $servicio['nombre_serv'];

            $serviciosData[] =
                (int) $servicio['total_atenciones'];
        }


        $tendenciaLabels = [];
        $tendenciaData = [];

        foreach ($tendencia as $dato) {

            $tendenciaLabels[] =
                $dato['fecha'];

            $tendenciaData[] =
                $dato['total'];
        }


        $horasLabels = [];
        $horasData = [];

        foreach ($horasPico as $dato) {

            $horasLabels[] =
                $dato['hora'];

            $horasData[] =
                $dato['total'];
        }


        // =================================================
        // CARGAR VISTA
        // =================================================

        require __DIR__ . '/../Views/Inicio/index.php';
    }
}
