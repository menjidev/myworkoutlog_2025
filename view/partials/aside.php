<aside class="sidebar">
    <div class="sidebar__inner">

        <?php if (!empty($fotoPerfilWeb)): ?>
            <div class="sidebar__avatar">
                <img class="avatar" src="<?php echo htmlspecialchars($fotoPerfilWeb); ?>" alt="Foto de perfil">
            </div>
        <?php endif; ?>

        <!-- Navegación principal -->
        <nav class="sidebar__nav">
            <?php if (esAdmin()): ?>
                <a href="index.php?controller=admin&action=gestionarUsuarios" class="sidebar__link">
                    Gestionar usuarios
                </a>
                <a href="index.php?controller=admin&action=gestionarEjercicios" class="sidebar__link">
                    Gestionar ejercicios
                </a>
                <a href="index.php?controller=auth&action=logout" class="sidebar__link">
                    Cerrar sesión
                </a>
            <?php else: ?>
                <a href="index.php?controller=dashboard&action=index" class="sidebar__link">
                    Inicio
                </a>
                <a href="index.php?controller=entrenamiento&action=index" class="sidebar__link">
                    Entrenamiento
                </a>
                <a href="index.php?controller=metrics&action=index" class="sidebar__link">
                    Métricas
                </a>
                <a href="index.php?controller=perfil&action=index" class="sidebar__link">
                    Perfil
                </a>
            <?php endif; ?>
        </nav>

        <!-- Pie del sidebar -->
        <div class="sidebar__footer">
            <div class="sidebar__logo">
                <img src="public/img/MyWorkoutLog_H_Dark.svg" alt="MyWorkoutLog">
            </div>
        </div>

    </div>
</aside>
