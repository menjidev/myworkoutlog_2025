<section class="perfil">
<!-- Mensajes de error -->
    <?php if (!empty($mensaje)): ?>
        <p class="perfil__alerta perfil__alerta--ok">
            <?php echo htmlspecialchars($mensaje); ?>
        </p>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <p class="perfil__alerta perfil__alerta--error">
            <?php echo htmlspecialchars($error); ?>
        </p>
    <?php endif; ?>

    <!-- Formulario principal: datos + foto -->
    <form class="perfil__form"
          action="index.php?controller=perfil&action=actualizar"
          method="post"
          enctype="multipart/form-data">

        <!-- Cabecera con foto -->
        <div class="perfil__cabecera">

            <div class="perfil__avatar-contenedor">
                <?php if (!empty($fotoPerfilWeb)): ?>
                    <img
                        class="perfil__avatar-imagen"
                        src="<?php echo htmlspecialchars($fotoPerfilWeb); ?>"
                        alt="Foto de perfil">
                <?php else: ?>
                    <div class="perfil__avatar-placeholder">
                        <i data-lucide="user"></i>
                    </div>
                <?php endif; ?>
            </div>

            <div class="perfil__avatar-acciones">
                <label class="perfil__avatar-boton">
                    Añadir nueva foto de perfil
                    <input
                        class="perfil__avatar-input"
                        type="file"
                        name="foto_perfil"
                        accept="image/*">
                </label>

                <?php if (!empty($fotoPerfilWeb)): ?>
                    <a class="perfil__avatar-eliminar"
                       href="index.php?controller=perfil&action=eliminarFoto"
                       onclick="return confirm('¿Seguro que quieres eliminar tu foto de perfil?');">
                        Eliminar foto de perfil
                    </a>
                <?php endif; ?>
            </div>
                </div>

        <!-- Bloque: datos personales -->
        <section class="perfil__bloque">
            <h2 class="perfil__titulo-seccion">Editar datos personales</h2>

            <div class="perfil__campo">
                <label class="perfil__label" for="perfil-nombre">
                    Nuevo nombre
                </label>
                <input
                    class="perfil__input"
                    type="text"
                    id="perfil-nombre"
                    name="nombre_usuario"
                    value="<?php echo htmlspecialchars($usuario->getNombreUsuario()); ?>"
                    required>
            </div>

            <div class="perfil__campo">
                <label class="perfil__label" for="perfil-email">
                    Nuevo email
                </label>
                <input
                    class="perfil__input"
                    type="email"
                    id="perfil-email"
                    name="email"
                    value="<?php echo htmlspecialchars($usuario->getEmail()); ?>"
                    required>
            </div>
        </section>

        <!-- Bloque: contraseña -->
        <section class="perfil__bloque">
            <h2 class="perfil__titulo-seccion">Cambiar contraseña</h2>

            <div class="perfil__campo">
                <label class="perfil__label" for="perfil-pass-actual">
                    Contraseña actual
                </label>
                <input
                    class="perfil__input"
                    type="password"
                    id="perfil-pass-actual"
                    name="password_actual"
                    autocomplete="current-password">
            </div>

            <div class="perfil__campo">
                <label class="perfil__label" for="perfil-pass-nueva">
                    Nueva contraseña
                </label>
                <input
                    class="perfil__input"
                    type="password"
                    id="perfil-pass-nueva"
                    name="password_nueva"
                    autocomplete="new-password">
            </div>

            <div class="perfil__campo">
                <label class="perfil__label" for="perfil-pass-nueva2">
                    Repetir nueva contraseña
                </label>
                <input
                    class="perfil__input"
                    type="password"
                    id="perfil-pass-nueva2"
                    name="password_nueva2"
                    autocomplete="new-password">
            </div>
        </section>

        <div class="perfil__acciones perfil__acciones--fila">
    <button type="submit" class="perfil__boton-guardar">
        Confirmar cambios
    </button>
     <div>
    <a href="index.php?controller=auth&action=logout" class="perfil__boton-logout">
        Cerrar sesión
    </a>
    </div>
</div>
    </form>
   
    <!-- Catálogo de ejercicios personales -->
    <section class="perfil-ejercicios">
        <h2 class="perfil__titulo-seccion">Catálogo de ejercicios personales</h2>

        <?php if (empty($ejerciciosPersonales)): ?>
            <p class="perfil-ejercicios__mensaje">
                Todavía no has añadido ejercicios personales.
            </p>
        <?php else: ?>
            <ul class="perfil-ejercicios__lista">
                <?php foreach ($ejerciciosPersonales as $e): ?>
                    <li class="perfil-ejercicios__item">
                        <div class="perfil-ejercicios__texto">
                            <span class="perfil-ejercicios__nombre">
                                <?php echo htmlspecialchars($e->getNombreEjercicio()); ?>
                            </span>
                            <span class="perfil-ejercicios__grupo">
                                <?php echo htmlspecialchars($e->getGrupoMuscular()); ?>
                            </span>
                        </div>

                        <a class="perfil-ejercicios__boton-eliminar"
                           href="index.php?controller=ejercicio&action=eliminarPersonal&id=<?php echo $e->getId(); ?>"
                           onclick="return confirm('¿Seguro que quieres eliminar este ejercicio personal?');">
                            <i data-lucide="minus-circle"></i>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </section>

</section>
