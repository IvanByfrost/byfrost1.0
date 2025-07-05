<h2>Datos Básicos del Estudiante</h2>
<ul>
    <li><strong>Nombre:</strong> <?= htmlspecialchars($student['name'] ?? '') ?></li>
    <li><strong>Documento:</strong> <?= htmlspecialchars($student['document'] ?? '') ?></li>
    <li><strong>Correo:</strong> <?= htmlspecialchars($student['email'] ?? '') ?></li>
    <li><strong>Teléfono:</strong> <?= htmlspecialchars($student['phone'] ?? '') ?></li>
    <!-- Agrega más campos según tu estructura -->
</ul> 