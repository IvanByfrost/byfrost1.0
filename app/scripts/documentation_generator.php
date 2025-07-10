<?php
/**
 * Documentation Generator - ByFrost
 * Genera documentación automática del proyecto
 */

class DocumentationGenerator {
    private $projectRoot;
    private $outputDir;
    private $documentation = [];
    
    public function __construct() {
        $this->projectRoot = __DIR__ . '/../../';
        $this->outputDir = $this->projectRoot . 'documentation/';
        
        // Crear directorio de documentación
        if (!is_dir($this->outputDir)) {
            mkdir($this->outputDir, 0755, true);
        }
    }
    
    /**
     * Generar documentación completa
     */
    public function generateDocumentation() {
        echo "📚 Generando documentación completa de ByFrost...\n\n";
        
        $this->analyzeProjectStructure();
        $this->documentControllers();
        $this->documentModels();
        $this->documentViews();
        $this->documentDatabase();
        $this->documentRouting();
        $this->documentSecurity();
        $this->generateAPI();
        $this->generateUserGuide();
        $this->generateDeveloperGuide();
        $this->generateREADME();
        
        echo "✅ Documentación generada exitosamente\n";
        echo "📁 Ubicación: {$this->outputDir}\n";
    }
    
    /**
     * Analizar estructura del proyecto
     */
    private function analyzeProjectStructure() {
        echo "📁 Analizando estructura del proyecto...\n";
        
        $this->documentation['structure'] = [
            'controllers' => $this->scanDirectory('app/controllers'),
            'models' => $this->scanDirectory('app/models'),
            'views' => $this->scanDirectory('app/views'),
            'library' => $this->scanDirectory('app/library'),
            'resources' => $this->scanDirectory('app/resources'),
            'scripts' => $this->scanDirectory('app/scripts')
        ];
        
        echo "   ✅ Estructura analizada\n";
    }
    
    /**
     * Escanear directorio recursivamente
     */
    private function scanDirectory($path) {
        $fullPath = $this->projectRoot . $path;
        if (!is_dir($fullPath)) {
            return [];
        }
        
        $items = [];
        $files = scandir($fullPath);
        
        foreach ($files as $file) {
            if ($file === '.' || $file === '..') continue;
            
            $itemPath = $fullPath . '/' . $file;
            if (is_dir($itemPath)) {
                $items[$file] = [
                    'type' => 'directory',
                    'contents' => $this->scanDirectory($path . '/' . $file)
                ];
            } else {
                $items[$file] = [
                    'type' => 'file',
                    'size' => filesize($itemPath),
                    'modified' => date('Y-m-d H:i:s', filemtime($itemPath))
                ];
            }
        }
        
        return $items;
    }
    
    /**
     * Documentar controladores
     */
    private function documentControllers() {
        echo "🎮 Documentando controladores...\n";
        
        $controllers = glob($this->projectRoot . 'app/controllers/*.php');
        $this->documentation['controllers'] = [];
        
        foreach ($controllers as $controller) {
            $content = file_get_contents($controller);
            $className = pathinfo($controller, PATHINFO_FILENAME);
            
            $controllerDoc = [
                'name' => $className,
                'file' => basename($controller),
                'methods' => $this->extractMethods($content),
                'dependencies' => $this->extractDependencies($content),
                'description' => $this->extractDescription($content)
            ];
            
            $this->documentation['controllers'][$className] = $controllerDoc;
        }
        
        echo "   ✅ " . count($controllers) . " controladores documentados\n";
    }
    
    /**
     * Extraer métodos de una clase
     */
    private function extractMethods($content) {
        $methods = [];
        preg_match_all('/public\s+function\s+(\w+)\s*\([^)]*\)/', $content, $matches);
        
        foreach ($matches[1] as $method) {
            $methods[] = [
                'name' => $method,
                'visibility' => 'public',
                'description' => $this->extractMethodDescription($content, $method)
            ];
        }
        
        return $methods;
    }
    
    /**
     * Extraer descripción de método
     */
    private function extractMethodDescription($content, $methodName) {
        $pattern = '/\/\*\*\s*\n\s*\*\s*([^*\n]+)\s*\n\s*\*\/\s*\n\s*public\s+function\s+' . $methodName . '/';
        if (preg_match($pattern, $content, $matches)) {
            return trim($matches[1]);
        }
        return '';
    }
    
