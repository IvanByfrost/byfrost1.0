  <form class="form-container">
    <h2>🏫 Información General del Colegio</h2>
    <ul>
      <li>
        <label for="nombre">Nombre</label>
        <input type="text" id="nombre" name="nombre">
      </li>
      <li>
        <label for="codigoDANE">Código DANE</label>
        <input type="text" id="codigoDANE" name="codigoDANE">
      </li>
      <li>
        <label for="nit">NIT:</label>
        <input type="text" id="nit" name="nit">
      </li>
      <li>
        <label>Nivel Educativo ofrecido:</label>
        <div class="checkbox-group">
          <label><input type="checkbox" name="nivel[]" value="preescolar"> Preescolar</label>
          <label><input type="checkbox" name="nivel[]" value="primaria"> Básica Primaria</label>
          <label><input type="checkbox" name="nivel[]" value="secundaria"> Básica Secundaria</label>
          <label><input type="checkbox" name="nivel[]" value="media"> Media (Bachillerato)</label>
          <label><input type="checkbox" name="nivel[]" value="tecnica"> Técnica / Técnica laboral</label>
        </div>
      </li>
      <div class="col-6">
        <button type="submit" id="iniciar-sesion"
          class="btn btn-primary d-block w-100 text-center">
          Registrar Colegio
        </button>
      </div>
    </ul>

    <h2>📍 Ubicación</h2>
    <ul>
      <li>
        <label for="departamento">Departamento:</label>
        <input type="text" id="departamento" name="departamento">
      </li>
      <li>
        <label for="municipio">Municipio / Ciudad:</label>
        <input type="text" id="municipio" name="municipio">
      </li>
      <li>
        <label for="direccion">Dirección:</label>
        <input type="text" id="direccion" name="direccion">
      </li>
      <li>
        <label for="telefono">Teléfono de Contacto:</label>
        <input type="tel" id="telefono" name="telefono">
      </li>
      <li>
        <label for="correo">Correo Electrónico Institucional:</label>
        <input type="email" id="correo" name="correo">
      </li>
    </ul>
  </div>
</body>
</html>
=======
  </form>
>>>>>>> f2792e8ff1699fe280a62dd205452243499a4d67
