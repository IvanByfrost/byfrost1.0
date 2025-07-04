<?php
// Vista de configuración de perfil personal para el usuario autenticado
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Subir Foto</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      text-align: center;
      padding-top: 50px;
    }

    .upload-container {
      border: 1px solid #ccc;
      padding: 20px;
      width: 250px;
      margin: auto;
      border-radius: 8px;
      background-color: #f9f9f9;
    }

    .profile-placeholder {
      width: 120px;
      height: 120px;
      border-radius: 50%;
      background: url('https://upload.wikimedia.org/wikipedia/commons/9/99/Sample_User_Icon.png') no-repeat center;
      background-size: cover;
      margin: auto;
      margin-bottom: 15px;
    }

    .btn-upload {
      background-color: #007bff;
      color: white;
      border: none;
      padding: 10px 16px;
      border-radius: 5px;
      cursor: pointer;
      margin-bottom: 10px;
    }

    .btn-upload:hover {
      background-color: #0056b3;
    }

    input[type="file"] {
      margin-top: 5px;
    }
  </style>
</head>
<body>

  <div class="upload-container">
    <div class="profile-placeholder"></div>
    <button class="btn-upload">Cargar foto</button><br>
    <input type="file" id="fileInput">
  </div>

</body>
</html>

?>
<div class="profile-settings-container" style="max-width: 500px; margin: 2rem auto; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.07); padding: 2rem;">
  <h2 style="text-align:center;">⚙️ Mi perfil</h2>
  <form id="profileSettingsForm">
    <div class="mb-3">
      <label for="profileFirstName">Nombre</label>
      <input type="text" class="inputEstilo1" id="profileFirstName" name="first_name" required>
    </div>
    <div class="mb-3">
      <label for="profileLastName">Apellido</label>
      <input type="text" class="inputEstilo1" id="profileLastName" name="last_name" required>
    </div>
    <div class="mb-3">
      <label for="profileEmail">Correo electrónico</label>
      <input type="email" class="inputEstilo1" id="profileEmail" name="email" required>
    </div>
    <div class="mb-3">
      <label for="profilePhone">Teléfono</label>
      <input type="text" class="inputEstilo1" id="profilePhone" name="phone">
    </div>
    <div class="mb-3">
      <label for="profileAddress">Dirección</label>
      <input type="text" class="inputEstilo1" id="profileAddress" name="address">
    </div>
    <div class="d-grid gap-2">
      <button type="submit" class="btn btn-primary">Guardar cambios</button>
    </div>
    <div id="profileSettingsMsg" style="margin-top:10px;"></div>
  </form>
</div>