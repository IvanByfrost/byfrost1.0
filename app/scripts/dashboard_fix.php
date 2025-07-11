<?php
/**
 * Dashboard Fix - Script de diagnóstico y reparación
 * ByFrost - Sistema de corrección automática de dashboards
 */

define('ROOT', dirname(dirname(__DIR__)));
require_once ROOT . '/config.php';
require_once ROOT . '/app/scripts/connection.php';

class DashboardFix {
    private $dbConn;
    private $errors = [];
    private $fixes = [];
    private $logFile = ROOT . '/app/logs/dashboard_fix.log';
    
    public function __construct() {
        try {
            $this->dbConn = getConnection();
        } catch (Exception $e) {
            $this->logError("Error de conexión: " . $e->getMessage());
        }
    }
    
    /**
     * Ejecuta todas las verificaciones y reparaciones
     */
    public function runFullDiagnostic() {
        echo "🔍 INICIANDO DIAGNÓSTICO COMPLETO DE DASHBOARDS\n";
        echo str_repeat("=", 50) . "\n\n";
        
        $this->checkFileStructure();
        $this->checkControllers();
        $this->checkViews();
        $this->checkJavaScript();
        $this->checkDatabase();
        $this->checkRouting();
        $this->checkPermissions();
        
        $this->generateReport();
    }
    
    /**
     * Verifica la estructura de archivos
     */
    private function checkFileStructure() {
        echo "📁 Verificando estructura de archivos...\n";
        
        $requiredDirs = [
            'app/controllers',
            'app/models',
            'app/views',
            'app/resources/js',
            'app/resources/css',
            'app/library',
            'app/logs'
        ];
        
        foreach ($requiredDirs as $dir) {
            $fullPath = ROOT . '/' . $dir;
            if (!is_dir($fullPath)) {
                $this->errors[] = "Directorio faltante: $dir";
                mkdir($fullPath, 0755, true);
                $this->fixes[] = "Creado directorio: $dir";
            }
        }
        
        // Verificar archivos críticos
        $criticalFiles = [
            'app/controllers/MainController.php',
            'app/library/SessionManager.php',
            'app/resources/js/loadView.js',
            'config.php'
        ];
        
        foreach ($criticalFiles as $file) {
            $fullPath = ROOT . '/' . $file;
            if (!file_exists($fullPath)) {
                $this->errors[] = "Archivo crítico faltante: $file";
            }
        }
        
        echo "✅ Verificación de estructura completada\n\n";
    }
    
    /**
     * Verifica controladores
     */
    private function checkControllers() {
        echo "🎮 Verificando controladores...\n";
        
        $controllers = [
            'DirectorController.php',
            'UserController.php',
            'SchoolController.php',
            'PayrollController.php',
            'ActivityController.php',
            'StudentController.php',
            'TeacherController.php'
        ];
        
        foreach ($controllers as $controller) {
            $filePath = ROOT . '/app/controllers/' . $controller;
            if (file_exists($filePath)) {
                // Verificar sintaxis PHP
                $output = [];
                $returnCode = 0;
                exec("php -l " . escapeshellarg($filePath) . " 2>&1", $output, $returnCode);
                
                if ($returnCode !== 0) {
                    $this->errors[] = "Error de sintaxis en $controller: " . implode("\n", $output);
                }
            } else {
                $this->errors[] = "Controlador faltante: $controller";
            }
        }
        
        echo "✅ Verificación de controladores completada\n\n";
    }
    
    /**
     * Verifica vistas
     */
    private function checkViews() {
        echo "👁️ Verificando vistas...\n";
        
        $views = [
            'director/dashboard.php',
            'director/dashboardHome.php',
            'user/assignRole.php',
            'school/createSchool.php',
            'payroll/dashboard.php'
        ];
        
        foreach ($views as $view) {
            $filePath = ROOT . '/app/views/' . $view;
            if (!file_exists($filePath)) {
                $this->errors[] = "Vista faltante: $view";
            }
        }
        
        echo "✅ Verificación de vistas completada\n\n";
    }
    
    /**
     * Verifica JavaScript
     */
    private function checkJavaScript() {
        echo "⚡ Verificando JavaScript...\n";
        
        $jsFiles = [
            'app/resources/js/loadView.js',
            'app/resources/js/dashboard.js',
            'app/resources/js/activityDashboard.js'
        ];
        
        foreach ($jsFiles as $file) {
            $filePath = ROOT . '/' . $file;
            if (file_exists($filePath)) {
                // Verificar sintaxis básica
                $content = file_get_contents($filePath);
                if (strpos($content, 'function') === false && strpos($content, 'const') === false) {
                    $this->errors[] = "Archivo JavaScript posiblemente vacío o corrupto: $file";
                }
            } else {
                $this->errors[] = "Archivo JavaScript faltante: $file";
            }
        }
        
        echo "✅ Verificación de JavaScript completada\n\n";
    }
    
