<h2>Documentos Adjuntos</h2>
<a href="?controller=student&action=createDocument&studentId=<?= urlencode($_GET['studentId']) ?>">Nuevo Documento</a>
<ul>
<?php foreach ($documents as $doc): ?>
    <li>
        <a href="<?= htmlspecialchars($doc['file_path']) ?>" target="_blank"> <?= htmlspecialchars($doc['file_name']) ?> </a> (<?= htmlspecialchars($doc['type']) ?>, <?= htmlspecialchars($doc['uploaded_at']) ?>)
        <a href="?controller=student&action=editDocument&documentId=<?= $doc['id'] ?>">Editar</a>
        <a href="?controller=student&action=deleteDocument&documentId=<?= $doc['id'] ?>" onclick="return confirm('¿Eliminar documento?')">Eliminar</a>
    </li>
<?php endforeach; ?>
</ul> 