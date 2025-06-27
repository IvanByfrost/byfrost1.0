        <h1>Listado de Rectores</h1>
        <a href="#" onclick="loadView('director/createDirector')">Agregar Nuevo Rector</a>
        <br><br>
        <table border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($directors)): ?>
                    <?php foreach ($directors as $director): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($director['id_rector']); ?></td>
                            <td><?php echo htmlspecialchars($director['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($director['apellido']); ?></td>
                            <td><?php echo htmlspecialchars($director['email']); ?></td>
                            <td><?php echo htmlspecialchars($director['telefono']); ?></td>
                            <td>
                                <a href="/software_academico/rector/editar?id=<?php echo htmlspecialchars($director['id_rector']); ?>">Editar</a> |
                                <a href="/software_academico/rector/eliminar?id=<?php echo htmlspecialchars($director['id_rector']); ?>" onclick="return confirm('¿Estás seguro de eliminar a este rector?');">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">No hay rectores registrados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
