<h2><?= isset($task) ? 'Editar' : 'Nueva' ?> Tarea</h2>
<form method="post">
    <input type="text" name="title" required placeholder="Título" value="<?= htmlspecialchars($task['title'] ?? '') ?>">
    <textarea name="description" placeholder="Descripción"><?= htmlspecialchars($task['description'] ?? '') ?></textarea>
    <input type="date" name="due_date" value="<?= htmlspecialchars($task['due_date'] ?? '') ?>">
    <select name="status">
        <option value="pending" <?= (isset($task) && $task['status'] == 'pending') ? 'selected' : '' ?>>Pendiente</option>
        <option value="completed" <?= (isset($task) && $task['status'] == 'completed') ? 'selected' : '' ?>>Completada</option>
    </select>
    <button type="submit">Guardar</button>
</form> 