    /**
     * Extraer dependencias
     */
    private function extractDependencies($content) {
        $dependencies = [];
        
        // Buscar extends
        if (preg_match('/extends\s+(\w+)/', $content, $matches)) {
            $dependencies[] = 'extends ' . $matches[1];
        }
        
        // Buscar uses
        preg_match_all('/use\s+([^;]+);/', $content, $matches);
        foreach ($matches[1] as $use) {
            $dependencies[] = 'use ' . trim($use);
        }
        
        return $dependencies;
    }
    
    /**
     * Extraer descripción de clase
     */
    private function extractDescription($content) {
        if (preg_match('/\/\*\*\s*\n\s*\*\s*([^*\n]+)/', $content, $matches)) {
            return trim($matches[1]);
        }
        return '';
    }
    
    /**
     * Documentar modelos
     */
    private function documentModels() {
        echo "📊 Documentando modelos...\n";
        
        $models = glob($this->projectRoot . 'app/models/*.php');
        $this->documentation['models'] = [];
        
        foreach ($models as $model) {
            $content = file_get_contents($model);
            $className = pathinfo($model, PATHINFO_FILENAME);
            
            $modelDoc = [
                'name' => $className,
                'file' => basename($model),
                'methods' => $this->extractMethods($content),
                'properties' => $this->extractProperties($content),
                'description' => $this->extractDescription($content)
            ];
            
            $this->documentation['models'][$className] = $modelDoc;
        }
        
        echo "   ✅ " . count($models) . " modelos documentados\n";
    }
    
    /**
     * Extraer propiedades de una clase
     */
    private function extractProperties($content) {
        $properties = [];
        preg_match_all('/private\s+\$(\w+)/', $content, $matches);
        
        foreach ($matches[1] as $property) {
            $properties[] = [
                'name' => $property,
                'visibility' => 'private',
                'type' => 'mixed'
            ];
        }
        
        return $properties;
    }
    
    /**
     * Documentar vistas
     */
    private function documentViews() {
        echo "👁️ Documentando vistas...\n";
        
        $views = glob($this->projectRoot . 'app/views/**/*.php', GLOB_BRACE);
        $this->documentation['views'] = [];
        
        foreach ($views as $view) {
            $relativePath = str_replace($this->projectRoot, '', $view);
            $content = file_get_contents($view);
            
            $viewDoc = [
                'path' => $relativePath,
                'size' => filesize($view),
                'includes' => $this->extractIncludes($content),
                'forms' => $this->extractForms($content),
                'javascript' => $this->extractJavaScript($content)
            ];
            
            $this->documentation['views'][$relativePath] = $viewDoc;
        }
        
        echo "   ✅ " . count($views) . " vistas documentadas\n";
    }
    
    /**
     * Extraer includes de una vista
     */
    private function extractIncludes($content) {
        $includes = [];
        preg_match_all('/(include|require)(_once)?\s*\([\'"]([^\'"]+)[\'"]\)/', $content, $matches);
        
        foreach ($matches[3] as $include) {
            $includes[] = $include;
        }
        
        return $includes;
    }
    
    /**
     * Extraer formularios de una vista
     */
    private function extractForms($content) {
        $forms = [];
        preg_match_all('/<form[^>]*>/', $content, $matches);
        
        foreach ($matches[0] as $form) {
            if (preg_match('/action=[\'"]([^\'"]+)[\'"]/', $form, $actionMatch)) {
                $forms[] = $actionMatch[1];
            }
        }
        
        return $forms;
    }
    
    /**
     * Extraer JavaScript de una vista
     */
    private function extractJavaScript($content) {
        $scripts = [];
        preg_match_all('/<script[^>]*>(.*?)<\/script>/s', $content, $matches);
        
        foreach ($matches[1] as $script) {
            $scripts[] = trim($script);
        }
        
        return $scripts;
    }
    
    /**
     * Documentar base de datos
     */
    private function documentDatabase() {
        echo "🗄️ Documentando base de datos...\n";
        
        $sqlFiles = glob($this->projectRoot . 'app/scripts/*.sql');
        $this->documentation['database'] = [
            'tables' => [],
            'views' => [],
            'procedures' => []
        ];
        
        foreach ($sqlFiles as $sqlFile) {
            $content = file_get_contents($sqlFile);
            
            // Extraer tablas
            preg_match_all('/CREATE\s+TABLE\s+(\w+)\s*\(/i', $content, $matches);
            foreach ($matches[1] as $table) {
                $this->documentation['database']['tables'][] = $table;
            }
            
            // Extraer vistas
            preg_match_all('/CREATE\s+VIEW\s+(\w+)/i', $content, $matches);
            foreach ($matches[1] as $view) {
                $this->documentation['database']['views'][] = $view;
            }
            
            // Extraer procedimientos
            preg_match_all('/CREATE\s+PROCEDURE\s+(\w+)/i', $content, $matches);
            foreach ($matches[1] as $procedure) {
                $this->documentation['database']['procedures'][] = $procedure;
            }
        }
        
        echo "   ✅ Base de datos documentada\n";
    }
    
