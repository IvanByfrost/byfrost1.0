<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Colegio</title>
<body>
  <div class="form-container">
    <h2>🏫 Información General del Colegio</h2>
    <ul>
      <li>
        <label for="nombre">Nombre del Colegio:</label>
        <input type="text" id="nombre" name="nombre">
      </li>
      <li>
        <label for="codigoDANE">Código DANE (si aplica):</label>
        <input type="text" id="codigoDANE" name="codigoDANE">
      </li>
      <li>
        <label for="nit">NIT:</label>
        <input type="text" id="nit" name="nit">
      </li>
      <li>
        <label>Tipo de Institución:</label>
        <div class="checkbox-group">
          <label><input type="radio" name="tipo" value="publica"> Pública</label>
          <label><input type="radio" name="tipo" value="privada"> Privada</label>
          <label><input type="radio" name="tipo" value="mixta"> Mixta</label>
        </div>
      </li>
      <li>
        <label>Nivel Educativo Ofrecido:</label>
        <div class="checkbox-group">
          <label><input type="checkbox" name="nivel[]" value="preescolar"> Preescolar</label>
          <label><input type="checkbox" name="nivel[]" value="primaria"> Básica Primaria</label>
          <label><input type="checkbox" name="nivel[]" value="secundaria"> Básica Secundaria</label>
          <label><input type="checkbox" name="nivel[]" value="media"> Media (Bachillerato)</label>
          <label><input type="checkbox" name="nivel[]" value="tecnica"> Técnica / Técnica laboral</label>
        </div>
      </li>
    </ul>
