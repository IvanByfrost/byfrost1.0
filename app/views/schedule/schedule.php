<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estudents schedule</title>
    <link rel="stylesheet" href="css\schedule/schedule.css">
</head>
<body>
    <div class="container">
        <h1>Horarios de Estudiantes</h1>

        <form action="#" method="post">
            <label for="estudiante">Seleccionar Estudiante:</label>
            <select name="estudiante" id="estudiante">
                <option value="0">Seleccione un estudiante</option>
                <option value="1">Juan Pérez</option>
                <option value="2">María García</option>
                <option value="3">Carlos López</option>
            </select>
            <button type="submit">Ver Horario</button>
        </form>

        <h2>Horario de Juan Pérez (Ejemplo)</h2>
        <table>
            <thead>
                <tr>
                    <th>Día</th>
                    <th>Hora Inicio</th>
                    <th>Hora Fin</th>
                    <th>Materia</th>
                    <th>Salón</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Lunes</td>
                    <td>08:00</td>
                    <td>09:30</td>
                    <td>Matemáticas I</td>
                    <td>Aula 101</td>
                </tr>
                <tr>
                    <td>Lunes</td>
                    <td>10:00</td>
                    <td>11:30</td>
                    <td>Física General</td>
                    <td>Laboratorio B</td>
                </tr>
                <tr>
                    <td>Martes</td>
                    <td>09:00</td>
                    <td>10:30</td>
                    <td>Química Orgánica</td>
                    <td>Aula 205</td>
                </tr>
                <tr>
                    <td>Miércoles</td>
                    <td>14:00</td>
                    <td>15:30</td>
                    <td>Historia Universal</td>
                    <td>Auditorio</td>
                </tr>
                </tbody>
        </table>

        </div>
</body>
</html>