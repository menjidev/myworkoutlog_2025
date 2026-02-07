<section class="entrenamiento-resumen">
    <div class=" entrenamiento-resumen__card">
        <div class="notas-serie__cabecera entrenamiento-resumen__cabecera">
            <h1 class="notas-serie__titulo">Resumen del entrenamiento</h1>
            <p class="notas-serie__descripcion">
                Revisa de un vistazo todas las series y sus notas para este entrenamiento.
            </p>
    </div>

        <div class="entrenamiento-resumen__bloque entrenamiento-resumen__bloque--principal">
            <p>
                <strong>Nombre:</strong>
                <?php echo htmlspecialchars($entrenamiento->getNombreEntrenamiento(), ENT_QUOTES, 'UTF-8'); ?>
            </p>
            <p>
                <strong>Fecha:</strong>
                <?php echo htmlspecialchars($entrenamiento->getFecha(), ENT_QUOTES, 'UTF-8'); ?>
            </p>
            <?php $notasEntreno = trim((string)($entrenamiento->getNotas() ?? '')); ?>
            <p>
                <strong>Notas del entrenamiento:</strong>
                <?php if ($notasEntreno !== ''): ?>
                    <?php echo nl2br(htmlspecialchars($notasEntreno, ENT_QUOTES, 'UTF-8')); ?>
                <?php else: ?>
                    Sin notas
                <?php endif; ?>
            </p>
        </div>

        <?php if (!empty($ejerciciosEnEntrenamiento)): ?>
            <?php foreach ($ejerciciosEnEntrenamiento as $idEjerResumen): ?>
                <?php
                if (!isset($ejerciciosPorId[$idEjerResumen])) {
                    continue;
                }

                $ejerResumen   = $ejerciciosPorId[$idEjerResumen];
                $seriesResumen = $seriesPorEjercicio[$idEjerResumen] ?? [];
                ?>
                <div class="entrenamiento-resumen__bloque">
                    <h2 class="entrenamiento-resumen__nombre-ejercicio">
                        <?php echo htmlspecialchars($ejerResumen->getNombreEjercicio(), ENT_QUOTES, 'UTF-8'); ?>
                    </h2>

                    <?php if (empty($seriesResumen)): ?>
                        <p class="entrenamiento-resumen__sin-series">
                            No hay series registradas para este ejercicio.
                        </p>
                    <?php else: ?>
                        <ul class="entrenamiento-resumen__lista-series">
                            <?php foreach ($seriesResumen as $serieResumen): ?>
                                <li class="entrenamiento-resumen__serie">
                                    <p>
                                        <strong>Serie <?php echo htmlspecialchars($serieResumen->getNumSerie()); ?>:</strong>
                                        <?php echo htmlspecialchars($serieResumen->getPesoKg()); ?> kg ·
                                        <?php echo htmlspecialchars($serieResumen->getRepeticiones()); ?> reps ·
                                        <?php
                                        $desc = $serieResumen->getDescansoSeg();
                                        echo $desc !== null
                                            ? htmlspecialchars($desc) . ' s de descanso'
                                            : 'sin descanso';
                                        ?>
                                    </p>
                                    <?php
                                    $notaSerie = trim((string)($serieResumen->getNotas() ?? ''));
                                    if ($notaSerie !== ''):
                                    ?>
                                        <p class="entrenamiento-resumen__nota-serie">
                                            <strong>Nota:</strong>
                                            <?php echo nl2br(htmlspecialchars($notaSerie, ENT_QUOTES, 'UTF-8')); ?>
                                        </p>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="entrenamiento-resumen__bloque">
                <p class="entrenamiento-resumen__sin-series">
                    Este entrenamiento todavía no tiene series registradas.
                </p>
            </div>
        <?php endif; ?>

        <div class="entrenamiento-resumen__acciones">
            <a
                href="index.php?controller=entrenamiento&action=ver&id=<?php echo $entrenamiento->getId(); ?>"
                class="notas-serie__boton notas-serie__boton--secundario"
            >
                Volver al entrenamiento
            </a>
        </div>
    </div>
</section>