    /**
     * Documentar sistema de routing
     */
    private function documentRouting() {
        echo "🛣️ Documentando sistema de routing...\n";
        
        $routerFiles = [
            'app/library/Router.php',
            'app/scripts/routerView.php',
            'app/resources/js/loadView.js'
        ];
        
        $this->documentation['routing'] = [
            'files' => [],
            'routes' => [],
            'javascript' => []
        ];
        
        foreach ($routerFiles as $file) {
            $fullPath = $this->projectRoot . $file;
            if (file_exists($fullPath)) {
                $content = file_get_contents($fullPath);
                
                $this->documentation['routing']['files'][$file] = [
                    'size' => filesize($fullPath),
                    'methods' => $this->extractMethods($content)
                ];
            }
        }
        
        echo "   ✅ Sistema de routing documentado\n";
    }
    
    /**
     * Documentar seguridad
     */
    private function documentSecurity() {
        echo "🔒 Documentando seguridad...\n";
        
        $securityFiles = [
            'app/library/SecurityMiddleware.php',
            'app/library/PermissionManager.php'
        ];
        
        $this->documentation['security'] = [
            'middleware' => [],
            'permissions' => [],
            'validation' => []
        ];
        
        foreach ($securityFiles as $file) {
            $fullPath = $this->projectRoot . $file;
            if (file_exists($fullPath)) {
                $content = file_get_contents($fullPath);
                
                $this->documentation['security']['middleware'][$file] = [
                    'methods' => $this->extractMethods($content),
                    'validation_rules' => $this->extractValidationRules($content)
                ];
            }
        }
        
        echo "   ✅ Seguridad documentada\n";
    }
    
    /**
     * Extraer reglas de validación
     */
    private function extractValidationRules($content) {
        $rules = [];
        preg_match_all('/validate[A-Z]\w+/', $content, $matches);
        
        foreach ($matches[0] as $rule) {
            $rules[] = $rule;
        }
        
        return $rules;
    }
    
    /**
     * Generar documentación de API
     */
    private function generateAPI() {
        echo "🔌 Generando documentación de API...\n";
        
        $apiDoc = "# API Documentation - ByFrost\n\n";
        $apiDoc .= "## Endpoints\n\n";
        
        foreach ($this->documentation['controllers'] as $controller => $info) {
            $apiDoc .= "### $controller\n\n";
            $apiDoc .= $info['description'] ? "**Descripción:** {$info['description']}\n\n" : "";
            
            foreach ($info['methods'] as $method) {
                $apiDoc .= "#### {$method['name']}\n\n";
                if ($method['description']) {
                    $apiDoc .= "{$method['description']}\n\n";
                }
                $apiDoc .= "- **Método:** {$method['visibility']}\n";
                $apiDoc .= "- **Ruta:** `/{$controller}/{$method['name']}`\n\n";
            }
            $apiDoc .= "---\n\n";
        }
        
        file_put_contents($this->outputDir . 'API.md', $apiDoc);
        echo "   ✅ API.md generado\n";
    }
    
