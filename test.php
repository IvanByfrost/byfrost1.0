<?php
echo "¡Apache está funcionando correctamente!";
echo "<br>";
echo "Directorio actual: " . __DIR__;
echo "<br>";
echo "URL base: " . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
echo "<br>";
echo "Puerto: " . ($_SERVER['SERVER_PORT'] ?? 'No especificado');
?> 