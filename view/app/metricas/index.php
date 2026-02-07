<section class="metricas">

    <!-- GRID DE TARJETAS SUPERIORES -->
    <div class="metricas__contenedor">
        <div class="metricas-resumen__filtros">
            <h3 class="metricas-resumen__etiqueta">Rango:</h3>

            <form class="metricas-resumen__filtros-form" action="index.php" method="get">
                <input type="hidden" name="controller" value="metrics">
                <input type="hidden" name="action" value="index">

                <?php if (isset($year, $month)) : ?>
                    <input type="hidden" name="month_picker"
                        value="<?php echo $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT); ?>">
                <?php endif; ?>
                <?php if (isset($q)) : ?>
                    <input type="hidden" name="q" value="<?php echo htmlspecialchars($q, ENT_QUOTES); ?>">
                <?php endif; ?>

                <div class="metricas-resumen__select-contenedor">
                    <select
                        name="filtro"
                        class="metricas-resumen__select"
                        onchange="this.form.submit()"
                    >
                        <option value="ultimos30" <?php echo $filtroActual === 'ultimos30' ? 'selected' : ''; ?>>
                            Últimos 30 días
                        </option>
                        <option value="mes_anterior" <?php echo $filtroActual === 'mes_anterior' ? 'selected' : ''; ?>>
                            Mes anterior
                        </option>
                        <option value="siempre" <?php echo $filtroActual === 'siempre' ? 'selected' : ''; ?>>
                           Desde siempre 
                        </option>
                    </select>
                     <span class="metricas-resumen__select-icono">
                        <i data-lucide="chevron-down"></i>
                    </span>
                </div>
            </form>
        </div>

        <!-- TARJETA RESUMEN NUMÉRICO -->
        <section id="metricas__resumen" class="metricas__tarjeta metricas-resumen">
    <div class="metricas-resumen__cabecera">
        <h2 class="metricas-resumen__titulo">Resumen</h2>
    </div>

    <ul class="metricas-resumen__lista">
        <!-- Bloques globales -->
        <li class="metricas-resumen__item">
            <span class="metricas-resumen__valor">
                <?php echo $totalEntrenamientos; ?>
            </span>
            <span class="metricas-resumen__texto">
                Entrenamientos
            </span>
        </li>

        <li class="metricas-resumen__item">
            <span class="metricas-resumen__valor">
                <?php echo $totalSeries; ?>
            </span>
            <span class="metricas-resumen__texto">
                Series registradas
            </span>
        </li>

        <!-- Bloques por grupo muscular -->
        <?php foreach ($resumenGrupos as $grupo => $totalDias): ?>
            <li class="metricas-resumen__item">
                <span class="metricas-resumen__valor">
                    <?php echo $totalDias; ?>
                </span>
                <span class="metricas-resumen__texto">
                    Entrenamiento/s de <?php echo htmlspecialchars($grupo); ?>
                </span>
            </li>
        <?php endforeach; ?>
    </ul>
