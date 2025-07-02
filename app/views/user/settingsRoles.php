<?php
// Este archivo es un parcial para dashboards BYFROST.
// No debe tener <html>, <head>, <body> ni <script>.
// El JS debe cargarse desde app/resources/js/settingsRoles.js cuando se use esta vista.
?>
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
    <button onclick="showSection('recuperar', event)">🔐 Recuperar contraseña</button>
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

    <!-- Recuperar -->
    <section id="recuperar" class="section">
      <h3>🔐 Recuperar contraseña</h3>
      <input id="usuarioRecuperar" placeholder="Usuario (correo)" />
      <button class="action" onclick="recuperarClave()">Recuperar</button>
      <div id="recuperarMsg"></div>
    </section>
  </main>
</body>
</html> 