    /**
     * Verifica base de datos
     */
    private function checkDatabase() {
        echo "🗄️ Verificando base de datos...\n";
        
        if (!$this->dbConn) {
            $this->errors[] = "No se pudo conectar a la base de datos";
            return;
        }
        
        try {
            // Verificar tablas críticas
            $criticalTables = ['users', 'schools', 'students', 'teachers', 'activities'];
            
            foreach ($criticalTables as $table) {
                $stmt = $this->dbConn->prepare("SHOW TABLES LIKE ?");
                $stmt->execute([$table]);
                
                if ($stmt->rowCount() === 0) {
                    $this->errors[] = "Tabla faltante en base de datos: $table";
                }
            }
            
            // Verificar permisos de usuario
            $stmt = $this->dbConn->prepare("SELECT COUNT(*) as count FROM users WHERE role IN ('director', 'root')");
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result['count'] == 0) {
                $this->errors[] = "No hay usuarios con rol de director o root en la base de datos";
            }
            
        } catch (Exception $e) {
            $this->errors[] = "Error verificando base de datos: " . $e->getMessage();
        }
        
        echo "✅ Verificación de base de datos completada\n\n";
    }
    
    /**
     * Verifica sistema de routing
     */
    private function checkRouting() {
        echo "🛣️ Verificando sistema de routing...\n";
        
        $routingFiles = [
            'app/scripts/routerView.php',
            'app/library/Router.php'
        ];
        
        foreach ($routingFiles as $file) {
            $filePath = ROOT . '/' . $file;
            if (!file_exists($filePath)) {
                $this->errors[] = "Archivo de routing faltante: $file";
            } else {
                // Verificar sintaxis
                $output = [];
                $returnCode = 0;
                exec("php -l " . escapeshellarg($filePath) . " 2>&1", $output, $returnCode);
                
                if ($returnCode !== 0) {
                    $this->errors[] = "Error de sintaxis en $file: " . implode("\n", $output);
                }
            }
        }
        
        echo "✅ Verificación de routing completada\n\n";
    }
    
    /**
     * Verifica permisos de archivos
     */
    private function checkPermissions() {
        echo "🔐 Verificando permisos...\n";
        
        $directories = [
            'app/logs',
            'app/views',
            'app/resources'
        ];
        
        foreach ($directories as $dir) {
            $fullPath = ROOT . '/' . $dir;
            if (is_dir($fullPath)) {
                if (!is_writable($fullPath)) {
                    $this->errors[] = "Directorio no escribible: $dir";
                    chmod($fullPath, 0755);
                    $this->fixes[] = "Permisos corregidos para: $dir";
                }
            }
        }
        
        echo "✅ Verificación de permisos completada\n\n";
    }
    
    /**
     * Genera reporte final
     */
    private function generateReport() {
        echo str_repeat("=", 50) . "\n";
        echo "📋 REPORTE FINAL DE DIAGNÓSTICO\n";
        echo str_repeat("=", 50) . "\n\n";
        
        if (empty($this->errors) && empty($this->fixes)) {
            echo "✅ El sistema está funcionando correctamente\n";
            echo "No se encontraron errores críticos\n\n";
        } else {
            if (!empty($this->errors)) {
                echo "❌ ERRORES ENCONTRADOS (" . count($this->errors) . "):\n";
                echo str_repeat("-", 30) . "\n";
                foreach ($this->errors as $i => $error) {
                    echo ($i + 1) . ". $error\n";
                }
                echo "\n";
            }
            
            if (!empty($this->fixes)) {
                echo "🔧 REPARACIONES APLICADAS (" . count($this->fixes) . "):\n";
                echo str_repeat("-", 30) . "\n";
                foreach ($this->fixes as $i => $fix) {
                    echo ($i + 1) . ". $fix\n";
                }
                echo "\n";
            }
        }
        
        // Guardar log
        $this->saveLog();
        
        echo "📝 Log guardado en: " . $this->logFile . "\n";
        echo "🎯 DIAGNÓSTICO COMPLETADO\n\n";
    }
    
    /**
     * Guarda el log de errores
     */
    private function saveLog() {
        $logContent = date('Y-m-d H:i:s') . " - DIAGNÓSTICO DE DASHBOARDS\n";
        $logContent .= str_repeat("-", 50) . "\n";
        
        if (!empty($this->errors)) {
            $logContent .= "ERRORES:\n";
            foreach ($this->errors as $error) {
                $logContent .= "- $error\n";
            }
            $logContent .= "\n";
        }
        
        if (!empty($this->fixes)) {
            $logContent .= "REPARACIONES:\n";
            foreach ($this->fixes as $fix) {
                $logContent .= "- $fix\n";
            }
            $logContent .= "\n";
        }
        
        file_put_contents($this->logFile, $logContent, FILE_APPEND);
    }
    
    /**
     * Registra un error
     */
    private function logError($message) {
        $this->errors[] = $message;
        error_log($message);
    }
}

// Ejecutar diagnóstico si se llama directamente
if (basename(__FILE__) === basename($_SERVER['SCRIPT_NAME'])) {
    $fixer = new DashboardFix();
    $fixer->runFullDiagnostic();
}
?> 