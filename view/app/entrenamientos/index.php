    <div class="contenedor-entrenamientos__icono-texto">
        <div class="contenedor-entrenamientos__contenedor-icono">
            <i class="contenedor-entrenamientos__icono" data-lucide="dumbbell"></i>
        </div>
        <div class="contenedor-entrenamientos__contenedor-texto">
            <p class="contenedor-entrenamientos__texto">Empezar:</p>
            <p class="contenedor-entrenamientos__texto">
                Agrega un ejercicio para empezar el entrenamiento
            </p>
        </div>
    </div>

    <form class="formulario-crear-ent"
         action="index.php?controller=entrenamiento&action=crearYElegirEjercicio"
          method="post">

        <!-- Fecha de hoy en campo oculto -->
        <input type="hidden" name="fecha" value="<?php echo date('Y-m-d'); ?>">

        <div class="formulario-crear-ent__label-input">
            <label class="formulario-crear-ent__label">
                Nombre del entrenamiento: <br>
                <input class="formulario-crear-ent__input"
                       type="text"
                       name="nombre_entrenamiento"
                       required>
            </label>
        </div>

        <div class="formulario-crear-ent__label-input">
            <label class="formulario-crear-ent__label formulario-crear-ent__label--textarea">
                Notas: <br>
                <textarea class="formulario-crear-ent__input formulario-crear-ent__input--textarea"
                          name="notas"
                          maxlength="35"
                          rows="4"></textarea>
            </label>
        </div>
        
        <button class="formulario-crear-ent__button" type="submit">
            Añadir ejercicio
        </button>
    </form>
    <section class="dashboard-entrenamientos dashboard-entrenamientos--vista-entrenamientos">
    <div class="dashboard-entrenamientos__header">
        <h2 class="dashboard-entrenamientos__titulo">
            Últimos entrenamientos
        </h2>
    </div>

    <?php if (!empty($ultimosEntrenamientos)): ?>
        <ul class="dashboard-entrenamientos__lista">
            <?php foreach ($ultimosEntrenamientos as $ent): ?>
                <?php
                // Asumiendo que en BBDD la fecha es Y-m-d
                $fechaObj = DateTime::createFromFormat('Y-m-d', $ent->getFecha());
                $fechaFormateada = $fechaObj
                    ? $fechaObj->format('d/m/Y')
                    : htmlspecialchars($ent->getFecha());
                ?>
                <li class="dashboard-entrenamientos__lista__item">
                    <div class="dashboard-entrenamientos__info">
                        <span class="dashboard-entrenamientos__nombre">
                            <?= htmlspecialchars($ent->getNombreEntrenamiento()); ?>
                        </span>
                        <span class="dashboard-entrenamientos__fecha">
                            <?= $fechaFormateada; ?>
                        </span>
                    </div>
                    <a
                        class="dashboard-entrenamientos__button"
                        href="index.php?controller=entrenamiento&action=ver&id=<?= $ent->getId(); ?>"
                    >
                        Detalles
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p class="dashboard-last__empty">
            Aún no has registrado entrenamientos. Empieza desde el apartado <strong>Entrenar</strong>.
        </p>
    <?php endif; ?>
</section>
