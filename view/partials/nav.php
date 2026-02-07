
<?php if (esAdmin()): ?>
    <nav class="navbar">
        <a href="index.php?controller=admin&action=gestionarUsuarios">
            <div class="navbar__contenedor">
                <div class="navbar__contenedor-icono">
                    <i data-lucide="user"></i>
                </div>
                <div class="navbar__contenedor-texto">
                    <p>Ges. Usuarios</p>
                </div>
            </div>
        </a>
        <a href="index.php?controller=admin&action=gestionarEjercicios">
            <div class="navbar__contenedor">
                <div class="navbar__contenedor-icono">
                    <i data-lucide="dumbbell"></i>
                </div>
                <div class="navbar__contenedor-texto">
                    <p>Ges. Ejercicios</p>
                </div>
            </div>
        </a>
        <a href="index.php?controller=auth&action=logout">
            <div class="navbar__contenedor">
                <div class="navbar__contenedor-icono">
                    <i data-lucide="cog"></i>
                </div>
                <div class="navbar__contenedor-texto">
                    <p>Cerrar sesión</p>
                </div>
            </div>
        </a>
    </nav>

<?php else: ?>
    <nav class="navbar">
        <a href="index.php?controller=dashboard&action=index">
            <div class="navbar__contenedor">
                <div class="navbar__contenedor-icono">
                    <i data-lucide="house"></i>
                </div>
                <div class="navbar__contenedor-texto">
                    <p>Inicio</p>
                </div>
            </div>
        </a>

        <a href="index.php?controller=entrenamiento&action=index">
            <div class="navbar__contenedor">
                <div class="navbar__contenedor-icono">
                    <i data-lucide="dumbbell"></i>
                </div>
                <div class="navbar__contenedor-texto">
                    <p>Entrenar</p>
                </div>
            </div>
        </a>

        <a href="index.php?controller=metrics&action=index">
            <div class="navbar__contenedor">
                <div class="navbar__contenedor-icono">
                    <i data-lucide="chart-no-axes-column-increasing"></i>
                </div>
                <div class="navbar__contenedor-texto">
                    <p>Métricas</p>
                </div>
            </div>
        </a>

        <a href="index.php?controller=perfil&action=index">
            <div class="navbar__contenedor">
                <div class="navbar__contenedor-icono">
                    <i data-lucide="user"></i>
                </div>
                <div class="navbar__contenedor-texto">
                    <p>Perfil</p>
                </div>
            </div>
        </a>
    </nav>
<?php endif; ?>
