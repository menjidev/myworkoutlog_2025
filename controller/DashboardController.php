<?php

require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/../model/Entrenamiento.php';
require_once __DIR__ . '/../model/Serie.php';

class DashboardController
{
    public function index(){

        requireLogin();

        $idUsuario = $_SESSION['id_usuario'];

        $hoy = new DateTime();

        $primerDiaMes = (clone $hoy)->modify('first day of this month')->format('Y-m-d');
        $ultimoDiaMes = (clone $hoy)->modify('last day of this month')->format('Y-m-d');

        $entrenamientosMes      = Entrenamiento::listarPorUsuario($idUsuario, $primerDiaMes, $ultimoDiaMes);
        $totalEntrenamientosMes = count($entrenamientosMes);

        $totalSeriesMes = Serie::contarSeriesPorUsuarioYRango(
            $idUsuario,
            $primerDiaMes,
            $ultimoDiaMes
        );

        $topFuerzaMes = Serie::ejercicioConMasPeso(
            $idUsuario,
            $primerDiaMes,
            $ultimoDiaMes
        );

        $entrenamientosTodos   = Entrenamiento::listarPorUsuario($idUsuario, null, null);
        $ultimosEntrenamientos = array_slice($entrenamientosTodos, 0, 7);

        $tituloPagina   = 'Inicio';
        $vistaContenido = __DIR__ . '/../view/app/home/index.php';

        require __DIR__ . '/../view/layout.php';
    }
}
