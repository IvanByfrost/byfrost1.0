<?php
/**
 * Validation Audit - ByFrost
 * Auditoría automática de validaciones y sanitización en controladores y procesos
 */

class ValidationAudit {
    private $issues = [];
    private $suggestions = [];
    private $criticalIssues = [];
    
    public function __construct() {
        echo "🔍 Iniciando auditoría de validaciones y sanitización...\n\n";
        
        $this->auditControllers();
        $this->auditProcesses();
        $this->generateAuditReport();
    }
    
    /**
     * Auditar controladores
     */
    private function auditControllers() {
        echo "📋 Auditing controllers...\n";
        
        $controllers = glob('app/controllers/*.php');
        
        foreach ($controllers as $controller) {
            $this->auditFile($controller, 'Controller');
        }
    }
    
    /**
     * Auditar procesos
     */
    private function auditProcesses() {
        echo "⚙️ Auditing processes...\n";
        
        $processes = glob('app/processes/*.php');
        
        foreach ($processes as $process) {
            $this->auditFile($process, 'Process');
        }
    }
    
    /**
     * Auditar archivo específico
     */
    private function auditFile($file, $type) {
        $content = file_get_contents($file);
        $filename = basename($file);
        $issues = [];
        
        echo "   📄 Analyzing: $filename\n";
        
        // Buscar variables $_GET, $_POST, $_REQUEST
        preg_match_all('/\$_GET\[([^\]]+)\]/', $content, $getMatches);
        preg_match_all('/\$_POST\[([^\]]+)\]/', $content, $postMatches);
        preg_match_all('/\$_REQUEST\[([^\]]+)\]/', $content, $requestMatches);
        
        // Verificar si están sanitizadas
        $unsanitizedGet = [];
        $unsanitizedPost = [];
        $unsanitizedRequest = [];
        
        foreach ($getMatches[1] as $var) {
            if (strpos($content, "htmlspecialchars(\$_GET[$var])") === false && 
                strpos($content, "filter_var(\$_GET[$var]") === false &&
                strpos($content, "strip_tags(\$_GET[$var]") === false) {
                $unsanitizedGet[] = $var;
            }
        }
        
        foreach ($postMatches[1] as $var) {
            if (strpos($content, "htmlspecialchars(\$_POST[$var])") === false && 
                strpos($content, "filter_var(\$_POST[$var]") === false &&
                strpos($content, "strip_tags(\$_POST[$var]") === false) {
                $unsanitizedPost[] = $var;
            }
        }
        
        foreach ($requestMatches[1] as $var) {
            if (strpos($content, "htmlspecialchars(\$_REQUEST[$var])") === false && 
                strpos($content, "filter_var(\$_REQUEST[$var]") === false &&
                strpos($content, "strip_tags(\$_REQUEST[$var]") === false) {
                $unsanitizedRequest[] = $var;
            }
        }
        
        // Buscar consultas SQL directas
        preg_match_all('/\$pdo->query\s*\(\s*[\'"]([^\'"]+)[\'"]/', $content, $sqlMatches);
        preg_match_all('/\$pdo->prepare\s*\(\s*[\'"]([^\'"]+)[\'"]/', $content, $prepareMatches);
        
        // Verificar si hay validaciones de tipo
        $hasTypeValidation = strpos($content, 'is_numeric') !== false || 
                           strpos($content, 'is_string') !== false ||
                           strpos($content, 'filter_var') !== false ||
                           strpos($content, 'preg_match') !== false;
        
        // Verificar si hay validaciones de longitud
        $hasLengthValidation = strpos($content, 'strlen') !== false || 
                             strpos($content, 'mb_strlen') !== false;
        
        // Verificar si hay validaciones de email
        $hasEmailValidation = strpos($content, 'filter_var') !== false && 
                            strpos($content, 'FILTER_VALIDATE_EMAIL') !== false;
        
        // Verificar si hay validaciones de fecha
        $hasDateValidation = strpos($content, 'strtotime') !== false || 
                           strpos($content, 'date') !== false;
        
        // Generar reporte para este archivo
        if (!empty($unsanitizedGet) || !empty($unsanitizedPost) || !empty($unsanitizedRequest)) {
            $issues[] = "Variables no sanitizadas encontradas";
            
            if (!empty($unsanitizedGet)) {
                $this->issues[] = "$filename: Variables GET no sanitizadas: " . implode(', ', $unsanitizedGet);
            }
            
            if (!empty($unsanitizedPost)) {
                $this->issues[] = "$filename: Variables POST no sanitizadas: " . implode(', ', $unsanitizedPost);
            }
            
            if (!empty($unsanitizedRequest)) {
                $this->issues[] = "$filename: Variables REQUEST no sanitizadas: " . implode(', ', $unsanitizedRequest);
            }
        }
        
        // Verificar consultas SQL
        if (!empty($sqlMatches[1])) {
            $this->criticalIssues[] = "$filename: Consultas SQL directas detectadas (riesgo de inyección SQL)";
        }
        
        // Generar sugerencias
        if (!$hasTypeValidation) {
            $this->suggestions[] = "$filename: Agregar validaciones de tipo de datos";
        }
        
        if (!$hasLengthValidation) {
            $this->suggestions[] = "$filename: Agregar validaciones de longitud";
        }
        
        if (!$hasEmailValidation && strpos($content, 'email') !== false) {
            $this->suggestions[] = "$filename: Agregar validación de formato de email";
        }
        
        if (!$hasDateValidation && (strpos($content, 'fecha') !== false || strpos($content, 'date') !== false)) {
            $this->suggestions[] = "$filename: Agregar validación de fechas";
        }
        
        // Verificar manejo de errores
        if (strpos($content, 'try') === false && strpos($content, 'catch') === false) {
            $this->suggestions[] = "$filename: Considerar agregar manejo de excepciones";
        }
        
        // Verificar validaciones de sesión
        if (strpos($content, 'session') !== false && strpos($content, 'isset($_SESSION') === false) {
            $this->suggestions[] = "$filename: Verificar validaciones de sesión";
        }
        
        // Verificar validaciones de permisos
        if (strpos($content, 'role') !== false || strpos($content, 'permission') !== false) {
            if (strpos($content, 'checkRole') === false && strpos($content, 'hasPermission') === false) {
                $this->suggestions[] = "$filename: Verificar validaciones de roles/permisos";
            }
        }
    }
    
