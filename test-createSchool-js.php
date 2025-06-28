<?php
/**
 * Script de prueba para verificar el JavaScript de creación de escuela
 */

// Configuración
define('ROOT', __DIR__);
require_once 'config.php';

echo "<!DOCTYPE html>";
echo "<html lang='es'>";
echo "<head>";
echo "<meta charset='UTF-8'>";
echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
echo "<title>Prueba JavaScript - Crear Escuela</title>";
echo "<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' rel='stylesheet'>";
echo "<link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css' rel='stylesheet'>";
echo "</head>";
echo "<body>";

echo "<div class='container mt-4'>";
echo "<h1>Prueba de JavaScript - Crear Escuela</h1>";
echo "<p>Verificando que el archivo JavaScript se cargue correctamente.</p>";

// Verificar si el archivo JavaScript existe
$jsPath = ROOT . '/app/resources/js/createSchool.js';
if (file_exists($jsPath)) {
    echo "<div class='alert alert-success'>";
    echo "<i class='fas fa-check-circle'></i> El archivo JavaScript existe en: " . $jsPath;
    echo "</div>";
    
    // Mostrar el contenido del archivo
    echo "<div class='card mt-3'>";
    echo "<div class='card-header'>";
    echo "<h5>Contenido del archivo createSchool.js</h5>";
    echo "</div>";
    echo "<div class='card-body'>";
    echo "<pre style='max-height: 300px; overflow-y: auto;'>";
    echo htmlspecialchars(file_get_contents($jsPath));
    echo "</pre>";
    echo "</div>";
    echo "</div>";
} else {
    echo "<div class='alert alert-danger'>";
    echo "<i class='fas fa-exclamation-triangle'></i> El archivo JavaScript NO existe en: " . $jsPath;
    echo "</div>";
}

// Verificar la URL base
echo "<div class='card mt-3'>";
echo "<div class='card-header'>";
echo "<h5>Configuración de URLs</h5>";
echo "</div>";
echo "<div class='card-body'>";
echo "<ul>";
echo "<li><strong>URL Base:</strong> " . url . "</li>";
echo "<li><strong>Ruta JS:</strong> " . url . "app/resources/js/createSchool.js</li>";
echo "<li><strong>Ruta completa:</strong> " . getBaseUrl() . "app/resources/js/createSchool.js</li>";
echo "</ul>";
echo "</div>";
echo "</div>";

// Formulario de prueba
echo "<div class='card mt-3'>";
echo "<div class='card-header'>";
echo "<h5>Formulario de Prueba</h5>";
echo "</div>";
echo "<div class='card-body'>";
echo "<form method='POST' id='createSchool' class='dash-form'>";
echo "<div class='row'>";
echo "<div class='col-md-6 mb-3'>";
echo "<label for='school_name' class='form-label'>Nombre de la Escuela *</label>";
echo "<input type='text' id='school_name' name='school_name' class='form-control' placeholder='Ej: Colegio San José' required>";
echo "</div>";
echo "<div class='col-md-6 mb-3'>";
echo "<label for='school_dane' class='form-label'>Código DANE *</label>";
echo "<input type='text' id='school_dane' name='school_dane' class='form-control' placeholder='Ej: 11100123456' required>";
echo "</div>";
echo "<div class='col-md-6 mb-3'>";
echo "<label for='school_document' class='form-label'>NIT *</label>";
echo "<input type='text' id='school_document' name='school_document' class='form-control' placeholder='Ej: 900123456-7' required>";
echo "</div>";
echo "<div class='col-md-6 mb-3'>";
echo "<label for='email' class='form-label'>Email</label>";
echo "<input type='email' id='email' name='email' class='form-control' placeholder='Ej: info@colegio.edu.co'>";
echo "</div>";
echo "</div>";
echo "<button type='submit' class='btn btn-primary'>";
echo "<i class='fas fa-save'></i> Probar Envío";
echo "</button>";
echo "</form>";
echo "</div>";
echo "</div>";

// Verificar carga de JavaScript
echo "<div class='card mt-3'>";
echo "<div class='card-header'>";
echo "<h5>Estado del JavaScript</h5>";
echo "</div>";
echo "<div class='card-body'>";
echo "<div id='js-status'>Verificando...</div>";
echo "</div>";
echo "</div>";

echo "</div>"; // container

// Incluir el JavaScript
echo "<script src='" . url . "app/resources/js/createSchool.js'></script>";
echo "<script>";
echo "document.addEventListener('DOMContentLoaded', function() {";
echo "    const statusDiv = document.getElementById('js-status');";
echo "    if (typeof validateForm === 'function') {";
echo "        statusDiv.innerHTML = '<div class=\"alert alert-success\"><i class=\"fas fa-check-circle\"></i> JavaScript cargado correctamente</div>';";
echo "    } else {";
echo "        statusDiv.innerHTML = '<div class=\"alert alert-danger\"><i class=\"fas fa-exclamation-triangle\"></i> Error: JavaScript no se cargó correctamente</div>';";
echo "    }";
echo "});";
echo "</script>";

echo "<script src='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js'></script>";
echo "</body>";
echo "</html>";
?> 