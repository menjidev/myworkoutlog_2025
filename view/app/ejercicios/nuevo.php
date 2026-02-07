<section class="nuevo-ejercicio">
    <div class="nuevo-ejercicio__card">

        <div class="nuevo-ejercicio__cabecera">
            <h1 class="nuevo-ejercicio__titulo">
                <?= htmlspecialchars($tituloPagina) ?>
            </h1>

            <a class="nuevo-ejercicio__volver"
               href="index.php?controller=ejercicio&action=index<?php
                    if (!empty($idEntrenamiento)) {
                        echo '&id_entrenamiento=' . (int)$idEntrenamiento;
                    }
               ?>">
                 Volver al catálogo
            </a>
                </div>

        <?php if (!empty($error)): ?>
            <p class="nuevo-ejercicio__alerta nuevo-ejercicio__alerta--error">
                <?= htmlspecialchars($error) ?>
            </p>
        <?php endif; ?>

        <form class="nuevo-ejercicio__form"
              action="index.php?controller=ejercicio&action=crear"
              method="post">

            <?php if (!empty($idEntrenamiento)): ?>
                <input type="hidden" name="id_entrenamiento"
                       value="<?= (int)$idEntrenamiento ?>">
            <?php endif; ?>

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

                <a href="index.php?controller=ejercicio&action=index<?php
                        if (!empty($idEntrenamiento)) {
                            echo '&id_entrenamiento=' . (int)$idEntrenamiento;
                        }
                   ?>"
                   class="nuevo-ejercicio__boton nuevo-ejercicio__boton--secundario">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</section>