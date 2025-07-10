<?php
/**
 * Error Diagnostic - ByFrost
 * Diagnóstico específico de errores del sistema
 */

// Configuración de base de datos
$host = 'localhost';
$dbname = 'byfrost_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Conexión a la base de datos establecida\n\n";
} catch (PDOException $e) {
    echo "❌ Error de conexión: " . $e->getMessage() . "\n";
    exit(1);
}

class ErrorDiagnostic {
    private $pdo;
    private $errors = [];
    private $warnings = [];
    private $solutions = [];
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    /**
     * Ejecutar diagnóstico completo de errores
     */
    public function runDiagnostic() {
        echo "🔍 Iniciando diagnóstico de errores ByFrost...\n\n";
        
        $this->checkPHPSyntaxErrors();
        $this->checkDatabaseErrors();
        $this->checkFileErrors();
        $this->checkRoutingErrors();
        $this->checkSecurityErrors();
        $this->checkJavaScriptErrors();
        $this->checkIncludeErrors();
        $this->checkPermissionErrors();
        $this->checkConfigurationErrors();
        $this->generateErrorReport();
    }
    
    /**
     * Verificar errores de sintaxis PHP
     */
    private function checkPHPSyntaxErrors() {
        echo "🔧 Verificando errores de sintaxis PHP...\n";
        
        $phpFiles = glob('app/**/*.php', GLOB_BRACE);
        $syntaxErrors = [];
        
        foreach ($phpFiles as $file) {
            $output = [];
            $returnCode = 0;
            
            // Verificar sintaxis PHP
            exec("php -l \"$file\" 2>&1", $output, $returnCode);
            
            if ($returnCode !== 0) {
                $syntaxErrors[] = [
                    'file' => $file,
                    'error' => implode("\n", $output)
                ];
            }
        }
        
        if (!empty($syntaxErrors)) {
            echo "   ❌ Errores de sintaxis encontrados: " . count($syntaxErrors) . "\n";
            foreach ($syntaxErrors as $error) {
                echo "      - {$error['file']}: {$error['error']}\n";
                $this->errors[] = "Error de sintaxis en {$error['file']}: {$error['error']}";
            }
        } else {
            echo "   ✅ No se encontraron errores de sintaxis PHP\n";
        }
    }
    
    /**
     * Verificar errores de base de datos
     */
    private function checkDatabaseErrors() {
        echo "🗄️ Verificando errores de base de datos...\n";
        
        $dbErrors = [];
        
        // Verificar conexión
        try {
            $stmt = $this->pdo->query("SELECT 1");
            $result = $stmt->fetch();
            if (!$result) {
                $dbErrors[] = "Conexión a base de datos falló";
            }
        } catch (PDOException $e) {
            $dbErrors[] = "Error de conexión: " . $e->getMessage();
        }
        
        // Verificar tablas críticas
        $criticalTables = ['users', 'schools', 'grades', 'subjects', 'user_roles'];
        foreach ($criticalTables as $table) {
            try {
                $stmt = $this->pdo->query("SHOW TABLES LIKE '$table'");
                if ($stmt->rowCount() === 0) {
                    $dbErrors[] = "Tabla crítica faltante: $table";
                }
            } catch (PDOException $e) {
                $dbErrors[] = "Error verificando tabla $table: " . $e->getMessage();
            }
        }
        
        // Verificar consultas comunes
        $commonQueries = [
            'SELECT * FROM users LIMIT 1' => 'Consulta básica de usuarios',
            'SELECT * FROM schools LIMIT 1' => 'Consulta básica de escuelas',
            'SELECT COUNT(*) FROM users' => 'Conteo de usuarios'
        ];
        
        foreach ($commonQueries as $query => $description) {
            try {
                $stmt = $this->pdo->query($query);
                $stmt->fetch();
            } catch (PDOException $e) {
                $dbErrors[] = "Error en $description: " . $e->getMessage();
            }
        }
        
        if (!empty($dbErrors)) {
            echo "   ❌ Errores de base de datos encontrados: " . count($dbErrors) . "\n";
            foreach ($dbErrors as $error) {
                echo "      - $error\n";
                $this->errors[] = $error;
            }
        } else {
            echo "   ✅ No se encontraron errores de base de datos\n";
        }
    }
    
