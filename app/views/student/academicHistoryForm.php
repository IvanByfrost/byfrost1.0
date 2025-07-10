<h2><?= isset($history) ? 'Editar' : 'Nuevo' ?> Registro Académico</h2>
<form method="post">
    <input type="hidden" name="csrf_token" value='<?= Validator::generateCSRFToken() ?>'>

    <input type="text" name="year" required placeholder="Año" value="<?= htmlspecialchars($history['year'] ?? '') ?>">
    <textarea name="summary" placeholder="Resumen académico"><?= htmlspecialchars($history['summary'] ?? '') ?></textarea>
    <input type="text" name="gpa" placeholder="GPA" value="<?= htmlspecialchars($history['gpa'] ?? '') ?>">
    <button type="submit">Guardar</button>
</form> 