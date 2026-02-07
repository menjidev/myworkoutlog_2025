<section class="entrenamiento">
    <div class="entrenamiento__cabecera">
        <form action="index.php?controller=entrenamiento&action=actualizar" class="entrenamiento__cabecera-form" method="post">
            <input type="hidden" name="id" value="<?php echo $entrenamiento->getId(); ?>">
            <input type="hidden" name="fecha" value="<?php echo htmlspecialchars($entrenamiento->getFecha()); ?>">

            <h2 class="entrenamiento__titulo">
                <input
                    class="entrenamiento__titulo-input"
                    type="text"
                    name="nombre_entrenamiento"
                    value="<?php echo htmlspecialchars($entrenamiento->getNombreEntrenamiento()); ?>"
                    required
                >
            </h2>

            <p class="entrenamiento__fecha">
                <?php
                $fechaObj = new DateTime($entrenamiento->getFecha());
                echo $fechaObj->format('d-m-Y');
                ?>
            </p>

            <div class="entrenamiento__notas-wrapper">
                <textarea
                    class="entrenamiento__notas-input"
                    name="notas"
                    rows="1"
                    maxlength="35"
                    placeholder="Añadir notas del entrenamiento..."
                ><?php echo htmlspecialchars($entrenamiento->getNotas() ?? ''); ?></textarea>

                <button type="submit" class="entrenamiento__cabecera-guardar">
                    Guardar
                </button>
            </div>
        </form>
    </div>

    <div class="contenedor__volver-atras">
        <a class="contenedor__volver-atras__link" href="index.php?controller=entrenamiento&action=index">
            <i data-lucide="arrow-left"></i>Volver atrás
        </a>
    </div>

    <!-- Tarjetas por ejercicio -->
    <?php if (empty($ejerciciosEnEntrenamiento)): ?>
        <p class="entrenamiento__sin-ejercicios">
            Todavía no hay ejercicios en este entrenamiento. Usa el botón
            <strong>Añadir ejercicio</strong> para empezar.
        </p>
    <?php else: ?>
        <?php foreach ($ejerciciosEnEntrenamiento as $idEjer): ?>
            <?php
            if (!isset($ejerciciosPorId[$idEjer])) {
                // Ejercicio borrado o no accesible para el usuario
                continue;
            }

            $ejer         = $ejerciciosPorId[$idEjer];
            $seriesEj     = $seriesPorEjercicio[$idEjer] ?? [];
            $siguienteNum = count($seriesEj) + 1;
            $ultimaSerie  = !empty($seriesEj) ? end($seriesEj) : null;
            if (!empty($seriesEj)) {
                reset($seriesEj);
            }

            // Recomendaciones del laboratorio
            $recoInicial   = $recomendacionesIniciales[$idEjer]  ?? null;
            $recoSiguiente = $recomendacionesSiguientes[$idEjer] ?? null;

            // Peso sugerido para el formulario de la nueva serie
            $pesoSugerido = '';
            if (empty($seriesEj) && $recoInicial) {
                // Primera serie del día para este ejercicio
                $pesoSugerido = $recoInicial['peso_recomendado'];
            } elseif (!empty($seriesEj) && $recoSiguiente) {
                // Ya hay series hoy → sugerimos para la siguiente
                $pesoSugerido = $recoSiguiente['peso_recomendado'];
            }
            ?>
            <section class="tarjeta-ejercicio">
                <h3 class="tarjeta-ejercicio__titulo">
                    <?php echo htmlspecialchars($ejer->getNombreEjercicio()); ?>
                </h3>

                <form class="tarjeta-ejercicio__form-series"
                      action="index.php?controller=serie&action=crear"
                      method="post">
                    <input type="hidden" name="id_entrenamiento" value="<?php echo $entrenamiento->getId(); ?>">
                    <input type="hidden" name="id_ejercicio" value="<?php echo $idEjer; ?>">
                    <input type="hidden" name="num_serie" value="<?php echo $siguienteNum; ?>">
                    <input type="hidden" name="notas" value="">

                    <table class="tabla-series">
                        <thead class="tabla-series__encabezado">
                        <tr class="tabla-series__fila tabla-series__fila--encabezado">
                            <th class="tabla-series__celda">Serie</th>
                            <th class="tabla-series__celda">Reps</th>
                            <th class="tabla-series__celda">Kilos</th>
                            <th class="tabla-series__celda">Descanso</th>
                            <th class="tabla-series__celda">Notas</th>
                        </tr>
                        </thead>
                        <tbody class="tabla-series__cuerpo">
                        <?php if (!empty($seriesEj)): ?>
                            <?php foreach ($seriesEj as $s): ?>
                                <tr class="tabla-series__fila">
                                    <td class="tabla-series__celda">
                                        <?php echo htmlspecialchars($s->getNumSerie()); ?>
                                    </td>
                                    <td class="tabla-series__celda">
                                        <?php echo htmlspecialchars($s->getRepeticiones()); ?>
                                    </td>
                                    <td class="tabla-series__celda">
                                        <?php echo htmlspecialchars($s->getPesoKg()); ?>
                                    </td>
                                    <td class="tabla-series__celda">
                                        <?php
                                        $desc = $s->getDescansoSeg();
                                        echo $desc !== null ? htmlspecialchars($desc) . ' s' : '-';
                                        ?>
                                    </td>
                                    <td class="tabla-series__celda tabla-series__celda--link">
                                        <?php
                                        $notaSerie   = trim((string)($s->getNotas() ?? ''));
                                        $tieneNotas  = $notaSerie !== '';
                                        ?>
                                        <?php if ($tieneNotas): ?>
                                            <button
                                                type="button"
                                                class="tabla-series__link-boton"
                                                data-modal-target="#modal-ver-notas-serie"
                                                data-nota-serie="<?php echo htmlspecialchars($notaSerie, ENT_QUOTES, 'UTF-8'); ?>"
                                            >
                                                Ver más
                                            </button>
                                        <?php else: ?>
                                            <!-- Sin notas -->
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>

                        <!-- Fila para nueva serie (Serie 1, luego 2, etc.) -->
                        <tr class="tabla-series__fila tabla-series__fila--nueva">
                            <td class="tabla-series__celda">
                                <?php echo $siguienteNum; ?>
                            </td>
                            <td class="tabla-series__celda">
                                <input
                                    class="tabla-series__input"
                                    type="number"
                                    placeholder="Añadir"
                                    name="repeticiones"
                                    min="1"
                                    value=""
                                    required
                                >
                            </td>
                            <td class="tabla-series__celda">
                                <input
                                    class="tabla-series__input"
                                    type="number"
                                    placeholder="Añadir"
                                    name="peso_kg"
                                    step="0.5"
                                    min="0"
                                    value="<?php echo $pesoSugerido !== '' ? htmlspecialchars($pesoSugerido, ENT_QUOTES, 'UTF-8') : ''; ?>"
                                    required
                                >
                            </td>
                            <td class="tabla-series__celda">
                                <select class="tabla-series__select" name="descanso_seg">
                                    <option value="">Sin descanso</option>
                                    <?php for ($seg = 5; $seg <= 180; $seg += 5): ?>
                                        <option value="<?php echo $seg; ?>"
                                            <?php echo $seg === 60 ? 'selected' : ''; ?>>
                                            <?php echo $seg; ?> s
                                        </option>
                                    <?php endfor; ?>
                                </select>
                            </td>
                            <td class="tabla-series__celda tabla-series__celda--link centrar__boton">
                                <!-- Botón que abrirá el modal de notas para la nueva serie -->
                                <button
                                    type="button"
                                    class="boton-añadir-serie boton--primario"
                                >
                                    Añadir
                                </button>
                            </td>
                        </tr>
                        </tbody>
                    </table>

                    <!-- 🔬 Laboratorio MyWorkoutLog: debajo de la tabla -->
                    <?php if (empty($seriesEj) && $recoInicial): ?>
                        <?php
                        $detalles = array_map(
                            fn($sDet) => $sDet['reps'] . 'x' . $sDet['peso'] . 'kg',
                            $recoInicial['detalle_series']
                        );
                        $textoSeries = implode(', ', $detalles);
                        ?>
                        <p class="tarjeta-ejercicio__recomendacion">
                            <strong>Recomendación de MyWorkoutLog:</strong>
                            En el entrenamiento anterior hiciste
                            <?php echo count($recoInicial['detalle_series']); ?> series
                            (<?php echo htmlspecialchars($textoSeries, ENT_QUOTES, 'UTF-8'); ?>).
                            Para esta primera serie de hoy,
                            <?php if ($recoInicial['decision'] === 'subir'): ?>
                                intenta <strong><?php echo $recoInicial['peso_recomendado']; ?> kg</strong>.
                            <?php elseif ($recoInicial['decision'] === 'bajar'): ?>
                                prueba bajando a <strong><?php echo $recoInicial['peso_recomendado']; ?> kg</strong>.
                            <?php else: ?>
                                mantén <strong><?php echo $recoInicial['peso_base']; ?> kg</strong>.
                            <?php endif; ?>
                        </p>

                    <?php elseif (!empty($seriesEj) && $recoSiguiente): ?>
                        <p class="tarjeta-ejercicio__recomendacion">
                            <strong>Recomendación de MyWorkoutLog:</strong>
                            En la última serie hiciste
                            <?php echo $recoSiguiente['reps_actuales']; ?> reps con
                            <?php echo $recoSiguiente['peso_actual']; ?> kg.

                            <?php if ($recoSiguiente['decision'] === 'subir'): ?>
                                Has alcanzado el techo del rango objetivo
                                (<?php echo Serie::LAB_REPS_MAX; ?> reps).
                                Prueba con <strong><?php echo $recoSiguiente['peso_recomendado']; ?> kg</strong>.
                            <?php elseif ($recoSiguiente['decision'] === 'bajar'): ?>
                                Te has quedado por debajo del rango objetivo
                                (<?php echo Serie::LAB_REPS_MIN; ?>–<?php echo Serie::LAB_REPS_MAX; ?> reps).
                                Prueba bajando a <strong><?php echo $recoSiguiente['peso_recomendado']; ?> kg</strong>.
                            <?php else: ?>
                                Estás dentro del rango objetivo
                                (<?php echo Serie::LAB_REPS_MIN; ?>–<?php echo Serie::LAB_REPS_MAX; ?> reps),
                                mantén <strong><?php echo $recoSiguiente['peso_actual']; ?> kg</strong>.
                            <?php endif; ?>
                        </p>

                    <?php else: ?>
                        <p class="tarjeta-ejercicio__recomendacion">
                            Las recomendaciónes de MyWorkoutLog se activarán cuando tengas historial con este ejercicio.
                            Usa un rango de <?php echo Serie::LAB_REPS_MIN; ?>–<?php echo Serie::LAB_REPS_MAX; ?> repeticiones.
                        </p>
                    <?php endif; ?>

                    <div class="tarjeta-ejercicio__acciones-series">
                        <button class="tarjeta-ejercicio__boton boton--primario" type="submit">
                            Guardar serie
                        </button>

                        <?php if ($ultimaSerie): ?>
                            <a class="tarjeta-ejercicio__boton boton--secundario"
                               href="index.php?controller=serie&action=eliminar&id=<?php echo $ultimaSerie->getId(); ?>&id_entrenamiento=<?php echo $entrenamiento->getId(); ?>">
                                Eliminar serie
                            </a>
                        <?php else: ?>
                            <button class="tarjeta-ejercicio__boton boton--secundario" type="button" disabled>
                                Eliminar serie
                            </button>
                        <?php endif; ?>

                        <a class="tarjeta-ejercicio__boton boton--secundario"
                           href="index.php?controller=serie&action=eliminarEjercicio&id_entrenamiento=<?php echo $entrenamiento->getId(); ?>&id_ejercicio=<?php echo $idEjer; ?>">
                            Eliminar ejercicio
                        </a>
                    </div>
                </form>
            </section>
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- Botones grandes de la parte inferior -->
    <section class="acciones-entrenamiento">

        <a class="acciones-entrenamiento__boton acciones-entrenamiento__boton--primario"
           href="index.php?controller=ejercicio&action=index&id_entrenamiento=<?php echo $entrenamiento->getId(); ?>">
            <span class="acciones-entrenamiento__icono">
                <i data-lucide="circle-plus"></i>
            </span>
            <span class="acciones-entrenamiento__texto">Añadir ejercicio</span>
        </a>

        <a
            href="index.php?controller=entrenamiento&action=resumen&id=<?php echo $entrenamiento->getId(); ?>"
            class="acciones-entrenamiento__boton acciones-entrenamiento__boton--primario"
        >
            <span class="acciones-entrenamiento__icono">
                <i data-lucide="square-chart-gantt"></i>
            </span>
            <span class="acciones-entrenamiento__texto">Ver resumen</span>
        </a>

        <a class="acciones-entrenamiento__boton acciones-entrenamiento__boton--peligro"
           href="index.php?controller=entrenamiento&action=eliminar&id=<?php echo $entrenamiento->getId(); ?>">
            <span class="acciones-entrenamiento__icono">
                <i data-lucide="circle-minus"></i>
            </span>
            <span class="acciones-entrenamiento__texto">Eliminar entrenamiento</span>
        </a>
    </section>

    <!-- Modal: escribir notas para la nueva serie -->
    <div class="modal-ejercicio" id="modal-notas-serie" aria-hidden="true" role="dialog" aria-modal="true">
        <div class="modal-ejercicio__overlay" data-modal-close></div>

        <div class="modal-ejercicio__contenido">
            <section class="notas-serie">
                <div class="notas-serie__card">
                    <div class="notas-serie__cabecera">
                        <h2 class="notas-serie__titulo">Notas de la serie</h2>
                        <p class="notas-serie__descripcion">
                            Escribe aquí cualquier nota para esta serie (sensaciones, RPE, técnica, etc.).
                        </p>
                    </div>

                    <div class="notas-serie__campo">
                        <label class="notas-serie__label" for="modal-notas-serie-textarea">
                            Notas
                        </label>
                        <textarea
                            id="modal-notas-serie-textarea"
                            class="notas-serie__textarea"
                            rows="4"
                            placeholder="Ejemplo: RPE 8, última repetición muy justa…"
                        ></textarea>
                    </div>

                    <div class="notas-serie__acciones">
                        <button
                            type="button"
                            class="notas-serie__boton notas-serie__boton--primario"
                            id="modal-notas-serie-guardar"
                        >
                            Guardar notas
                        </button>

                        <button
                            type="button"
                            class="notas-serie__boton notas-serie__boton--secundario"
                            data-modal-close
                        >
                            Cancelar
                        </button>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <!-- Modal: ver notas de una serie existente -->
    <div class="modal-ejercicio" id="modal-ver-notas-serie" aria-hidden="true" role="dialog" aria-modal="true">
        <div class="modal-ejercicio__overlay" data-modal-close></div>

        <div class="modal-ejercicio__contenido">
            <section class="notas-serie">
                <div class="notas-serie__card">
                    <div class="notas-serie__cabecera">
                        <h2 class="notas-serie__titulo">Notas de la serie</h2>
                    </div>

                    <p class="notas-serie__texto" id="modal-ver-notas-serie-texto"></p>

                    <div class="notas-serie__acciones">
                        <button
                            type="button"
                            class="notas-serie__boton notas-serie__boton--secundario"
                            data-modal-close
                        >
                            Cerrar
                        </button>
                    </div>
                </div>
            </section>
        </div>
    </div>

</section>