    /**
     * Verificar errores de archivos
     */
    private function checkFileErrors() {
        echo "📁 Verificando errores de archivos...\n";
        
        $fileErrors = [];
        
        // Verificar archivos críticos
        $criticalFiles = [
            'config.php',
            'index.php',
            'app/scripts/connection.php',
            'app/library/Router.php',
            'app/scripts/routerView.php',
            'app/resources/js/loadView.js'
        ];
        
        foreach ($criticalFiles as $file) {
            if (!file_exists($file)) {
                $fileErrors[] = "Archivo crítico faltante: $file";
            } elseif (!is_readable($file)) {
                $fileErrors[] = "Archivo no legible: $file";
            }
        }
        
        // Verificar permisos de archivos
        $permissionErrors = [];
        foreach ($criticalFiles as $file) {
            if (file_exists($file)) {
                $perms = fileperms($file);
                $perms = substr(sprintf('%o', $perms), -4);
                
                if ($perms != '0644' && $perms != '0600') {
                    $permissionErrors[] = "$file ($perms)";
                }
            }
        }
        
        if (!empty($fileErrors)) {
            echo "   ❌ Errores de archivos encontrados: " . count($fileErrors) . "\n";
            foreach ($fileErrors as $error) {
                echo "      - $error\n";
                $this->errors[] = $error;
            }
        }
        
        if (!empty($permissionErrors)) {
            echo "   ⚠️  Problemas de permisos: " . count($permissionErrors) . "\n";
            foreach ($permissionErrors as $error) {
                echo "      - $error\n";
                $this->warnings[] = "Permisos incorrectos: $error";
            }
        }
        
        if (empty($fileErrors) && empty($permissionErrors)) {
            echo "   ✅ No se encontraron errores de archivos\n";
        }
    }
    
    /**
     * Verificar errores de routing
     */
    private function checkRoutingErrors() {
        echo "🛣️ Verificando errores de routing...\n";
        
        $routingErrors = [];
        
        // Verificar Router.php
        if (file_exists('app/library/Router.php')) {
            $routerContent = file_get_contents('app/library/Router.php');
            
            if (strpos($routerContent, 'class Router') === false) {
                $routingErrors[] = 'Clase Router no encontrada en Router.php';
            }
            
            if (strpos($routerContent, 'processRoute') === false) {
                $routingErrors[] = 'Método processRoute no encontrado en Router.php';
            }
        } else {
            $routingErrors[] = 'Router.php no encontrado';
        }
        
        // Verificar routerView.php
        if (file_exists('app/scripts/routerView.php')) {
            $routerViewContent = file_get_contents('app/scripts/routerView.php');
            
            if (strpos($routerViewContent, 'Router') === false) {
                $routingErrors[] = 'Router no referenciado en routerView.php';
            }
        } else {
            $routingErrors[] = 'routerView.php no encontrado';
        }
        
        // Verificar loadView.js
        if (file_exists('app/resources/js/loadView.js')) {
            $loadViewContent = file_get_contents('app/resources/js/loadView.js');
            
            if (strpos($loadViewContent, 'loadView') === false) {
                $routingErrors[] = 'Función loadView no encontrada en loadView.js';
            }
        } else {
            $routingErrors[] = 'loadView.js no encontrado';
        }
        
        if (!empty($routingErrors)) {
            echo "   ❌ Errores de routing encontrados: " . count($routingErrors) . "\n";
            foreach ($routingErrors as $error) {
                echo "      - $error\n";
                $this->errors[] = $error;
            }
        } else {
            echo "   ✅ No se encontraron errores de routing\n";
        }
    }
    
