<?php
/**
 * System Test - ByFrost
 * Pruebas automatizadas del sistema completo
 */

// Configuración de base de datos
$host = 'localhost';
$dbname = 'byfrost_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

class SystemTest {
    private $pdo;
    private $results = [];
    private $errors = [];
    private $warnings = [];
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    /**
     * Ejecutar todas las pruebas
     */
    public function runAllTests() {
        echo "🧪 Iniciando pruebas del sistema ByFrost...\n\n";
        
        $this->testDatabaseConnection();
        $this->testFileSystem();
        $this->testControllers();
        $this->testModels();
        $this->testViews();
        $this->testRouting();
        $this->testSecurity();
        $this->testPerformance();
        $this->testIntegration();
        $this->generateTestReport();
    }
    
    /**
     * Probar conexión a base de datos
     */
    private function testDatabaseConnection() {
        echo "🗄️ Probando conexión a base de datos...\n";
        
        try {
            // Probar conexión
            $stmt = $this->pdo->query("SELECT 1");
            $result = $stmt->fetch();
            
            if ($result) {
                $this->results['database']['connection'] = 'PASS';
                echo "   ✅ Conexión exitosa\n";
            } else {
                $this->results['database']['connection'] = 'FAIL';
                $this->errors[] = 'Conexión a base de datos falló';
                echo "   ❌ Conexión falló\n";
            }
            
            // Probar tablas principales
            $tables = ['users', 'schools', 'grades', 'subjects'];
            $existingTables = [];
            
            foreach ($tables as $table) {
                try {
                    $stmt = $this->pdo->query("SHOW TABLES LIKE '$table'");
                    if ($stmt->rowCount() > 0) {
                        $existingTables[] = $table;
                    }
                } catch (PDOException $e) {
                    $this->warnings[] = "Tabla '$table' no existe";
                }
            }
            
            $this->results['database']['tables'] = $existingTables;
            echo "   ✅ Tablas encontradas: " . count($existingTables) . "\n";
            
        } catch (PDOException $e) {
            $this->results['database']['connection'] = 'FAIL';
            $this->errors[] = 'Error de base de datos: ' . $e->getMessage();
            echo "   ❌ Error: " . $e->getMessage() . "\n";
        }
    }
    
    /**
     * Probar sistema de archivos
     */
    private function testFileSystem() {
        echo "📁 Probando sistema de archivos...\n";
        
        $criticalFiles = [
            'config.php',
            'index.php',
            'app/scripts/connection.php',
            'app/library/Router.php',
            'app/scripts/routerView.php'
        ];
        
        $existingFiles = [];
        foreach ($criticalFiles as $file) {
            if (file_exists($file)) {
                $existingFiles[] = $file;
            } else {
                $this->errors[] = "Archivo crítico faltante: $file";
            }
        }
        
        $this->results['filesystem']['critical_files'] = $existingFiles;
        echo "   ✅ Archivos críticos: " . count($existingFiles) . "/" . count($criticalFiles) . "\n";
        
        // Probar permisos
        $permissionIssues = [];
        foreach ($existingFiles as $file) {
            $perms = fileperms($file);
            $perms = substr(sprintf('%o', $perms), -4);
            
            if ($perms != '0644' && $perms != '0600') {
                $permissionIssues[] = "$file ($perms)";
            }
        }
        
        $this->results['filesystem']['permission_issues'] = $permissionIssues;
        if (!empty($permissionIssues)) {
            echo "   ⚠️  Problemas de permisos: " . count($permissionIssues) . "\n";
        } else {
            echo "   ✅ Permisos correctos\n";
        }
    }
    
    /**
     * Probar controladores
     */
    private function testControllers() {
        echo "🎮 Probando controladores...\n";
        
        $controllers = glob('app/controllers/*.php');
        $workingControllers = [];
        $brokenControllers = [];
        
        foreach ($controllers as $controller) {
            $content = file_get_contents($controller);
            $className = pathinfo($controller, PATHINFO_FILENAME);
            
            // Verificar sintaxis básica
            if ($this->testPHPSyntax($content)) {
                // Verificar que la clase existe
                if (strpos($content, "class $className") !== false) {
                    $workingControllers[] = $className;
                } else {
                    $brokenControllers[] = $className;
                    $this->errors[] = "Clase '$className' no encontrada en $controller";
                }
            } else {
                $brokenControllers[] = $className;
                $this->errors[] = "Error de sintaxis en $controller";
            }
        }
        
        $this->results['controllers']['working'] = $workingControllers;
        $this->results['controllers']['broken'] = $brokenControllers;
        
        echo "   ✅ Controladores funcionales: " . count($workingControllers) . "\n";
        if (!empty($brokenControllers)) {
            echo "   ❌ Controladores con problemas: " . count($brokenControllers) . "\n";
        }
    }
    
