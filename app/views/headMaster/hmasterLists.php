<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Rectores</title>
    <link rel="stylesheet" href="/software_academico/public/css/style.css"> </head>
<body>
    <h1>Listado de Rectores</h1>
    <a href="/software_academico/rector/crear">Agregar Nuevo Rector</a>
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
            <?php if (!empty($rectores)): ?>
                <?php foreach ($rectores as $rector): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($rector['id_rector']); ?></td>
                        <td><?php echo htmlspecialchars($rector['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($rector['apellido']); ?></td>
                        <td><?php echo htmlspecialchars($rector['email']); ?></td>
                        <td><?php echo htmlspecialchars($rector['telefono']); ?></td>
                        <td>
                            <a href="/software_academico/rector/editar?id=<?php echo htmlspecialchars($rector['id_rector']); ?>">Editar</a> |
                            <a href="/software_academico/rector/eliminar?id=<?php echo htmlspecialchars($rector['id_rector']); ?>" onclick="return confirm('¿Estás seguro de eliminar a este rector?');">Eliminar</a>
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
</body>
</html>