    /**
     * Verificar errores de seguridad
     */
    private function checkSecurityErrors() {
        echo "🔒 Verificando errores de seguridad...\n";
        
        $securityErrors = [];
        
        // Verificar archivos con información sensible
        $sensitiveFiles = ['config.php', 'app/scripts/connection.php'];
        foreach ($sensitiveFiles as $file) {
            if (file_exists($file)) {
                $perms = fileperms($file);
                $perms = substr(sprintf('%o', $perms), -4);
                
                if ($perms == '0666' || $perms == '0777') {
                    $securityErrors[] = "Permisos inseguros en $file ($perms)";
                }
            }
        }
        
        // Verificar variables no sanitizadas
        $phpFiles = glob('app/**/*.php', GLOB_BRACE);
        foreach ($phpFiles as $file) {
            $content = file_get_contents($file);
            
            // Buscar variables no sanitizadas
            if (preg_match('/\$_GET\[[^\]]+\]/', $content) && strpos($content, 'htmlspecialchars') === false) {
                $securityErrors[] = "Variable GET no sanitizada en $file";
            }
            
            if (preg_match('/\$_POST\[[^\]]+\]/', $content) && strpos($content, 'htmlspecialchars') === false) {
                $securityErrors[] = "Variable POST no sanitizada en $file";
            }
        }
        
        if (!empty($securityErrors)) {
            echo "   ❌ Errores de seguridad encontrados: " . count($securityErrors) . "\n";
            foreach ($securityErrors as $error) {
                echo "      - $error\n";
                $this->errors[] = $error;
            }
        } else {
            echo "   ✅ No se encontraron errores de seguridad\n";
        }
    }
    
    /**
     * Verificar errores de JavaScript
     */
    private function checkJavaScriptErrors() {
        echo "📜 Verificando errores de JavaScript...\n";
        
        $jsErrors = [];
        $jsFiles = glob('app/resources/js/*.js');
        
        foreach ($jsFiles as $file) {
            $content = file_get_contents($file);
            
            // Verificar sintaxis básica
            if (strpos($content, 'function') !== false) {
                // Verificar paréntesis balanceados
                $openParens = substr_count($content, '(');
                $closeParens = substr_count($content, ')');
                if ($openParens !== $closeParens) {
                    $jsErrors[] = "Paréntesis no balanceados en $file";
                }
                
                // Verificar llaves balanceadas
                $openBraces = substr_count($content, '{');
                $closeBraces = substr_count($content, '}');
                if ($openBraces !== $closeBraces) {
                    $jsErrors[] = "Llaves no balanceadas en $file";
                }
            }
        }
        
        if (!empty($jsErrors)) {
            echo "   ❌ Errores de JavaScript encontrados: " . count($jsErrors) . "\n";
            foreach ($jsErrors as $error) {
                echo "      - $error\n";
                $this->errors[] = $error;
            }
        } else {
            echo "   ✅ No se encontraron errores de JavaScript\n";
        }
    }
    
    /**
     * Verificar errores de includes
     */
    private function checkIncludeErrors() {
        echo "📦 Verificando errores de includes...\n";
        
        $includeErrors = [];
        $phpFiles = glob('app/**/*.php', GLOB_BRACE);
        
        foreach ($phpFiles as $file) {
            $content = file_get_contents($file);
            
            // Buscar includes y requires
            preg_match_all('/(include|require)(_once)?\s*\([\'"]([^\'"]+)[\'"]\)/', $content, $matches);
            
            foreach ($matches[3] as $include) {
                // Verificar si el archivo incluido existe
                $includePath = dirname($file) . '/' . $include;
                if (!file_exists($includePath)) {
                    $includeErrors[] = "Include faltante en $file: $include";
                }
            }
        }
        
        if (!empty($includeErrors)) {
            echo "   ❌ Errores de includes encontrados: " . count($includeErrors) . "\n";
            foreach ($includeErrors as $error) {
                echo "      - $error\n";
                $this->errors[] = $error;
            }
        } else {
            echo "   ✅ No se encontraron errores de includes\n";
        }
    }
    
    /**
     * Verificar errores de permisos
     */
    private function checkPermissionErrors() {
        echo "🔐 Verificando errores de permisos...\n";
        
        $permissionErrors = [];
        
        // Verificar directorios críticos
        $criticalDirs = ['app/cache', 'app/logs', 'app/uploads'];
        foreach ($criticalDirs as $dir) {
            if (!is_dir($dir)) {
                $permissionErrors[] = "Directorio faltante: $dir";
            } elseif (!is_writable($dir)) {
                $permissionErrors[] = "Directorio no escribible: $dir";
            }
        }
        
        // Verificar archivos críticos
        $criticalFiles = ['config.php', 'app/scripts/connection.php'];
        foreach ($criticalFiles as $file) {
            if (file_exists($file)) {
                if (!is_readable($file)) {
                    $permissionErrors[] = "Archivo no legible: $file";
                }
            }
        }
        
        if (!empty($permissionErrors)) {
            echo "   ❌ Errores de permisos encontrados: " . count($permissionErrors) . "\n";
            foreach ($permissionErrors as $error) {
                echo "      - $error\n";
                $this->errors[] = $error;
            }
        } else {
            echo "   ✅ No se encontraron errores de permisos\n";
        }
    }
    
