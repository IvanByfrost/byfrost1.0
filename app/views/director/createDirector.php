    <h1>Crear Nuevo Rector</h1>
    <br><br>
    <form action="/software_academico/rector/guardar" method="POST">
        <label for="nombre">Nombre:</label><br>
        <input type="text" id="nombre" name="nombre" required><br><br>

        <label for="apellido">Apellido:</label><br>
        <input type="text" id="apellido" name="apellido" class="inputEstilo1" required><br><br>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" class="inputEstilo1" required><br><br>

        <label for="password">Contraseña:</label><br>
        <input type="password" id="password" name="password" required minlength="6" title="La contraseña debe tener al menos 6 caracteres" class="inputEstilo1"><br><br>

        <label for="telefono">Teléfono:</label><br>
        <input type="tel" id="telefono" name="telefono" pattern="[0-9]{7,15}" title="Ingrese un número de teléfono válido (7 a 15 dígitos)" class="inputEstilo1"><br><br>

        <button type="submit">Guardar Rector</button>
    </form>

    <div class="container-fluid" style="margin-top: 20px;">
        <button class="btn btn-outline-primary" onclick="loadView('root/mainRoot')">
            <i class="fa fa-home"></i> Volver al Inicio
        </button>
    </div>