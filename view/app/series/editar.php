    <h1>Editar serie</h1>

    <form action="index.php?controller=serie&action=actualizar" method="post">
        <input type="hidden" name="id" value="<?php echo $serieEditar->getId(); ?>">
        <input type="hidden" name="id_entrenamiento" value="<?php echo $ent->getId(); ?>">

        <label>
            Ejercicio:
            <select name="id_ejercicio" required>
                <?php foreach ($ejercicios as $ejer): ?>
                    <option value="<?php echo $ejer->getId(); ?>"
                        <?php if ($ejer->getId() === $serieEditar->getIdEjercicio()) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($ejer->getNombreEjercicio()); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>
        <br><br>

        <label>
            Nº serie:
            <input type="number" name="num_serie" value="<?php echo $serieEditar->getNumSerie(); ?>" min="1">
        </label>
        <br><br>

        <label>
            Repeticiones:
            <input type="number" name="repeticiones" value="<?php echo $serieEditar->getRepeticiones(); ?>" min="1">
        </label>
        <br><br>

        <label>
            Peso (kg):
            <input type="number" step="0.5" name="peso_kg" value="<?php echo $serieEditar->getPesoKg(); ?>">
        </label>
        <br><br>

        <label>
            Descanso (segundos):
            <input type="number" name="descanso_seg" value="<?php echo $serieEditar->getDescansoSeg(); ?>" min="0">
        </label>
        <br><br>

        <label>
            Notas:
            <br>
            <textarea name="notas" rows="3" cols="40"><?php
                echo htmlspecialchars($serieEditar->getNotas() ?? '');
            ?></textarea>
        </label>
        <br><br>

        <button type="submit">Guardar cambios</button>
        <a href="index.php?controller=entrenamiento&action=ver&id=<?php echo $ent->getId(); ?>">Cancelar</a>
    </form>
