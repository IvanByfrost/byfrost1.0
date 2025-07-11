<?php
// Script de prueba para verificar búsqueda por rol
echo "<h1>Test: Búsqueda por Rol</h1>";

echo "<h2>Problema Reportado:</h2>";
echo "<p>El rol no lo está pescando en el formulario de consulta de usuarios.</p>";

echo "<h2>Cambios Implementados:</h2>";
echo "<ol>";
echo "<li><strong>Mejorada función searchUserAJAX</strong>: Ahora usa AJAX directo en lugar de loadView</li>";
echo "<li><strong>Agregado validación robusta</strong>: Validación de campos antes del envío</li>";
echo "<li><strong>Agregado logging detallado</strong>: Para debug de la búsqueda</li>";
echo "<li><strong>Nuevo subject en assignProcess.php</strong>: search_users_for_consult</li>";
echo "<li><strong>Función displaySearchResults</strong>: Para mostrar resultados dinámicamente</li>";
echo "</ol>";

echo "<h2>Archivos Modificados:</h2>";
echo "<ul>";
echo "<li><strong>app/views/user/consultUser.php</strong>: Mejorada función searchUserAJAX</li>";
echo "<li><strong>app/processes/assignProcess.php</strong>: Agregado case search_users_for_consult</li>";
echo "</ul>";

echo "<h2>Flujo de Búsqueda por Rol:</h2>";
echo "<ol>";
echo "<li><strong>Usuario selecciona 'Por Rol'</strong> en el dropdown</li>";
echo "<li><strong>Se muestra campo de rol</strong> con opciones disponibles</li>";
echo "<li><strong>Usuario selecciona un rol</strong> (ej: director, coordinator, etc.)</li>";
echo "<li><strong>Usuario hace clic en Buscar</strong></li>";
echo "<li><strong>JavaScript valida</strong> que se seleccionó un rol</li>";
echo "<li><strong>Se envía petición AJAX</strong> a assignProcess.php</li>";
echo "<li><strong>Backend procesa</strong> con subject='search_users_for_consult'</li>";
echo "<li><strong>Se llama getUsersByRole</strong> en el modelo</li>";
echo "<li><strong>Se devuelven resultados</strong> en formato JSON</li>";
echo "<li><strong>JavaScript muestra</strong> los resultados en la tabla</li>";
echo "</ol>";

echo "<h2>Código JavaScript Mejorado:</h2>";
echo "<pre>";
echo "function searchUserAJAX(e) {
    e.preventDefault();
    console.log('DEBUG: searchUserAJAX iniciado');
    
    const searchType = document.getElementById('search_type').value;
    console.log('DEBUG: Tipo de búsqueda:', searchType);
    
    if (!searchType) {
        alert('Por favor, selecciona un tipo de búsqueda.');
        return false;
    }
    
    // Validación específica para rol
    if (searchType === 'role') {
        const roleType = document.getElementById('role_type').value;
        if (!roleType) {
            alert('Por favor, selecciona un rol.');
            return false;
        }
        searchData = {
            search_type: 'role',
            role_type: roleType
        };
    }
    
    // Envío AJAX
    fetch('app/processes/assignProcess.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: new URLSearchParams({
            subject: 'search_users_for_consult',
            ...searchData
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'ok' && data.data.length > 0) {
            displaySearchResults(data.data);
        } else {
            showError('No se encontraron usuarios con ese rol.');
        }
    })
    .catch(error => {
        showError('Error de conexión con el servidor.');
    });
}";
echo "</pre>";

echo "<h2>Código Backend Agregado:</h2>";
echo "<pre>";
echo "case 'search_users_for_consult':
    \$searchType = htmlspecialchars(\$_POST['search_type'] ?? '');
    
    switch (\$searchType) {
        case 'role':
            \$roleType = htmlspecialchars(\$_POST['role_type'] ?? '');
            if (!\$roleType) {
                throw new Exception('Falta dato requerido: tipo de rol');
            }
            \$users = \$model->getUsersByRole(\$roleType, \$currentUserRole);
            break;
    }
    
    echo json_encode([
        'status' => 'ok',
        'msg' => 'Usuarios encontrados',
        'data' => \$users
    ]);";
echo "</pre>";

echo "<h2>Instrucciones de Prueba:</h2>";
echo "<ol>";
echo "<li><strong>Abrir la consola del navegador</strong> (F12)</li>";
echo "<li><strong>Ir a consulta de usuarios</strong>: Navegar a la página de consulta</li>";
echo "<li><strong>Seleccionar 'Por Rol'</strong> en el dropdown de tipo de búsqueda</li>";
echo "<li><strong>Seleccionar un rol</strong> (ej: director, coordinator, student)</li>";
echo "<li><strong>Hacer clic en Buscar</strong></li>";
echo "<li><strong>Verificar logs</strong>: Revisar los logs DEBUG en la consola</li>";
echo "<li><strong>Verificar resultados</strong>: Debe mostrar usuarios con ese rol</li>";
echo "</ol>";

echo "<h2>Logs Esperados:</h2>";
echo "<pre>";
echo "DEBUG: searchUserAJAX iniciado
DEBUG: Tipo de búsqueda: role
DEBUG: Datos de búsqueda: {search_type: 'role', role_type: 'director'}
DEBUG: Respuesta HTTP: 200
DEBUG: Respuesta del servidor: {status: 'ok', data: [...]}";
echo "</pre>";

echo "<h2>Posibles Problemas y Soluciones:</h2>";
echo "<ul>";
echo "<li><strong>No se muestra campo de rol</strong>: Verificar función toggleSearchFields()</li>";
echo "<li><strong>Error de validación</strong>: Verificar que se selecciona un rol</li>";
echo "<li><strong>Error de conexión</strong>: Verificar ruta de assignProcess.php</li>";
echo "<li><strong>Error de permisos</strong>: Verificar rol del usuario actual</li>";
echo "<li><strong>No hay resultados</strong>: Verificar que existen usuarios con ese rol</li>";
echo "</ul>";

echo "<h2>Comando para Probar:</h2>";
echo "<pre>";
echo "// Abrir en el navegador:
http://localhost:8000/?view=user&action=consultUser

// Verificar logs del servidor:
tail -f /path/to/your/logs/error.log";
echo "</pre>";

echo "<h2>Verificación de Base de Datos:</h2>";
echo "<p>Para verificar que hay usuarios con roles, ejecutar:</p>";
echo "<pre>";
echo "SELECT u.first_name, u.last_name, ur.role_type 
FROM users u 
INNER JOIN user_roles ur ON u.user_id = ur.user_id 
WHERE ur.is_active = 1 
AND u.is_active = 1 
ORDER BY ur.role_type, u.first_name;";
echo "</pre>";

echo "<h2>Próximos Pasos:</h2>";
echo "<ol>";
echo "<li><strong>Probar la búsqueda</strong>: Verificar que funciona correctamente</li>";
echo "<li><strong>Limpiar logs</strong>: Remover logs de debug una vez confirmado</li>";
echo "<li><strong>Optimizar código</strong>: Limpiar y optimizar el código</li>";
echo "<li><strong>Documentar</strong>: Documentar la solución</li>";
echo "</ol>";
?> 