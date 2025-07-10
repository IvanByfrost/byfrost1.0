<h2><?= isset($report) ? 'Editar' : 'Nuevo' ?> Reporte</h2>
<form method="post" enctype="multipart/form-data">
    <input type="hidden" name="csrf_token" value='<?= Validator::generateCSRFToken() ?>'>

    <input type="text" name="title" required placeholder="Título" value="<?= htmlspecialchars($report['title'] ?? '') ?>">
    <textarea name="description" placeholder="Descripción"><?= htmlspecialchars($report['description'] ?? '') ?></textarea>
    <input type="date" name="date" value="<?= htmlspecialchars($report['date'] ?? '') ?>">
    <input type="file" name="file">
    <input type="text" name="grade" placeholder="Calificación" value="<?= htmlspecialchars($report['grade'] ?? '') ?>">
    <button type="submit">Guardar</button>
</form> 