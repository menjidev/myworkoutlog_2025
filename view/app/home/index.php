    <div class="dashboard__titulo">
    <h1 class="dashboard__titulo__h1">
    Hola, <?php echo htmlspecialchars($_SESSION['nombre_usuario'] ?? ''); ?> bienvenido/a a <span class="dashboard__titulo__h1--terciario">MyWorkoutLog.</span>
    </h1>
</div>
<!-- SECCION RESUMENES ENTRENAMIENTOS -->
<section class="dashboard-resumen">
    <div class="dashboard-resumen__contenedor-titulo">
        <h2 class="dashboard-resumen__contenedor-titulo__h2">
            Resumen de este mes:
        </h2>

        <div class="dashboard-resumen__contenedor-cards">
            
            <div class="dashboard-resumen__card">
                <div class="dashboard-resumen__card__contenedor-icono">
                     <p class="dashboard-resumen__card__contenedor-icono__titulo">Entrenamientos</p>
                     <i data-lucide="calendar-days" class="dashboard-resumen__card__contenedor-icono__icono"></i>
                   
                </div>
                <div class="dashboard-resumen__card__valor-variable">
                    <p class="dashboard-resumen__card__parrafo">
                        <?php echo (int) $totalEntrenamientosMes; ?>
                         entrenamientos realizados.
                    </p>
                </div>
            </div>

            <div class="dashboard-resumen__contenedor-cards">
            <div class="dashboard-resumen__card">
                <div class="dashboard-resumen__card__contenedor-icono">
                     <p class="dashboard-resumen__card__contenedor-icono__titulo">Series registradas</p>
                     <i data-lucide="hash" class="dashboard-resumen__card__contenedor-icono__icono"></i>
                   
                </div>
                <div class="dashboard-resumen__card__valor-variable">
                    <p class="dashboard-resumen__card__parrafo">
                        <?php echo (int) $totalSeriesMes; ?>
                         series este mes.
                    </p>
                </div>
            </div>

             <div class="dashboard-resumen__contenedor-cards">
            <div class="dashboard-resumen__card">
                <div class="dashboard-resumen__card__contenedor-icono">
                     <p class="dashboard-resumen__card__contenedor-icono__titulo">Ejercicio más fuerte</p>
                     <i data-lucide="dumbbell" class="dashboard-resumen__card__contenedor-icono__icono"></i>
                   
                </div>
                <div class="dashboard-resumen__card__valor-variable">
                    <p class="dashboard-resumen__card__parrafo">
                        <?php if (!empty($topFuerzaMes) && !empty($topFuerzaMes['nombre_ejercicio'])): ?>
                        <?php echo htmlspecialchars($topFuerzaMes['nombre_ejercicio']); ?>
                    <?php else: ?>
                        Sin datos todavía
                    <?php endif; ?>
                    <?php if (!empty($topFuerzaMes) && isset($topFuerzaMes['max_peso'])): ?>
                    <p class="dashboard-summary__item-valor-variable">
                        Peso: <?php echo (float)$topFuerzaMes['max_peso']; ?> kg
                    </p>
                <?php else: ?>
                    <p class="dashboard-summary__item-valor-variable">
                        Registra algunas series para ver tu ejercicio más fuerte
                    </p>
                <?php endif; ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="dashboard-resumen__contenedor-ver-mas">
        <a href="index.php?controller=metrics&action=index" class="dashboard-resumen__contenedor-ver-mas__link">Ver más</a>
    </div>
</section>

<!-- SECCION ULTIMOS ENTRENAMIENTOS -->
<section class="dashboard-entrenamientos">
    <div class="dashboard-entrenamientos__header">
        <h2 class="dashboard-entrenamientos__titulo">
            Últimos entrenamientos
        </h2>
    </div>

    <?php if (!empty($ultimosEntrenamientos)): ?>
        <ul class="dashboard-entrenamientos__lista">
            <?php foreach ($ultimosEntrenamientos as $ent): ?>
                <?php
                // Formateamos la fecha (asumiendo formato Y-m-d en la BBDD)
                $fechaObj = DateTime::createFromFormat('Y-m-d', $ent->getFecha());
                $fechaFormateada = $fechaObj ? $fechaObj->format('d/m/Y') : htmlspecialchars($ent->getFecha());
                ?>
                <li class="dashboard-entrenamientos__lista__item">
                    <div class="dashboard-entrenamientos__info">
                        <span class="dashboard-entrenamientos__nombre">
                            <?php echo htmlspecialchars($ent->getNombreEntrenamiento()); ?>
                        </span>
                        <span class="dashboard-entrenamientos__fecha">
                            <?php echo $fechaFormateada; ?>
                        </span>
                    </div>

                    <a
                        class="dashboard-entrenamientos__button"
                        href="index.php?controller=entrenamiento&action=ver&id=<?php echo $ent->getId(); ?>"
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
