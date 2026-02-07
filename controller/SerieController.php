<?php

require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/../model/Serie.php';
require_once __DIR__ . '/../model/Entrenamiento.php';
require_once __DIR__ . '/../model/Ejercicio.php';

class SerieController{

    public function crear(){

        requireLogin();

        $idUsuario       = $_SESSION['id_usuario'];
        $idEntrenamiento = (int)($_POST['id_entrenamiento'] ?? 0);
        $idEjercicio     = (int)($_POST['id_ejercicio'] ?? 0);
        $numSerie        = (int)($_POST['num_serie'] ?? 1);
        $repeticiones    = (int)($_POST['repeticiones'] ?? 0);
        $pesoKg          = $_POST['peso_kg'] !== '' ? (float)$_POST['peso_kg'] : null;
        $descansoSeg     = $_POST['descanso_seg'] !== '' ? (int)$_POST['descanso_seg'] : null;
        $notas           = $_POST['notas'] !== '' ? $_POST['notas'] : null;

        $ent = Entrenamiento::buscarPorId($idEntrenamiento, $idUsuario);
        if (!$ent || $idEjercicio <= 0 || $repeticiones <= 0) {
            header('Location: index.php?controller=entrenamiento&action=ver&id=' . $idEntrenamiento);
            exit;
        }

        $serie = new Serie(
            null,
            $idEntrenamiento,
            $idEjercicio,
            $numSerie,
            $repeticiones,
            $pesoKg,
            $descansoSeg,
            $notas
        );

        $serie->crear();

        header('Location: index.php?controller=entrenamiento&action=ver&id=' . $idEntrenamiento);
        exit;
    }

    public function editar(){

        requireLogin();

        $idUsuario       = $_SESSION['id_usuario'];
        $idSerie         = (int)($_GET['id'] ?? 0);
        $idEntrenamiento = (int)($_GET['id_entrenamiento'] ?? 0);

        $ent = Entrenamiento::buscarPorId($idEntrenamiento, $idUsuario);
        if (!$ent) {
            header('Location: index.php?controller=entrenamiento&action=index');
            exit;
        }

        $series      = Serie::listarPorEntrenamiento($idEntrenamiento, $idUsuario);
        $serieEditar = null;
        foreach ($series as $s) {
            if ($s->getId() === $idSerie) {
                $serieEditar = $s;
                break;
            }
        }

        if (!$serieEditar) {
            header('Location: index.php?controller=entrenamiento&action=ver&id=' . $idEntrenamiento);
            exit;
        }

        $ejercicios = Ejercicio::listarPorUsuario($idUsuario);

        $tituloPagina   = 'Editar serie';
        $vistaContenido = __DIR__ . '/../view/app/series/editar.php';

        require __DIR__ . '/../view/layout.php';
    }

    public function actualizar(){

        requireLogin();

        $idUsuario       = $_SESSION['id_usuario'];
        $idSerie         = (int)($_POST['id'] ?? 0);
        $idEntrenamiento = (int)($_POST['id_entrenamiento'] ?? 0);

        $ent = Entrenamiento::buscarPorId($idEntrenamiento, $idUsuario);
        if (!$ent) {
            header('Location: index.php?controller=entrenamiento&action=index');
            exit;
        }

        $series = Serie::listarPorEntrenamiento($idEntrenamiento, $idUsuario);
        $serie  = null;
        foreach ($series as $s) {
            if ($s->getId() === $idSerie) {
                $serie = $s;
                break;
            }
        }

        if (!$serie) {
            header('Location: index.php?controller=entrenamiento&action=ver&id=' . $idEntrenamiento);
            exit;
        }

        $idEjercicio  = (int)($_POST['id_ejercicio'] ?? 0);
        $numSerie     = (int)($_POST['num_serie'] ?? 1);
        $repeticiones = (int)($_POST['repeticiones'] ?? 0);
        $pesoKg       = $_POST['peso_kg'] !== '' ? (float)$_POST['peso_kg'] : null;
        $descansoSeg  = $_POST['descanso_seg'] !== '' ? (int)$_POST['descanso_seg'] : null;
        $notas        = $_POST['notas'] !== '' ? $_POST['notas'] : null;

        $serie->setIdEjercicio($idEjercicio);
        $serie->setNumSerie($numSerie);
        $serie->setRepeticiones($repeticiones);
        $serie->setPesoKg($pesoKg);
        $serie->setDescansoSeg($descansoSeg);
        $serie->setNotas($notas);

        $serie->actualizar();

        header('Location: index.php?controller=entrenamiento&action=ver&id=' . $idEntrenamiento);
        exit;
    }

    public function eliminar(){

        requireLogin();

        $idUsuario       = $_SESSION['id_usuario'];
        $idSerie         = (int)($_GET['id'] ?? 0);
        $idEntrenamiento = (int)($_GET['id_entrenamiento'] ?? 0);

        $ent = Entrenamiento::buscarPorId($idEntrenamiento, $idUsuario);
        if (!$ent) {
            header('Location: index.php?controller=entrenamiento&action=index');
            exit;
        }

        $series = Serie::listarPorEntrenamiento($idEntrenamiento, $idUsuario);
        foreach ($series as $s) {
            if ($s->getId() === $idSerie) {
                $s->eliminar();
                break;
            }
        }

        header('Location: index.php?controller=entrenamiento&action=ver&id=' . $idEntrenamiento);
        exit;
    }

    public function eliminarEjercicio() {
        requireLogin();

        $idUsuario = $_SESSION['id_usuario'] ?? null;

        $idEntrenamiento = (int)($_POST['id_entrenamiento'] ?? $_GET['id_entrenamiento'] ?? 0);
        $idEjercicio     = (int)($_POST['id_ejercicio'] ?? $_GET['id_ejercicio'] ?? 0);

        if ($idEntrenamiento <= 0 || $idEjercicio <= 0) {
            header('Location: index.php?controller=entrenamiento&action=index');
            exit;
        }

        Serie::eliminarPorEntrenamientoYEjercicio($idEntrenamiento, $idEjercicio);

        header('Location: index.php?controller=entrenamiento&action=ver&id=' . $idEntrenamiento);
        exit;
    }
}