    /**
     * Verificar errores de configuración
     */
    private function checkConfigurationErrors() {
        echo "⚙️ Verificando errores de configuración...\n";
        
        $configErrors = [];
        
        // Verificar config.php
        if (file_exists('config.php')) {
            $configContent = file_get_contents('config.php');
            
            // Verificar constantes críticas
            if (strpos($configContent, 'define(\'ROOT\'') === false) {
                $configErrors[] = 'Constante ROOT no definida en config.php';
            }
            
            if (strpos($configContent, 'define(\'url\'') === false) {
                $configErrors[] = 'Constante url no definida en config.php';
            }
        } else {
            $configErrors[] = 'config.php no encontrado';
        }
        
        // Verificar connection.php
        if (file_exists('app/scripts/connection.php')) {
            $connectionContent = file_get_contents('app/scripts/connection.php');
            
            if (strpos($connectionContent, 'function getConnection') === false) {
                $configErrors[] = 'Función getConnection no encontrada en connection.php';
            }
        } else {
            $configErrors[] = 'connection.php no encontrado';
        }
        
        if (!empty($configErrors)) {
            echo "   ❌ Errores de configuración encontrados: " . count($configErrors) . "\n";
            foreach ($configErrors as $error) {
                echo "      - $error\n";
                $this->errors[] = $error;
            }
        } else {
            echo "   ✅ No se encontraron errores de configuración\n";
        }
    }
    
    /**
     * Generar reporte de errores
     */
    private function generateErrorReport() {
        echo "\n" . str_repeat("=", 60) . "\n";
        echo "📋 REPORTE DE DIAGNÓSTICO DE ERRORES - BYFROST\n";
        echo str_repeat("=", 60) . "\n";
        
        // Resumen ejecutivo
        echo "\n🎯 RESUMEN EJECUTIVO\n";
        echo str_repeat("-", 30) . "\n";
        echo "❌ Errores críticos: " . count($this->errors) . "\n";
        echo "⚠️  Advertencias: " . count($this->warnings) . "\n";
        
        if (empty($this->errors) && empty($this->warnings)) {
            echo "✅ El sistema está libre de errores detectados\n";
        } else {
            echo "🔧 Se requieren correcciones\n";
        }
        
        // Errores críticos
        if (!empty($this->errors)) {
            echo "\n🚨 ERRORES CRÍTICOS\n";
            echo str_repeat("-", 30) . "\n";
            foreach ($this->errors as $i => $error) {
                echo ($i + 1) . ". $error\n";
            }
        }
        
        // Advertencias
        if (!empty($this->warnings)) {
            echo "\n⚠️  ADVERTENCIAS\n";
            echo str_repeat("-", 30) . "\n";
            foreach ($this->warnings as $i => $warning) {
                echo ($i + 1) . ". $warning\n";
            }
        }
        
        // Soluciones recomendadas
        echo "\n💡 SOLUCIONES RECOMENDADAS\n";
        echo str_repeat("-", 30) . "\n";
        
        if (!empty($this->errors)) {
            echo "1. 🔧 Ejecutar el optimizador automático:\n";
            echo "   php app/scripts/auto_optimizer.php\n\n";
            
            echo "2. 🧪 Ejecutar pruebas del sistema:\n";
            echo "   php app/scripts/system_test.php\n\n";
            
            echo "3. 📊 Analizar rendimiento:\n";
            echo "   php app/scripts/performance_analyzer.php\n\n";
        } else {
            echo "✅ El sistema está funcionando correctamente\n";
            echo "📈 Puedes ejecutar el monitor para ver métricas:\n";
            echo "   php app/scripts/system_monitor.php monitor\n";
        }
        
        echo "\n" . str_repeat("=", 60) . "\n";
        echo "🔍 Diagnóstico completado\n";
        echo str_repeat("=", 60) . "\n";
    }
}

// Ejecutar diagnóstico
echo "🔍 Iniciando diagnóstico de errores ByFrost...\n\n";

$diagnostic = new ErrorDiagnostic($pdo);
$diagnostic->runDiagnostic();

echo "\n🎉 Diagnóstico completado!\n";
?> 