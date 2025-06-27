  <form class="dash-form">
    <h2>🏫 Información General del Colegio</h2>
    <ul>
      <li class="input-group">
        <label for="nombre">Nombre</label>
        <input type="text" id="nombre" name="nombre" required>
      </li>
      <li class="input-group">
        <label for="codigoDANE">Código DANE</label>
        <input type="text" id="codigoDANE" name="codigoDANE">
      </li>
      <li class="input-group">
        <label for="nit">NIT:</label>
        <input type="text" id="nit" name="nit">
      </li>
      <li class="input-group">
        <label>Nivel Educativo ofrecido:</label>
        <div class="checkbox-group">
          <label><input type="checkbox" name="nivel[]" value="preescolar"> Preescolar</label>
          <label><input type="checkbox" name="nivel[]" value="primaria"> Básica Primaria</label>
          <label><input type="checkbox" name="nivel[]" value="secundaria"> Básica Secundaria</label>
          <label><input type="checkbox" name="nivel[]" value="media"> Media (Bachillerato)</label>
          <label><input type="checkbox" name="nivel[]" value="tecnica"> Técnica / Técnica laboral</label>
        </div>
      </li>
    </ul>
    <div class="col-6">
      <button type="submit" id="iniciar-sesion" class="btn btn-primary d-block w-100 text-center">
        Siguiente
      </button>
    </div>
  </form>