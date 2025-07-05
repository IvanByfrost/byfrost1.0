<?php
// Test específico para simular el sidebar del root y diagnosticar problemas de nómina
echo "<h1>🔍 Test de Sidebar Root - Nómina</h1>";

// Configurar variables
if (!defined('ROOT')) {
    define('ROOT', __DIR__);
}
require_once '../config.php';

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Sidebar Root - Nómina</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar {
            width: 250px;
            background: #2c3e50;
            color: white;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
        }
        .sidebar ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .sidebar li {
            border-bottom: 1px solid #34495e;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            padding: 15px 20px;
            display: block;
            transition: background 0.3s;
        }
        .sidebar a:hover {
            background: #34495e;
        }
        .submenu {
            background: #34495e;
            display: none;
        }
        .submenu li {
            border-bottom: 1px solid #2c3e50;
        }
        .submenu a {
            padding: 10px 30px;
            font-size: 14px;
        }
        .has-submenu:hover .submenu {
            display: block;
        }
        .mainContent {
            margin-left: 250px;
            padding: 20px;
        }
        .test-result {
            margin: 10px 0;
            padding: 10px;
            border-radius: 4px;
        }
        .success { background-color: #d4edda; border: 1px solid #c3e6cb; color: #155724; }
        .error { background-color: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; }
        .info { background-color: #d1ecf1; border: 1px solid #bee5eb; color: #0c5460; }
    </style>
</head>
<body>
    <div class="sidebar">
        <ul>
            <li><a href="#" onclick="safeLoadView('root/menuRoot')"><i data-lucide="home"></i>Inicio</a></li>
            <li class="has-submenu"><a href="#"><i data-lucide="school"></i>Colegios</a>
                <ul class="submenu">
                    <li><a href="#" onclick="safeLoadView('school/createSchool')">Registrar Colegio</a></li>
                    <li><a href="#" onclick="safeLoadView('school/consultSchool')">Consultar Colegio</a></li>
                </ul>
            </li>
            <li class="has-submenu">
                <a href="#"><i data-lucide="user"></i>Usuarios</a>
                <ul class="submenu">
                    <li><a href="#" onclick="safeLoadView('user/consultUser')"><i data-lucide="user-search"></i>Consultar Usuario</a></li>
                    <li><a href="#" onclick="safeLoadView('user/assignRole')"><i data-lucide="user-pen"></i>Asignar rol</a></li>
                    <li><a href="#" onclick="safeLoadView('user/showRoleHistory')"><i data-lucide="book-user"></i>Historial de roles</a></li>
                </ul>
            </li>
            <li class="has-submenu">
                <a href="#"><i data-lucide="dollar-sign"></i>Nómina</a>
                <ul class="submenu">
                    <li><a href="#" onclick="safeLoadView('payroll/dashboard')"><i data-lucide="bar-chart-3"></i>Dashboard</a></li>
                    <li><a href="#" onclick="safeLoadView('payroll/employees')"><i data-lucide="users"></i>Empleados</a></li>
                    <li><a href="#" onclick="safeLoadView('payroll/periods')"><i data-lucide="calendar"></i>Períodos</a></li>
                    <li><a href="#" onclick="safeLoadView('payroll/absences')"><i data-lucide="user-x"></i>Ausencias</a></li>
                    <li><a href="#" onclick="safeLoadView('payroll/overtime')"><i data-lucide="clock"></i>Horas Extras</a></li>
                    <li><a href="#" onclick="safeLoadView('payroll/bonuses')"><i data-lucide="gift"></i>Bonificaciones</a></li>
                    <li><a href="#" onclick="safeLoadView('payroll/reports')"><i data-lucide="file-text"></i>Reportes</a></li>
                </ul>
            </li>
            <li class="has-submenu">
                <a href="#"><i data-lucide="key"></i>Permisos</a>
                <ul class="submenu">
                    <li><a href="#" onclick="safeLoadView('role/index')">Editar permisos</a></li>
                </ul>
            </li>
            <li class="has-submenu">
                <a href="#"><i data-lucide="bar-chart-2"></i>Reportes</a>
                <ul class="submenu">
                    <li><a href="#" onclick="safeLoadView('director/editDirector')">Crear reporte</a></li>
                    <li><a href="#" onclick="safeLoadView('director/createDirector')">Consultar reporte</a></li>
                    <li><a href="#" onclick="safeLoadView('director/createDirector')">Consultar estadística</a></li>
                </ul>
            </li>
            
            <li class="has-submenu">
                <a href="#"><i data-lucide="settings"></i>Configuración</a>
                <ul class="submenu">
                    <li><a href="#" onclick="safeLoadView('user/settingsRoles?section=usuarios')"> Usuarios</a></li>
                    <li><a href="#" onclick="safeLoadView('user/settingsRoles?section=recuperar')"> Recuperar contraseña</a></li>
                </ul>
            </li>
        </ul>
    </div>

    <div id="mainContent" class="mainContent">
        <h2>Test de Sidebar Root - Nómina</h2>
        <p>Haz clic en las opciones del sidebar para probar la carga de nómina.</p>
        
        <div id="testResults">
            <div class="test-result info">
                <strong>Estado:</strong> Esperando pruebas...
            </div>
        </div>
        
        <div class="mt-4">
            <h3>Pruebas Rápidas:</h3>
            <button class="btn btn-primary" onclick="testPayrollDashboard()">Test Dashboard Nómina</button>
            <button class="btn btn-success" onclick="testPayrollEmployees()">Test Empleados</button>
            <button class="btn btn-warning" onclick="testPayrollPeriods()">Test Períodos</button>
            <button class="btn btn-info" onclick="testLoadViewFunction()">Test loadView</button>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <!-- Configuración de URL base -->
    <script>
        const BASE_URL = window.location.origin + window.location.pathname.replace(/\/[^\/]*$/, '/');
        console.log('BASE_URL configurada:', BASE_URL);
    </script>
    
    <!-- Cargar loadView.js -->
    <script src="../app/resources/js/loadView.js"></script>
    
    <script>
        // Inicializar Lucide
        lucide.createIcons();
        
        // Función para mostrar resultados
        function showResult(message, type = 'info') {
            const results = document.getElementById('testResults');
            const div = document.createElement('div');
            div.className = `test-result ${type}`;
            div.innerHTML = `<strong>${new Date().toLocaleTimeString()}:</strong> ${message}`;
            results.appendChild(div);
        }
        
        // Función de respaldo para safeLoadView (como en las vistas)
        window.safeLoadView = function(viewName) {
            console.log('safeLoadView llamado con:', viewName);
            showResult(`Intentando cargar: ${viewName}`, 'info');
            
            if (typeof loadView === 'function') {
                console.log('loadView disponible, ejecutando...');
                showResult('loadView disponible, ejecutando...', 'success');
                loadView(viewName);
            } else {
                console.error('loadView no está disponible, redirigiendo...');
                showResult('loadView no disponible, redirigiendo...', 'error');
                // Fallback: redirigir a la página
                const url = `${BASE_URL}?view=${viewName.replace('/', '&action=')}`;
                window.location.href = url;
            }
        };
        
        // Función de prueba para dashboard de nómina
        function testPayrollDashboard() {
            showResult('🔄 Probando dashboard de nómina...', 'info');
            safeLoadView('payroll/dashboard');
        }
        
        // Función de prueba para empleados
        function testPayrollEmployees() {
            showResult('🔄 Probando empleados de nómina...', 'info');
            safeLoadView('payroll/employees');
        }
        
        // Función de prueba para períodos
        function testPayrollPeriods() {
            showResult('🔄 Probando períodos de nómina...', 'info');
            safeLoadView('payroll/periods');
        }
        
        // Función de prueba para loadView
        function testLoadViewFunction() {
            showResult('🔄 Verificando función loadView...', 'info');
            
            if (typeof loadView === 'function') {
                showResult('✅ Función loadView está disponible', 'success');
                console.log('loadView es una función:', loadView);
            } else {
                showResult('❌ Función loadView NO está disponible', 'error');
                console.error('loadView no es una función:', typeof loadView);
            }
        }
        
        // Verificar al cargar la página
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Página cargada, verificando loadView...');
            showResult('Página cargada, verificando componentes...', 'info');
            
            // Verificar loadView
            if (typeof loadView === 'function') {
                showResult('✅ loadView disponible', 'success');
            } else {
                showResult('❌ loadView no disponible', 'error');
            }
            
            // Verificar BASE_URL
            showResult(`✅ BASE_URL: ${BASE_URL}`, 'success');
        });
    </script>
</body>
</html> 