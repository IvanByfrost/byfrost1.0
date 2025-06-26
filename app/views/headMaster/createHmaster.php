<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Crear Nuevo Rector</title>
    
</head>
<body>
    <h1>Crear Nuevo Rector</h1>
    <a href="xampp">Volver al Listado</a>
    <br><br>
    <form action="/software_academico/rector/guardar" method="POST">
        <label for="nombre">Nombre:</label><br>
        <input type="text" id="nombre" name="nombre" required><br><br>

        <label for="apellido">Apellido:</label><br>
        <input type="text" id="apellido" name="apellido" required><br><br>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br><br>

        <label for="password">Contraseña:</label><br>
        <input type="password" id="password" name="password" required minlength="6" title="La contraseña debe tener al menos 6 caracteres"><br><br>

        <label for="telefono">Teléfono:</label><br>
        <input type="tel" id="telefono" name="telefono" pattern="[0-9]{7,15}" title="Ingrese un número de teléfono válido (7 a 15 dígitos)"><br><br>

        <button type="submit">Guardar Rector</button>
    </form>
</body>
</html>
