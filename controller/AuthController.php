<?php

require_once __DIR__ . '/../model/Usuario.php';

class AuthController{

    public function login(){

        $error = $_GET['error'] ?? '';
        require __DIR__ . '/../view/auth/login.php';
    }

    public function procesarLogin(){

        $email    = $_POST['email']    ?? '';
        $password = $_POST['password'] ?? '';

        $usuario = Usuario::buscarPorEmail($email);

        if (!$usuario || !$usuario->validarPassword($password)) {
            header('Location: index.php?controller=auth&action=login&error=Credenciales+inv%C3%A1lidas');
            exit;
        }

        
        if ($usuario->getEstado() !== 'activo') {

            $mensaje = ($usuario->getEstado() === 'pendiente')
                ? 'Tu+cuenta+est%C3%A1+pendiente+de+aprobaci%C3%B3n+por+un+administrador'
                : 'Tu+cuenta+no+est%C3%A1+activa';

            header('Location: index.php?controller=auth&action=login&error=' . $mensaje);
            exit;
        }

        $_SESSION['id_usuario']     = $usuario->getId();
        $_SESSION['email']          = $usuario->getEmail();
        $_SESSION['rol']            = $usuario->getRol();
        $_SESSION['nombre_usuario'] = $usuario->getNombreUsuario();

        // Redirigir según rol
        if ($usuario->getRol() === 'admin') {
            header('Location: index.php?controller=admin&action=gestionarUsuarios');
        } else {
            header('Location: index.php?controller=dashboard&action=index');
        }

        exit;
    }

    public function logout(){

        session_unset();   
        session_destroy(); 

        header('Location: index.php?controller=auth&action=login');
        exit;
    }

    public function registro(){

        $error = $_GET['error'] ?? '';
        $ok    = $_GET['ok']    ?? '';

        require __DIR__ . '/../view/auth/registro.php';
    }

    public function procesarRegistro(){

        $nombre = $_POST['nombre_usuario'] ?? '';
        $email  = $_POST['email']          ?? '';
        $pass1  = $_POST['password']       ?? '';
        $pass2  = $_POST['password2']      ?? '';

        
        if (
            trim($nombre) === '' ||
            trim($email)  === '' ||
            trim($pass1)  === '' ||
            trim($pass2)  === ''
        ) {
            header('Location: index.php?controller=auth&action=registro&error=Todos+los+campos+son+obligatorios');
            exit;
        }

        if ($pass1 !== $pass2) {
            header('Location: index.php?controller=auth&action=registro&error=Las+contrase%C3%B1as+no+coinciden');
            exit;
        }

        $existe = Usuario::buscarPorEmail($email);
        if ($existe) {
            header('Location: index.php?controller=auth&action=registro&error=Ya+existe+un+usuario+con+ese+email');
            exit;
        }

        $u = new Usuario();
        $u->setNombreUsuario($nombre);
        $u->setEmail($email);
        $u->setPasswordPlano($pass1);
        $u->setRol('usuario');      
        $u->setEstado('pendiente'); 

        if ($u->crear()) {
            header('Location: index.php?controller=auth&action=login&error=Registro+completado.+Pendiente+de+activaci%C3%B3n');
        } else {
            header('Location: index.php?controller=auth&action=registro&error=Error+al+crear+el+usuario');
        }

        exit;
    }
}