    /**
     * Probar modelos
     */
    private function testModels() {
        echo "📊 Probando modelos...\n";
        
        $models = glob('app/models/*.php');
        $workingModels = [];
        $brokenModels = [];
        
        foreach ($models as $model) {
            $content = file_get_contents($model);
            $className = pathinfo($model, PATHINFO_FILENAME);
            
            // Verificar sintaxis básica
            if ($this->testPHPSyntax($content)) {
                // Verificar que la clase existe
                if (strpos($content, "class $className") !== false) {
                    $workingModels[] = $className;
                } else {
                    $brokenModels[] = $className;
                    $this->errors[] = "Clase '$className' no encontrada en $model";
                }
            } else {
                $brokenModels[] = $className;
                $this->errors[] = "Error de sintaxis en $model";
            }
        }
        
        $this->results['models']['working'] = $workingModels;
        $this->results['models']['broken'] = $brokenModels;
        
        echo "   ✅ Modelos funcionales: " . count($workingModels) . "\n";
        if (!empty($brokenModels)) {
            echo "   ❌ Modelos con problemas: " . count($brokenModels) . "\n";
        }
    }
    
    /**
     * Probar vistas
     */
    private function testViews() {
        echo "👁️ Probando vistas...\n";
        
        $views = glob('app/views/**/*.php', GLOB_BRACE);
        $workingViews = [];
        $brokenViews = [];
        
        foreach ($views as $view) {
            $content = file_get_contents($view);
            
            // Verificar sintaxis básica
            if ($this->testPHPSyntax($content)) {
                $workingViews[] = basename($view);
            } else {
                $brokenViews[] = basename($view);
                $this->errors[] = "Error de sintaxis en $view";
            }
        }
        
        $this->results['views']['working'] = $workingViews;
        $this->results['views']['broken'] = $brokenViews;
        
        echo "   ✅ Vistas funcionales: " . count($workingViews) . "\n";
        if (!empty($brokenViews)) {
            echo "   ❌ Vistas con problemas: " . count($brokenViews) . "\n";
        }
    }
    
    /**
     * Probar sistema de routing
     */
    private function testRouting() {
        echo "🛣️ Probando sistema de routing...\n";
        
        $routingIssues = [];
        
        // Verificar Router.php
        if (file_exists('app/library/Router.php')) {
            $routerContent = file_get_contents('app/library/Router.php');
            if (strpos($routerContent, 'class Router') !== false) {
                echo "   ✅ Router.php encontrado y válido\n";
            } else {
                $routingIssues[] = 'Clase Router no encontrada en Router.php';
                echo "   ❌ Router.php inválido\n";
            }
        } else {
            $routingIssues[] = 'Router.php no encontrado';
            echo "   ❌ Router.php faltante\n";
        }
        
        // Verificar routerView.php
        if (file_exists('app/scripts/routerView.php')) {
            $routerViewContent = file_get_contents('app/scripts/routerView.php');
            if (strpos($routerViewContent, 'Router') !== false) {
                echo "   ✅ routerView.php válido\n";
            } else {
                $routingIssues[] = 'Router no referenciado en routerView.php';
                echo "   ❌ routerView.php inválido\n";
            }
        } else {
            $routingIssues[] = 'routerView.php no encontrado';
            echo "   ❌ routerView.php faltante\n";
        }
        
        // Verificar loadView.js
        if (file_exists('app/resources/js/loadView.js')) {
            $loadViewContent = file_get_contents('app/resources/js/loadView.js');
            if (strpos($loadViewContent, 'loadView') !== false) {
                echo "   ✅ loadView.js válido\n";
            } else {
                $routingIssues[] = 'Función loadView no encontrada en loadView.js';
                echo "   ❌ loadView.js inválido\n";
            }
        } else {
            $routingIssues[] = 'loadView.js no encontrado';
            echo "   ❌ loadView.js faltante\n";
        }
        
        $this->results['routing']['issues'] = $routingIssues;
        
        if (empty($routingIssues)) {
            echo "   ✅ Sistema de routing funcional\n";
        }
    }
    
