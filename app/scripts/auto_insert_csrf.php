<?php
/**
 * Auto Insert CSRF - ByFrost
 * Inserta automáticamente el campo CSRF en todos los formularios de las vistas PHP y HTML
 */

$viewDirs = [
    'app/views/',
    'app/',
];

function insertCSRFToken($file) {
    $content = file_get_contents($file);
    $changed = false;

    // Buscar todos los formularios <form ...>
    $pattern = '/<form[^>]*>/i';
    if (preg_match_all($pattern, $content, $matches, PREG_OFFSET_CAPTURE)) {
        $offset = 0;
        foreach ($matches[0] as $match) {
            $formTag = $match[0];
            $pos = $match[1] + $offset;
            // Verificar si ya tiene un campo csrf_token
            $formEnd = strpos($content, '</form>', $pos);
            if ($formEnd !== false) {
                $formContent = substr($content, $pos, $formEnd - $pos);
                if (strpos($formContent, 'csrf_token') === false) {
                    // Insertar el campo CSRF justo después del <form ...>
                    $csrfField = "\n    <input type=\"hidden\" name=\"csrf_token\" value='<?= Validator::generateCSRFToken() ?>'>\n";
                    $insertPos = $pos + strlen($formTag);
                    $content = substr_replace($content, $csrfField, $insertPos, 0);
                    $changed = true;
                    $offset += strlen($csrfField);
                }
            }
        }
    }
    if ($changed) {
        file_put_contents($file, $content);
        echo "✅ CSRF token insertado en: $file\n";
    }
}

function processDir($dir) {
    $rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    foreach ($rii as $file) {
        if ($file->isDir()) continue;
        $ext = strtolower(pathinfo($file->getFilename(), PATHINFO_EXTENSION));
        if (in_array($ext, ['php', 'html'])) {
            insertCSRFToken($file->getPathname());
        }
    }
}

foreach ($viewDirs as $dir) {
    if (is_dir($dir)) {
        processDir($dir);
    }
}

echo "\n🎉 Inserción automática de tokens CSRF completada.\n";
?> 