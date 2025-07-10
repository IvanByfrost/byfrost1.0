<h2><?= isset($document) ? 'Editar' : 'Nuevo' ?> Documento</h2>
<form method="post" enctype="multipart/form-data">
    <input type="hidden" name="csrf_token" value='<?= Validator::generateCSRFToken() ?>'>

    <input type="text" name="file_name" required placeholder="Nombre del archivo" value="<?= htmlspecialchars($document['file_name'] ?? '') ?>">
    <input type="file" name="file">
    <input type="text" name="type" placeholder="Tipo de documento" value="<?= htmlspecialchars($document['type'] ?? '') ?>">
    <button type="submit">Guardar</button>
</form> 