<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Configuración Administrativa BYFROST</title>
  <style>
    * { box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
    body { margin: 0; display: flex; background: #f3f4f6; }
    .sidebar {
      width: 250px; background: #fff; padding: 20px; border-right: 1px solid #ddd; height: 100vh;
    }
    .sidebar h2 { margin-bottom: 30px; }
    .sidebar button {
      display: block; background: none; border: none; padding: 10px; font-size: 16px;
      width: 100%; text-align: left; cursor: pointer; border-radius: 8px; margin-bottom: 10px;
    }
    .sidebar button.active, .sidebar button:hover { background-color: #e0f2fe; }
    .main { flex: 1; padding: 30px; }
    .section {
      display: none; background: white; padding: 20px; border-radius: 12px;
      box-shadow: 0 1px 4px rgba(0,0,0,0.05);
    }
    .section.active { display: block; }
    input, select {
      width: 100%; padding: 8px; margin: 8px 0 15px; border: 1px solid #ccc; border-radius: 6px;
    }
    table {
      width: 100%; border-collapse: collapse; margin-top: 15px;
    }
    table, th, td { border: 1px solid #ccc; }
    th, td { padding: 10px; text-align: left; }
    button.action {
      background-color: #0284c7; color: white; border: none; padding: 10px 16px; border-radius: 6px; cursor: pointer;
    }
    .delete-btn { background-color: #dc2626; }
    .edit-btn { background-color: #facc15; color: black; }
  </style>
</head>
<body>
  <aside class="sidebar">
    <h2>⚙️ Configuración BYFROST</h2>
    <button class="active" onclick="showSection('usuarios')">👥 Usuarios</button>
    <button onclick="showSection('agregar')">➕ Agregar usuario</button>
    <button onclick="showSection('recuperar')">🔐 Recuperar contraseña</button>
    <button onclick="showSection('calificaciones')">📊 Ver calificaciones</button>
  </aside>

  <main class="main">
    <!-- Usuarios -->
    <section id="usuarios" class="section active">
      <h3>👥 Lista de usuarios BYFROST</h3>
      <table id="tablaUsuarios">
        <thead>
          <tr><th>Nombre</th><th>Usuario</th><th>Rol</th><th>Tipo Doc.</th><th>Número</th></tr>
        </thead>
        <tbody></tbody>
      </table>
    </section>

    <!-- Agregar -->
    <section id="agregar" class="section">
      <h3>➕ Agregar usuario</h3>
      <form onsubmit="agregarUsuario(event)">
        <input placeholder="Nombre completo" id="nombre" required />
        <input placeholder="Usuario" id="usuario" required />
        <input placeholder="Contraseña" id="clave" type="password" required />
        <select id="rol">
          <option>Estudiante</option>
          <option>Docente</option>
          <option>Administrativo</option>
          <option>Rector</option>
          <option>Coordinador</option>
        </select>
        <select id="tipoDoc">
          <option value="CC">CEDULA</option>
          <option value="TI">TARJETA IDENTIDAD</option>
          <option value="CE">PASAPORTE</option>
          <option value="RC">CEDULA EXTRANJERA</option>
        </select>
        <input placeholder="Número de documento" id="numeroDoc" required />
        <button type="submit" class="action">Guardar yuquita</button>
      </form>
    </section>

    <!-- Recuperar -->
    <section id="recuperar" class="section">
      <h3>🔐 Recuperar contraseña</h3>
      <input id="usuarioRecuperar" placeholder="Usuario" />
      <button class="action" onclick="recuperarClave()">Recuperar</button>
    </section>

    <!-- Calificaciones -->
    <section id="calificaciones" class="section">
      <h3>📊 Calificaciones</h3>
      <p>Simulado: mostrará un resumen general</p>
      <ul>
        <li>Camilo Rodríguez: Matemáticas - 95</li>
        <li>Valentina Gómez: Inglés - 88</li>
      </ul>
    </section>

  <script>
    let usuarios = JSON.parse(localStorage.getItem("usuarios") || "[]");

    function showSection(id) {
      document.querySelectorAll('.section').forEach(s => s.classList.remove('active'));
      document.getElementById(id).classList.add('active');
      document.querySelectorAll('.sidebar button').forEach(b => b.classList.remove('active'));
      event.target.classList.add('active');
      if (id === 'usuarios') renderTabla();
    }

    function agregarUsuario(e) {
      e.preventDefault();
      const nombre = nombreInput.value.trim();
      const usuario = usuarioInput.value.trim();
      const clave = claveInput.value;
      const rol = rolSelect.value;
      const tipoDoc = tipoDocSelect.value;
      const numeroDoc = numeroDocInput.value.trim();
      if (usuarios.find(u => u.usuario === usuario)) return alert("❌ Usuario ya existe.");
      usuarios.push({ nombre, usuario, clave, rol, tipoDoc, numeroDoc });
      localStorage.setItem("usuarios", JSON.stringify(usuarios));
      alert("✅ Usuario agregado.");
      e.target.reset();
      renderTabla();
    }

    function renderTabla() {
      const tbody = document.querySelector("#tablaUsuarios tbody");
      tbody.innerHTML = "";
      usuarios.forEach((u, i) => {
        tbody.innerHTML += `
          <tr>
            <td>${u.nombre}</td>
            <td>${u.usuario}</td>
            <td>${u.rol}</td>
            <td>${u.tipoDoc || ''}</td>
            <td>${u.numeroDoc || ''}</td>
            <td>
              <button class="action edit-btn" onclick="editarUsuario(${i})">Editar</button>
              <button class="action delete-btn" onclick="eliminarUsuario(${i})">Eliminar</button>
            </td>
          </tr>`;
      });
    }

    function editarUsuario(index) {
      const u = usuarios[index];
      const nuevoNombre = prompt("Editar nombre", u.nombre);
      const nuevaClave = prompt("Editar clave", u.clave);
      const nuevoRol = prompt("Editar rol (Estudiante, Docente, Administrativo)", u.rol);
      const nuevoTipoDoc = prompt("Editar tipo de documento", u.tipoDoc);
      const nuevoNumeroDoc = prompt("Editar número de documento", u.numeroDoc);
      if (nuevoNombre && nuevaClave && nuevoRol && nuevoTipoDoc && nuevoNumeroDoc) {
        usuarios[index] = { ...u, nombre: nuevoNombre, clave: nuevaClave, rol: nuevoRol, tipoDoc: nuevoTipoDoc, numeroDoc: nuevoNumeroDoc };
        localStorage.setItem("usuarios", JSON.stringify(usuarios));
        renderTabla();
      }
    }

    function eliminarUsuario(index) {
      if (confirm("¿Eliminar este usuario?")) {
        usuarios.splice(index, 1);
        localStorage.setItem("usuarios", JSON.stringify(usuarios));
        renderTabla();
      }
    }

    function recuperarClave() {
      const user = usuarioRecuperar.value.trim();
      const encontrado = usuarios.find(u => u.usuario === user);
      alert(encontrado ? `🔐 Tu clave es: ${encontrado.clave}` : "⚠️ Usuario no encontrado.");
    }

    const nombreInput = document.getElementById("nombre");
    const usuarioInput = document.getElementById("usuario");
    const claveInput = document.getElementById("clave");
    const rolSelect = document.getElementById("rol");
    const tipoDocSelect = document.getElementById("tipoDoc");
    const numeroDocInput = document.getElementById("numeroDoc");

    window.onload = renderTabla;
  </script>
</body>
</html>
<script>
  // Inicializar la tabla al cargar la página
  window.onload = renderTabla;
    // Mostrar la sección de usuarios por defecto
    showSection('usuarios');
</script>
</body>