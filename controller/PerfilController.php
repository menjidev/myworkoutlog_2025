<?php

require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/../model/Usuario.php';
require_once __DIR__ . '/../model/Ejercicio.php';

class PerfilController{

    public function index(){

        requireLogin();

        $idUsuario = $_SESSION['id_usuario'];

        $usuario = Usuario::buscarPorId($idUsuario);

        if (!$usuario) {
            header('Location: index.php?controller=auth&action=logout');
            exit;
        }

        $ejerciciosCatalogo = Ejercicio::listarPorUsuario($idUsuario);

        $ejerciciosPersonales = [];
        foreach ($ejerciciosCatalogo as $e) {
            if ((int)$e->getIdUsuario() === (int)$idUsuario) {
                $ejerciciosPersonales[] = $e;
            }
        }

        $mensaje = $_GET['msg']   ?? '';
        $error   = $_GET['error'] ?? '';

        $tituloPagina   = 'Perfil';
        $vistaContenido = __DIR__ . '/../view/app/perfil/index.php';

        require __DIR__ . '/../view/layout.php';
    }

    public function actualizar(){

        requireLogin();

        $idUsuario = $_SESSION['id_usuario'];
        $usuario   = Usuario::buscarPorId($idUsuario);

        if (!$usuario) {
            header('Location: index.php?controller=auth&action=logout');
            exit;
        }

        $nombre = trim($_POST['nombre_usuario'] ?? '');
        $email  = trim($_POST['email'] ?? '');

        $passActual = $_POST['password_actual'] ?? '';
        $passNueva  = $_POST['password_nueva']  ?? '';
        $passNueva2 = $_POST['password_nueva2'] ?? '';

        if ($nombre === '' || $email === '') {
            header('Location: index.php?controller=perfil&action=index&error=Nombre+y+email+son+obligatorios');
            exit;
        }

        $otro = Usuario::buscarPorEmail($email);
        if ($otro && $otro->getId() !== $idUsuario) {
            header('Location: index.php?controller=perfil&action=index&error=Ya+existe+un+usuario+con+ese+email');
            exit;
        }

        $usuario->setNombreUsuario($nombre);
        $usuario->setEmail($email);

        $quiereCambiarPass = ($passActual !== '' || $passNueva !== '' || $passNueva2 !== '');

        if ($quiereCambiarPass) {
            if ($passActual === '' || $passNueva === '' || $passNueva2 === '') {
                header('Location: index.php?controller=perfil&action=index&error=Para+cambiar+la+contrase%C3%B1a+debes+rellenar+todos+los+campos');
                exit;
            }

            if (!$usuario->validarPassword($passActual)) {
                header('Location: index.php?controller=perfil&action=index&error=La+contrase%C3%B1a+actual+no+es+correcta');
                exit;
            }

            if ($passNueva !== $passNueva2) {
                header('Location: index.php?controller=perfil&action=index&error=Las+contrase%C3%B1as+nuevas+no+coinciden');
                exit;
            }

            $usuario->setPasswordPlano($passNueva);
        }

        $okDatos = $usuario->actualizar();

        if (!empty($_FILES['foto_perfil']['name']) && $_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {

            $tmpName = $_FILES['foto_perfil']['tmp_name'];

            $info = getimagesize($tmpName);
            if ($info !== false) {

                $rutaCarpeta = __DIR__ . '/../public/img/perfil/';
                if (!is_dir($rutaCarpeta)) {
                    mkdir($rutaCarpeta, 0777, true);
                }

                $rutaFoto = $rutaCarpeta . $idUsuario . '.jpg';

                if (file_exists($rutaFoto)) {
                    unlink($rutaFoto);
                }

                move_uploaded_file($tmpName, $rutaFoto);
            }
        }

        if ($okDatos) {

            $_SESSION['email'] = $usuario->getEmail();
            header('Location: index.php?controller=perfil&action=index&msg=Perfil+actualizado+correctamente');
        } else {
            header('Location: index.php?controller=perfil&action=index&error=Error+al+actualizar+el+perfil');
        }
        exit;
    }

    public function eliminarFoto(){
        
        requireLogin();

        $idUsuario = $_SESSION['id_usuario'];

        $rutaFoto = __DIR__ . '/../public/img/perfil/' . $idUsuario . '.jpg';

        if (file_exists($rutaFoto)) {
            unlink($rutaFoto);
            $msg = 'Foto de perfil eliminada correctamente.';
        } else {
            $msg = 'No había ninguna foto de perfil que eliminar.';
        }

        header('Location: index.php?controller=perfil&action=index&msg=' . urlencode($msg));
        exit;
    }
}
