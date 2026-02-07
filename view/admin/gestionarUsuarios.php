    <?php if (!empty($mensaje)): ?>
        <p style="color:green;"><?php echo htmlspecialchars($mensaje); ?></p>
    <?php endif; ?>

        <table class="tabla-series tabla-series--gestionar-usuarios">
        <thead class="tabla-series__encabezado">
            <tr class="tabla-series__fila tabla-series__fila--encabezado">
                <th class="tabla-series__celda">Email</th>
                <th class="tabla-series__celda">Rol</th>
                <th class="tabla-series__celda">Estado</th>
                <th class="tabla-series__celda">Guardar</th>
                <th class="tabla-series__celda">Eliminar</th>
            </tr>
        </thead>

        <tbody class="tabla-series__cuerpo">
        <?php if (empty($usuarios)): ?>
            <tr class="tabla-series__fila">
                <td class="tabla-series__celda" colspan="6">
                    No hay usuarios registrados.
                </td>
            </tr>
        <?php else: ?>
            <?php foreach ($usuarios as $u): ?>
                <tr class="tabla-series__fila">
                    

                    <td class="tabla-series__celda tabla-series__celda--email">
                        <span title="<?php echo htmlspecialchars($u->getEmail(), ENT_QUOTES, 'UTF-8'); ?>">
                            <?php echo htmlspecialchars($u->getEmail(), ENT_QUOTES, 'UTF-8'); ?>
                        </span>
                    </td>

                    <!-- Columna Rol (select) -->
                    <td class="tabla-series__celda">
                        <form action="index.php?controller=admin&action=actualizarUsuario" method="post">
                            <input type="hidden" name="id" value="<?php echo $u->getId(); ?>">

                            <select name="rol" class="tabla-series__select tabla-series__select--rol">
                                <option value="usuario" <?php if ($u->getRol() === 'usuario') echo 'selected'; ?>>
                                    usuario
                                </option>
                                <option value="admin" <?php if ($u->getRol() === 'admin') echo 'selected'; ?>>
                                    admin
                                </option>
                            </select>
                    </td>

                    <!-- Columna Estado (select) -->
                    <td class="tabla-series__celda">
                            <select name="estado" class="tabla-series__select tabla-series__select--estado">
                                <option value="pendiente" <?php if ($u->getEstado() === 'pendiente') echo 'selected'; ?>>
                                    pendiente
                                </option>
                                <option value="activo" <?php if ($u->getEstado() === 'activo') echo 'selected'; ?>>
                                    activo
                                </option>
                            </select>
                    </td>


                    <!-- Columna Acciones -->
                    <td class="tabla-series__celda">
                            <button class="boton--primario boton-pequeño" type="submit">Guardar</button>
                    </td>
                    <td class="tabla-series__celda">
                            <?php if ($u->getId() !== (int)$_SESSION['id_usuario']): ?>
                                <a class="boton--secundario boton-pequeño" href="index.php?controller=admin&action=eliminarUsuario&id=<?php echo $u->getId(); ?>"
                                   onclick="return confirm('¿Seguro que quieres eliminar este usuario?');">
                                    Eliminar
                                </a>
                                <?php else: ?>
                               <p class="small">(Tú mismo)</p>
                                </td>
                            <?php endif; ?> 
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>