    /**
     * Generar guía de usuario
     */
    private function generateUserGuide() {
        echo "👤 Generando guía de usuario...\n";
        
        $userGuide = "# Guía de Usuario - ByFrost\n\n";
        $userGuide .= "## Módulos Disponibles\n\n";
        
        $modules = [
            'school' => 'Gestión de escuelas',
            'user' => 'Gestión de usuarios',
            'student' => 'Gestión de estudiantes',
            'teacher' => 'Gestión de profesores',
            'coordinator' => 'Coordinación académica',
            'director' => 'Dirección institucional',
            'payroll' => 'Nómina y pagos',
            'activity' => 'Actividades académicas'
        ];
        
        foreach ($modules as $module => $description) {
            $userGuide .= "### $module\n\n";
            $userGuide .= "$description\n\n";
            
            // Buscar vistas relacionadas
            $relatedViews = [];
            foreach ($this->documentation['views'] as $view => $info) {
                if (strpos($view, $module) !== false) {
                    $relatedViews[] = $view;
                }
            }
            
            if (!empty($relatedViews)) {
                $userGuide .= "**Vistas disponibles:**\n";
                foreach ($relatedViews as $view) {
                    $userGuide .= "- `$view`\n";
                }
                $userGuide .= "\n";
            }
        }
        
        $userGuide .= "## Funcionalidades Principales\n\n";
        $userGuide .= "### 1. Autenticación\n";
        $userGuide .= "- Login con credenciales\n";
        $userGuide .= "- Gestión de roles\n";
        $userGuide .= "- Recuperación de contraseña\n\n";
        
        $userGuide .= "### 2. Dashboard\n";
        $userGuide .= "- Vista general del sistema\n";
        $userGuide .= "- Estadísticas en tiempo real\n";
        $userGuide .= "- Acceso rápido a funciones\n\n";
        
        $userGuide .= "### 3. Gestión de Datos\n";
        $userGuide .= "- Crear, editar, eliminar registros\n";
        $userGuide .= "- Búsqueda y filtros\n";
        $userGuide .= "- Exportación de datos\n\n";
        
        file_put_contents($this->outputDir . 'USER_GUIDE.md', $userGuide);
        echo "   ✅ USER_GUIDE.md generado\n";
    }
    
    /**
     * Generar guía de desarrollador
     */
    private function generateDeveloperGuide() {
        echo "👨‍💻 Generando guía de desarrollador...\n";
        
        $devGuide = "# Guía de Desarrollador - ByFrost\n\n";
        $devGuide .= "## Arquitectura del Sistema\n\n";
        
        $devGuide .= "### Estructura de Directorios\n\n";
        $devGuide .= "```\n";
        $devGuide .= "byfrost/\n";
        $devGuide .= "├── app/\n";
        $devGuide .= "│   ├── controllers/     # Controladores MVC\n";
        $devGuide .= "│   ├── models/          # Modelos de datos\n";
        $devGuide .= "│   ├── views/           # Vistas y templates\n";
        $devGuide .= "│   ├── library/         # Librerías y utilidades\n";
        $devGuide .= "│   ├── resources/       # CSS, JS, imágenes\n";
        $devGuide .= "│   └── scripts/         # Scripts de utilidad\n";
        $devGuide .= "├── config.php           # Configuración principal\n";
        $devGuide .= "└── index.php            # Punto de entrada\n";
        $devGuide .= "```\n\n";
        
        $devGuide .= "### Patrones de Diseño\n\n";
        $devGuide .= "1. **MVC (Model-View-Controller)**\n";
        $devGuide .= "2. **Singleton** para conexiones de BD\n";
        $devGuide .= "3. **Factory** para creación de objetos\n";
        $devGuide .= "4. **Observer** para eventos del sistema\n\n";
        
        $devGuide .= "### Convenciones de Nomenclatura\n\n";
        $devGuide .= "- **Controladores:** PascalCase + Controller (ej: `UserController`)\n";
        $devGuide .= "- **Modelos:** PascalCase + Model (ej: `UserModel`)\n";
        $devGuide .= "- **Vistas:** camelCase (ej: `userDashboard.php`)\n";
        $devGuide .= "- **Métodos:** camelCase (ej: `getUserById()`)\n";
        $devGuide .= "- **Variables:** camelCase (ej: `$userName`)\n\n";
        
        $devGuide .= "### Sistema de Routing\n\n";
        $devGuide .= "El sistema utiliza un router inteligente que:\n";
        $devGuide .= "- Detecta controladores automáticamente\n";
        $devGuide .= "- Mapea URLs a métodos de controlador\n";
        $devGuide .= "- Maneja parámetros de URL\n";
        $devGuide .= "- Proporciona caché inteligente\n\n";
        
        $devGuide .= "### Base de Datos\n\n";
        $devGuide .= "**Tecnología:** MySQL\n";
        $devGuide .= "**Patrón:** Active Record\n";
        $devGuide .= "**Optimizaciones:** Índices automáticos, vistas optimizadas\n\n";
        
        $devGuide .= "### Seguridad\n\n";
        $devGuide .= "1. **Validación de entrada** en todos los formularios\n";
        $devGuide .= "2. **Sanitización de datos** antes de mostrar\n";
        $devGuide .= "3. **Control de acceso** basado en roles\n";
        $devGuide .= "4. **Protección CSRF** en formularios\n";
        $devGuide .= "5. **Logs de seguridad** para auditoría\n\n";
        
        $devGuide .= "## Guía de Contribución\n\n";
        $devGuide .= "### 1. Configuración del Entorno\n\n";
        $devGuide .= "```bash\n";
        $devGuide .= "# Clonar repositorio\n";
        $devGuide .= "git clone [url-del-repositorio]\n";
        $devGuide .= "cd byfrost\n\n";
        $devGuide .= "# Configurar base de datos\n";
        $devGuide .= "php app/scripts/setup_clean_database.php\n\n";
        $devGuide .= "# Ejecutar servidor\n";
        $devGuide .= "php -S localhost:8000\n";
        $devGuide .= "```\n\n";
        
        $devGuide .= "### 2. Estándares de Código\n\n";
        $devGuide .= "- Usar PSR-4 para autoloading\n";
        $devGuide .= "- Documentar todas las funciones públicas\n";
        $devGuide .= "- Seguir convenciones de nomenclatura\n";
        $devGuide .= "- Escribir pruebas unitarias\n";
        $devGuide .= "- Validar entrada de usuario\n\n";
        
        $devGuide .= "### 3. Flujo de Trabajo\n\n";
        $devGuide .= "1. Crear rama para nueva funcionalidad\n";
        $devGuide .= "2. Desarrollar y probar localmente\n";
        $devGuide .= "3. Ejecutar analizador de rendimiento\n";
        $devGuide .= "4. Crear pull request\n";
        $devGuide .= "5. Revisión de código\n";
        $devGuide .= "6. Merge a main\n\n";
        
        file_put_contents($this->outputDir . 'DEVELOPER_GUIDE.md', $devGuide);
        echo "   ✅ DEVELOPER_GUIDE.md generado\n";
    }
    
