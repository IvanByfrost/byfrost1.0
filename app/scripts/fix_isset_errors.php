<?php
/**
 * Fix isset() Errors - ByFrost
 * Corrige automáticamente todos los usos incorrectos de isset() sobre el resultado de una expresión
 */

class IssetErrorFixer {
    private $fixed = 0;
    private $errors = [];
    
    public function __construct() {
        echo "🔧 Corrigiendo errores de isset()...\n\n";
        
        $this->fixControllers();
        $this->fixProcesses();
        $this->fixLibraries();
        $this->generateReport();
    }
    
    /**
     * Corregir controladores
     */
    private function fixControllers() {
        echo "📋 Corrigiendo controladores...\n";
        
        $controllers = glob('app/controllers/*.php');
        
        foreach ($controllers as $controller) {
            $this->fixFile($controller);
        }
    }
    
    /**
     * Corregir procesos
     */
    private function fixProcesses() {
        echo "⚙️ Corrigiendo procesos...\n";
        
        $processes = glob('app/processes/*.php');
        
        foreach ($processes as $process) {
            $this->fixFile($process);
        }
    }
    
    /**
     * Corregir librerías
     */
    private function fixLibraries() {
        echo "📚 Corrigiendo librerías...\n";
        
        $libraries = glob('app/library/*.php');
        
        foreach ($libraries as $library) {
            $this->fixFile($library);
        }
    }
    
    /**
     * Corregir archivo específico
     */
    private function fixFile($file) {
        $content = file_get_contents($file);
        $filename = basename($file);
        $originalContent = $content;
        
        echo "   📄 Processing: $filename\n";
        
        // Corregir isset(htmlspecialchars(...))
        $content = preg_replace(
            '/isset\s*\(\s*htmlspecialchars\s*\(\s*\$([^)]+)\s*\)\s*\)/',
            'isset($1) && htmlspecialchars($1)',
            $content
        );
        
        // Corregir isset(filter_var(...))
        $content = preg_replace(
            '/isset\s*\(\s*filter_var\s*\(\s*\$([^)]+)\s*\)\s*\)/',
            'isset($1) && filter_var($1)',
            $content
        );
        
        // Corregir isset(strip_tags(...))
        $content = preg_replace(
            '/isset\s*\(\s*strip_tags\s*\(\s*\$([^)]+)\s*\)\s*\)/',
            'isset($1) && strip_tags($1)',
            $content
        );
        
        // Corregir isset(trim(...))
        $content = preg_replace(
            '/isset\s*\(\s*trim\s*\(\s*\$([^)]+)\s*\)\s*\)/',
            'isset($1) && trim($1)',
            $content
        );
        
        // Corregir isset($_POST[...]) y isset($_GET[...]) con htmlspecialchars
        $content = preg_replace(
            '/isset\s*\(\s*htmlspecialchars\s*\(\s*\$_(POST|GET)\s*\[([^\]]+)\]\s*\)\s*\)/',
            'isset($_$1[$2]) && htmlspecialchars($_$1[$2])',
            $content
        );
        
        // Corregir isset($_REQUEST[...]) con htmlspecialchars
        $content = preg_replace(
            '/isset\s*\(\s*htmlspecialchars\s*\(\s*\$_(REQUEST)\s*\[([^\]]+)\]\s*\)\s*\)/',
            'isset($_$1[$2]) && htmlspecialchars($_$1[$2])',
            $content
        );
        
        // Agregar session_start() seguro al inicio de las clases
        if (strpos($content, 'class ') !== false && strpos($content, 'session_start()') === false) {
            $content = preg_replace(
                '/(public function __construct\s*\([^)]*\)\s*\{)/',
                '$1
        // Asegurar que session_start() solo se llame una vez y antes de cualquier salida
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }',
                $content
            );
        }
        
        if ($content !== $originalContent) {
            if (file_put_contents($file, $content)) {
                echo "   ✅ Corregido: $filename\n";
                $this->fixed++;
            } else {
                $this->errors[] = "Error corrigiendo $filename";
            }
        }
    }
    
    /**
     * Generar reporte
     */
    private function generateReport() {
        echo "\n" . str_repeat("=", 60) . "\n";
        echo "🔧 REPORTE DE CORRECCIÓN DE ERRORES ISSET()\n";
        echo str_repeat("=", 60) . "\n";
        
        echo "\n✅ CORRECCIONES REALIZADAS\n";
        echo str_repeat("-", 40) . "\n";
        echo "🔧 Archivos corregidos: $this->fixed\n";
        echo "❌ Errores encontrados: " . count($this->errors) . "\n";
        
        if (!empty($this->errors)) {
            echo "\n❌ ERRORES\n";
            echo str_repeat("-", 40) . "\n";
            foreach ($this->errors as $error) {
                echo "- $error\n";
            }
        }
        
        echo "\n🛠️ CORRECCIONES APLICADAS\n";
        echo str_repeat("-", 40) . "\n";
        echo "✅ isset(htmlspecialchars(...)) → isset(...) && htmlspecialchars(...)\n";
        echo "✅ isset(filter_var(...)) → isset(...) && filter_var(...)\n";
        echo "✅ isset(strip_tags(...)) → isset(...) && strip_tags(...)\n";
        echo "✅ isset(trim(...)) → isset(...) && trim(...)\n";
        echo "✅ session_start() seguro agregado a constructores\n";
        
        echo "\n💡 PRÓXIMOS PASOS\n";
        echo str_repeat("-", 40) . "\n";
        echo "1. 🧪 Ejecutar pruebas de validación nuevamente\n";
        echo "2. 📝 Verificar que no hay errores de sintaxis\n";
        echo "3. 🔄 Probar flujos principales del sistema\n";
        
        echo "\n" . str_repeat("=", 60) . "\n";
        echo "🎉 ¡Corrección de errores isset() completada!\n";
        echo str_repeat("=", 60) . "\n";
    }
}

// Ejecutar corrección
$fixer = new IssetErrorFixer();

echo "\n🎉 ¡Corrección completada!\n";
?> 