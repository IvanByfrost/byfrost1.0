<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Configuración Administrativa BYFROST</title>
  <link rel="stylesheet" href="/app/resources/css/settingsRoles.css">
</head>
<body>
  <aside class="sidebar">
    <h2>⚙️ Configuración BYFROST</h2>
    <button class="active" onclick="showSection('usuarios', event)">👥 Usuarios</button>
    <button onclick="showSection('agregar', event)">➕ Agregar usuario</button>
    <button onclick="showSection('recuperar', event)">🔐 Recuperar contraseña</button>
    <button onclick="showSection('calificaciones', event)">📊 Ver calificaciones</button>
  </aside>

  <main class="main">
    <!-- Usuarios -->
    <section id="usuarios" class="section active">
      <h3>👥 Lista de usuarios BYFROST</h3>
      <table id="tablaUsuarios">
        <thead>
          <tr><th>Nombre</th><th>Usuario</th><th>Rol</th><th>Tipo Doc.</th><th>Número</th><th>Acciones</th></tr>
        </thead>
        <tbody></tbody>
      </table>
    </section>

    <!-- Agregar -->
    <section id="agregar" class="section">
      <h3>➕ Agregar usuario</h3>
      <form id="formAgregarUsuario">
        <input placeholder="Nombre completo" id="nombre" name="nombre" required />
        <input placeholder="Usuario (correo)" id="usuario" name="usuario" required />
        <input placeholder="Contraseña" id="clave" name="clave" type="password" required />
        <select id="rol" name="rol">
          <option value="student">Estudiante</option>
          <option value="professor">Docente</option>
          <option value="administrative">Administrativo</option>
          <option value="director">Rector</option>
          <option value="coordinator">Coordinador</option>
        </select>
        <select id="tipoDoc" name="tipoDoc">
          <option value="CC">CEDULA</option>
          <option value="TI">TARJETA IDENTIDAD</option>
          <option value="CE">PASAPORTE</option>
          <option value="RC">CEDULA EXTRANJERA</option>
        </select>
        <input placeholder="Número de documento" id="numeroDoc" name="numeroDoc" required />
        <button type="submit" class="action">Guardar usuario</button>
      </form>
    </section>

    <!-- Recuperar -->
    <section id="recuperar" class="section">
      <h3>🔐 Recuperar contraseña</h3>
      <input id="usuarioRecuperar" placeholder="Usuario (correo)" />
      <button class="action" onclick="recuperarClave()">Recuperar</button>
      <div id="recuperarMsg"></div>
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
    function showSection(id, event) {
      document.querySelectorAll('.section').forEach(s => s.classList.remove('active'));
      document.getElementById(id).classList.add('active');
      document.querySelectorAll('.sidebar button').forEach(b => b.classList.remove('active'));
      if(event) event.target.classList.add('active');
      if (id === 'usuarios') cargarUsuarios();
    }

    // Cargar usuarios vía AJAX
    async function cargarUsuarios() {
      const tbody = document.querySelector("#tablaUsuarios tbody");
      tbody.innerHTML = '<tr><td colspan="6">Cargando...</td></tr>';
      try {
        const res = await fetch('?view=user&action=getUsersAjax');
        const data = await res.json();
        if (data.success && Array.isArray(data.users)) {
          tbody.innerHTML = '';
          data.users.forEach(u => {
            tbody.innerHTML += `
              <tr>
                <td>${u.first_name} ${u.last_name}</td>
                <td>${u.email}</td>
                <td>${u.role_type || ''}</td>
                <td>${u.credential_type || ''}</td>
                <td>${u.credential_number || ''}</td>
                <td>
                  <button class="action edit-btn" onclick="editarUsuario(${u.user_id})">Editar</button>
                  <button class="action delete-btn" onclick="eliminarUsuario(${u.user_id})">Eliminar</button>
                </td>
              </tr>`;
          });
        } else {
          tbody.innerHTML = '<tr><td colspan="6">No hay usuarios.</td></tr>';
        }
      } catch (e) {
        tbody.innerHTML = '<tr><td colspan="6">Error al cargar usuarios.</td></tr>';
      }
    }

    // Agregar usuario vía AJAX
    document.getElementById('formAgregarUsuario').onsubmit = async function(e) {
      e.preventDefault();
      const form = e.target;
      const datos = {
        nombre: form.nombre.value,
        usuario: form.usuario.value,
        clave: form.clave.value,
        rol: form.rol.value,
        tipoDoc: form.tipoDoc.value,
        numeroDoc: form.numeroDoc.value
      };
      const res = await fetch('?view=user&action=createUserAjax', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(datos)
      });
      const data = await res.json();
      alert(data.message || (data.success ? 'Usuario creado' : 'Error al crear usuario'));
      if (data.success) {
        form.reset();
        showSection('usuarios');
      }
    };

    // Editar usuario (abrir prompt)
    async function editarUsuario(userId) {
      // Aquí puedes abrir un modal o prompt para editar, luego enviar AJAX a editUserAjax
      const nuevoNombre = prompt('Nuevo nombre completo:');
      if (!nuevoNombre) return;
      const res = await fetch('?view=user&action=editUserAjax', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ user_id: userId, nombre: nuevoNombre })
      });
      const data = await res.json();
      alert(data.message || (data.success ? 'Usuario editado' : 'Error al editar usuario'));
      if (data.success) cargarUsuarios();
    }

    // Eliminar usuario
    async function eliminarUsuario(userId) {
      if (!confirm('¿Eliminar este usuario?')) return;
      const res = await fetch('?view=user&action=deleteUserAjax', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ user_id: userId })
      });
      const data = await res.json();
      alert(data.message || (data.success ? 'Usuario eliminado' : 'Error al eliminar usuario'));
      if (data.success) cargarUsuarios();
    }

    // Recuperar clave (simulado)
    async function recuperarClave() {
      const usuario = document.getElementById('usuarioRecuperar').value;
      if (!usuario) return alert('Ingresa el usuario (correo)');
      // Aquí podrías hacer un fetch a un endpoint real de recuperación
      document.getElementById('recuperarMsg').innerText = 'Funcionalidad en desarrollo.';
    }

    // Inicializar
    window.onload = function() {
      // Leer parámetro 'section' de la URL
      const params = new URLSearchParams(window.location.search);
      const section = params.get('section');
      if (section && document.getElementById(section)) {
        showSection(section);
      } else {
        cargarUsuarios();
        showSection('usuarios');
      }
    };
  </script>
</body>
</html> 