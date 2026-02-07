<?php

require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/../model/Ejercicio.php';

class EjercicioController{

    public function index(){

        requireLogin();

        $idUsuario = $_SESSION['id_usuario'];

        $q               = trim($_GET['q'] ?? '');
        $grupoSel        = $_GET['grupo'] ?? 'todos';
        $idEntrenamiento = isset($_GET['id_entrenamiento'])
            ? (int) $_GET['id_entrenamiento']
            : null;

        $nombreFiltro = $q !== '' ? $q : null;
        $grupoFiltro  = ($grupoSel !== 'todos') ? $grupoSel : null;

        $ejercicios = Ejercicio::listarCatalogoFiltrado(
            $idUsuario,
            $nombreFiltro,
            $grupoFiltro
        );

        $grupos = Ejercicio::listarGruposMuscularesCatalogo($idUsuario);

        $tituloPagina   = 'Catálogo de ejercicios';
        $vistaContenido = __DIR__ . '/../view/app/ejercicios/index.php';

        require __DIR__ . '/../view/layout.php';
    }

    
    public function crear(){
        
        requireLogin();

        $idUsuario       = $_SESSION['id_usuario'];
        $idEntrenamiento = isset($_POST['id_entrenamiento'])
            ? (int)$_POST['id_entrenamiento']
            : null;

        $nombre = normalizarTextoFormulario($_POST['nombre_ejercicio'] ?? '');
        $grupo  = $_POST['grupo_muscular'] ?? '';
        $tipo   = normalizarTextoFormulario($_POST['tipo'] ?? '');

        if ($nombre === '' || $grupo === '' || $tipo === '') {
            
            $url = 'index.php?controller=ejercicio&action=index&error=Todos+los+campos+son+obligatorios';
            if ($idEntrenamiento) {
                $url .= '&id_entrenamiento=' . $idEntrenamiento;
            }
            header('Location: ' . $url);
            exit;
        }

        $gruposPermitidos = Ejercicio::listarGruposMuscularesCatalogo($idUsuario);
        if (!in_array($grupo, $gruposPermitidos, true)) {
            $url = 'index.php?controller=ejercicio&action=index&error=Grupo+muscular+inv%C3%A1lido';
            if ($idEntrenamiento) {
                $url .= '&id_entrenamiento=' . $idEntrenamiento;
            }
            header('Location: ' . $url);
            exit;
        }

        $ej = new Ejercicio(
            null,
            $idUsuario,
            $nombre,
            $grupo,
            $tipo
        );

        if ($ej->crear()) {
            if ($idEntrenamiento) {
                header(
                    'Location: index.php?controller=entrenamiento&action=ver'
                    . '&id=' . $idEntrenamiento
                    . '&id_ejercicio=' . $ej->getId()
                );
            } else {
                header('Location: index.php?controller=ejercicio&action=index&msg=Ejercicio+creado+correctamente');
            }
        } else {
            $url = 'index.php?controller=ejercicio&action=index&error=Error+al+crear+el+ejercicio';
            if ($idEntrenamiento) {
                $url .= '&id_entrenamiento=' . $idEntrenamiento;
            }
            header('Location: ' . $url);
        }

        exit;
        }

    public function eliminarPersonal(){

        requireLogin();

        $idUsuario = $_SESSION['id_usuario'];
        $id        = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        $ejercicio = Ejercicio::buscarPorId($id, $idUsuario);

        if (!$ejercicio || $ejercicio->getIdUsuario() !== $idUsuario) {
            $msg = 'Ejercicio no encontrado o no pertenece al usuario.';
            header('Location: index.php?controller=perfil&action=index&error=' . urlencode($msg));
            exit;
        }

        try {
            $ok = $ejercicio->eliminar($idUsuario, false);

            if ($ok) {
                $msg = 'Ejercicio personal eliminado correctamente.';
                header('Location: index.php?controller=perfil&action=index&msg=' . urlencode($msg));
            } else {
                $msg = 'No se ha podido eliminar el ejercicio.';
                header('Location: index.php?controller=perfil&action=index&error=' . urlencode($msg));
            }
        } catch (Throwable $e) {
            $msg = 'No se puede eliminar el ejercicio porque tiene series asociadas.';
            header('Location: index.php?controller=perfil&action=index&error=' . urlencode($msg));
        }

        exit;
    }
}
