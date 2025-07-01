
    <h2>Editar permisos para el rol: <strong><?= htmlspecialchars($permissions['role_type']) ?></strong></h2>

    <form method="post" action="#">
        <h2>Seleccionar Rol para Editar Permisos</h2>

    <form method="get" action="/">
        <input type="hidden" name="controller" value="role">
        <input type="hidden" name="action" value="edit">

        <label for="role_type">Rol:</label>
        <select name="role_type" id="role_type" required>
            <option value="">-- Selecciona un rol --</option>
            <option value="student">Estudiante</option>
            <option value="parent">Padre</option>
            <option value="professor">Profesor</option>
            <option value="coordinator">Coordinador</option>
            <option value="director">Director</option>
            <option value="treasurer">Tesorero</option>
            <option value="root">Administrador</option>
        </select>
        
        <div class="checkbox-group">
            <label>
                <input type="checkbox" name="can_create" <?= $permissions['can_create'] ? 'checked' : '' ?>>
                Crear
            </label>
            <label>
                <input type="checkbox" name="can_read" <?= $permissions['can_read'] ? 'checked' : '' ?>>
                Leer
            </label>
        </div>

        <div class="checkbox-group">
            <label>
                <input type="checkbox" name="can_update" <?= $permissions['can_update'] ? 'checked' : '' ?>>
                Actualizar
            </label>
            <label>
                <input type="checkbox" name="can_delete" <?= $permissions['can_delete'] ? 'checked' : '' ?>>
                Eliminar
            </label>
        </div>

        <button type="submit">Guardar Permisos</button>
    </form>

</body>
</html>
