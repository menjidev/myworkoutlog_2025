<?php

require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/../model/Entrenamiento.php';
require_once __DIR__ . '/../model/Serie.php';
require_once __DIR__ . '/../model/Ejercicio.php';

class EntrenamientoController{
    
    public function index(){

        requireLogin();

        $idUsuario = $_SESSION['id_usuario'];

        $desde = $_GET['desde'] ?? null;
        $hasta = $_GET['hasta'] ?? null;

        $entrenamientos = Entrenamiento::listarPorUsuario($idUsuario, $desde, $hasta);

        $ultimosEntrenamientos = array_slice($entrenamientos, 0, 7);

        $tituloPagina   = 'Entrenamientos';
        $vistaContenido = __DIR__ . '/../view/app/entrenamientos/index.php';

        require __DIR__ . '/../view/layout.php';
    }

    public function resumen(){

    requireLogin();

    $idUsuario = $_SESSION['id_usuario'] ?? null;
    $id        = isset($_GET['id']) ? (int)$_GET['id'] : 0;

    if (!$idUsuario || $id <= 0) {
        header('Location: index.php?controller=entrenamiento&action=index');
        exit;
    }

    $entrenamiento = Entrenamiento::buscarPorId($id, $idUsuario);
    if (!$entrenamiento) {
        header('Location: index.php?controller=entrenamiento&action=index');
        exit;
    }

    $series = Serie::listarPorEntrenamiento($entrenamiento->getId(), $idUsuario);

    $ejerciciosPorId           = [];
    $seriesPorEjercicio        = [];
    $ejerciciosEnEntrenamiento = [];

    foreach ($series as $serie) {
        $idEjercicio = $serie->getIdEjercicio();

        if (!isset($ejerciciosPorId[$idEjercicio])) {
            $ejercicio = Ejercicio::buscarPorId($idEjercicio, $idUsuario);
            if (!$ejercicio) {
                continue;
            }

            $ejerciciosPorId[$idEjercicio]   = $ejercicio;
            $ejerciciosEnEntrenamiento[]     = $idEjercicio;
        }

        $seriesPorEjercicio[$idEjercicio][] = $serie;
    }

    $tituloPagina   = 'Resumen del entrenamiento';
    $vistaContenido = __DIR__ . '/../view/app/entrenamientos/resumen.php';

    require __DIR__ . '/../view/layout.php';
    }


    public function crearYElegirEjercicio(){

        requireLogin();

        $idUsuario = $_SESSION['id_usuario'];

        $fecha  = $_POST['fecha'] ?? date('Y-m-d');
        $nombre = normalizarTextoFormulario($_POST['nombre_entrenamiento'] ?? '');
        $notas  = $_POST['notas'] ?? null; 

        if (trim($nombre) === '') {
            $error = urlencode('El nombre del entrenamiento es obligatorio');
            header('Location: index.php?controller=entrenamiento&action=index&error=' . $error);
            exit;
        }

        $ent = new Entrenamiento(
            null,
            $idUsuario,
            $fecha,
            $nombre,
            $notas
        );

        if (!$ent->crear()) {
            $error = urlencode('No se ha podido crear el entrenamiento');
            header('Location: index.php?controller=entrenamiento&action=index&error=' . $error);
            exit;
        }

        $idNuevo = $ent->getId();

        header(
            'Location: index.php?controller=ejercicio&action=index'
            . '&id_entrenamiento=' . $idNuevo
        );
        exit;
    }

