<?php
echo "¡Test simple funcionando!";
echo "<br>";
echo "Directorio actual: " . __DIR__;
echo "<br>";
echo "Nombre del directorio: " . basename(__DIR__);
echo "<br>";
echo "URL completa: " . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
?> 