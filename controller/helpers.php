<?php

function requireLogin(): void{

    if (empty($_SESSION['id_usuario'])) {
        header('Location: index.php?controller=auth&action=login');
        exit;
    }
}

function esAdmin(): bool{

    return isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin';
}

function obtenerFotoPerfilWeb(): ?string{

    if (empty($_SESSION['id_usuario'])) {
        return null;
    }

    $idUsuario = (int) $_SESSION['id_usuario'];
    $ruta      = "public/img/perfil/{$idUsuario}.jpg";

    if (file_exists($ruta)) {
        return $ruta;
    }

    return null;
}

function normalizarTextoFormulario(?string $valor): string{

    if ($valor === null) {
        return '';
    }

    $valor = trim($valor);
    if ($valor === '') {
        return '';
    }

    $valor = mb_strtolower($valor, 'UTF-8');
    return mb_convert_case($valor, MB_CASE_TITLE, 'UTF-8');
}
