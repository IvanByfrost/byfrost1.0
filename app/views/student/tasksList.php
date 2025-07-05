<h2>Tareas</h2>
<a href="?controller=student&action=createTask&studentId=<?= urlencode($_GET['studentId']) ?>">Nueva Tarea</a>
<ul>
<?php foreach ($tasks as $task): ?>
    <li>
        <strong><?= htmlspecialchars($task['title']) ?></strong> (<?= htmlspecialchars($task['status']) ?>, <?= htmlspecialchars($task['due_date']) ?>)
        <a href="?controller=student&action=editTask&taskId=<?= $task['id'] ?>">Editar</a>
        <a href="?controller=student&action=deleteTask&taskId=<?= $task['id'] ?>" onclick="return confirm('¿Eliminar tarea?')">Eliminar</a>
    </li>
<?php endforeach; ?>
</ul> 