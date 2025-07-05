<h2><?= isset($work) ? 'Editar' : 'Nuevo' ?> Trabajo</h2>
<form method="post" enctype="multipart/form-data">
    <input type="text" name="title" required placeholder="Título" value="<?= htmlspecialchars($work['title'] ?? '') ?>">
    <textarea name="description" placeholder="Descripción"><?= htmlspecialchars($work['description'] ?? '') ?></textarea>
    <input type="date" name="due_date" value="<?= htmlspecialchars($work['due_date'] ?? '') ?>">
    <select name="status">
        <option value="pending" <?= (isset($work) && $work['status'] == 'pending') ? 'selected' : '' ?>>Pendiente</option>
        <option value="completed" <?= (isset($work) && $work['status'] == 'completed') ? 'selected' : '' ?>>Completado</option>
    </select>
    <input type="file" name="file">
    <input type="text" name="grade" placeholder="Calificación" value="<?= htmlspecialchars($work['grade'] ?? '') ?>">
    <button type="submit">Guardar</button>
</form> 