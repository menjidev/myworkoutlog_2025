<?php

require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/../model/Usuario.php';
require_once __DIR__ . '/../model/Ejercicio.php';

class AdminController{

    private function requireAdmin(): void{

        requireLogin();

        if (!esAdmin()) {
            header('Location: index.php?controller=dashboard&action=index');
            exit;
        }
    }

    public function gestionarUsuarios(){

        $this->requireAdmin();

        $usuarios = Usuario::listarTodos();
        $mensaje  = $_GET['msg'] ?? '';

        $tituloPagina   = 'Gestionar usuarios';
        $vistaContenido = __DIR__ . '/../view/admin/gestionarUsuarios.php';

        require __DIR__ . '/../view/layout.php';
    }

    public function activar(){

        $this->requireAdmin();

        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        $usuario = Usuario::buscarPorId($id);
        if ($usuario) {
            $usuario->setEstado('activo');
            $usuario->actualizar();
            $msg = 'Usuario activado correctamente';
        } else {
            $msg = 'Usuario no encontrado';
        }

        header('Location: index.php?controller=admin&action=gestionarUsuarios&msg=' . urlencode($msg));
        exit;
    }

    public function eliminarUsuario(){

        $this->requireAdmin();

        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        if ($id === (int)$_SESSION['id_usuario']) {
            $msg = 'No puedes eliminar tu propio usuario.';
            header('Location: index.php?controller=admin&action=gestionarUsuarios&msg=' . urlencode($msg));
            exit;
        }

        $usuario = Usuario::buscarPorId($id);
        if ($usuario) {
            $usuario->eliminar();
            $msg = 'Usuario eliminado correctamente.';
        } else {
            $msg = 'Usuario no encontrado.';
        }

        header('Location: index.php?controller=admin&action=gestionarUsuarios&msg=' . urlencode($msg));
        exit;
    }

    public function actualizarUsuario(){

        $this->requireAdmin();

        $id     = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        $rol    = $_POST['rol']    ?? '';
        $estado = $_POST['estado'] ?? '';

        $usuario = Usuario::buscarPorId($id);

        if (!$usuario) {
            $msg = 'Usuario no encontrado.';
            header('Location: index.php?controller=admin&action=gestionarUsuarios&msg=' . urlencode($msg));
            exit;
        }

        if ($id === (int)$_SESSION['id_usuario'] && $usuario->getRol() !== $rol) {
            $msg = 'No puedes cambiar tu propio rol.';
            header('Location: index.php?controller=admin&action=gestionarUsuarios&msg=' . urlencode($msg));
            exit;
        }

        $usuario->setRol($rol);
        $usuario->setEstado($estado);

        if ($usuario->actualizar()) {
            $msg = 'Usuario actualizado correctamente.';
        } else {
            $msg = 'Error al actualizar el usuario.';
        }

        header('Location: index.php?controller=admin&action=gestionarUsuarios&msg=' . urlencode($msg));
        exit;
    }

   public function gestionarEjercicios(){

            $this->requireAdmin();

            $idAdmin   = (int)$_SESSION['id_usuario'];
            $q         = trim($_GET['q'] ?? '');
            $grupoSel  = $_GET['grupo'] ?? 'todos';

            $nombreFiltro = $q !== '' ? $q : null;
            $grupoFiltro  = ($grupoSel !== 'todos') ? $grupoSel : null;

            $ejercicios = Ejercicio::listarCatalogoFiltrado(
                $idAdmin,
                $nombreFiltro,
                $grupoFiltro
            );

            $grupos  = Ejercicio::listarGruposMuscularesCatalogo($idAdmin);
            $mensaje = $_GET['msg'] ?? '';

            $tituloPagina   = 'Gestionar ejercicios';
            $vistaContenido = __DIR__ . '/../view/admin/gestionarEjercicios.php';

            require __DIR__ . '/../view/layout.php';
        }


    public function crearEjercicioGlobal(){

        $this->requireAdmin();

        $nombre = normalizarTextoFormulario($_POST['nombre_ejercicio'] ?? '');
        $grupo  = normalizarTextoFormulario($_POST['grupo_muscular'] ?? '');
        $tipo   = normalizarTextoFormulario($_POST['tipo'] ?? '');

        if ($nombre === '' || $grupo === '' || $tipo === '') {
            $msg = 'Todos los campos son obligatorios.';
            header('Location: index.php?controller=admin&action=gestionarEjercicios&msg=' . urlencode($msg));
            exit;
        }

        $ej = new Ejercicio(
            null,
            null,
            $nombre,
            $grupo,
            $tipo
        );

        if ($ej->crear()) {
            $msg = 'Ejercicio global creado correctamente.';
        } else {
            $msg = 'Error al crear el ejercicio.';
        }

        header('Location: index.php?controller=admin&action=gestionarEjercicios&msg=' . urlencode($msg));
        exit;
    }

    public function eliminarEjercicioGlobal(){
        
        $this->requireAdmin();

        $id      = (int)($_GET['id'] ?? 0);
        $idAdmin = (int)$_SESSION['id_usuario'];

        $ejercicio = Ejercicio::buscarPorId($id, $idAdmin);

        if (!$ejercicio) {
            $msg = 'Ejercicio no encontrado.';
        } elseif ($ejercicio->getIdUsuario() !== null) {
            $msg = 'Solo se pueden eliminar ejercicios del catálogo general.';
        } else {
            $ejercicio->eliminar($idAdmin, true);
            $msg = 'Ejercicio eliminado correctamente.';
        }

        header('Location: index.php?controller=admin&action=gestionarEjercicios&msg=' . urlencode($msg));
        exit;
    }
}
