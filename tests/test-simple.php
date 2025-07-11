<?php
echo "=== TEST SIMPLE PHP ===\n";
echo "PHP funciona correctamente\n";
echo "Directorio actual: " . getcwd() . "\n";
echo "Archivos en el directorio:\n";

$files = scandir('.');
foreach ($files as $file) {
    if ($file != '.' && $file != '..') {
        echo "- $file\n";
    }
}

echo "\n=== FIN TEST ===\n";
?> 