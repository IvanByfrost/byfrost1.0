<?php
/**
 * Error Fixer - ByFrost
 * Corrige automáticamente los errores identificados
 */

class ErrorFixer {
    private $fixed = 0;
    private $errors = [];
    
    public function __construct() {
        // Crear directorios faltantes
        $this->createMissingDirectories();
        
        // Corregir permisos
        $this->fixPermissions();
        
        // Corregir variables no sanitizadas
        $this->fixUnsanitizedVariables();
        
        // Corregir configuración
        $this->fixConfiguration();
        
        // Generar reporte
        $this->generateReport();
    }
    
    /**
     * Crear directorios faltantes
     */
    private function createMissingDirectories() {
        echo "📁 Creando directorios faltantes...\n";
        
        $directories = [
            'app/cache',
            'app/logs',
            'app/uploads',
            'app/cache/views',
            'app/cache/database',
            'app/logs/errors',
            'app/logs/access',
            'app/uploads/images',
            'app/uploads/documents'
        ];
        
        foreach ($directories as $dir) {
            if (!is_dir($dir)) {
                if (mkdir($dir, 0755, true)) {
                    echo "   ✅ Directorio creado: $dir\n";
                    $this->fixed++;
                } else {
                    $this->errors[] = "Error creando directorio: $dir";
                }
            }
        }
    }
    
    /**
     * Corregir permisos de archivos
     */
    private function fixPermissions() {
        echo "🔐 Corrigiendo permisos de archivos...\n";
        
        $criticalFiles = [
            'config.php',
            'index.php',
            'app/scripts/connection.php',
            'app/library/Router.php',
            'app/scripts/routerView.php',
            'app/resources/js/loadView.js'
        ];
        
        foreach ($criticalFiles as $file) {
            if (file_exists($file)) {
                if (chmod($file, 0644)) {
                    echo "   ✅ Permisos corregidos: $file\n";
                    $this->fixed++;
                } else {
                    $this->errors[] = "Error corrigiendo permisos: $file";
                }
            }
        }
    }
    
    /**
     * Corregir variables no sanitizadas
     */
    private function fixUnsanitizedVariables() {
        echo "🛡️ Corrigiendo variables no sanitizadas...\n";
        
        $phpFiles = glob('app/**/*.php', GLOB_BRACE);
        
        foreach ($phpFiles as $file) {
            $content = file_get_contents($file);
            $originalContent = $content;
            
            // Reemplazar $_GET no sanitizado
            $content = preg_replace('/\$_GET\[([^\]]+)\]/', 'htmlspecialchars(htmlspecialchars($_GET[$1]))', $content);
            
            // Reemplazar $_POST no sanitizado
            $content = preg_replace('/\$_POST\[([^\]]+)\]/', 'htmlspecialchars(htmlspecialchars($_POST[$1]))', $content);
            
            // Reemplazar $_REQUEST no sanitizado
            $content = preg_replace('/\$_REQUEST\[([^\]]+)\]/', 'htmlspecialchars(htmlspecialchars($_REQUEST[$1]))', $content);
            
            if ($content !== $originalContent) {
                if (file_put_contents($file, $content)) {
                    echo "   ✅ Variables sanitizadas en: " . basename($file) . "\n";
                    $this->fixed++;
                } else {
                    $this->errors[] = "Error sanitizando variables en $file";
                }
            }
        }
    }
    
    /**
     * Corregir configuración
     */
    private function fixConfiguration() {
        echo "⚙️ Corrigiendo configuración...\n";
        
        // Corregir config.php
        if (file_exists('config.php')) {
            $configContent = file_get_contents('config.php');
            
            // Agregar constante url si no existe
            if (strpos($configContent, 'define(\'url\'') === false) {
                $configContent = str_replace(
                    'define(\'ROOT\'',
                    "define('url', 'http://localhost:8000/');\ndefine('ROOT'",
                    $configContent
                );
                
                if (file_put_contents('config.php', $configContent)) {
                    echo "   ✅ Constante url agregada a config.php\n";
                    $this->fixed++;
                } else {
                    $this->errors[] = "Error agregando constante url a config.php";
                }
            }
        }
        
        // Verificar connection.php
        if (file_exists('app/scripts/connection.php')) {
            $connectionContent = file_get_contents('app/scripts/connection.php');
            
            if (strpos($connectionContent, 'function getConnection') === false) {
                // Crear función getConnection si no existe
                $newFunction = "
function getConnection() {
    global \$host, \$dbname, \$username, \$password;
    
    try {
        \$pdo = new PDO(\"mysql:host=\$host;dbname=\$dbname;charset=utf8mb4\", \$username, \$password);
        \$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return \$pdo;
    } catch (PDOException \$e) {
        die(\"Error de conexión: \" . \$e->getMessage());
    }
}
";
                
                $connectionContent .= $newFunction;
                
                if (file_put_contents('app/scripts/connection.php', $connectionContent)) {
                    echo "   ✅ Función getConnection agregada a connection.php\n";
                    $this->fixed++;
                } else {
                    $this->errors[] = "Error agregando función getConnection a connection.php";
                }
            }
        }
    }
    
    /**
     * Generar reporte de correcciones
     */
    private function generateReport() {
        echo "\n" . str_repeat("=", 60) . "\n";
        echo "🔧 REPORTE DE CORRECCIÓN DE ERRORES - BYFROST\n";
        echo str_repeat("=", 60) . "\n";
        
        echo "\n✅ CORRECCIONES REALIZADAS\n";
        echo str_repeat("-", 30) . "\n";
        echo "🔧 Problemas corregidos: $this->fixed\n";
        
        if (!empty($this->errors)) {
            echo "\n❌ ERRORES ENCONTRADOS\n";
            echo str_repeat("-", 30) . "\n";
            foreach ($this->errors as $error) {
                echo "   - $error\n";
            }
        }
        
        echo "\n💡 PRÓXIMOS PASOS\n";
        echo str_repeat("-", 30) . "\n";
        echo "1. 🔍 Ejecutar diagnóstico nuevamente:\n";
        echo "   php app/scripts/error_diagnostic_standalone.php\n\n";
        
        echo "2. 🧪 Ejecutar pruebas del sistema:\n";
        echo "   php app/scripts/system_test.php\n\n";
        
        echo "3. 📊 Verificar rendimiento:\n";
        echo "   php app/scripts/performance_analyzer.php\n\n";
        
        echo "4. 🗄️ Configurar base de datos:\n";
        echo "   - Iniciar MySQL en XAMPP\n";
        echo "   - Ejecutar: php app/scripts/setup_clean_database.php\n\n";
        
        echo "\n" . str_repeat("=", 60) . "\n";
        echo "🎉 Corrección completada\n";
        echo str_repeat("=", 60) . "\n";
    }
}

// Ejecutar corrección
echo "🔧 Iniciando corrección automática de errores...\n\n";

$fixer = new ErrorFixer();

echo "\n🎉 Corrección completada!\n";
?> 