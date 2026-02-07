<?php if (!empty($mensaje)): ?>
    <p class="perfil__alerta perfil__alerta--ok">
        <?php echo htmlspecialchars($mensaje); ?>
    </p>
<?php endif; ?>

<form class="formulario-crear-ent"
      action="index.php?controller=admin&action=crearEjercicioGlobal"
      method="post">

    <div class="formulario-crear-ent__label-input">
        <label class="formulario-crear-ent__label">
            Nombre del ejercicio: <br>
            <input
                class="formulario-crear-ent__input"
                type="text"
                name="nombre_ejercicio"
                required
            >
        </label>
    </div>

    <div class="formulario-crear-ent__label-input">
        <label class="formulario-crear-ent__label">
            Grupo muscular: <br>
            <input
                class="formulario-crear-ent__input"
                type="text"
                name="grupo_muscular"
                placeholder="Pecho, Espalda, Pierna..."
                required
            >
        </label>
    </div>

    <div class="formulario-crear-ent__label-input">
        <label class="formulario-crear-ent__label">
            Tipo: <br>
            <input
                class="formulario-crear-ent__input"
                type="text"
                name="tipo"
                placeholder="Fuerza, Cardio..."
                required
            >
        </label>
    </div>

    <button class="formulario-crear-ent__button" type="submit">
        Añadir ejercicio al catálogo
    </button>
</form>

<section class="dashboard-entrenamientos dashboard-entrenamientos--vista-entrenamientos">
    <div class="dashboard-entrenamientos__header">
        <h2 class="dashboard-entrenamientos__titulo">
            Ejercicios globales actuales
        </h2>
    </div>

    <?php
    // Valores por defecto por si el controlador aún no pasa estas variables
    $qBusqueda       = $q        ?? '';
    $grupoSelLocal   = $grupoSel ?? 'todos';
    $gruposLocal     = $grupos   ?? [];
    ?>

    <!-- 🔍 Buscador igual que en ejercicios/index.php -->
    <form class="ejercicios__buscador" action="index.php" method="get">
        <input type="hidden" name="controller" value="admin">
        <input type="hidden" name="action" value="gestionarEjercicios">

        <div class="ejercicios__search-icono-input buscador-gestion-ejercicios">
            <i class="ejercicios__search-icono icono-gestion" data-lucide="search"></i>
            <input
                class="ejercicios__search-input"
                type="search"
                name="q"
                placeholder="Buscar ejercicio"
                value="<?php echo htmlspecialchars($qBusqueda); ?>"
            >
        </div>

        <div class="ejercicios__botones">
            <select
                class="ejercicios__boton ejercicios__boton--turquesa-borde"
                name="grupo"
                onchange="this.form.submit()"
            >
                <option value="todos">Todos los músculos</option>

                <?php foreach ($gruposLocal as $g): ?>
                    <option
                        value="<?php echo htmlspecialchars($g); ?>"
                        <?php if ($grupoSelLocal === $g) echo 'selected'; ?>
                    >
                        <?php echo htmlspecialchars($g); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </form>

    <?php if (!empty($ejercicios)): ?>
        <ul class="dashboard-entrenamientos__lista">
            <?php foreach ($ejercicios as $e): ?>
                <li class="dashboard-entrenamientos__lista__item">
                    <div class="dashboard-entrenamientos__info">
                        <span class="dashboard-entrenamientos__nombre">
                            <?php echo htmlspecialchars($e->getNombreEjercicio()); ?>
                        </span>
                        <span class="dashboard-entrenamientos__fecha">
                            <?php echo htmlspecialchars($e->getGrupoMuscular()); ?>
                            ·
                            <?php echo htmlspecialchars($e->getTipo()); ?>
                        </span>
                    </div>

                    <a
                        class="perfil-ejercicios__boton-eliminar"
                        href="index.php?controller=admin&action=eliminarEjercicioGlobal&id=<?php echo $e->getId(); ?>"
                        onclick="return confirm('¿Seguro que quieres eliminar este ejercicio del catálogo general?');"
                    >
                        <i data-lucide="minus-circle"></i>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p class="dashboard-last__empty">
            No hay ejercicios en el catálogo general.
        </p>
    <?php endif; ?>
</section>
