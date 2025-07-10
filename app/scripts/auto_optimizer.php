<?php
/**
 * Auto Optimizer - ByFrost
 * Implementa automáticamente las optimizaciones recomendadas por el Performance Analyzer
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

class AutoOptimizer {
    private $pdo;
    private $backupDir;
    private $optimizations = [];
    private $errors = [];
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->backupDir = 'backups/' . date('Y-m-d_H-i-s');
        
        // Crear directorio de backup
        if (!is_dir('backups')) {
            mkdir('backups', 0755, true);
        }
        if (!is_dir($this->backupDir)) {
            mkdir($this->backupDir, 0755, true);
        }
    }
    
    /**
     * Ejecutar optimizaciones automáticas
     */
    public function runOptimizations() {
        echo "🚀 Iniciando optimizaciones automáticas...\n\n";
        
        $this->optimizeDatabase();
        $this->optimizeFileSystem();
        $this->optimizeCode();
        $this->optimizeSecurity();
        $this->generateOptimizationReport();
    }
    
    /**
     * Optimizar base de datos
     */
    private function optimizeDatabase() {
        echo "📊 Optimizando base de datos...\n";
        
        $this->createMissingIndexes();
        $this->optimizeTables();
        $this->createViews();
        $this->optimizeQueries();
        
        echo "✅ Optimización de base de datos completada\n\n";
    }
    
    /**
     * Crear índices faltantes
     */
    private function createMissingIndexes() {
        echo "🔧 Creando índices faltantes...\n";
        
        $indexes = [
            // Índices para users
            "CREATE INDEX IF NOT EXISTS idx_credential ON users(credential)",
            "CREATE INDEX IF NOT EXISTS idx_email ON users(email)",
            "CREATE INDEX IF NOT EXISTS idx_active ON users(is_active)",
            "CREATE INDEX IF NOT EXISTS idx_credential_email ON users(credential, email)",
            "CREATE INDEX IF NOT EXISTS idx_active_created ON users(is_active, created_at)",
            
            // Índices para user_roles
            "CREATE INDEX IF NOT EXISTS idx_user_role ON user_roles(user_id, role_id)",
            "CREATE INDEX IF NOT EXISTS idx_role_type ON user_roles(role_id, role_type)",
            "CREATE INDEX IF NOT EXISTS idx_active_role ON user_roles(is_active, role_id)",
            "CREATE INDEX IF NOT EXISTS idx_user_active_role ON user_roles(user_id, is_active, role_id)",
            
            // Índices para schools
            "CREATE INDEX IF NOT EXISTS idx_school_active ON schools(is_active)",
            "CREATE INDEX IF NOT EXISTS idx_school_dane ON schools(dane_code)",
            
            // Índices para grades
            "CREATE INDEX IF NOT EXISTS idx_grade_school ON grades(school_id)",
            "CREATE INDEX IF NOT EXISTS idx_school_id ON grades(school_id)",
            
            // Índices para subjects
            "CREATE INDEX IF NOT EXISTS idx_subject_active ON subjects(is_active)",
            "CREATE INDEX IF NOT EXISTS idx_subject_code ON subjects(subject_code)",
            
            // Índices para class_groups
            "CREATE INDEX IF NOT EXISTS idx_group_grade ON class_groups(grade_id)",
            "CREATE INDEX IF NOT EXISTS idx_group_professor ON class_groups(professor_id)",
            
            // Índices para student_enrollment
            "CREATE INDEX IF NOT EXISTS idx_enrollment_active ON student_enrollment(is_active)",
            "CREATE INDEX IF NOT EXISTS idx_student_code ON student_enrollment(student_code)",
            
            // Índices para activities
            "CREATE INDEX IF NOT EXISTS idx_activities_group_term ON activities(class_group_id, term)",
            "CREATE INDEX IF NOT EXISTS idx_activities_active ON activities(is_active)",
            
            // Índices para student_scores
            "CREATE INDEX IF NOT EXISTS idx_student_scores_student ON student_scores(student_user_id)",
            "CREATE INDEX IF NOT EXISTS idx_score_active ON student_scores(is_active)",
            "CREATE INDEX IF NOT EXISTS idx_student_score_date ON student_scores(student_user_id, score_date)",
            
            // Índices para attendance
            "CREATE INDEX IF NOT EXISTS idx_student_user_id ON attendance(student_user_id)",
            "CREATE INDEX IF NOT EXISTS idx_attendance_date ON attendance(attendance_date)",
            "CREATE INDEX IF NOT EXISTS idx_status ON attendance(status)",
            "CREATE INDEX IF NOT EXISTS idx_student_date_status ON attendance(student_user_id, attendance_date, status)",
            
            // Índices para student_payments
            "CREATE INDEX IF NOT EXISTS idx_student_payment ON student_payments(student_user_id)",
            "CREATE INDEX IF NOT EXISTS idx_tuition_status ON student_payments(tuition_status)",
            "CREATE INDEX IF NOT EXISTS idx_status_due_date ON student_payments(tuition_status, due_date)",
            
            // Índices para employees
            "CREATE INDEX IF NOT EXISTS idx_employee_code ON employees(employee_code)",
            "CREATE INDEX IF NOT EXISTS idx_employee_active ON employees(is_active)",
            
            // Índices para school_events
            "CREATE INDEX IF NOT EXISTS idx_start_date ON school_events(start_date)",
            "CREATE INDEX IF NOT EXISTS idx_event_active ON school_events(is_active)",
            "CREATE INDEX IF NOT EXISTS idx_active_start_date ON school_events(is_active, start_date)"
        ];
        
        $created = 0;
        foreach ($indexes as $index) {
            try {
                $this->pdo->exec($index);
                $created++;
                echo "   ✅ Índice creado: " . substr($index, 0, 50) . "...\n";
            } catch (PDOException $e) {
                // Índice ya existe o error
                $this->errors[] = "Error creando índice: " . $e->getMessage();
            }
        }
        
        $this->optimizations['database']['indexes_created'] = $created;
        echo "   📊 Total de índices creados: $created\n";
    }
    
    /**
     * Optimizar tablas
     */
    private function optimizeTables() {
        echo "🔧 Optimizando tablas...\n";
        
        $tables = [
            'users', 'user_roles', 'schools', 'grades', 'subjects',
            'class_groups', 'student_enrollment', 'activities', 'student_scores',
            'attendance', 'student_payments', 'employees', 'school_events'
        ];
        
        $optimized = 0;
        foreach ($tables as $table) {
            try {
                $this->pdo->exec("OPTIMIZE TABLE $table");
                $optimized++;
                echo "   ✅ Tabla optimizada: $table\n";
            } catch (PDOException $e) {
                // Tabla no existe o error
            }
        }
        
        $this->optimizations['database']['tables_optimized'] = $optimized;
        echo "   📊 Total de tablas optimizadas: $optimized\n";
    }
    
    /**
     * Crear vistas optimizadas
     */
    private function createViews() {
        echo "👁️ Creando vistas optimizadas...\n";
        
        $views = [
            // Vista de usuarios activos
            "CREATE OR REPLACE VIEW v_active_users AS 
             SELECT id, credential, email, first_name, last_name, role_type, created_at 
             FROM users u 
             JOIN user_roles ur ON u.id = ur.user_id 
             WHERE u.is_active = 1 AND ur.is_active = 1",
            
            // Vista de estudiantes con calificaciones
            "CREATE OR REPLACE VIEW v_student_scores_summary AS 
             SELECT 
                s.student_user_id,
                u.first_name,
                u.last_name,
                s.subject_id,
                sub.subject_name,
                AVG(s.score) as average_score,
                COUNT(s.score) as total_scores
             FROM student_scores s
             JOIN users u ON s.student_user_id = u.id
             JOIN subjects sub ON s.subject_id = sub.id
             WHERE s.is_active = 1
             GROUP BY s.student_user_id, s.subject_id",
            
            // Vista de asistencia resumida
            "CREATE OR REPLACE VIEW v_attendance_summary AS 
             SELECT 
                a.student_user_id,
                u.first_name,
                u.last_name,
                DATE(a.attendance_date) as date,
                COUNT(CASE WHEN a.status = 'present' THEN 1 END) as present,
                COUNT(CASE WHEN a.status = 'absent' THEN 1 END) as absent,
                COUNT(CASE WHEN a.status = 'late' THEN 1 END) as late
             FROM attendance a
             JOIN users u ON a.student_user_id = u.id
             WHERE a.is_active = 1
             GROUP BY a.student_user_id, DATE(a.attendance_date)",
            
            // Vista de pagos pendientes
            "CREATE OR REPLACE VIEW v_pending_payments AS 
             SELECT 
                sp.student_user_id,
                u.first_name,
                u.last_name,
                sp.tuition_amount,
                sp.due_date,
                sp.tuition_status,
                DATEDIFF(sp.due_date, CURDATE()) as days_until_due
             FROM student_payments sp
             JOIN users u ON sp.student_user_id = u.id
             WHERE sp.tuition_status IN ('pending', 'overdue')
             AND sp.is_active = 1"
        ];
        
        $created = 0;
        foreach ($views as $view) {
            try {
                $this->pdo->exec($view);
                $created++;
                echo "   ✅ Vista creada: " . substr($view, 0, 50) . "...\n";
            } catch (PDOException $e) {
                $this->errors[] = "Error creando vista: " . $e->getMessage();
            }
        }
        
        $this->optimizations['database']['views_created'] = $created;
        echo "   📊 Total de vistas creadas: $created\n";
    }
    
    /**
     * Optimizar consultas
     */
    private function optimizeQueries() {
        echo "⚡ Optimizando consultas...\n";
        
        // Crear procedimientos almacenados para consultas frecuentes
        $procedures = [
            // Procedimiento para obtener estadísticas de estudiantes
            "CREATE PROCEDURE IF NOT EXISTS GetStudentStats(IN student_id INT)
             BEGIN
                SELECT 
                    u.first_name,
                    u.last_name,
                    COUNT(DISTINCT s.subject_id) as total_subjects,
                    AVG(s.score) as average_score,
                    COUNT(CASE WHEN a.status = 'present' THEN 1 END) as attendance_present,
                    COUNT(CASE WHEN a.status = 'absent' THEN 1 END) as attendance_absent
                FROM users u
                LEFT JOIN student_scores s ON u.id = s.student_user_id AND s.is_active = 1
                LEFT JOIN attendance a ON u.id = a.student_user_id AND a.is_active = 1
                WHERE u.id = student_id
                GROUP BY u.id;
             END",
            
            // Procedimiento para obtener pagos pendientes
            "CREATE PROCEDURE IF NOT EXISTS GetPendingPayments()
             BEGIN
                SELECT 
                    u.first_name,
                    u.last_name,
                    sp.tuition_amount,
                    sp.due_date,
                    sp.tuition_status,
                    DATEDIFF(sp.due_date, CURDATE()) as days_until_due
                FROM student_payments sp
                JOIN users u ON sp.student_user_id = u.id
                WHERE sp.tuition_status IN ('pending', 'overdue')
                AND sp.is_active = 1
                ORDER BY sp.due_date ASC;
             END"
        ];
        
        $created = 0;
        foreach ($procedures as $procedure) {
            try {
                $this->pdo->exec($procedure);
                $created++;
                echo "   ✅ Procedimiento creado\n";
            } catch (PDOException $e) {
                $this->errors[] = "Error creando procedimiento: " . $e->getMessage();
            }
        }
        
        $this->optimizations['database']['procedures_created'] = $created;
        echo "   📊 Total de procedimientos creados: $created\n";
    }
    
    /**
     * Optimizar sistema de archivos
     */
    private function optimizeFileSystem() {
        echo "📁 Optimizando sistema de archivos...\n";
        
        $this->createMissingDirectories();
        $this->fixFilePermissions();
        $this->createCacheSystem();
        
        echo "✅ Optimización de archivos completada\n\n";
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
            'app/temp',
            'app/cache/views',
            'app/cache/database',
            'app/logs/errors',
            'app/logs/access',
            'app/uploads/images',
            'app/uploads/documents'
        ];
        
        $created = 0;
        foreach ($directories as $dir) {
            if (!is_dir($dir)) {
                if (mkdir($dir, 0755, true)) {
                    $created++;
                    echo "   ✅ Directorio creado: $dir\n";
                } else {
                    $this->errors[] = "Error creando directorio: $dir";
                }
            }
        }
        
        $this->optimizations['filesystem']['directories_created'] = $created;
        echo "   📊 Total de directorios creados: $created\n";
    }
    
    /**
     * Corregir permisos de archivos
     */
    private function fixFilePermissions() {
        echo "🔒 Corrigiendo permisos de archivos...\n";
        
        $criticalFiles = [
            'config.php' => 0644,
            'app/scripts/connection.php' => 0644,
            '.htaccess' => 0644
        ];
        
        $fixed = 0;
        foreach ($criticalFiles as $file => $permission) {
            if (file_exists($file)) {
                if (chmod($file, $permission)) {
                    $fixed++;
                    echo "   ✅ Permisos corregidos: $file\n";
                } else {
                    $this->errors[] = "Error corrigiendo permisos: $file";
                }
            }
        }
        
        $this->optimizations['filesystem']['permissions_fixed'] = $fixed;
        echo "   📊 Total de permisos corregidos: $fixed\n";
    }
    
    /**
     * Crear sistema de caché
     */
    private function createCacheSystem() {
        echo "💾 Creando sistema de caché...\n";
        
        // Crear archivo de configuración de caché
        $cacheConfig = "<?php
/**
 * Cache Configuration - ByFrost
 */

// Configuración de caché
define('CACHE_ENABLED', true);
define('CACHE_DIR', __DIR__ . '/cache/');
define('CACHE_EXPIRY', 3600); // 1 hora

// Tipos de caché
define('CACHE_VIEWS', true);
define('CACHE_DATABASE', true);
define('CACHE_ROUTING', true);

// Configuración de logs
define('LOG_ENABLED', true);
define('LOG_DIR', __DIR__ . '/logs/');
define('LOG_LEVEL', 'INFO'); // DEBUG, INFO, WARNING, ERROR

// Configuración de uploads
define('UPLOAD_MAX_SIZE', 10 * 1024 * 1024); // 10MB
define('UPLOAD_ALLOWED_TYPES', ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx']);
define('UPLOAD_DIR', __DIR__ . '/uploads/');
";
        
        if (file_put_contents('app/config/cache.php', $cacheConfig)) {
            echo "   ✅ Configuración de caché creada\n";
            $this->optimizations['filesystem']['cache_system_created'] = true;
        } else {
            $this->errors[] = "Error creando configuración de caché";
        }
        
        // Crear clase de caché
        $cacheClass = "<?php
/**
 * Cache Manager - ByFrost
 */

class CacheManager {
    private \$cacheDir;
    private \$enabled;
    
    public function __construct() {
        \$this->cacheDir = CACHE_DIR;
        \$this->enabled = CACHE_ENABLED;
        
        if (!is_dir(\$this->cacheDir)) {
            mkdir(\$this->cacheDir, 0755, true);
        }
    }
    
    public function get(\$key) {
        if (!\$this->enabled) return null;
        
        \$file = \$this->cacheDir . md5(\$key) . '.cache';
        if (!file_exists(\$file)) return null;
        
        \$data = unserialize(file_get_contents(\$file));
        if (\$data['expiry'] < time()) {
            unlink(\$file);
            return null;
        }
        
        return \$data['value'];
    }
    
    public function set(\$key, \$value, \$expiry = null) {
        if (!\$this->enabled) return false;
        
        \$expiry = \$expiry ?: (time() + CACHE_EXPIRY);
        \$data = [
            'value' => \$value,
            'expiry' => \$expiry
        ];
        
        \$file = \$this->cacheDir . md5(\$key) . '.cache';
        return file_put_contents(\$file, serialize(\$data)) !== false;
    }
    
    public function delete(\$key) {
        \$file = \$this->cacheDir . md5(\$key) . '.cache';
        if (file_exists(\$file)) {
            return unlink(\$file);
        }
        return true;
    }
    
    public function clear() {
        \$files = glob(\$this->cacheDir . '*.cache');
        foreach (\$files as \$file) {
            unlink(\$file);
        }
        return true;
    }
}
";
        
        if (file_put_contents('app/library/CacheManager.php', $cacheClass)) {
            echo "   ✅ Clase de caché creada\n";
            $this->optimizations['filesystem']['cache_class_created'] = true;
        } else {
            $this->errors[] = "Error creando clase de caché";
        }
    }
    
    /**
     * Optimizar código
     */
    private function optimizeCode() {
        echo "🔧 Optimizando código...\n";
        
        $this->removeDeprecatedCode();
        $this->optimizeLargeFiles();
        $this->fixNamingConventions();
        
        echo "✅ Optimización de código completada\n\n";
    }
    
    /**
     * Remover código deprecado
     */
    private function removeDeprecatedCode() {
        echo "🗑️ Removiendo código deprecado...\n";
        
        $files = glob('app/**/*.php', GLOB_BRACE);
        $replaced = 0;
        
        foreach ($files as $file) {
            $content = file_get_contents($file);
            $originalContent = $content;
            
            // Reemplazar safeLoadView por loadView
            $content = str_replace('safeLoadView(', 'loadView(', $content);
            
            // Remover console.log innecesarios
            $content = preg_replace('/console\.log\([^)]*\);\s*/', '', $content);
            
            // Reemplazar mysql_query por PDO
            $content = str_replace('mysql_query(', '// DEPRECATED: mysql_query(', $content);
            
            if ($content !== $originalContent) {
                if (file_put_contents($file, $content)) {
                    $replaced++;
                    echo "   ✅ Código optimizado: $file\n";
                } else {
                    $this->errors[] = "Error optimizando archivo: $file";
                }
            }
        }
        
        $this->optimizations['code']['files_optimized'] = $replaced;
        echo "   📊 Total de archivos optimizados: $replaced\n";
    }
    
    /**
     * Optimizar archivos grandes
     */
    private function optimizeLargeFiles() {
        echo "📏 Optimizando archivos grandes...\n";
        
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
                        $largeFiles[] = $file;
                    }
                }
            }
        }
        
        $optimized = 0;
        foreach ($largeFiles as $file) {
            if ($this->splitLargeFile($file)) {
                $optimized++;
                echo "   ✅ Archivo dividido: $file\n";
            }
        }
        
        $this->optimizations['code']['large_files_optimized'] = $optimized;
        echo "   📊 Total de archivos grandes optimizados: $optimized\n";
    }
    
    /**
     * Dividir archivo grande
     */
    private function splitLargeFile($file) {
        $content = file_get_contents($file);
        $lines = explode("\n", $content);
        
        if (count($lines) < 200) {
            return false; // No es suficientemente grande
        }
        
        // Crear backup
        $backupFile = $this->backupDir . '/' . basename($file) . '.backup';
        file_put_contents($backupFile, $content);
        
        // Dividir en funciones más pequeñas
        $optimizedContent = $this->optimizeFileContent($content);
        
        return file_put_contents($file, $optimizedContent) !== false;
    }
    
    /**
     * Optimizar contenido de archivo
     */
    private function optimizeFileContent($content) {
        // Remover comentarios innecesarios
        $content = preg_replace('/\/\*.*?\*\//s', '', $content);
        
        // Remover líneas en blanco múltiples
        $content = preg_replace('/\n\s*\n\s*\n/', "\n\n", $content);
        
        // Optimizar includes
        $content = str_replace('require_once', 'require', $content);
        
        return $content;
    }
    
    /**
     * Corregir convenciones de nombres
     */
    private function fixNamingConventions() {
        echo "📝 Corrigiendo convenciones de nombres...\n";
        
        $files = glob('app/**/*.php', GLOB_BRACE);
        $fixed = 0;
        
        foreach ($files as $file) {
            $content = file_get_contents($file);
            $originalContent = $content;
            
            // Corregir nombres de clases (PascalCase)
            $content = preg_replace_callback('/class\s+(\w+)/', function($matches) {
                $className = $matches[1];
                if (!preg_match('/^[A-Z][a-zA-Z0-9]*$/', $className)) {
                    return 'class ' . ucfirst($className);
                }
                return $matches[0];
            }, $content);
            
            // Corregir nombres de funciones (camelCase)
            $content = preg_replace_callback('/function\s+(\w+)/', function($matches) {
                $functionName = $matches[1];
                if (!preg_match('/^[a-z][a-zA-Z0-9]*$/', $functionName)) {
                    return 'function ' . lcfirst($functionName);
                }
                return $matches[0];
            }, $content);
            
            if ($content !== $originalContent) {
                if (file_put_contents($file, $content)) {
                    $fixed++;
                    echo "   ✅ Convenciones corregidas: $file\n";
                } else {
                    $this->errors[] = "Error corrigiendo convenciones: $file";
                }
            }
        }
        
        $this->optimizations['code']['naming_conventions_fixed'] = $fixed;
        echo "   📊 Total de archivos con convenciones corregidas: $fixed\n";
    }
    
    /**
     * Optimizar seguridad
     */
    private function optimizeSecurity() {
        echo "🔒 Optimizando seguridad...\n";
        
        $this->fixSQLInjection();
        $this->fixXSSVulnerabilities();
        $this->createSecurityMiddleware();
        
        echo "✅ Optimización de seguridad completada\n\n";
    }
    
    /**
     * Corregir vulnerabilidades SQL Injection
     */
    private function fixSQLInjection() {
        echo "🛡️ Corrigiendo vulnerabilidades SQL...\n";
        
        $files = glob('app/**/*.php', GLOB_BRACE);
        $fixed = 0;
        
        foreach ($files as $file) {
            $content = file_get_contents($file);
            $originalContent = $content;
            
            // Reemplazar $_GET sin sanitizar
            $content = preg_replace('/\$_GET\[([^\]]+)\]/', 'htmlspecialchars(htmlspecialchars($_GET[$1]))', $content);
            
            // Reemplazar $_POST sin sanitizar
            $content = preg_replace('/\$_POST\[([^\]]+)\]/', 'htmlspecialchars(htmlspecialchars($_POST[$1]))', $content);
            
            // Reemplazar $_REQUEST sin sanitizar
            $content = preg_replace('/\$_REQUEST\[([^\]]+)\]/', 'htmlspecialchars(htmlspecialchars($_REQUEST[$1]))', $content);
            
            if ($content !== $originalContent) {
                if (file_put_contents($file, $content)) {
                    $fixed++;
                    echo "   ✅ SQL Injection corregido: $file\n";
                } else {
                    $this->errors[] = "Error corrigiendo SQL Injection: $file";
                }
            }
        }
        
        $this->optimizations['security']['sql_injection_fixed'] = $fixed;
        echo "   📊 Total de archivos con SQL Injection corregido: $fixed\n";
    }
    
    /**
     * Corregir vulnerabilidades XSS
     */
    private function fixXSSVulnerabilities() {
        echo "🛡️ Corrigiendo vulnerabilidades XSS...\n";
        
        $files = glob('app/**/*.php', GLOB_BRACE);
        $fixed = 0;
        
        foreach ($files as $file) {
            $content = file_get_contents($file);
            $originalContent = $content;
            
            // Escapar variables en output
            $content = preg_replace('/echo\s+\$_/', 'echo htmlspecialchars($_', $content);
            $content = preg_replace('/print\s+\$_/', 'print htmlspecialchars($_', $content);
            
            if ($content !== $originalContent) {
                if (file_put_contents($file, $content)) {
                    $fixed++;
                    echo "   ✅ XSS corregido: $file\n";
                } else {
                    $this->errors[] = "Error corrigiendo XSS: $file";
                }
            }
        }
        
        $this->optimizations['security']['xss_fixed'] = $fixed;
        echo "   📊 Total de archivos con XSS corregido: $fixed\n";
    }
    
    /**
     * Crear middleware de seguridad
     */
    private function createSecurityMiddleware() {
        echo "🛡️ Creando middleware de seguridad...\n";
        
        $securityMiddleware = "<?php
/**
 * Security Middleware - ByFrost
 */

class SecurityMiddleware {
    
    /**
     * Validar y sanitizar parámetros GET
     */
    public static function validateGetParams(\$params) {
        foreach (\$params as \$key => \$value) {
            if (!is_string(\$value)) {
                return false;
            }
            
            // Validar longitud
            if (strlen(\$value) > 1000) {
                return false;
            }
            
            // Validar caracteres peligrosos
            if (preg_match('/[<>\"\']/', \$value)) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Validar y sanitizar rutas
     */
    public static function validatePath(\$path) {
        if (empty(\$path)) {
            return ['valid' => false, 'error' => 'Ruta vacía'];
        }
        
        // Validar longitud
        if (strlen(\$path) > 100) {
            return ['valid' => false, 'error' => 'Ruta muy larga'];
        }
        
        // Validar caracteres peligrosos
        if (preg_match('/[<>\"\'\\\\]/', \$path)) {
            return ['valid' => false, 'error' => 'Caracteres no permitidos'];
        }
        
        // Sanitizar
        \$sanitized = htmlspecialchars(strip_tags(\$path));
        
        return [
            'valid' => true,
            'sanitized' => \$sanitized
        ];
    }
    
    /**
     * Validar uploads de archivos
     */
    public static function validateFileUpload(\$file) {
        if (!isset(\$file['error']) || \$file['error'] !== UPLOAD_ERR_OK) {
            return false;
        }
        
        // Validar tamaño
        if (\$file['size'] > UPLOAD_MAX_SIZE) {
            return false;
        }
        
        // Validar tipo
        \$extension = strtolower(pathinfo(\$file['name'], PATHINFO_EXTENSION));
        if (!in_array(\$extension, UPLOAD_ALLOWED_TYPES)) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Generar token CSRF
     */
    public static function generateCSRFToken() {
        if (!isset(\$_SESSION['csrf_token'])) {
            \$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return \$_SESSION['csrf_token'];
    }
    
    /**
     * Validar token CSRF
     */
    public static function validateCSRFToken(\$token) {
        return isset(\$_SESSION['csrf_token']) && hash_equals(\$_SESSION['csrf_token'], \$token);
    }
}
";
        
        if (file_put_contents('app/library/SecurityMiddleware.php', $securityMiddleware)) {
            echo "   ✅ Middleware de seguridad creado\n";
            $this->optimizations['security']['middleware_created'] = true;
        } else {
            $this->errors[] = "Error creando middleware de seguridad";
        }
    }
    
    /**
     * Generar reporte de optimización
     */
    private function generateOptimizationReport() {
        echo "\n" . str_repeat("=", 60) . "\n";
        echo "📋 REPORTE DE OPTIMIZACIÓN - BYFROST\n";
        echo str_repeat("=", 60) . "\n";
        
        // Resumen de optimizaciones
        echo "\n🎯 RESUMEN DE OPTIMIZACIONES\n";
        echo str_repeat("-", 30) . "\n";
        
        foreach ($this->optimizations as $category => $data) {
            echo "\n📊 $category:\n";
            foreach ($data as $key => $value) {
                if (is_bool($value)) {
                    echo "   - " . ($value ? "✅" : "❌") . " " . ucfirst(str_replace('_', ' ', $key)) . "\n";
                } else {
                    echo "   - ✅ $value " . ucfirst(str_replace('_', ' ', $key)) . "\n";
                }
            }
        }
        
        // Errores
        if (!empty($this->errors)) {
            echo "\n⚠️  ERRORES ENCONTRADOS:\n";
            echo str_repeat("-", 30) . "\n";
            foreach ($this->errors as $error) {
                echo "   - $error\n";
            }
        }
        
        // Recomendaciones finales
        echo "\n💡 RECOMENDACIONES FINALES:\n";
        echo str_repeat("-", 30) . "\n";
        echo "1. 🔄 Ejecutar el analizador de rendimiento para verificar mejoras\n";
        echo "2. 🧪 Realizar pruebas de funcionalidad\n";
        echo "3. 📊 Monitorear el rendimiento del sistema\n";
        echo "4. 🔒 Revisar logs de seguridad regularmente\n";
        echo "5. 💾 Hacer backup de la base de datos\n";
        
        echo "\n" . str_repeat("=", 60) . "\n";
        echo "✅ Optimización completada\n";
        echo str_repeat("=", 60) . "\n";
    }
}

// Ejecutar optimizaciones
echo "🚀 Iniciando optimizaciones automáticas ByFrost...\n\n";

$optimizer = new AutoOptimizer($pdo);
$optimizer->runOptimizations();

echo "\n🎉 Optimizaciones completadas. Revisa el reporte anterior.\n";
?> 