    /**
     * Generar reporte de auditoría
     */
    private function generateAuditReport() {
        echo "\n" . str_repeat("=", 70) . "\n";
        echo "🔍 REPORTE DE AUDITORÍA DE VALIDACIONES - BYFROST\n";
        echo str_repeat("=", 70) . "\n";
        
        // Resumen ejecutivo
        echo "\n🎯 RESUMEN EJECUTIVO\n";
        echo str_repeat("-", 40) . "\n";
        echo "❌ Problemas críticos: " . count($this->criticalIssues) . "\n";
        echo "⚠️  Problemas de validación: " . count($this->issues) . "\n";
        echo "💡 Sugerencias de mejora: " . count($this->suggestions) . "\n";
        
        // Problemas críticos
        if (!empty($this->criticalIssues)) {
            echo "\n🚨 PROBLEMAS CRÍTICOS\n";
            echo str_repeat("-", 40) . "\n";
            foreach ($this->criticalIssues as $i => $issue) {
                echo ($i + 1) . ". $issue\n";
            }
        }
        
        // Problemas de validación
        if (!empty($this->issues)) {
            echo "\n⚠️  PROBLEMAS DE VALIDACIÓN\n";
            echo str_repeat("-", 40) . "\n";
            foreach ($this->issues as $i => $issue) {
                echo ($i + 1) . ". $issue\n";
            }
        }
        
        // Sugerencias
        if (!empty($this->suggestions)) {
            echo "\n💡 SUGERENCIAS DE MEJORA\n";
            echo str_repeat("-", 40) . "\n";
            foreach ($this->suggestions as $i => $suggestion) {
                echo ($i + 1) . ". $suggestion\n";
            }
        }
        
        // Recomendaciones específicas
        echo "\n🛠️  RECOMENDACIONES ESPECÍFICAS\n";
        echo str_repeat("-", 40) . "\n";
        echo "1. 🔒 Crear librería de validación centralizada:\n";
        echo "   - Validator.php con funciones isEmail(), isInt(), etc.\n";
        echo "   - Funciones de sanitización seguras\n";
        echo "   - Validaciones de formato y rango\n\n";
        
        echo "2. 🛡️ Implementar validaciones por tipo de dato:\n";
        echo "   - Emails: filter_var() con FILTER_VALIDATE_EMAIL\n";
        echo "   - Números: is_numeric() + rango\n";
        echo "   - Fechas: strtotime() + formato\n";
        echo "   - Strings: strlen() + caracteres permitidos\n\n";
        
        echo "3. 🔐 Reforzar seguridad en puntos críticos:\n";
        echo "   - Login/Registro: validación estricta\n";
        echo "   - Cambios de contraseña: verificación actual\n";
        echo "   - Datos sensibles: encriptación + validación\n";
        echo "   - Subida de archivos: tipo + tamaño + contenido\n\n";
        
        echo "4. ⚠️ Manejo de errores seguro:\n";
        echo "   - No mostrar detalles internos al usuario\n";
        echo "   - Logs de errores para debugging\n";
        echo "   - Mensajes de error claros y útiles\n\n";
        
        echo "5. 🧪 Implementar pruebas de validación:\n";
        echo "   - Casos de prueba para cada validación\n";
        echo "   - Pruebas de inyección SQL/XSS\n";
        echo "   - Validación de casos edge\n\n";
        
        echo "\n" . str_repeat("=", 70) . "\n";
        echo "🎯 Próximo paso: Implementar validaciones críticas\n";
        echo str_repeat("=", 70) . "\n";
    }
}

// Ejecutar auditoría
$audit = new ValidationAudit();

echo "\n🎉 Auditoría completada!\n";
?> 