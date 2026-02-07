<div class="modal-ejercicio" id="modal-nuevo-ejercicio" aria-hidden="true" role="dialog" aria-modal="true">
  <div class="modal-ejercicio__overlay" data-modal-close></div>

  <div class="modal-ejercicio__contenido">
        <section class="nuevo-ejercicio">
            <div class="nuevo-ejercicio__card">

                <div class="nuevo-ejercicio__cabecera">
                    <h1 class="nuevo-ejercicio__titulo">
                        Nuevo ejercicio
                    </h1>
                        </div>

                <?php if (!empty($error)): ?>
                    <p class="nuevo-ejercicio__alerta nuevo-ejercicio__alerta--error">
                        <?= htmlspecialchars($error) ?>
                    </p>
                <?php endif; ?>

                <form class="nuevo-ejercicio__form"
                    action="index.php?controller=ejercicio&action=crear"
                    method="post">

                     <input type="hidden" name="id_entrenamiento"
                        id="nuevo-ejercicio-id-entrenamiento"
                        value="<?= !empty($idEntrenamiento) ? (int)$idEntrenamiento : '' ?>">

                    <div class="nuevo-ejercicio__campo">
                        <label class="nuevo-ejercicio__label" for="nombre_ejercicio">
                            Nombre del ejercicio
                        </label>
                        <input
                            id="nombre_ejercicio"
                            type="text"
                            name="nombre_ejercicio"
                            class="nuevo-ejercicio__input"
                            required
                        >
                    </div>

                    <div class="nuevo-ejercicio__campo">
                        <label class="nuevo-ejercicio__label" for="grupo_muscular">
                            Grupo muscular
                        </label>

                        <select
                            id="grupo_muscular"
                            name="grupo_muscular"
                            class="nuevo-ejercicio__input nuevo-ejercicio__select"
                            required
                        >
                            <option class="option__disabled" value="" disabled selected>Selecciona un grupo muscular</option>

                            <?php foreach ($grupos as $grupo): ?>
                                <option value="<?= htmlspecialchars($grupo) ?>">
                                    <?= htmlspecialchars($grupo) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="nuevo-ejercicio__campo">
                        <label class="nuevo-ejercicio__label" for="tipo">
                            Tipo
                            <span class="nuevo-ejercicio__label-ayuda">(Fuerza, Cardio, Movilidad...)</span>
                        </label>
                        <input
                            id="tipo"
                            type="text"
                            name="tipo"
                            class="nuevo-ejercicio__input"
                            required
                        >
                    </div>

                    <div class="nuevo-ejercicio__acciones">
                        <button type="submit" class="nuevo-ejercicio__boton nuevo-ejercicio__boton--primario">
                            Guardar ejercicio
                        </button>

                       <button type="button"
                        id="btn-cancelar-nuevo-ejercicio"
                        class="nuevo-ejercicio__boton nuevo-ejercicio__boton--secundario" data-modal-close>
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </section>
  </div>
</div>

<section class="ejercicios">
    <div class="contenedor__volver-atras contenedor__volver-atras--ejercicios">
        <a class="contenedor__volver-atras__link" href="index.php?controller=entrenamiento&action=ver&id=<?php echo $idEntrenamiento?>"><i data-lucide="arrow-left"></i>Volver atrás</a>
    </div>
    <form class="ejercicios__buscador" action="index.php" method="get">
        <input type="hidden" name="controller" value="ejercicio">
        <input type="hidden" name="action" value="index">
        <?php if ($idEntrenamiento): ?>
            <input type="hidden" name="id_entrenamiento" value="<?php echo $idEntrenamiento; ?>">
        <?php endif; ?>

        <div class="ejercicios__search-icono-input">
            <i class="ejercicios__search-icono" data-lucide="search"></i>
            <input
                class="ejercicios__search-input"
                type="search"
                name="q"
                placeholder="Buscar ejercicio"
                value="<?php echo htmlspecialchars($q); ?>">
        </div>

        <div class="ejercicios__botones">
           <a
                href="#"
                class="ejercicios__boton ejercicios__boton--turquesa"
                data-modal-target="#modal-nuevo-ejercicio"
                <?php if (!empty($idEntrenamiento)): ?>
                    data-id-entrenamiento="<?= (int)$idEntrenamiento ?>"
                <?php endif; ?>
            >
                Nuevo ejercicio
            </a>

            <select
                class="ejercicios__boton ejercicios__boton--turquesa-borde"
                name="grupo"
                onchange="this.form.submit()"
            >
                <option value="todos">Todos los músculos</option>
                <?php foreach ($grupos as $g): ?>
                    <option
                        value="<?php echo htmlspecialchars($g); ?>"
                        <?php if ($grupoSel === $g) echo 'selected'; ?>
                    >
                        <?php echo htmlspecialchars($g); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </form>
    <ul class="ejercicios__lista">
            <?php foreach ($ejercicios as $e): ?>
                <li class="ejercicios__item">
                    <div class="ejercicios__info">
                        <span class="ejercicios__nombre">
                            <?php echo htmlspecialchars($e->getNombreEjercicio()); ?>
                        </span>
                        <span class="ejercicios__grupo">
                            - <?php echo htmlspecialchars($e->getGrupoMuscular()); ?>
                        </span>
                    </div>

                    <div class="ejercicios__item-actions">
                            <!-- Al pulsar + vamos a ver el entrenamiento con ese ejercicio seleccionado -->
                            <a
                                class="ejercicios__boton--add"
                                href="index.php?controller=entrenamiento&action=ver&id=<?php echo $idEntrenamiento; ?>&id_ejercicio=<?php echo $e->getId(); ?>"
                            >
                                <i data-lucide="circle-plus"></i>
                            </a>
                    </div>
                </li>
            <?php endforeach; ?>
    </ul>
</section>