    /**
     * Generar README principal
     */
    private function generateREADME() {
        echo "📖 Generando README principal...\n";
        
        $readme = "# ByFrost - Sistema de Gestión Escolar\n\n";
        $readme .= "![ByFrost Logo](app/resources/img/logo.png)\n\n";
        
        $readme .= "## 📋 Descripción\n\n";
        $readme .= "ByFrost es un sistema integral de gestión escolar que proporciona herramientas completas para la administración académica, gestión de estudiantes, nómina y coordinación institucional.\n\n";
        
        $readme .= "## ✨ Características Principales\n\n";
        $readme .= "### 🎓 Gestión Académica\n";
        $readme .= "- Gestión de estudiantes y calificaciones\n";
        $readme .= "- Control de asistencia\n";
        $readme .= "- Planificación de actividades\n";
        $readme .= "- Reportes académicos\n\n";
        
        $readme .= "### 👥 Gestión de Usuarios\n";
        $readme .= "- Sistema de roles y permisos\n";
        $readme .= "- Autenticación segura\n";
        $readme .= "- Gestión de perfiles\n\n";
        
        $readme .= "### 🏫 Administración Escolar\n";
        $readme .= "- Gestión de escuelas y sedes\n";
        $readme .= "- Coordinación académica\n";
        $readme .= "- Dirección institucional\n\n";
        
        $readme .= "### 💰 Nómina y Pagos\n";
        $readme .= "- Gestión de empleados\n";
        $readme .= "- Control de nómina\n";
        $readme .= "- Gestión de pagos estudiantiles\n\n";
        
        $readme .= "## 🚀 Instalación\n\n";
        $readme .= "### Requisitos\n\n";
        $readme .= "- PHP 7.4 o superior\n";
        $readme .= "- MySQL 5.7 o superior\n";
        $readme .= "- Apache/Nginx\n";
        $readme .= "- Composer (opcional)\n\n";
        
        $readme .= "### Pasos de Instalación\n\n";
        $readme .= "1. **Clonar repositorio**\n";
        $readme .= "   ```bash\n";
        $readme .= "   git clone [url-del-repositorio]\n";
        $readme .= "   cd byfrost\n";
        $readme .= "   ```\n\n";
        
        $readme .= "2. **Configurar base de datos**\n";
        $readme .= "   ```bash\n";
        $readme .= "   # Crear base de datos\n";
        $readme .= "   mysql -u root -p -e \"CREATE DATABASE byfrost_db;\"\n\n";
        $readme .= "   # Importar estructura\n";
        $readme .= "   mysql -u root -p byfrost_db < app/scripts/ByFrost_Unified_Database.sql\n\n";
        $readme .= "   # Insertar datos básicos\n";
        $readme .= "   mysql -u root -p byfrost_db < app/scripts/ByFrost_Basic_Inserts.sql\n";
        $readme .= "   ```\n\n";
        
        $readme .= "3. **Configurar aplicación**\n";
        $readme .= "   ```bash\n";
        $readme .= "   # Editar config.php con datos de conexión\n";
        $readme .= "   nano config.php\n";
        $readme .= "   ```\n\n";
        
        $readme .= "4. **Ejecutar optimizaciones**\n";
        $readme .= "   ```bash\n";
        $readme .= "   php app/scripts/auto_optimizer.php\n";
        $readme .= "   ```\n\n";
        
        $readme .= "5. **Iniciar servidor**\n";
        $readme .= "   ```bash\n";
        $readme .= "   php -S localhost:8000\n";
        $readme .= "   ```\n\n";
        
        $readme .= "## 📊 Estadísticas del Proyecto\n\n";
        $readme .= "- **Controladores:** " . count($this->documentation['controllers']) . "\n";
        $readme .= "- **Modelos:** " . count($this->documentation['models']) . "\n";
        $readme .= "- **Vistas:** " . count($this->documentation['views']) . "\n";
        $readme .= "- **Tablas BD:** " . count($this->documentation['database']['tables']) . "\n";
        $readme .= "- **Vistas BD:** " . count($this->documentation['database']['views']) . "\n\n";
        
        $readme .= "## 🛠️ Herramientas de Desarrollo\n\n";
        $readme .= "- **Analizador de Rendimiento:** `php app/scripts/performance_analyzer.php`\n";
        $readme .= "- **Optimizador Automático:** `php app/scripts/auto_optimizer.php`\n";
        $readme .= "- **Monitor del Sistema:** `php app/scripts/system_monitor.php`\n";
        $readme .= "- **Generador de Documentación:** `php app/scripts/documentation_generator.php`\n\n";
        
        $readme .= "## 📚 Documentación\n\n";
        $readme .= "- [Guía de Usuario](documentation/USER_GUIDE.md)\n";
        $readme .= "- [Guía de Desarrollador](documentation/DEVELOPER_GUIDE.md)\n";
        $readme .= "- [Documentación de API](documentation/API.md)\n";
        $readme .= "- [Manual de Base de Datos](documentation/DATABASE.md)\n\n";
        
        $readme .= "## 🤝 Contribución\n\n";
        $readme .= "1. Fork el proyecto\n";
        $readme .= "2. Crea una rama para tu funcionalidad (`git checkout -b feature/AmazingFeature`)\n";
        $readme .= "3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)\n";
        $readme .= "4. Push a la rama (`git push origin feature/AmazingFeature`)\n";
        $readme .= "5. Abre un Pull Request\n\n";
        
        $readme .= "## 📄 Licencia\n\n";
        $readme .= "Este proyecto está bajo la Licencia MIT. Ver el archivo `LICENSE` para más detalles.\n\n";
        
        $readme .= "## 📞 Soporte\n\n";
        $readme .= "Para soporte técnico o preguntas:\n";
        $readme .= "- 📧 Email: soporte@byfrost.com\n";
        $readme .= "- 📱 Teléfono: +57 300 123 4567\n";
        $readme .= "- 🌐 Web: https://byfrost.com/soporte\n\n";
        
        $readme .= "## 🏆 Agradecimientos\n\n";
        $readme .= "- Equipo de desarrollo ByFrost\n";
        $readme .= "- Comunidad de usuarios\n";
        $readme .= "- Contribuidores del proyecto\n\n";
        
        $readme .= "---\n\n";
        $readme .= "*Desarrollado con ❤️ por el equipo ByFrost*\n";
        
        file_put_contents($this->outputDir . 'README.md', $readme);
        echo "   ✅ README.md generado\n";
    }
}

// Ejecutar generador de documentación
echo "📚 Iniciando generador de documentación ByFrost...\n\n";

$generator = new DocumentationGenerator();
$generator->generateDocumentation();

echo "\n🎉 Documentación generada exitosamente!\n";
echo "📁 Revisa la carpeta 'documentation/' para ver todos los archivos generados.\n";
?> 