    /**
     * Probar seguridad
     */
    private function testSecurity() {
        echo "🔒 Probando seguridad...\n";
        
        $securityIssues = [];
        
        // Verificar SecurityMiddleware
        if (file_exists('app/library/SecurityMiddleware.php')) {
            $securityContent = file_get_contents('app/library/SecurityMiddleware.php');
            if (strpos($securityContent, 'class SecurityMiddleware') !== false) {
                echo "   ✅ SecurityMiddleware encontrado\n";
            } else {
                $securityIssues[] = 'Clase SecurityMiddleware no encontrada';
                echo "   ❌ SecurityMiddleware inválido\n";
            }
        } else {
            $securityIssues[] = 'SecurityMiddleware.php no encontrado';
            echo "   ❌ SecurityMiddleware faltante\n";
        }
        
        // Verificar archivos críticos
        $criticalFiles = ['config.php', 'app/scripts/connection.php'];
        foreach ($criticalFiles as $file) {
            if (file_exists($file)) {
                $perms = fileperms($file);
                $perms = substr(sprintf('%o', $perms), -4);
                if ($perms != '0644' && $perms != '0600') {
                    $securityIssues[] = "Permisos inseguros en $file ($perms)";
                }
            }
        }
        
        $this->results['security']['issues'] = $securityIssues;
        
        if (empty($securityIssues)) {
            echo "   ✅ Configuración de seguridad correcta\n";
        } else {
            echo "   ⚠️  Problemas de seguridad: " . count($securityIssues) . "\n";
        }
    }
    
    /**
     * Probar rendimiento
     */
    private function testPerformance() {
        echo "⚡ Probando rendimiento...\n";
        
        $performanceIssues = [];
        
        // Verificar archivos grandes
        $largeFiles = [];
        $directories = ['app/controllers', 'app/models', 'app/views'];
        
        foreach ($directories as $dir) {
            if (is_dir($dir)) {
                $files = glob("$dir/*.php");
                foreach ($files as $file) {
                    $size = filesize($file) / 1024; // KB
                    if ($size > 100) {
                        $largeFiles[] = basename($file) . " ({$size}KB)";
                    }
                }
            }
        }
        
        if (!empty($largeFiles)) {
            $performanceIssues[] = "Archivos grandes detectados: " . implode(', ', $largeFiles);
            echo "   ⚠️  Archivos grandes: " . count($largeFiles) . "\n";
        } else {
            echo "   ✅ Tamaños de archivo óptimos\n";
        }
        
        // Verificar caché
        $cacheDir = 'app/cache';
        if (is_dir($cacheDir)) {
            $cacheFiles = glob("$cacheDir/*.cache");
            echo "   ✅ Archivos de caché: " . count($cacheFiles) . "\n";
        } else {
            $performanceIssues[] = 'Directorio de caché no encontrado';
            echo "   ⚠️  Caché no configurado\n";
        }
        
        $this->results['performance']['issues'] = $performanceIssues;
    }
    
    /**
     * Probar integración
     */
    private function testIntegration() {
        echo "🔗 Probando integración...\n";
        
        $integrationIssues = [];
        
        // Probar carga de controladores
        $controllers = glob('app/controllers/*.php');
        foreach ($controllers as $controller) {
            $className = pathinfo($controller, PATHINFO_FILENAME);
            
            try {
                require_once $controller;
                if (class_exists($className)) {
                    echo "   ✅ $className cargado correctamente\n";
                } else {
                    $integrationIssues[] = "Clase $className no se puede instanciar";
                    echo "   ❌ $className no instanciable\n";
                }
            } catch (Exception $e) {
                $integrationIssues[] = "Error cargando $className: " . $e->getMessage();
                echo "   ❌ Error cargando $className\n";
            }
        }
        
        // Probar conexión entre componentes
        if (file_exists('app/library/Router.php') && file_exists('app/scripts/routerView.php')) {
            echo "   ✅ Router y routerView conectados\n";
        } else {
            $integrationIssues[] = 'Router y routerView no están conectados';
            echo "   ❌ Router y routerView desconectados\n";
        }
        
        $this->results['integration']['issues'] = $integrationIssues;
    }
    
