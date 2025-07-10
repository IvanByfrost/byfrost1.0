<?php
/**
 * Performance Analyzer - ByFrost
 * Analiza el rendimiento del sistema y proporciona recomendaciones
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

class PerformanceAnalyzer {
    private $pdo;
    private $results = [];
    private $startTime;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->startTime = microtime(true);
    }
    
    /**
     * Análisis completo del sistema
     */
    public function analyzeSystem() {
        echo "🔍 Iniciando análisis completo del sistema ByFrost...\n\n";
        
        $this->analyzeDatabasePerformance();
        $this->analyzeFileSystem();
        $this->analyzeRoutingSystem();
        $this->analyzeCodeQuality();
        $this->analyzeSecurity();
        $this->generateReport();
    }
    
    /**
     * Análisis de rendimiento de base de datos
     */
    private function analyzeDatabasePerformance() {
        echo "📊 Analizando rendimiento de base de datos...\n";
        
        // Verificar índices
        $this->checkDatabaseIndexes();
        
        // Analizar consultas lentas
        $this->analyzeSlowQueries();
        
        // Verificar fragmentación
        $this->checkTableFragmentation();
        
        // Analizar uso de memoria
        $this->analyzeMemoryUsage();
        
        echo "✅ Análisis de base de datos completado\n\n";
    }
    
    /**
     * Verificar índices de base de datos
     */
    private function checkDatabaseIndexes() {
        $tables = [
            'users', 'user_roles', 'schools', 'grades', 'subjects',
            'class_groups', 'student_enrollment', 'activities', 'student_scores',
            'attendance', 'student_payments', 'employees', 'school_events'
        ];
        
        $missingIndexes = [];
        $recommendedIndexes = [];
        
        foreach ($tables as $table) {
            try {
                // Verificar si la tabla existe
                $stmt = $this->pdo->query("SHOW TABLES LIKE '$table'");
                if ($stmt->rowCount() === 0) {
                    continue;
                }
                
                // Obtener índices existentes
                $stmt = $this->pdo->query("SHOW INDEX FROM $table");
                $indexes = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                $indexNames = array_column($indexes, 'Key_name');
                
                // Verificar índices recomendados
                $recommended = $this->getRecommendedIndexes($table);
                foreach ($recommended as $index) {
                    if (!in_array($index, $indexNames)) {
                        $missingIndexes[] = "$table.$index";
                    }
                }
                
                // Verificar índices compuestos importantes
                $importantIndexes = $this->getImportantIndexes($table);
                foreach ($importantIndexes as $index) {
                    if (!in_array($index, $indexNames)) {
                        $recommendedIndexes[] = "$table.$index";
                    }
                }
                
            } catch (PDOException $e) {
                // Tabla no existe o error
            }
        }
        
        $this->results['database']['missing_indexes'] = $missingIndexes;
        $this->results['database']['recommended_indexes'] = $recommendedIndexes;
        
        if (!empty($missingIndexes)) {
            echo "⚠️  Índices faltantes: " . count($missingIndexes) . "\n";
            foreach ($missingIndexes as $index) {
                echo "   - $index\n";
            }
        }
        
        if (!empty($recommendedIndexes)) {
            echo "💡 Índices recomendados: " . count($recommendedIndexes) . "\n";
            foreach ($recommendedIndexes as $index) {
                echo "   - $index\n";
            }
        }
    }
    
    /**
     * Obtener índices recomendados por tabla
     */
    private function getRecommendedIndexes($table) {
        $recommendations = [
            'users' => ['idx_credential', 'idx_email', 'idx_active'],
            'user_roles' => ['idx_user_role', 'idx_role_type', 'idx_active_role'],
            'schools' => ['idx_school_active', 'idx_school_dane'],
            'grades' => ['idx_grade_school', 'idx_school_id'],
            'subjects' => ['idx_subject_active', 'idx_subject_code'],
            'class_groups' => ['idx_group_grade', 'idx_group_professor'],
            'student_enrollment' => ['idx_enrollment_active', 'idx_student_code'],
            'activities' => ['idx_activities_group_term', 'idx_activities_active'],
            'student_scores' => ['idx_student_scores_student', 'idx_score_active'],
            'attendance' => ['idx_student_user_id', 'idx_attendance_date', 'idx_status'],
            'student_payments' => ['idx_student_payment', 'idx_tuition_status'],
            'employees' => ['idx_employee_code', 'idx_employee_active'],
            'school_events' => ['idx_start_date', 'idx_event_active']
        ];
        
        return $recommendations[$table] ?? [];
    }
    
    /**
     * Obtener índices importantes por tabla
     */
    private function getImportantIndexes($table) {
        $important = [
            'users' => ['idx_credential_email', 'idx_active_created'],
            'user_roles' => ['idx_user_active_role'],
            'student_scores' => ['idx_student_score_date'],
            'attendance' => ['idx_student_date_status'],
            'student_payments' => ['idx_status_due_date'],
            'school_events' => ['idx_active_start_date']
        ];
        
        return $important[$table] ?? [];
    }
    
    /**
     * Analizar consultas lentas
     */
    private function analyzeSlowQueries() {
        echo "🔍 Analizando consultas potencialmente lentas...\n";
        
        $slowQueries = [];
        
        // Consultas que podrían ser lentas
        $queries = [
            'SELECT COUNT(*) FROM users WHERE is_active = 1' => 'Conteo de usuarios activos',
            'SELECT * FROM student_scores WHERE student_user_id = ?' => 'Calificaciones por estudiante',
            'SELECT * FROM attendance WHERE attendance_date = CURDATE()' => 'Asistencia de hoy',
            'SELECT * FROM student_payments WHERE tuition_status IN ("pending", "overdue")' => 'Pagos pendientes',
            'SELECT * FROM school_events WHERE start_date >= NOW()' => 'Eventos próximos'
        ];
        
        foreach ($queries as $query => $description) {
            try {
                $start = microtime(true);
                $stmt = $this->pdo->query($query);
                $stmt->fetchAll();
                $time = microtime(true) - $start;
                
                if ($time > 0.1) { // Más de 100ms
                    $slowQueries[] = [
                        'query' => $query,
                        'description' => $description,
                        'time' => round($time * 1000, 2)
                    ];
                }
            } catch (PDOException $e) {
                // Ignorar errores de consultas que no existen
            }
        }
        
        $this->results['database']['slow_queries'] = $slowQueries;
        
        if (!empty($slowQueries)) {
            echo "🐌 Consultas lentas detectadas: " . count($slowQueries) . "\n";
            foreach ($slowQueries as $query) {
                echo "   - {$query['description']}: {$query['time']}ms\n";
            }
        }
    }
    
    /**
     * Verificar fragmentación de tablas
     */
    private function checkTableFragmentation() {
        echo "📋 Verificando fragmentación de tablas...\n";
        
        $fragmentedTables = [];
        
        try {
            $stmt = $this->pdo->query("SHOW TABLE STATUS");
            $tables = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($tables as $table) {
                $dataLength = $table['Data_length'];
                $dataFree = $table['Data_free'];
                
                if ($dataLength > 0 && $dataFree > 0) {
                    $fragmentation = ($dataFree / $dataLength) * 100;
                    if ($fragmentation > 10) { // Más de 10% fragmentación
                        $fragmentedTables[] = [
                            'table' => $table['Name'],
                            'fragmentation' => round($fragmentation, 2)
                        ];
                    }
                }
            }
        } catch (PDOException $e) {
            // Error al obtener información de tablas
        }
        
        $this->results['database']['fragmented_tables'] = $fragmentedTables;
        
        if (!empty($fragmentedTables)) {
            echo "⚠️  Tablas fragmentadas: " . count($fragmentedTables) . "\n";
            foreach ($fragmentedTables as $table) {
                echo "   - {$table['table']}: {$table['fragmentation']}%\n";
            }
        }
    }
    
    /**
     * Analizar uso de memoria
     */
    private function analyzeMemoryUsage() {
        echo "💾 Analizando uso de memoria...\n";
        
        try {
            $stmt = $this->pdo->query("SHOW VARIABLES LIKE 'max_connections'");
            $maxConnections = $stmt->fetch(PDO::FETCH_ASSOC)['Value'];
            
            $stmt = $this->pdo->query("SHOW STATUS LIKE 'Threads_connected'");
            $currentConnections = $stmt->fetch(PDO::FETCH_ASSOC)['Value'];
            
            $connectionUsage = ($currentConnections / $maxConnections) * 100;
            
            $this->results['database']['connection_usage'] = round($connectionUsage, 2);
            
            if ($connectionUsage > 80) {
                echo "⚠️  Uso alto de conexiones: {$connectionUsage}%\n";
            }
        } catch (PDOException $e) {
            // Error al obtener estadísticas
        }
    }
    
    /**
     * Análisis del sistema de archivos
     */
    private function analyzeFileSystem() {
        echo "📁 Analizando sistema de archivos...\n";
        
        $this->analyzeFileSizes();
        $this->analyzeFilePermissions();
        $this->analyzeDirectoryStructure();
        
        echo "✅ Análisis de archivos completado\n\n";
    }
    
    /**
     * Analizar tamaños de archivos
     */
    private function analyzeFileSizes() {
        $largeFiles = [];
        $directories = [
            'app/controllers' => 100, // KB
            'app/models' => 100,
            'app/views' => 50,
            'app/resources/js' => 200,
            'app/resources/css' => 100
        ];
        
        foreach ($directories as $dir => $maxSize) {
            if (is_dir($dir)) {
                $files = glob("$dir/*.php");
                foreach ($files as $file) {
                    $size = filesize($file) / 1024; // KB
                    if ($size > $maxSize) {
                        $largeFiles[] = [
                            'file' => $file,
                            'size' => round($size, 2),
                            'max_recommended' => $maxSize
                        ];
                    }
                }
            }
        }
        
        $this->results['filesystem']['large_files'] = $largeFiles;
        
        if (!empty($largeFiles)) {
            echo "📏 Archivos grandes detectados: " . count($largeFiles) . "\n";
            foreach ($largeFiles as $file) {
                echo "   - {$file['file']}: {$file['size']}KB (máx: {$file['max_recommended']}KB)\n";
            }
        }
    }
    
    /**
     * Analizar permisos de archivos
     */
    private function analyzeFilePermissions() {
        $insecureFiles = [];
        $criticalFiles = [
            'config.php',
            'app/scripts/connection.php',
            '.htaccess'
        ];
        
        foreach ($criticalFiles as $file) {
            if (file_exists($file)) {
                $perms = fileperms($file);
                $perms = substr(sprintf('%o', $perms), -4);
                
                if ($perms != '0644' && $perms != '0600') {
                    $insecureFiles[] = [
                        'file' => $file,
                        'permissions' => $perms,
                        'recommended' => '0644'
                    ];
                }
            }
        }
        
        $this->results['filesystem']['insecure_files'] = $insecureFiles;
        
        if (!empty($insecureFiles)) {
            echo "🔒 Archivos con permisos inseguros: " . count($insecureFiles) . "\n";
            foreach ($insecureFiles as $file) {
                echo "   - {$file['file']}: {$file['permissions']} (recomendado: {$file['recommended']})\n";
            }
        }
    }
    
    /**
     * Analizar estructura de directorios
     */
    private function analyzeDirectoryStructure() {
        $missingDirs = [];
        $recommendedDirs = [
            'app/cache',
            'app/logs',
            'app/uploads',
            'app/temp'
        ];
        
        foreach ($recommendedDirs as $dir) {
            if (!is_dir($dir)) {
                $missingDirs[] = $dir;
            }
        }
        
        $this->results['filesystem']['missing_directories'] = $missingDirs;
        
        if (!empty($missingDirs)) {
            echo "📁 Directorios recomendados faltantes: " . count($missingDirs) . "\n";
            foreach ($missingDirs as $dir) {
                echo "   - $dir\n";
            }
        }
    }
    
    /**
     * Análisis del sistema de routing
     */
    private function analyzeRoutingSystem() {
        echo "🛣️ Analizando sistema de routing...\n";
        
        $this->analyzeRouterFiles();
        $this->analyzeControllerStructure();
        $this->analyzeViewStructure();
        
        echo "✅ Análisis de routing completado\n\n";
    }
    
    /**
     * Analizar archivos del router
     */
    private function analyzeRouterFiles() {
        $routerFiles = [
            'app/library/Router.php',
            'app/scripts/routerView.php',
            'app/resources/js/loadView.js'
        ];
        
        $routerIssues = [];
        
        foreach ($routerFiles as $file) {
            if (!file_exists($file)) {
                $routerIssues[] = "Archivo faltante: $file";
            } else {
                $size = filesize($file) / 1024; // KB
                if ($size > 50) {
                    $routerIssues[] = "Archivo muy grande: $file ({$size}KB)";
                }
            }
        }
        
        $this->results['routing']['issues'] = $routerIssues;
        
        if (!empty($routerIssues)) {
            echo "⚠️  Problemas en sistema de routing: " . count($routerIssues) . "\n";
            foreach ($routerIssues as $issue) {
                echo "   - $issue\n";
            }
        }
    }
    
    /**
     * Analizar estructura de controladores
     */
    private function analyzeControllerStructure() {
        $controllers = glob('app/controllers/*.php');
        $controllerStats = [
            'total' => count($controllers),
            'large_files' => 0,
            'missing_methods' => []
        ];
        
        foreach ($controllers as $controller) {
            $size = filesize($controller) / 1024; // KB
            if ($size > 100) {
                $controllerStats['large_files']++;
            }
            
            // Verificar métodos básicos
            $content = file_get_contents($controller);
            if (strpos($content, 'class') !== false) {
                $className = pathinfo($controller, PATHINFO_FILENAME);
                if (strpos($content, 'function index') === false && strpos($content, 'public function index') === false) {
                    $controllerStats['missing_methods'][] = "$className::index()";
                }
            }
        }
        
        $this->results['routing']['controller_stats'] = $controllerStats;
        
        echo "📊 Estadísticas de controladores:\n";
        echo "   - Total: {$controllerStats['total']}\n";
        echo "   - Archivos grandes: {$controllerStats['large_files']}\n";
        echo "   - Métodos faltantes: " . count($controllerStats['missing_methods']) . "\n";
    }
    
    /**
     * Analizar estructura de vistas
     */
    private function analyzeViewStructure() {
        $views = glob('app/views/**/*.php', GLOB_BRACE);
        $viewStats = [
            'total' => count($views),
            'large_files' => 0,
            'missing_includes' => []
        ];
        
        foreach ($views as $view) {
            $size = filesize($view) / 1024; // KB
            if ($size > 50) {
                $viewStats['large_files']++;
            }
            
            // Verificar includes faltantes
            $content = file_get_contents($view);
            if (strpos($content, '<?php') !== false && strpos($content, 'include') === false && strpos($content, 'require') === false) {
                $viewStats['missing_includes'][] = $view;
            }
        }
        
        $this->results['routing']['view_stats'] = $viewStats;
        
        echo "📊 Estadísticas de vistas:\n";
        echo "   - Total: {$viewStats['total']}\n";
        echo "   - Archivos grandes: {$viewStats['large_files']}\n";
        echo "   - Sin includes: " . count($viewStats['missing_includes']) . "\n";
    }
    
    /**
     * Análisis de calidad de código
     */
    private function analyzeCodeQuality() {
        echo "🔍 Analizando calidad de código...\n";
        
        $this->analyzeCodeDuplication();
        $this->analyzeFunctionComplexity();
        $this->analyzeNamingConventions();
        
        echo "✅ Análisis de código completado\n\n";
    }
    
    /**
     * Analizar duplicación de código
     */
    private function analyzeCodeDuplication() {
        $duplicatedPatterns = [];
        $files = glob('app/**/*.php', GLOB_BRACE);
        
        $patterns = [
            'loadView(' => 'Función loadView',
            'safeLoadView(' => 'Función safeLoadView (deprecated)',
            'console.log(' => 'Console.log',
            'error_log(' => 'Error logging',
            'SELECT * FROM' => 'SELECT * (ineficiente)',
            'mysql_query(' => 'mysql_query (deprecated)'
        ];
        
        foreach ($files as $file) {
            $content = file_get_contents($file);
            foreach ($patterns as $pattern => $description) {
                $count = substr_count($content, $pattern);
                if ($count > 5) {
                    $duplicatedPatterns[] = [
                        'file' => $file,
                        'pattern' => $description,
                        'count' => $count
                    ];
                }
            }
        }
        
        $this->results['code_quality']['duplicated_patterns'] = $duplicatedPatterns;
        
        if (!empty($duplicatedPatterns)) {
            echo "🔄 Patrones duplicados detectados: " . count($duplicatedPatterns) . "\n";
            foreach ($duplicatedPatterns as $pattern) {
                echo "   - {$pattern['file']}: {$pattern['pattern']} ({$pattern['count']} veces)\n";
            }
        }
    }
    
    /**
     * Analizar complejidad de funciones
     */
    private function analyzeFunctionComplexity() {
        $complexFunctions = [];
        $files = glob('app/**/*.php', GLOB_BRACE);
        
        foreach ($files as $file) {
            $content = file_get_contents($file);
            $lines = explode("\n", $content);
            
            $inFunction = false;
            $functionName = '';
            $lineCount = 0;
            
            foreach ($lines as $line) {
                $line = trim($line);
                
                if (preg_match('/function\s+(\w+)/', $line, $matches)) {
                    if ($inFunction && $lineCount > 50) {
                        $complexFunctions[] = [
                            'file' => $file,
                            'function' => $functionName,
                            'lines' => $lineCount
                        ];
                    }
                    
                    $inFunction = true;
                    $functionName = $matches[1];
                    $lineCount = 0;
                } elseif ($inFunction) {
                    $lineCount++;
                    
                    if (strpos($line, '}') !== false) {
                        if ($lineCount > 50) {
                            $complexFunctions[] = [
                                'file' => $file,
                                'function' => $functionName,
                                'lines' => $lineCount
                            ];
                        }
                        $inFunction = false;
                    }
                }
            }
        }
        
        $this->results['code_quality']['complex_functions'] = $complexFunctions;
        
        if (!empty($complexFunctions)) {
            echo "⚠️  Funciones complejas detectadas: " . count($complexFunctions) . "\n";
            foreach ($complexFunctions as $func) {
                echo "   - {$func['file']}::{$func['function']} ({$func['lines']} líneas)\n";
            }
        }
    }
    
    /**
     * Analizar convenciones de nombres
     */
    private function analyzeNamingConventions() {
        $namingIssues = [];
        $files = glob('app/**/*.php', GLOB_BRACE);
        
        foreach ($files as $file) {
            $content = file_get_contents($file);
            
            // Verificar nombres de clases
            if (preg_match('/class\s+(\w+)/', $content, $matches)) {
                $className = $matches[1];
                if (!preg_match('/^[A-Z][a-zA-Z0-9]*$/', $className)) {
                    $namingIssues[] = "Clase con nombre incorrecto: $className en $file";
                }
            }
            
            // Verificar nombres de funciones
            preg_match_all('/function\s+(\w+)/', $content, $matches);
            foreach ($matches[1] as $functionName) {
                if (!preg_match('/^[a-z][a-zA-Z0-9]*$/', $functionName)) {
                    $namingIssues[] = "Función con nombre incorrecto: $functionName en $file";
                }
            }
        }
        
        $this->results['code_quality']['naming_issues'] = $namingIssues;
        
        if (!empty($namingIssues)) {
            echo "📝 Problemas de nomenclatura: " . count($namingIssues) . "\n";
            foreach ($namingIssues as $issue) {
                echo "   - $issue\n";
            }
        }
    }
    
    /**
     * Análisis de seguridad
     */
    private function analyzeSecurity() {
        echo "🔒 Analizando seguridad...\n";
        
        $this->analyzeSQLInjection();
        $this->analyzeXSSVulnerabilities();
        $this->analyzeFileUploads();
        
        echo "✅ Análisis de seguridad completado\n\n";
    }
    
    /**
     * Analizar vulnerabilidades SQL Injection
     */
    private function analyzeSQLInjection() {
        $sqlIssues = [];
        $files = glob('app/**/*.php', GLOB_BRACE);
        
        foreach ($files as $file) {
            $content = file_get_contents($file);
            
            // Buscar patrones peligrosos
            $dangerousPatterns = [
                '$_GET' => 'Variable GET sin sanitizar',
                '$_POST' => 'Variable POST sin sanitizar',
                '$_REQUEST' => 'Variable REQUEST sin sanitizar',
                'mysql_query(' => 'mysql_query (deprecated)',
                'mysqli_query(' => 'mysqli_query sin prepared statements'
            ];
            
            foreach ($dangerousPatterns as $pattern => $description) {
                if (strpos($content, $pattern) !== false) {
                    $sqlIssues[] = [
                        'file' => $file,
                        'issue' => $description,
                        'pattern' => $pattern
                    ];
                }
            }
        }
        
        $this->results['security']['sql_injection'] = $sqlIssues;
        
        if (!empty($sqlIssues)) {
            echo "⚠️  Posibles vulnerabilidades SQL: " . count($sqlIssues) . "\n";
            foreach ($sqlIssues as $issue) {
                echo "   - {$issue['file']}: {$issue['issue']}\n";
            }
        }
    }
    
    /**
     * Analizar vulnerabilidades XSS
     */
    private function analyzeXSSVulnerabilities() {
        $xssIssues = [];
        $files = glob('app/**/*.php', GLOB_BRACE);
        
        foreach ($files as $file) {
            $content = file_get_contents($file);
            
            // Buscar patrones XSS
            if (strpos($content, 'echo $_') !== false || strpos($content, 'print $_') !== false) {
                $xssIssues[] = [
                    'file' => $file,
                    'issue' => 'Variable sin escapar en output'
                ];
            }
        }
        
        $this->results['security']['xss_vulnerabilities'] = $xssIssues;
        
        if (!empty($xssIssues)) {
            echo "⚠️  Posibles vulnerabilidades XSS: " . count($xssIssues) . "\n";
            foreach ($xssIssues as $issue) {
                echo "   - {$issue['file']}: {$issue['issue']}\n";
            }
        }
    }
    
    /**
     * Analizar uploads de archivos
     */
    private function analyzeFileUploads() {
        $uploadIssues = [];
        $files = glob('app/**/*.php', GLOB_BRACE);
        
        foreach ($files as $file) {
            $content = file_get_contents($file);
            
            if (strpos($content, '$_FILES') !== false) {
                // Verificar validación de archivos
                if (strpos($content, 'pathinfo') === false && strpos($content, 'mime_content_type') === false) {
                    $uploadIssues[] = [
                        'file' => $file,
                        'issue' => 'Upload sin validación de tipo de archivo'
                    ];
                }
            }
        }
        
        $this->results['security']['file_upload_issues'] = $uploadIssues;
        
        if (!empty($uploadIssues)) {
            echo "⚠️  Problemas en uploads: " . count($uploadIssues) . "\n";
            foreach ($uploadIssues as $issue) {
                echo "   - {$issue['file']}: {$issue['issue']}\n";
            }
        }
    }
    
    /**
     * Generar reporte completo
     */
    private function generateReport() {
        $totalTime = microtime(true) - $this->startTime;
        
        echo "\n" . str_repeat("=", 60) . "\n";
        echo "📋 REPORTE COMPLETO DE RENDIMIENTO - BYFROST\n";
        echo str_repeat("=", 60) . "\n";
        
        // Resumen ejecutivo
        $this->generateExecutiveSummary();
        
        // Recomendaciones
        $this->generateRecommendations();
        
        // Estadísticas
        $this->generateStatistics($totalTime);
        
        echo "\n" . str_repeat("=", 60) . "\n";
        echo "✅ Análisis completado en " . round($totalTime, 2) . " segundos\n";
        echo str_repeat("=", 60) . "\n";
    }
    
    /**
     * Generar resumen ejecutivo
     */
    private function generateExecutiveSummary() {
        echo "\n🎯 RESUMEN EJECUTIVO\n";
        echo str_repeat("-", 30) . "\n";
        
        $totalIssues = 0;
        $criticalIssues = 0;
        
        // Contar problemas
        foreach ($this->results as $category => $data) {
            if (is_array($data)) {
                foreach ($data as $key => $value) {
                    if (is_array($value)) {
                        $totalIssues += count($value);
                        if (in_array($key, ['sql_injection', 'xss_vulnerabilities', 'missing_indexes'])) {
                            $criticalIssues += count($value);
                        }
                    }
                }
            }
        }
        
        echo "📊 Problemas detectados: $totalIssues\n";
        echo "🚨 Problemas críticos: $criticalIssues\n";
        
        if ($criticalIssues > 0) {
            echo "⚠️  ATENCIÓN: Se detectaron problemas críticos de seguridad\n";
        }
        
        if ($totalIssues > 20) {
            echo "🔧 RECOMENDACIÓN: El sistema necesita optimización\n";
        } else {
            echo "✅ El sistema está en buen estado general\n";
        }
    }
    
    /**
     * Generar recomendaciones
     */
    private function generateRecommendations() {
        echo "\n💡 RECOMENDACIONES\n";
        echo str_repeat("-", 30) . "\n";
        
        $recommendations = [];
        
        // Recomendaciones de base de datos
        if (!empty($this->results['database']['missing_indexes'])) {
            $recommendations[] = "🔧 Crear índices faltantes para mejorar rendimiento";
        }
        
        if (!empty($this->results['database']['slow_queries'])) {
            $recommendations[] = "⚡ Optimizar consultas lentas";
        }
        
        // Recomendaciones de archivos
        if (!empty($this->results['filesystem']['large_files'])) {
            $recommendations[] = "📏 Dividir archivos grandes en módulos más pequeños";
        }
        
        if (!empty($this->results['filesystem']['insecure_files'])) {
            $recommendations[] = "🔒 Corregir permisos de archivos críticos";
        }
        
        // Recomendaciones de código
        if (!empty($this->results['code_quality']['complex_functions'])) {
            $recommendations[] = "🔧 Refactorizar funciones complejas";
        }
        
        if (!empty($this->results['code_quality']['duplicated_patterns'])) {
            $recommendations[] = "🔄 Eliminar código duplicado";
        }
        
        // Recomendaciones de seguridad
        if (!empty($this->results['security']['sql_injection'])) {
            $recommendations[] = "🚨 URGENTE: Corregir vulnerabilidades SQL Injection";
        }
        
        if (!empty($this->results['security']['xss_vulnerabilities'])) {
            $recommendations[] = "🚨 URGENTE: Corregir vulnerabilidades XSS";
        }
        
        foreach ($recommendations as $i => $rec) {
            echo ($i + 1) . ". $rec\n";
        }
    }
    
    /**
     * Generar estadísticas
     */
    private function generateStatistics($totalTime) {
        echo "\n📈 ESTADÍSTICAS\n";
        echo str_repeat("-", 30) . "\n";
        
        echo "⏱️  Tiempo de análisis: " . round($totalTime, 2) . "s\n";
        
        if (isset($this->results['database']['connection_usage'])) {
            echo "🔌 Uso de conexiones DB: {$this->results['database']['connection_usage']}%\n";
        }
        
        if (isset($this->results['routing']['controller_stats'])) {
            $stats = $this->results['routing']['controller_stats'];
            echo "📁 Controladores: {$stats['total']}\n";
            echo "📏 Archivos grandes: {$stats['large_files']}\n";
        }
        
        if (isset($this->results['routing']['view_stats'])) {
            $stats = $this->results['routing']['view_stats'];
            echo "👁️  Vistas: {$stats['total']}\n";
            echo "📏 Vistas grandes: {$stats['large_files']}\n";
        }
    }
}

// Ejecutar análisis
echo "🚀 Iniciando análisis de rendimiento ByFrost...\n\n";

$analyzer = new PerformanceAnalyzer($pdo);
$analyzer->analyzeSystem();

echo "\n🎉 Análisis completado. Revisa el reporte anterior para recomendaciones.\n";
?> 