<h2><?= isset($activity) ? 'Editar' : 'Nueva' ?> Actividad</h2>
<form method="post">
    <input type="hidden" name="csrf_token" value='<?= Validator::generateCSRFToken() ?>'>

    <input type="text" name="title" required placeholder="Título" value="<?= htmlspecialchars($activity['title'] ?? '') ?>">
    <textarea name="description" placeholder="Descripción"><?= htmlspecialchars($activity['description'] ?? '') ?></textarea>
    <input type="date" name="date" value="<?= htmlspecialchars($activity['date'] ?? '') ?>">
    <select name="status">
        <option value="pending" <?= (isset($activity) && $activity['status'] == 'pending') ? 'selected' : '' ?>>Pendiente</option>
        <option value="completed" <?= (isset($activity) && $activity['status'] == 'completed') ? 'selected' : '' ?>>Completada</option>
    </select>
    <button type="submit">Guardar</button>
</form> 