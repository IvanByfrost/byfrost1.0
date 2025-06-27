<form method = "POST" id = "consultSchool" class="dash-form">
    <h2>Información general del colegio</h2>
    <p>Por favor, completa la siguiente información para consultar un nuevo colegio.</p>
    <ul>

      <div class="col- 12 input-group">
        <input type="text" id="school_name" name="school_name" class="inputEstilo1" placeholder="Nombre del colegio" required>
      </div>
      <div class="input-group">
        <input type="text" id="codigoDANE" name="codigoDANE" class="inputEstilo1" placeholder="Código DANE del colegio" required>
      </div>
      <div class="input-group">
        <input type="text" id="nit" name="nit" class="inputEstilo1" placeholder="NIT del colegio" required>
      </div>
  </ul>
    <div class="col-6">
      <button type="submit" class="btn btn-primary d-block w-100 text-center">
        Consultar
      </button>
    </div>
  </form>