
    <h1>Editar Rector</h1>
    <a href="/software_academico/rector/listar">Volver al Listado</a>
    <br><br>
    <?php if (isset($director)): ?>
    <form action="/software_academico/rector/actualizar" method="POST">
        <input type="hidden" name="id_rector" value="<?php echo htmlspecialchars($director['id_rector']); ?>">

        <label for="nombre">Nombre:</label><br>
        <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($director['nombre']); ?>" required><br><br>

        <label for="apellido">Apellido:</label><br>
        <input type="text" id="apellido" name="apellido" value="<?php echo htmlspecialchars($director['apellido']); ?>" required><br><br>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($director['email']); ?>" required><br><br>

        <label for="telefono">Teléfono:</label><br>
        <input type="text" id="telefono" name="telefono" value="<?php echo htmlspecialchars($director['telefono']); ?>"><br><br>

        <button type="submit">Actualizar Rector</button>
    </form>
    <?php else: ?>
        <p>Rector no encontrado para editar.</p>
    <?php endif; ?>