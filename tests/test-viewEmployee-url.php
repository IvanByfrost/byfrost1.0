<?php
// Script para probar la URL exacta de viewEmployee
require_once '../config.php';

// Simular la URL que se está generando
$_GET['view'] = 'payroll';
$_GET['action'] = 'viewEmployee';
$_GET['id'] = '1';

echo "<h1>Prueba de URL viewEmployee</h1>";
echo "<p><strong>URL simulada:</strong> ?view=payroll&action=viewEmployee&id=1</p>";

// Incluir el router
require_once '../app/scripts/routerView.php';

echo "<p>Si llegaste aquí, el router procesó correctamente la URL.</p>";
?> 