<h2>Documentos Adjuntos</h2>
<ul>
<?php foreach ($documents as $doc): ?>
    <li><a href="<?= htmlspecialchars($doc['file_path']) ?>" target="_blank"> <?= htmlspecialchars($doc['file_name']) ?> </a> (<?= htmlspecialchars($doc['type']) ?>, <?= htmlspecialchars($doc['uploaded_at']) ?>)</li>
<?php endforeach; ?>
</ul> 