</section>

        <!-- TARJETA EJERCICIO CON MÁS FUERZA -->
        <section class="metricas__tarjeta metricas-top-fuerza">
    <h2 class="metricas-top-fuerza__titulo">Ejercicio con más fuerza</h2>

    <?php if (empty($topFuerza)): ?>
        <p class="metricas-top-fuerza__mensaje metricas-top-fuerza__mensaje--hint">
            No hay datos suficientes en este rango
            <?php if ($grupoFuerzaActual !== 'todos'): ?>
                para el grupo <strong><?php echo htmlspecialchars($grupoFuerzaActual); ?></strong>.

            <?php else: ?>.
            <?php endif; ?>
        </p>
    <?php else: ?>
        <p class="metricas-top-fuerza__ejercicio">
            <strong><?php echo htmlspecialchars($topFuerza['nombre_ejercicio']); ?></strong>
        </p>

        <div class="metricas-top-fuerza__contenedor__dato">
            <p class="metricas-top-fuerza__dato">
                Peso máximo:
                <strong class="color_terciario"><?php echo $topFuerza['max_peso']; ?> kg</strong>
                <?php if ($grupoFuerzaActual !== 'todos'): ?>
                    <span class="metricas-top-fuerza__grupo">
                        (<?php echo htmlspecialchars($grupoFuerzaActual); ?>)
                    </span>
                <?php endif; ?>
            </p>

            <!-- Select de grupo muscular (misma estructura visual, ahora funcional) -->
            <form
                class="contenedor__metricas-top-fuerza__select-form"
                action="index.php#metricas__resumen"
                method="get"
            >
                <input type="hidden" name="controller" value="metrics">
                <input type="hidden" name="action" value="index">
                <input type="hidden" name="filtro" value="<?php echo htmlspecialchars($filtroActual); ?>">

                <!-- Conservamos mes y búsqueda actuales -->
                <input type="hidden" name="month_picker"
                       value="<?php echo $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT); ?>">
                <input type="hidden" name="q"
                       value="<?php echo htmlspecialchars($q ?? '', ENT_QUOTES); ?>">

                <div class="contenedor__metricas-top-fuerza__select">
                    <select
                        name="grupo_fuerza"
                        class="metricas-top-fuerza__select"
                        onchange="this.form.submit()"
                    >
                        <option value="todos" <?php echo $grupoFuerzaActual === 'todos' ? 'selected' : ''; ?>>
                            Todos los grupos
                        </option>

                        <?php foreach ($gruposMusculares as $grupo): ?>
                            <option value="<?php echo htmlspecialchars($grupo, ENT_QUOTES); ?>"
                                <?php echo $grupoFuerzaActual === $grupo ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($grupo); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <span class="metricas-top-fuerza__select-icono">
                        <i data-lucide="chevron-down"></i>
                    </span>
                </div>
            </form>
        </div>
    <?php endif; ?>
