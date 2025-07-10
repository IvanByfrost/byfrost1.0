<?php
/**
 * System Monitor - ByFrost
 * Monitoreo en tiempo real del sistema
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

class SystemMonitor {
    private $pdo;
    private $logFile;
    private $metrics = [];
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->logFile = 'app/logs/system_monitor.log';
        
        // Crear directorio de logs si no existe
        if (!is_dir('app/logs')) {
            mkdir('app/logs', 0755, true);
        }
    }
    
    /**
     * Iniciar monitoreo
     */
    public function startMonitoring() {
        echo "🔍 Iniciando monitoreo del sistema ByFrost...\n";
        echo "📊 Presiona Ctrl+C para detener\n\n";
        
        while (true) {
            $this->collectMetrics();
            $this->displayMetrics();
            $this->checkAlerts();
            $this->saveMetrics();
            
            sleep(30); // Actualizar cada 30 segundos
        }
    }
    
    /**
     * Recolectar métricas del sistema
     */
    private function collectMetrics() {
        $this->metrics = [
            'timestamp' => date('Y-m-d H:i:s'),
            'database' => $this->getDatabaseMetrics(),
            'filesystem' => $this->getFilesystemMetrics(),
            'performance' => $this->getPerformanceMetrics(),
            'security' => $this->getSecurityMetrics(),
            'errors' => $this->getErrorMetrics()
        ];
    }
    
    /**
     * Obtener métricas de base de datos
     */
    private function getDatabaseMetrics() {
        $metrics = [];
        
        try {
            // Conexiones activas
            $stmt = $this->pdo->query("SHOW STATUS LIKE 'Threads_connected'");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $metrics['active_connections'] = $result['Value'];
            
            // Tamaño de base de datos
            $stmt = $this->pdo->query("SELECT 
                ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS 'DB Size in MB'
                FROM information_schema.tables 
                WHERE table_schema = '$dbname'");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $metrics['database_size_mb'] = $result['DB Size in MB'];
            
            // Consultas lentas
            $stmt = $this->pdo->query("SHOW STATUS LIKE 'Slow_queries'");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $metrics['slow_queries'] = $result['Value'];
            
            // Tablas con más registros
            $stmt = $this->pdo->query("SELECT 
                table_name, 
                table_rows 
                FROM information_schema.tables 
                WHERE table_schema = '$dbname' 
                ORDER BY table_rows DESC 
                LIMIT 5");
            $metrics['largest_tables'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            $metrics['error'] = $e->getMessage();
        }
        
        return $metrics;
    }
    
    /**
     * Obtener métricas del sistema de archivos
     */
    private function getFilesystemMetrics() {
        $metrics = [];
        
        // Espacio en disco
        $metrics['disk_free_space'] = round(disk_free_space('.') / 1024 / 1024 / 1024, 2); // GB
        $metrics['disk_total_space'] = round(disk_total_space('.') / 1024 / 1024 / 1024, 2); // GB
        $metrics['disk_usage_percent'] = round((1 - ($metrics['disk_free_space'] / $metrics['disk_total_space'])) * 100, 2);
        
        // Archivos grandes
        $largeFiles = [];
        $directories = ['app/controllers', 'app/models', 'app/views', 'app/resources/js'];
        
        foreach ($directories as $dir) {
            if (is_dir($dir)) {
                $files = glob("$dir/*.php");
                foreach ($files as $file) {
                    $size = filesize($file) / 1024; // KB
                    if ($size > 100) {
                        $largeFiles[] = [
                            'file' => basename($file),
                            'size_kb' => round($size, 2)
                        ];
                    }
                }
            }
        }
        
        $metrics['large_files'] = $largeFiles;
        
        // Permisos de archivos críticos
        $criticalFiles = ['config.php', 'app/scripts/connection.php', '.htaccess'];
        $metrics['file_permissions'] = [];
        
        foreach ($criticalFiles as $file) {
            if (file_exists($file)) {
                $perms = fileperms($file);
                $metrics['file_permissions'][$file] = substr(sprintf('%o', $perms), -4);
            }
        }
        
        return $metrics;
    }
    
    /**
     * Obtener métricas de rendimiento
     */
    private function getPerformanceMetrics() {
        $metrics = [];
        
        // Uso de memoria
        $metrics['memory_usage'] = round(memory_get_usage(true) / 1024 / 1024, 2); // MB
        $metrics['memory_peak'] = round(memory_get_peak_usage(true) / 1024 / 1024, 2); // MB
        
        // Tiempo de carga
        $metrics['load_time'] = microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'];
        
        // Archivos de caché
        $cacheDir = 'app/cache';
        if (is_dir($cacheDir)) {
            $cacheFiles = glob("$cacheDir/*.cache");
            $metrics['cache_files'] = count($cacheFiles);
            $metrics['cache_size_mb'] = 0;
            
            foreach ($cacheFiles as $file) {
                $metrics['cache_size_mb'] += filesize($file);
            }
            $metrics['cache_size_mb'] = round($metrics['cache_size_mb'] / 1024 / 1024, 2);
        }
        
        // Logs recientes
        $logDir = 'app/logs';
        if (is_dir($logDir)) {
            $logFiles = glob("$logDir/*.log");
            $metrics['log_files'] = count($logFiles);
        }
        
        return $metrics;
    }
    
    /**
     * Obtener métricas de seguridad
     */
    private function getSecurityMetrics() {
        $metrics = [];
        
        // Intentos de acceso fallidos
        $metrics['failed_logins'] = $this->countFailedLogins();
        
        // Archivos con permisos inseguros
        $insecureFiles = [];
        $criticalFiles = ['config.php', 'app/scripts/connection.php'];
        
        foreach ($criticalFiles as $file) {
            if (file_exists($file)) {
                $perms = fileperms($file);
                $perms = substr(sprintf('%o', $perms), -4);
                if ($perms != '0644' && $perms != '0600') {
                    $insecureFiles[] = $file;
                }
            }
        }
        
        $metrics['insecure_files'] = $insecureFiles;
        
        // Errores de seguridad en logs
        $metrics['security_errors'] = $this->countSecurityErrors();
        
        return $metrics;
    }
    
    /**
     * Obtener métricas de errores
     */
    private function getErrorMetrics() {
        $metrics = [];
        
        // Errores en logs
        $logDir = 'app/logs';
        $metrics['error_count'] = 0;
        $metrics['recent_errors'] = [];
        
        if (is_dir($logDir)) {
            $logFiles = glob("$logDir/*.log");
            foreach ($logFiles as $logFile) {
                if (file_exists($logFile)) {
                    $content = file_get_contents($logFile);
                    $errorCount = substr_count($content, 'ERROR');
                    $metrics['error_count'] += $errorCount;
                    
                    // Últimos errores
                    $lines = explode("\n", $content);
                    $recentLines = array_slice($lines, -10);
                    foreach ($recentLines as $line) {
                        if (strpos($line, 'ERROR') !== false) {
                            $metrics['recent_errors'][] = trim($line);
                        }
                    }
                }
            }
        }
        
        return $metrics;
    }
    
    /**
     * Contar intentos de login fallidos
     */
    private function countFailedLogins() {
        try {
            $stmt = $this->pdo->query("SELECT COUNT(*) as failed FROM users WHERE last_login_attempt > DATE_SUB(NOW(), INTERVAL 1 HOUR) AND login_failed = 1");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['failed'] ?? 0;
        } catch (PDOException $e) {
            return 0;
        }
    }
    
    /**
     * Contar errores de seguridad
     */
    private function countSecurityErrors() {
        $logDir = 'app/logs';
        $securityErrors = 0;
        
        if (is_dir($logDir)) {
            $logFiles = glob("$logDir/*.log");
            foreach ($logFiles as $logFile) {
                if (file_exists($logFile)) {
                    $content = file_get_contents($logFile);
                    $securityErrors += substr_count($content, 'SECURITY');
                    $securityErrors += substr_count($content, 'SQL_INJECTION');
                    $securityErrors += substr_count($content, 'XSS');
                }
            }
        }
        
        return $securityErrors;
    }
    
    /**
     * Mostrar métricas en pantalla
     */
    private function displayMetrics() {
        // Limpiar pantalla
        system('clear');
        
        echo "🔍 MONITOR DEL SISTEMA BYFROST - " . date('Y-m-d H:i:s') . "\n";
        echo str_repeat("=", 60) . "\n\n";
        
        // Base de datos
        echo "📊 BASE DE DATOS:\n";
        echo "   Conexiones activas: " . ($this->metrics['database']['active_connections'] ?? 'N/A') . "\n";
        echo "   Tamaño BD: " . ($this->metrics['database']['database_size_mb'] ?? 'N/A') . " MB\n";
        echo "   Consultas lentas: " . ($this->metrics['database']['slow_queries'] ?? 'N/A') . "\n";
        
        if (isset($this->metrics['database']['largest_tables'])) {
            echo "   Tablas más grandes:\n";
            foreach ($this->metrics['database']['largest_tables'] as $table) {
                echo "     - {$table['table_name']}: {$table['table_rows']} registros\n";
            }
        }
        
        echo "\n";
        
        // Sistema de archivos
        echo "📁 SISTEMA DE ARCHIVOS:\n";
        echo "   Espacio libre: " . ($this->metrics['filesystem']['disk_free_space'] ?? 'N/A') . " GB\n";
        echo "   Uso de disco: " . ($this->metrics['filesystem']['disk_usage_percent'] ?? 'N/A') . "%\n";
        echo "   Archivos grandes: " . count($this->metrics['filesystem']['large_files']) . "\n";
        
        if (!empty($this->metrics['filesystem']['large_files'])) {
            foreach (array_slice($this->metrics['filesystem']['large_files'], 0, 3) as $file) {
                echo "     - {$file['file']}: {$file['size_kb']} KB\n";
            }
        }
        
        echo "\n";
        
        // Rendimiento
        echo "⚡ RENDIMIENTO:\n";
        echo "   Memoria en uso: " . ($this->metrics['performance']['memory_usage'] ?? 'N/A') . " MB\n";
        echo "   Memoria pico: " . ($this->metrics['performance']['memory_peak'] ?? 'N/A') . " MB\n";
        echo "   Tiempo de carga: " . round(($this->metrics['performance']['load_time'] ?? 0) * 1000, 2) . " ms\n";
        echo "   Archivos de caché: " . ($this->metrics['performance']['cache_files'] ?? 0) . "\n";
        echo "   Tamaño caché: " . ($this->metrics['performance']['cache_size_mb'] ?? 0) . " MB\n";
        
        echo "\n";
        
        // Seguridad
        echo "🔒 SEGURIDAD:\n";
        echo "   Logins fallidos (1h): " . ($this->metrics['security']['failed_logins'] ?? 0) . "\n";
        echo "   Archivos inseguros: " . count($this->metrics['security']['insecure_files']) . "\n";
        echo "   Errores de seguridad: " . ($this->metrics['security']['security_errors'] ?? 0) . "\n";
        
        echo "\n";
        
        // Errores
        echo "❌ ERRORES:\n";
        echo "   Total de errores: " . ($this->metrics['errors']['error_count'] ?? 0) . "\n";
        
        if (!empty($this->metrics['errors']['recent_errors'])) {
            echo "   Últimos errores:\n";
            foreach (array_slice($this->metrics['errors']['recent_errors'], 0, 3) as $error) {
                echo "     - " . substr($error, 0, 80) . "...\n";
            }
        }
        
        echo "\n" . str_repeat("=", 60) . "\n";
        echo "🔄 Actualizando en 30 segundos... (Ctrl+C para detener)\n";
    }
    
    /**
     * Verificar alertas
     */
    private function checkAlerts() {
        $alerts = [];
        
        // Alerta de memoria alta
        if (($this->metrics['performance']['memory_usage'] ?? 0) > 100) {
            $alerts[] = "⚠️  ALERTA: Uso de memoria alto (" . $this->metrics['performance']['memory_usage'] . " MB)";
        }
        
        // Alerta de espacio en disco bajo
        if (($this->metrics['filesystem']['disk_usage_percent'] ?? 0) > 90) {
            $alerts[] = "⚠️  ALERTA: Espacio en disco bajo (" . $this->metrics['filesystem']['disk_usage_percent'] . "%)";
        }
        
        // Alerta de conexiones altas
        if (($this->metrics['database']['active_connections'] ?? 0) > 50) {
            $alerts[] = "⚠️  ALERTA: Muchas conexiones activas (" . $this->metrics['database']['active_connections'] . ")";
        }
        
        // Alerta de archivos inseguros
        if (!empty($this->metrics['security']['insecure_files'])) {
            $alerts[] = "🚨 ALERTA: Archivos con permisos inseguros detectados";
        }
        
        // Alerta de errores críticos
        if (($this->metrics['errors']['error_count'] ?? 0) > 10) {
            $alerts[] = "🚨 ALERTA: Muchos errores detectados (" . $this->metrics['errors']['error_count'] . ")";
        }
        
        // Mostrar alertas
        if (!empty($alerts)) {
            echo "\n🚨 ALERTAS:\n";
            foreach ($alerts as $alert) {
                echo "   $alert\n";
            }
            echo "\n";
        }
    }
    
    /**
     * Guardar métricas en log
     */
    private function saveMetrics() {
        $logEntry = date('Y-m-d H:i:s') . " - " . json_encode($this->metrics) . "\n";
        file_put_contents($this->logFile, $logEntry, FILE_APPEND | LOCK_EX);
    }
    
    /**
     * Generar reporte de métricas
     */
    public function generateReport($hours = 24) {
        echo "📊 Generando reporte de métricas (últimas $hours horas)...\n";
        
        if (!file_exists($this->logFile)) {
            echo "❌ No hay datos de monitoreo disponibles\n";
            return;
        }
        
        $logContent = file_get_contents($this->logFile);
        $lines = explode("\n", $logContent);
        
        $metrics = [];
        $cutoffTime = time() - ($hours * 3600);
        
        foreach ($lines as $line) {
            if (empty($line)) continue;
            
            $parts = explode(" - ", $line);
            if (count($parts) >= 2) {
                $timestamp = strtotime($parts[0]);
                if ($timestamp >= $cutoffTime) {
                    $data = json_decode($parts[1], true);
                    if ($data) {
                        $metrics[] = $data;
                    }
                }
            }
        }
        
        if (empty($metrics)) {
            echo "❌ No hay datos en el rango de tiempo especificado\n";
            return;
        }
        
        // Calcular estadísticas
        $this->calculateStatistics($metrics);
    }
    
    /**
     * Calcular estadísticas de métricas
     */
    private function calculateStatistics($metrics) {
        echo "\n📈 ESTADÍSTICAS DEL PERIODO:\n";
        echo str_repeat("-", 40) . "\n";
        
        // Estadísticas de memoria
        $memoryUsage = array_column($metrics, 'performance.memory_usage');
        $memoryUsage = array_filter($memoryUsage);
        
        if (!empty($memoryUsage)) {
            echo "💾 MEMORIA:\n";
            echo "   Promedio: " . round(array_sum($memoryUsage) / count($memoryUsage), 2) . " MB\n";
            echo "   Máximo: " . round(max($memoryUsage), 2) . " MB\n";
            echo "   Mínimo: " . round(min($memoryUsage), 2) . " MB\n";
        }
        
        // Estadísticas de conexiones
        $connections = array_column($metrics, 'database.active_connections');
        $connections = array_filter($connections);
        
        if (!empty($connections)) {
            echo "\n🔌 CONEXIONES DB:\n";
            echo "   Promedio: " . round(array_sum($connections) / count($connections), 2) . "\n";
            echo "   Máximo: " . max($connections) . "\n";
            echo "   Mínimo: " . min($connections) . "\n";
        }
        
        // Estadísticas de errores
        $errors = array_column($metrics, 'errors.error_count');
        $errors = array_filter($errors);
        
        if (!empty($errors)) {
            echo "\n❌ ERRORES:\n";
            echo "   Total: " . array_sum($errors) . "\n";
            echo "   Promedio por hora: " . round(array_sum($errors) / count($metrics), 2) . "\n";
            echo "   Máximo en una hora: " . max($errors) . "\n";
        }
        
        echo "\n" . str_repeat("=", 40) . "\n";
    }
}

// Manejar argumentos de línea de comandos
$action = $argv[1] ?? 'monitor';

$monitor = new SystemMonitor($pdo);

switch ($action) {
    case 'monitor':
        $monitor->startMonitoring();
        break;
        
    case 'report':
        $hours = $argv[2] ?? 24;
        $monitor->generateReport($hours);
        break;
        
    default:
        echo "Uso: php system_monitor.php [monitor|report] [horas]\n";
        echo "  monitor - Iniciar monitoreo en tiempo real\n";
        echo "  report [horas] - Generar reporte de métricas\n";
        break;
}
?> 