    public function actualizar(){

        requireLogin();

        $idUsuario = $_SESSION['id_usuario'];
        $id        = isset($_POST['id']) ? (int)$_POST['id'] : 0;

        if ($id <= 0) {
            header('Location: index.php?controller=entrenamiento&action=index');
            exit;
        }

        $entrenamiento = Entrenamiento::buscarPorId($id, $idUsuario);
        if (!$entrenamiento) {
            header('Location: index.php?controller=entrenamiento&action=index');
            exit;
        }

        $fecha = $_POST['fecha'] ?? $entrenamiento->getFecha();
        $entrenamiento->setFecha($fecha);

        if (isset($_POST['nombre_entrenamiento'])) {
            $nombre = trim($_POST['nombre_entrenamiento']);

            if ($nombre === '') {
                $nombre = $entrenamiento->getNombreEntrenamiento();
            }

            $entrenamiento->setNombreEntrenamiento($nombre);
        }

        if (array_key_exists('notas', $_POST)) {
            $notas = trim($_POST['notas']);
            $entrenamiento->setNotas($notas !== '' ? $notas : null);
        }

        $entrenamiento->actualizar();

        header('Location: index.php?controller=entrenamiento&action=ver&id=' . $id);
        exit;
    }

    public function eliminar(){

        requireLogin();

        $idUsuario = $_SESSION['id_usuario'];
        $id        = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        $entrenamiento = Entrenamiento::buscarPorId($id, $idUsuario);

        if ($entrenamiento) {
            $entrenamiento->eliminar();
        }

        header('Location: index.php?controller=entrenamiento&action=index');
        exit;
    }

    public function ver(){

        requireLogin();

        $idUsuario       = $_SESSION['id_usuario'];
        $idEntrenamiento = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        if ($idEntrenamiento <= 0) {
            header('Location: index.php?controller=entrenamiento&action=index');
            exit;
        }

        $entrenamiento = Entrenamiento::buscarPorId($idEntrenamiento, $idUsuario);
        if (!$entrenamiento) {
            header('Location: index.php?controller=entrenamiento&action=index');
            exit;
        }

        $series = Serie::listarPorEntrenamiento($idEntrenamiento, $idUsuario);

        $ejercicios = Ejercicio::listarCatalogoFiltrado($idUsuario, null, null);

        $selectedEjercicio = isset($_GET['id_ejercicio']) ? (int)$_GET['id_ejercicio'] : null;

        $ejerciciosPorId = [];
        foreach ($ejercicios as $ejer) {
            $ejerciciosPorId[$ejer->getId()] = $ejer;
        }

        $seriesPorEjercicio = [];
        foreach ($series as $s) {
            $idEjer = $s->getIdEjercicio();
            if (!isset($seriesPorEjercicio[$idEjer])) {
                $seriesPorEjercicio[$idEjer] = [];
            }
            $seriesPorEjercicio[$idEjer][] = $s;
        }

        $ejerciciosEnEntrenamiento = array_keys($seriesPorEjercicio);

        if (!empty($selectedEjercicio)
            && !in_array($selectedEjercicio, $ejerciciosEnEntrenamiento, true)
        ) {
            $ejerciciosEnEntrenamiento[] = $selectedEjercicio;
        }

        $recomendacionesIniciales  = [];
        $recomendacionesSiguientes = [];

        foreach ($ejerciciosEnEntrenamiento as $idEjer) {
            $seriesEj = $seriesPorEjercicio[$idEjer] ?? [];

            if (empty($seriesEj)) {
            
                $reco = Serie::recomendarPesoInicial(
                    $idUsuario,
                    $idEjer,
                    $entrenamiento->getId()
                );
                if ($reco !== null) {
                    $recomendacionesIniciales[$idEjer] = $reco;
                }
            } else {
            
                $ultimaSerie = end($seriesEj);
                if ($ultimaSerie instanceof Serie) {
                    $recomendacionesSiguientes[$idEjer] = Serie::recomendarDesdeSerieActual(
                        $ultimaSerie->getRepeticiones(),
                        (float)$ultimaSerie->getPesoKg()
                    );
                }
            }
        }

        $tituloPagina   = 'Entrenamiento';
        $vistaContenido = __DIR__ . '/../view/app/entrenamientos/ver.php';

        require __DIR__ . '/../view/layout.php';
    }
}
