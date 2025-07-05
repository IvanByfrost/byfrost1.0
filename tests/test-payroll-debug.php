<?php
// Test específico para diagnosticar problemas de carga de nómina desde el sidebar
echo "<h1>🔍 Diagnóstico de Carga de Nómina desde Sidebar</h1>";

// Configurar variables
if (!defined('ROOT')) {
    define('ROOT', __DIR__);
}
require_once '../config.php';

echo "<h2>1. Verificación de JavaScript:</h2>";
echo "<div id='js-test'>Verificando JavaScript...</div>";

echo "<h2>2. Verificación de BASE_URL:</h2>";
echo "<div id='base-url-test'>Verificando BASE_URL...</div>";

echo "<h2>3. Verificación de loadView:</h2>";
echo "<div id='loadview-test'>Verificando loadView...</div>";

echo "<h2>4. Pruebas de Carga:</h2>";
echo "<div id='load-tests'>";
echo "<button onclick='testPayrollDashboard()' class='btn btn-primary'>Test Dashboard Nómina</button> ";
echo "<button onclick='testPayrollEmployees()' class='btn btn-success'>Test Empleados</button> ";
echo "<button onclick='testPayrollPeriods()' class='btn btn-warning'>Test Períodos</button>";
echo "</div>";

echo "<h2>5. Resultados:</h2>";
echo "<div id='results' class='mt-3'></div>";

?>

<script>
// Configurar BASE_URL
const BASE_URL = window.location.origin + window.location.pathname.replace(/\/[^\/]*$/, '/');
console.log('BASE_URL configurada:', BASE_URL);

// Función para mostrar resultados
function showResult(message, type = 'info') {
    const results = document.getElementById('results');
    const div = document.createElement('div');
    div.className = `alert alert-${type}`;
    div.innerHTML = message;
    results.appendChild(div);
}

// Verificar JavaScript
document.getElementById('js-test').innerHTML = '✅ JavaScript funcionando';

// Verificar BASE_URL
document.getElementById('base-url-test').innerHTML = `✅ BASE_URL: ${BASE_URL}`;

// Verificar loadView
function checkLoadView() {
    const loadviewTest = document.getElementById('loadview-test');
    if (typeof loadView === 'function') {
        loadviewTest.innerHTML = '✅ Función loadView disponible';
        return true;
    } else {
        loadviewTest.innerHTML = '❌ Función loadView NO disponible';
        return false;
    }
}

// Cargar loadView.js dinámicamente si no está disponible
function loadLoadViewScript() {
    if (typeof loadView === 'function') {
        return Promise.resolve();
    }
    
    return new Promise((resolve, reject) => {
        const script = document.createElement('script');
        script.src = BASE_URL + 'app/resources/js/loadView.js';
        script.onload = () => {
            console.log('loadView.js cargado exitosamente');
            setTimeout(() => {
                if (typeof loadView === 'function') {
                    resolve();
                } else {
                    reject(new Error('loadView no disponible después de cargar el script'));
                }
            }, 100);
        };
        script.onerror = () => reject(new Error('Error cargando loadView.js'));
        document.head.appendChild(script);
    });
}

// Función de prueba para dashboard de nómina
async function testPayrollDashboard() {
    showResult('🔄 Probando dashboard de nómina...', 'info');
    
    try {
        await loadLoadViewScript();
        
        if (!checkLoadView()) {
            throw new Error('loadView no disponible');
        }
        
        // Simular el clic en el sidebar
        const target = document.getElementById('mainContent') || document.body;
        target.innerHTML = '<div class="text-center p-4"><i class="fas fa-spinner fa-spin"></i> Cargando dashboard de nómina...</div>';
        
        // Llamar a loadView como lo haría el sidebar
        loadView('payroll/dashboard');
        
        showResult('✅ Dashboard de nómina cargado correctamente', 'success');
        
    } catch (error) {
        showResult(`❌ Error cargando dashboard: ${error.message}`, 'danger');
        console.error('Error en testPayrollDashboard:', error);
    }
}

// Función de prueba para empleados
async function testPayrollEmployees() {
    showResult('🔄 Probando empleados de nómina...', 'info');
    
    try {
        await loadLoadViewScript();
        
        if (!checkLoadView()) {
            throw new Error('loadView no disponible');
        }
        
        const target = document.getElementById('mainContent') || document.body;
        target.innerHTML = '<div class="text-center p-4"><i class="fas fa-spinner fa-spin"></i> Cargando empleados...</div>';
        
        loadView('payroll/employees');
        
        showResult('✅ Empleados de nómina cargados correctamente', 'success');
        
    } catch (error) {
        showResult(`❌ Error cargando empleados: ${error.message}`, 'danger');
        console.error('Error en testPayrollEmployees:', error);
    }
}

// Función de prueba para períodos
async function testPayrollPeriods() {
    showResult('🔄 Probando períodos de nómina...', 'info');
    
    try {
        await loadLoadViewScript();
        
        if (!checkLoadView()) {
            throw new Error('loadView no disponible');
        }
        
        const target = document.getElementById('mainContent') || document.body;
        target.innerHTML = '<div class="text-center p-4"><i class="fas fa-spinner fa-spin"></i> Cargando períodos...</div>';
        
        loadView('payroll/periods');
        
        showResult('✅ Períodos de nómina cargados correctamente', 'success');
        
    } catch (error) {
        showResult(`❌ Error cargando períodos: ${error.message}`, 'danger');
        console.error('Error en testPayrollPeriods:', error);
    }
}

// Verificar al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    console.log('Página cargada, verificando loadView...');
    checkLoadView();
    
    // Intentar cargar loadView.js automáticamente
    loadLoadViewScript().then(() => {
        showResult('✅ loadView.js cargado automáticamente', 'success');
        checkLoadView();
    }).catch(error => {
        showResult(`❌ Error cargando loadView.js automáticamente: ${error.message}`, 'danger');
    });
});

// Función de respaldo para safeLoadView (como en las vistas)
window.safeLoadView = function(viewName) {
    console.log('safeLoadView llamado con:', viewName);
    
    if (typeof loadView === 'function') {
        console.log('loadView disponible, ejecutando...');
        loadView(viewName);
    } else {
        console.error('loadView no está disponible, redirigiendo...');
        // Fallback: redirigir a la página
        const url = `${BASE_URL}?view=${viewName.replace('/', '&action=')}`;
        window.location.href = url;
    }
};
</script>

<style>
.btn {
    padding: 8px 16px;
    margin: 5px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
    font-size: 14px;
}

.btn-primary { background-color: #007bff; color: white; }
.btn-success { background-color: #28a745; color: white; }
.btn-warning { background-color: #ffc107; color: black; }
.btn-danger { background-color: #dc3545; color: white; }

.alert {
    padding: 12px;
    margin: 10px 0;
    border-radius: 4px;
    border: 1px solid transparent;
}

.alert-info { background-color: #d1ecf1; border-color: #bee5eb; color: #0c5460; }
.alert-success { background-color: #d4edda; border-color: #c3e6cb; color: #155724; }
.alert-danger { background-color: #f8d7da; border-color: #f5c6cb; color: #721c24; }

.mt-3 { margin-top: 15px; }
</style> 