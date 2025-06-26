<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Panel Docente</title>
  <style>
    * {
      box-sizing: border-box;
      font-family: 'Segoe UI', sans-serif;
    }
    body {
      margin: 0;
      display: flex;
      background: #f6f9fb;
    }
    .sidebar {
      width: 240px;
      background-color: #ffffff;
      height: 100vh;
      padding: 20px;
      border-right: 1px solid #ddd;
    }
    .sidebar .menu button {
      display: flex;
      align-items: center;
      gap: 10px;
      background: none;
      border: none;
      padding: 10px;
      width: 100%;
      text-align: left;
      font-size: 16px;
      cursor: pointer;
      border-radius: 10px;
      margin-bottom: 10px;
    }
    .sidebar .menu button:hover,
    .sidebar .menu button.active {
      background-color: #e7f5ff;
    }
    .main {
      flex-grow: 1;
      padding: 30px;
    }
    .section {
      background: white;
      padding: 20px;
      border-radius: 14px;
      margin-bottom: 20px;
      box-shadow: 0 1px 4px rgba(0,0,0,0.05);
      display: none;
    }
    .section.active {
      display: block;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }
    table, th, td {
      border: 1px solid #ccc;
    }
    th, td {
      padding: 10px;
      text-align: left;
    }
    input, select {
      width: 100%;
      padding: 6px;
      border-radius: 6px;
      border: 1px solid #ccc;
    }
    input.invalid {
      border-color: red;
      background-color: #ffe5e5;
    }
    .save-button {
      background-color: #10b981;
      color: white;
      border: none;
      padding: 12px 20px;
      border-radius: 8px;
      margin-top: 15px;
      cursor: pointer;
      font-size: 16px;
    }
  </style>
</head>
<body>
  <aside class="sidebar">
    <h3>Lucia nova</h3>
    <p>Docente</p>
    <div class="menu">
      <button onclick="showSection('calificaciones')">📝 Calificaciones</button>
      <button onclick="showSection('horario')">📅 Horario</button>
      <button onclick="showSection('citaciones')">📌 Citaciones</button>
    </div>
  </aside>


    <section id="calificaciones" class="section">
      <h2>Registro de Calificaciones</h2>
      <table id="tablaCalificaciones">
        <thead>
          <tr>
            <th>Estudiante</th>
            <th>Asignatura</th>
            <th>Fecha</th>
            <th>Nota (1-100)</th>
            <th>Asistencia</th>
            <th>Observación</th>
          </tr>
        </thead>
        <tbody>
          <tr data-id="camilo">
            <td>Camilo franco</td>
            <td><select><option>Matemáticas</option><option>Inglés</option></select></td>
            <td><input type="date" /></td>
            <td><input type="number" min="1" max="100" /></td>
            <td><select><option>Presente</option><option>Ausente</option></select></td>
            <td><input type="text" /></td>
          </tr>
          <tr data-id="valentina">
            <td>johan carrillo</td>
            <td><select><option>Matemáticas</option><option>Inglés</option></select></td>
            <td><input type="date" /></td>
            <td><input type="number" min="1" max="100" /></td>
            <td><select><option>Presente</option><option>Ausente</option></select></td>
            <td><input type="text" /></td>
          </tr>
           <tr data-id="camilo">
            <td>ivan yuquita</td>
            <td><select><option>Matemáticas</option><option>Inglés</option></select></td>
            <td><input type="date" /></td>
            <td><input type="number" min="1" max="100" /></td>
            <td><select><option>Presente</option><option>Ausente</option></select></td>
            <td><input type="text" /></td>
          </tr>
        </tbody>
      </table>
      <button class="save-button" onclick="guardarNotas()">Guardar calificaciones</button>
    </section>

    <section id="horario" class="section">
      <h2>Horario</h2>
      <ul>
        <li>Lunes – Matemáticas – 08:00</li>
        <li>Martes – Inglés – 10:00</li>
        <li>Miércoles – Tecnología – 09:00</li>
      </ul>
    </section>

    <section id="citaciones" class="section">
      <h2>Citaciones</h2>
      <ul>
        <li>10 de abril – MUcho gogog en clase</li>
        <li>15 de mayo – Seguimiento académico por margarita</li>
      </ul>
    </section>
  </main>

  <script>
    function showSection(sectionId) {
      document.querySelectorAll('.section').forEach(sec => sec.classList.remove('active'));
      document.getElementById(sectionId).classList.add('active');
      document.querySelectorAll('.menu button').forEach(btn => btn.classList.remove('active'));
      event.target.classList.add('active');
    }

    function guardarNotas() {
      const rows = document.querySelectorAll('#tablaCalificaciones tbody tr');
      const datos = [];

      let todoValido = true;

      rows.forEach(row => {
        const id = row.dataset.id;
        const [asignatura, fecha, nota, asistencia, observacion] = row.querySelectorAll('select, input');

        const valorNota = parseFloat(nota.value);
        if (isNaN(valorNota) || valorNota < 0 || valorNota > 100) {
          nota.classList.add("invalid");
          todoValido = false;
        } else {
          nota.classList.remove("invalid");
        }

        datos.push({
          id,
          asignatura: asignatura.value,
          fecha: fecha.value,
          nota: nota.value,
          asistencia: asistencia.value,
          observacion: observacion.value
        });
      });

      if (todoValido) {
        localStorage.setItem("calificacionesGuardadas", JSON.stringify(datos));
        alert("✅ Calificaciones guardadas correctamente.");
      } else {
        alert("❌ Verifica que todas las notas estén entre 1 y 100.");
      }
    }

    function cargarNotas() {
      const data = localStorage.getItem("calificacionesGuardadas");
      if (!data) return;
      const calificaciones = JSON.parse(data);

      calificaciones.forEach(entry => {
        const row = document.querySelector(`tr[data-id="${entry.id}"]`);
        if (row) {
          const selects = row.querySelectorAll('select');
          const inputs = row.querySelectorAll('input');

          selects[0].value = entry.asignatura;
          inputs[0].value = entry.fecha;
          inputs[1].value = entry.nota;
          selects[1].value = entry.asistencia;
          inputs[2].value = entry.observacion;
        }
      });
    }

    window.onload = cargarNotas;
  </script>
</body>
</html>
