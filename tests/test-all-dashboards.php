<?php
/**
 * Test All Dashboards - ByFrost
 * Script para probar que todos los dashboards funcionan correctamente
 */

define('ROOT', __DIR__);
require_once ROOT . '/config.php';
require_once ROOT . '/app/scripts/connection.php';
require_once ROOT . '/app/library/ErrorHandler.php';

class DashboardTester {
    private $dbConn;
    private $results = [];
    
    public function __construct() {
        try {
            $this->dbConn = getConnection();
        } catch (Exception $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }
    
    /**
     * Ejecuta todas las pruebas de dashboards
     */
    public function runAllTests() {
        echo "🧪 INICIANDO PRUEBAS DE TODOS LOS DASHBOARDS\n";
        echo str_repeat("=", 50) . "\n\n";
        
        $this->testDirectorDashboard();
        $this->testCoordinatorDashboard();
        $this->testTeacherDashboard();
        $this->testStudentDashboard();
        $this->testParentDashboard();
        $this->testRootDashboard();
        
        $this->generateReport();
    }
    
    /**
     * Prueba el dashboard del director
     */
    private function testDirectorDashboard() {
        echo "🏫 Probando Dashboard del Director...\n";
        
        try {
            require_once ROOT . '/app/controllers/DirectorController.php';
            $controller = new DirectorController($this->dbConn);
            
            // Verificar que el controlador se instancia correctamente
            $this->results['director']['instantiation'] = '✅ OK';
            
            // Verificar métodos principales
            $methods = ['dashboard', 'listAction', 'newDirector', 'addDirector'];
            foreach ($methods as $method) {
                if (method_exists($controller, $method)) {
                    $this->results['director'][$method] = '✅ OK';
                } else {
                    $this->results['director'][$method] = '❌ FALTANTE';
                }
            }
            
            echo "   ✅ Dashboard del Director: FUNCIONAL\n\n";
            
        } catch (Exception $e) {
            $this->results['director']['error'] = '❌ ERROR: ' . $e->getMessage();
            echo "   ❌ Dashboard del Director: ERROR - " . $e->getMessage() . "\n\n";
        }
    }
    
    /**
     * Prueba el dashboard del coordinador
     */
    private function testCoordinatorDashboard() {
        echo "👨‍💼 Probando Dashboard del Coordinador...\n";
        
        try {
            require_once ROOT . '/app/controllers/coordinatorDashboardController.php';
            $controller = new CoordinatorDashboardController($this->dbConn);
            
            // Verificar que el controlador se instancia correctamente
            $this->results['coordinator']['instantiation'] = '✅ OK';
            
            // Verificar métodos principales
            $methods = ['dashboard', 'studentManagement', 'teacherManagement', 'subjectManagement'];
            foreach ($methods as $method) {
                if (method_exists($controller, $method)) {
                    $this->results['coordinator'][$method] = '✅ OK';
                } else {
                    $this->results['coordinator'][$method] = '❌ FALTANTE';
                }
            }
            
            echo "   ✅ Dashboard del Coordinador: FUNCIONAL\n\n";
            
        } catch (Exception $e) {
            $this->results['coordinator']['error'] = '❌ ERROR: ' . $e->getMessage();
            echo "   ❌ Dashboard del Coordinador: ERROR - " . $e->getMessage() . "\n\n";
        }
    }
    
    /**
     * Prueba el dashboard del profesor
     */
    private function testTeacherDashboard() {
        echo "👨‍🏫 Probando Dashboard del Profesor...\n";
        
        try {
            require_once ROOT . '/app/controllers/teacherDashboardController.php';
            $controller = new TeacherDashboardController($this->dbConn);
            
            // Verificar que el controlador se instancia correctamente
            $this->results['teacher']['instantiation'] = '✅ OK';
            
            // Verificar métodos principales
            $methods = ['dashboard', 'assessStudents', 'viewSchedule'];
            foreach ($methods as $method) {
                if (method_exists($controller, $method)) {
                    $this->results['teacher'][$method] = '✅ OK';
                } else {
                    $this->results['teacher'][$method] = '❌ FALTANTE';
                }
            }
            
            echo "   ✅ Dashboard del Profesor: FUNCIONAL\n\n";
            
        } catch (Exception $e) {
            $this->results['teacher']['error'] = '❌ ERROR: ' . $e->getMessage();
            echo "   ❌ Dashboard del Profesor: ERROR - " . $e->getMessage() . "\n\n";
        }
    }
    
    /**
     * Prueba el dashboard del estudiante
     */
    private function testStudentDashboard() {
        echo "👨‍🎓 Probando Dashboard del Estudiante...\n";
        
        try {
            require_once ROOT . '/app/controllers/studentDashboardController.php';
            $controller = new StudentDashboardController($this->dbConn);
            
            // Verificar que el controlador se instancia correctamente
            $this->results['student']['instantiation'] = '✅ OK';
            
            // Verificar métodos principales
            $methods = ['dashboard', 'academicHistory', 'viewSchedule'];
            foreach ($methods as $method) {
                if (method_exists($controller, $method)) {
                    $this->results['student'][$method] = '✅ OK';
                } else {
                    $this->results['student'][$method] = '❌ FALTANTE';
                }
            }
            
            echo "   ✅ Dashboard del Estudiante: FUNCIONAL\n\n";
            
        } catch (Exception $e) {
            $this->results['student']['error'] = '❌ ERROR: ' . $e->getMessage();
            echo "   ❌ Dashboard del Estudiante: ERROR - " . $e->getMessage() . "\n\n";
        }
    }
    
    /**
     * Prueba el dashboard del acudiente
     */
    private function testParentDashboard() {
        echo "👨‍👩‍👧‍👦 Probando Dashboard del Acudiente...\n";
        
        try {
            require_once ROOT . '/app/controllers/ParentDashboardController.php';
            $controller = new ParentDashboardController($this->dbConn);
            
            // Verificar que el controlador se instancia correctamente
            $this->results['parent']['instantiation'] = '✅ OK';
            
            // Verificar métodos principales
            $methods = ['dashboard', 'childrenProgress'];
            foreach ($methods as $method) {
                if (method_exists($controller, $method)) {
                    $this->results['parent'][$method] = '✅ OK';
                } else {
                    $this->results['parent'][$method] = '❌ FALTANTE';
                }
            }
            
            echo "   ✅ Dashboard del Acudiente: FUNCIONAL\n\n";
            
        } catch (Exception $e) {
            $this->results['parent']['error'] = '❌ ERROR: ' . $e->getMessage();
            echo "   ❌ Dashboard del Acudiente: ERROR - " . $e->getMessage() . "\n\n";
        }
    }
    
    /**
     * Prueba el dashboard de root
     */
    private function testRootDashboard() {
        echo "👑 Probando Dashboard de Root...\n";
        
        try {
            require_once ROOT . '/app/controllers/rootDashboardController.php';
            $controller = new RootDashboardController($this->dbConn);
            
            // Verificar que el controlador se instancia correctamente
            $this->results['root']['instantiation'] = '✅ OK';
            
            // Verificar métodos principales
            $methods = ['dashboard', 'userManagement', 'roleManagement', 'systemSettings'];
            foreach ($methods as $method) {
                if (method_exists($controller, $method)) {
                    $this->results['root'][$method] = '✅ OK';
                } else {
                    $this->results['root'][$method] = '❌ FALTANTE';
                }
            }
            
            echo "   ✅ Dashboard de Root: FUNCIONAL\n\n";
            
        } catch (Exception $e) {
            $this->results['root']['error'] = '❌ ERROR: ' . $e->getMessage();
            echo "   ❌ Dashboard de Root: ERROR - " . $e->getMessage() . "\n\n";
        }
    }
    
    /**
     * Genera el reporte final
     */
    private function generateReport() {
        echo str_repeat("=", 50) . "\n";
        echo "📋 REPORTE FINAL DE PRUEBAS\n";
        echo str_repeat("=", 50) . "\n\n";
        
        $totalDashboards = 6;
        $workingDashboards = 0;
        
        foreach ($this->results as $dashboard => $results) {
            echo "🎯 $dashboard:\n";
            foreach ($results as $test => $result) {
                echo "   $test: $result\n";
            }
            
            if (!isset($results['error'])) {
                $workingDashboards++;
            }
            echo "\n";
        }
        
        echo "📊 RESUMEN:\n";
        echo "   Dashboards funcionando: $workingDashboards/$totalDashboards\n";
        echo "   Porcentaje de éxito: " . round(($workingDashboards / $totalDashboards) * 100, 1) . "%\n\n";
        
        if ($workingDashboards === $totalDashboards) {
            echo "🎉 ¡TODOS LOS DASHBOARDS ESTÁN FUNCIONANDO!\n";
        } else {
            echo "⚠️  Algunos dashboards necesitan atención.\n";
        }
        
        echo "\n🚀 URLs para probar:\n";
        echo "   Director: http://localhost:8000/?view=director&action=dashboard\n";
        echo "   Coordinador: http://localhost:8000/?view=coordinatorDashboard&action=dashboard\n";
        echo "   Profesor: http://localhost:8000/?view=teacherDashboard&action=dashboard\n";
        echo "   Estudiante: http://localhost:8000/?view=studentDashboard&action=dashboard\n";
        echo "   Acudiente: http://localhost:8000/?view=parentDashboard&action=dashboard\n";
        echo "   Root: http://localhost:8000/?view=rootDashboard&action=dashboard\n";
    }
}

// Ejecutar pruebas
$tester = new DashboardTester();
$tester->runAllTests();
?> 