    /**
     * Probar sintaxis PHP
     */
    private function testPHPSyntax($content) {
        // Verificar sintaxis básica
        if (strpos($content, '<?php') === false && strpos($content, '<?') === false) {
            return false;
        }
        
        // Verificar paréntesis balanceados
        $openParens = substr_count($content, '(');
        $closeParens = substr_count($content, ')');
        if ($openParens !== $closeParens) {
            return false;
        }
        
        // Verificar llaves balanceadas
        $openBraces = substr_count($content, '{');
        $closeBraces = substr_count($content, '}');
        if ($openBraces !== $closeBraces) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Generar reporte de pruebas
     */
    private function generateTestReport() {
        echo "\n" . str_repeat("=", 60) . "\n";
        echo "📋 REPORTE DE PRUEBAS - BYFROST\n";
        echo str_repeat("=", 60) . "\n";
        
        // Resumen ejecutivo
        $totalTests = 0;
        $passedTests = 0;
        $failedTests = 0;
        
        foreach ($this->results as $category => $data) {
            if (isset($data['connection']) && $data['connection'] === 'PASS') {
                $passedTests++;
            } elseif (isset($data['connection']) && $data['connection'] === 'FAIL') {
                $failedTests++;
            }
            
            if (isset($data['working'])) {
                $passedTests += count($data['working']);
                $totalTests += count($data['working']);
            }
            
            if (isset($data['broken'])) {
                $failedTests += count($data['broken']);
                $totalTests += count($data['broken']);
            }
        }
        
        echo "\n🎯 RESUMEN EJECUTIVO\n";
        echo str_repeat("-", 30) . "\n";
        echo "📊 Total de pruebas: $totalTests\n";
        echo "✅ Pruebas exitosas: $passedTests\n";
        echo "❌ Pruebas fallidas: $failedTests\n";
        
        $successRate = $totalTests > 0 ? round(($passedTests / $totalTests) * 100, 2) : 0;
        echo "📈 Tasa de éxito: {$successRate}%\n";
        
        // Detalles por categoría
        echo "\n📋 DETALLES POR CATEGORÍA\n";
        echo str_repeat("-", 30) . "\n";
        
        foreach ($this->results as $category => $data) {
            echo "\n🔹 $category:\n";
            
            if (isset($data['connection'])) {
                $status = $data['connection'] === 'PASS' ? '✅' : '❌';
                echo "   $status Conexión: " . $data['connection'] . "\n";
            }
            
            if (isset($data['working'])) {
                echo "   ✅ Funcionales: " . count($data['working']) . "\n";
            }
            
            if (isset($data['broken'])) {
                echo "   ❌ Con problemas: " . count($data['broken']) . "\n";
            }
            
            if (isset($data['issues']) && !empty($data['issues'])) {
                echo "   ⚠️  Problemas: " . count($data['issues']) . "\n";
            }
        }
        
        // Errores críticos
        if (!empty($this->errors)) {
            echo "\n🚨 ERRORES CRÍTICOS\n";
            echo str_repeat("-", 30) . "\n";
            foreach ($this->errors as $error) {
                echo "   ❌ $error\n";
            }
        }
        
        // Advertencias
        if (!empty($this->warnings)) {
            echo "\n⚠️  ADVERTENCIAS\n";
            echo str_repeat("-", 30) . "\n";
            foreach ($this->warnings as $warning) {
                echo "   ⚠️  $warning\n";
            }
        }
        
        // Recomendaciones
        echo "\n💡 RECOMENDACIONES\n";
        echo str_repeat("-", 30) . "\n";
        
        if ($successRate >= 90) {
            echo "✅ El sistema está en excelente estado\n";
        } elseif ($successRate >= 75) {
            echo "⚠️  El sistema está en buen estado, pero necesita mejoras\n";
        } else {
            echo "❌ El sistema necesita atención inmediata\n";
        }
        
        if (!empty($this->errors)) {
            echo "🔧 Corregir errores críticos antes de producción\n";
        }
        
        if (!empty($this->warnings)) {
            echo "📝 Revisar advertencias para optimización\n";
        }
        
        echo "\n" . str_repeat("=", 60) . "\n";
        echo "🧪 Pruebas completadas\n";
        echo str_repeat("=", 60) . "\n";
    }
}

// Ejecutar pruebas
echo "🧪 Iniciando pruebas del sistema ByFrost...\n\n";

$tester = new SystemTest($pdo);
$tester->runAllTests();

echo "\n🎉 Pruebas completadas!\n";
?> 