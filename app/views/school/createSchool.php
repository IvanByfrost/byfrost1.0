  <form class="dash-form">
    <h2>Información general del colegio</h2>
    <p>Por favor, completa la siguiente información para crear un nuevo colegio.</p>
    <ul>
      <div class="col- 12 input-group">
        <input type="text" id="nombre" name="nombre" class="inputEstilo1" placeholder="Nombre del colegio" required">
      </div>
      <div class="input-group">
        <input type="text" id="codigoDANE" name="codigoDANE" class="inputEstilo1" placeholder="Código DANE del colegio" required>
      </div>
      <div class="input-group">
        <input type="text" id="nit" name="nit" class="inputEstilo1" placeholder="NIT del colegio" required>
      </div>
      <fieldset class="mb-3">
        <legend class="form-label">Nivel educativo ofrecido:</legend>

        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="nivel[]" id="nivelPreescolar" value="preescolar">
          <label class="form-check-label" for="nivelPreescolar">
            Preescolar
          </label>
        </div>

        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="nivel[]" id="nivelPrimaria" value="primaria">
          <label class="form-check-label" for="nivelPrimaria">
            Básica Primaria
          </label>
        </div>

        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="nivel[]" id="nivelSecundaria" value="secundaria">
          <label class="form-check-label" for="nivelSecundaria">
            Básica Secundaria
          </label>
        </div>

        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="nivel[]" id="nivelMedia" value="media">
          <label class="form-check-label" for="nivelMedia">
            Media (Bachillerato)
          </label>
        </div>

        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="nivel[]" id="nivelTecnica" value="tecnica">
          <label class="form-check-label" for="nivelTecnica">
            Técnica / Técnica laboral
          </label>
        </div>
      </fieldset>

    </ul>
    <div class="col-6">
      <button type="submit" id="iniciar-sesion" class="btn btn-primary d-block w-100 text-center">
        Siguiente
      </button>
    </div>
  </form>