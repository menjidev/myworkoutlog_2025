<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($tituloPagina); ?></title>
    <link rel="stylesheet" href="public/css/css.css">
</head>
<body class="layout-app">
<?php
$fotoPerfilWeb = obtenerFotoPerfilWeb();
?>
<header class="header">
    <div class="header-logo">
        <img src="public/img/MyWorkoutLog_H_Light.svg" alt="MyWorkoutLog">
    </div>

    <?php if (!empty($fotoPerfilWeb)): ?>
        <div class="header-usuario">
            <img class="avatar" src="<?php echo htmlspecialchars($fotoPerfilWeb); ?>" alt="Foto de perfil">
        </div>
    <?php endif; ?>
</header>