</section>


        <!-- TARJETA EJERCICIOS MÁS FRECUENTES -->
        <section class="metricas__tarjeta metricas-top-frecuencia">
            <h2 class="metricas-top-frecuencia__titulo">Ejercicios más frecuentes</h2>

            <?php if (empty($topFrecuencia)): ?>
                <p class="metricas-top-frecuencia__mensaje metricas-top-frecuencia__mensaje--hint">
                    No hay datos en este rango.
                </p>
            <?php else: ?>
                <table class="metricas-top-frecuencia__tabla">
                    <thead class="metricas-top-frecuencia__cabecera">
                    <tr>
                        <th class="metricas-top-frecuencia__celda metricas-top-frecuencia__celda--cabecera metricas-top-frecuencia__celda--nombre">
                            Ejercicio
                        </th>
                        <th class="metricas-top-frecuencia__celda metricas-top-frecuencia__celda--cabecera metricas-top-frecuencia__celda--contador">
                            Entrenamientos
                        </th>
                    </tr>
                    </thead>
                    <tbody class="metricas-top-frecuencia__cuerpo">
                    <?php foreach ($topFrecuencia as $item): ?>
                        <tr class="metricas-top-frecuencia__fila">
                            <td class="metricas-top-frecuencia__celda metricas-top-frecuencia__celda--nombre">
                                <?php echo htmlspecialchars($item['nombre_ejercicio']); ?>
                            </td>
                            <td class="metricas-top-frecuencia__celda metricas-top-frecuencia__celda--contador">
                                <?php echo $item['total_entrenamientos']; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </section>

    </div> <!-- .metricas__grid -->

    <!-- TARJETA CALENDARIO -->
    <section class="metricas__tarjeta metricas-calendario" id="metricas_calendario">
        <div class="metricas-calendario__cabecera">
            <div class="metricas-calendario__titulos">
                <h2 class="metricas-calendario__titulo">Calendario de entrenamientos</h2>
                <p class="metricas-calendario__subtitulo">
                    Mes visible:
                    <?php echo str_pad($month, 2, '0', STR_PAD_LEFT) . '/' . $year; ?>
                </p>
            </div>

            <form method="get"
                  action="index.php#metricas_calendario"
                  class="metricas-calendario__form">
                <input type="hidden" name="controller" value="metrics">
                <input type="hidden" name="action" value="index">

                <div class="metricas-calendario__campo">
                    <label class="metricas-calendario__label">
                        Mes:
                        <input
                            class="metricas-calendario__input metricas-calendario__input--mes"
                            type="month"
                            name="month_picker"
                            value="<?php echo $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT); ?>">
                    </label>
                </div>

                <div class="metricas-calendario__campo">
                  <label class="metricas-calendario__label">
                        Filtrar calendario:
                        <input
                            class="metricas-calendario__input metricas-calendario__input--busqueda"
                            type="text"
                            name="q"
                            placeholder="Ejercicio/Grupo Muscular"
                            value="<?php echo htmlspecialchars($q, ENT_QUOTES); ?>">
                    </label>
                </div>

                <button type="submit" class="metricas-calendario__boton">
                    Aplicar
                </button>
            </form>
            </div>

        <div class="metricas-calendario__contenido">
            <table class="metricas-calendario__tabla">
                <thead class="metricas-calendario__cabecera-tabla">
                <tr>
                    <th class="metricas-calendario__dia-semana">Lun</th>
                    <th class="metricas-calendario__dia-semana">Mar</th>
                    <th class="metricas-calendario__dia-semana">Mié</th>
                    <th class="metricas-calendario__dia-semana">Jue</th>
                    <th class="metricas-calendario__dia-semana">Vie</th>
                    <th class="metricas-calendario__dia-semana">Sáb</th>
                    <th class="metricas-calendario__dia-semana">Dom</th>
                </tr>
                </thead>
                <tbody class="metricas-calendario__cuerpo-tabla">
                <tr>
                    <?php
                    // Celdas vacías antes del día 1
                    for ($i = 1; $i < $primerDiaSemana; $i++) {
                        echo '<td class="metricas-calendario__dia metricas-calendario__dia--vacio"></td>';
                    }

                    $columna = $primerDiaSemana;

                    for ($dia = 1; $dia <= $diasMes; $dia++, $columna++) {

                        if ($columna > 7) {
                            $columna = 1;
                            echo '</tr><tr>';
                        }

                        $fechaDia      = sprintf('%04d-%02d-%02d', $year, $month, $dia);
                        $tieneEntrenos = !empty($calendario[$dia]);

                        $classes = ['metricas-calendario__dia'];
                        if ($tieneEntrenos) {
                            $classes[] = 'metricas-calendario__dia--entrenamiento';
                        }
                        if ($fechaDia === $hoy) {
                            $classes[] = 'metricas-calendario__dia--hoy';
                        }
                        $classAttr = ' class="' . implode(' ', $classes) . '"';

                        echo "<td{$classAttr}>";
                        echo '<div class="metricas-calendario__dia-contenido">';
                        echo '<div class="metricas-calendario__dia-numero">' . $dia . '</div>';

                        if ($tieneEntrenos) {
                            echo '<ul class="metricas-calendario__lista-entrenos">';
                            foreach ($calendario[$dia] as $entrenoDia) {
                                $idEntreno     = (int)$entrenoDia['id'];
                                $nombreEntreno = $entrenoDia['nombre'];

                                echo '<li class="metricas-calendario__entreno">';
                                echo '<a class="color_terciario" href="index.php?controller=entrenamiento&action=ver&id=' . $idEntreno . '">';
                                echo htmlspecialchars($nombreEntreno, ENT_QUOTES);
                                echo '</a>';
                                echo '</li>';
                            }
                            echo '</ul>';
                        }


                        echo '</div>';
                        echo '</td>';
                    }

                    // Rellenar hasta completar la última fila
                    if ($columna !== 1) {
                        for ($i = $columna; $i <= 7; $i++) {
                            echo '<td class="metricas-calendario__dia metricas-calendario__dia--vacio"></td>';
                        }
                    }
                    ?>
                </tr>
                </tbody>
            </table>
        </div>
    </section>

</section>
