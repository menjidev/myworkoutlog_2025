<?php

require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/../model/Entrenamiento.php';
require_once __DIR__ . '/../model/Serie.php';
require_once __DIR__ . '/../model/Ejercicio.php';

class MetricsController{

    public function index(){
        
        requireLogin();

        $idUsuario = $_SESSION['id_usuario'];

        $filtroActual = $_GET['filtro'] ?? 'ultimos30';

        if (isset($_GET['month_picker']) && preg_match('/^\d{4}-\d{2}$/', $_GET['month_picker'])) {
            [$yearStr, $monthStr] = explode('-', $_GET['month_picker']);
            $year  = (int)$yearStr;
            $month = (int)$monthStr;
        } else {
            $year  = (int)date('Y');
            $month = (int)date('n');
        }

        $q = isset($_GET['q']) ? trim($_GET['q']) : '';

        $entrenosMes = Entrenamiento::listarPorMes(
            $idUsuario,
            $year,
            $month,
            $q === '' ? null : $q
        );

        $calendario = [];
        foreach ($entrenosMes as $ent) {
            $dia = (int)date('j', strtotime($ent['fecha']));

            if (!isset($calendario[$dia])) {
                $calendario[$dia] = [];
            }

            $calendario[$dia][] = [
                'id'     => (int)$ent['id'],
                'nombre' => $ent['nombre_entrenamiento'],
            ];
        }

        $hoy   = date('Y-m-d');
        $desde = null;
        $hasta = null;

        $grupoFuerzaActual = $_GET['grupo_fuerza'] ?? 'todos';

        switch ($filtroActual) {
            case 'siempre':
                $desde = null;
                $hasta = null;
                break;

            case 'mes_anterior':
                $primerDiaMesActual   = new DateTime('first day of this month');
                $primerDiaMesAnterior = (clone $primerDiaMesActual)->modify('-1 month');
                $ultimoDiaMesAnterior = (clone $primerDiaMesAnterior)->modify('last day of this month');

                $desde = $primerDiaMesAnterior->format('Y-m-d');
                $hasta = $ultimoDiaMesAnterior->format('Y-m-d');
                break;

            case 'ultimos30':
            default:
                $hasta        = $hoy;
                $desde        = date('Y-m-d', strtotime('-30 days'));
                $filtroActual = 'ultimos30';
                break;
        }

        $gruposCatalogo   = Ejercicio::listarGruposMuscularesCatalogo($idUsuario);
        $gruposMusculares = $gruposCatalogo;

        $resumenGrupos = [];
        foreach ($gruposCatalogo as $grupo) {
            $resumenGrupos[$grupo] = 0;
        }

        $rowsGrupos = Serie::contarDiasPorGrupoMuscular($idUsuario, $desde, $hasta);

        foreach ($rowsGrupos as $row) {
            $grupo = $row['grupo_muscular'];
            $dias  = (int)$row['total_dias'];

            $resumenGrupos[$grupo] = $dias;
        }

        $entrenamientos      = Entrenamiento::listarPorUsuario($idUsuario, $desde, $hasta);
        $totalEntrenamientos = count($entrenamientos);

        $totalSeries = Serie::contarSeriesPorUsuarioYRango($idUsuario, $desde, $hasta);

        $grupoFiltro = ($grupoFuerzaActual === 'todos') ? null : $grupoFuerzaActual;

        $topFuerza = Serie::ejercicioConMasPeso(
            $idUsuario,
            $desde,
            $hasta,
            $grupoFiltro
        );

        $topFrecuencia = Serie::ejerciciosMasFrecuentes($idUsuario, $desde, $hasta, 5);

        $primerDiaSemana = (int)date('N', strtotime("$year-$month-01"));
        $diasMes         = (int)date('t', strtotime("$year-$month-01"));

        $tituloPagina   = 'Métricas';
        $vistaContenido = __DIR__ . '/../view/app/metricas/index.php';

        require __DIR__ . '/../view/layout.php';
    }
}
