<?php
/**
 * Ejemplo de uso del sistema de permisos
 * Muestra cómo implementar verificaciones de permisos en controladores y vistas
 */

// Configuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    .success { color: green; background: #e8f5e8; padding: 10px; border-radius: 5px; margin: 5px 0; }
    .error { color: red; background: #ffe8e8; padding: 10px; border-radius: 5px; margin: 5px 0; }
    .warning { color: orange; background: #fff8e8; padding: 10px; border-radius: 5px; margin: 5px 0; }
    .info { color: blue; background: #e8f0ff; padding: 10px; border-radius: 5px; margin: 5px 0; }
    .code { background: #f8f9fa; border: 1px solid #e9ecef; padding: 15px; border-radius: 5px; margin: 10px 0; font-family: monospace; }
    .example { background: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; border-radius: 5px; margin: 10px 0; }
</style>";

echo "<h1>📚 Ejemplo de Uso del Sistema de Permisos</h1>";

// Incluir dependencias
require_once '../config.php';
require_once '../app/library/PermissionManager.php';
require_once '../app/scripts/connection.php';

try {
    $dbConn = getConnection();
    $permissionManager = new PermissionManager($dbConn);
    
    echo "<div class='success'>✅ PermissionManager inicializado correctamente</div>";
    
    // Obtener información de permisos del usuario actual
    $permissionInfo = $permissionManager->getUserPermissionInfo();
    
    echo "<h2>👤 Información del Usuario Actual</h2>";
    echo "<div class='info'>";
    echo "<strong>Estado:</strong> " . ($permissionInfo['is_logged_in'] ? 'Logueado' : 'No logueado') . "<br>";
    if ($permissionInfo['is_logged_in']) {
        echo "<strong>Email:</strong> " . $permissionInfo['user_email'] . "<br>";
        echo "<strong>Rol:</strong> " . $permissionInfo['user_role'] . "<br>";
    }
    echo "</div>";
    
    echo "<h2>🔐 Permisos Actuales</h2>";
    echo "<div class='info'>";
    echo "Crear: " . ($permissionInfo['can_create'] ? '✅ Permitido' : '❌ Denegado') . "<br>";
    echo "Leer: " . ($permissionInfo['can_read'] ? '✅ Permitido' : '❌ Denegado') . "<br>";
    echo "Actualizar: " . ($permissionInfo['can_update'] ? '✅ Permitido' : '❌ Denegado') . "<br>";
    echo "Eliminar: " . ($permissionInfo['can_delete'] ? '✅ Permitido' : '❌ Denegado') . "<br>";
    echo "</div>";
    
} catch (Exception $e) {
    echo "<div class='error'>❌ Error: " . $e->getMessage() . "</div>";
    exit;
}

echo "<h2>💻 Ejemplos de Implementación</h2>";

echo "<h3>1. En Controladores</h3>";

echo "<div class='example'>";
echo "<strong>Ejemplo de controlador con verificación de permisos:</strong>";
echo "</div>";

echo "<div class='code'>";
echo "&lt;?php<br>";
echo "require_once ROOT . '/app/library/PermissionManager.php';<br><br>";
echo "class ExampleController extends MainController {<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;private \$permissionManager;<br><br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;public function __construct(\$dbConn) {<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;parent::__construct(\$dbConn);<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\$this->permissionManager = new PermissionManager(\$dbConn);<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;}<br><br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;// Método que requiere permiso de lectura<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;public function index() {<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;// Verificar permiso de lectura<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\$this->permissionManager->requirePermission('read');<br><br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;// Si llega aquí, tiene permisos<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\$data = \$this->model->getAllData();<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;include ROOT . '/app/views/example/index.php';<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;}<br><br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;// Método que requiere permiso de creación<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;public function create() {<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\$this->permissionManager->requirePermission('create');<br><br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;if (\$_POST) {<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\$this->model->create(\$_POST);<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;header('Location: ?view=example');<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}<br><br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;include ROOT . '/app/views/example/create.php';<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;}<br><br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;// Método que requiere múltiples permisos<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;public function update(\$id) {<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;// Requerir permisos de lectura y actualización<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;if (!\$this->permissionManager->hasAllPermissions(['read', 'update'])) {<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;header('Location: ?view=Error&action=unauthorized');<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;exit;<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}<br><br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\$data = \$this->model->getById(\$id);<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;include ROOT . '/app/views/example/edit.php';<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;}<br><br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;// Método AJAX con verificación de permisos<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;public function deleteAjax(\$id) {<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\$this->permissionManager->requirePermissionAjax('delete');<br><br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\$result = \$this->model->delete(\$id);<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;echo json_encode(['success' => \$result]);<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;}<br>";
echo "}<br>";
echo "?&gt;";
echo "</div>";

echo "<h3>2. En Vistas</h3>";

echo "<div class='example'>";
echo "<strong>Ejemplo de vista con botones condicionales según permisos:</strong>";
echo "</div>";

echo "<div class='code'>";
echo "&lt;?php<br>";
echo "\$permissionManager = new PermissionManager();<br>";
echo "?&gt;<br><br>";
echo "&lt;div class=\"container\"&gt;<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&lt;h1&gt;Lista de Elementos&lt;/h1&gt;<br><br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&lt;?php if (\$permissionManager->canCreate()): ?&gt;<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;a href=\"?view=example&amp;action=create\" class=\"btn btn-primary\"&gt;<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;i class=\"fas fa-plus\"&gt;&lt;/i&gt; Crear Nuevo<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;/a&gt;<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&lt;?php endif; ?&gt;<br><br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&lt;table class=\"table\"&gt;<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;thead&gt;<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;tr&gt;<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;th&gt;ID&lt;/th&gt;<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;th&gt;Nombre&lt;/th&gt;<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;th&gt;Acciones&lt;/th&gt;<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;/tr&gt;<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;/thead&gt;<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;tbody&gt;<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;?php foreach (\$items as \$item): ?&gt;<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;tr&gt;<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td&gt;&lt;?= \$item['id'] ?&gt;&lt;/td&gt;<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td&gt;&lt;?= \$item['name'] ?&gt;&lt;/td&gt;<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td&gt;<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;?php if (\$permissionManager->canRead()): ?&gt;<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;a href=\"?view=example&amp;action=view&amp;id=&lt;?= \$item['id'] ?&gt;\" class=\"btn btn-info btn-sm\"&gt;<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;i class=\"fas fa-eye\"&gt;&lt;/i&gt; Ver<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;/a&gt;<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;?php endif; ?&gt;<br><br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;?php if (\$permissionManager->canUpdate()): ?&gt;<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;a href=\"?view=example&amp;action=edit&amp;id=&lt;?= \$item['id'] ?&gt;\" class=\"btn btn-warning btn-sm\"&gt;<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;i class=\"fas fa-edit\"&gt;&lt;/i&gt; Editar<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;/a&gt;<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;?php endif; ?&gt;<br><br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;?php if (\$permissionManager->canDelete()): ?&gt;<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;button onclick=\"deleteItem(&lt;?= \$item['id'] ?&gt;)\" class=\"btn btn-danger btn-sm\"&gt;<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;i class=\"fas fa-trash\"&gt;&lt;/i&gt; Eliminar<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;/button&gt;<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;?php endif; ?&gt;<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;/td&gt;<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;/tr&gt;<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;?php endforeach; ?&gt;<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;/tbody&gt;<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&lt;/table&gt;<br>";
echo "&lt;/div&gt;";
echo "</div>";

echo "<h3>3. En JavaScript</h3>";

echo "<div class='example'>";
echo "<strong>Ejemplo de JavaScript con verificación de permisos:</strong>";
echo "</div>";

echo "<div class='code'>";
echo "&lt;script&gt;<br>";
echo "// Verificar permisos antes de realizar acciones<br>";
echo "function deleteItem(id) {<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;if (!confirm('¿Estás seguro de que quieres eliminar este elemento?')) {<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;return;<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;}<br><br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;fetch(`?view=example&amp;action=deleteAjax&amp;id=\${id}`, {<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;method: 'POST',<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;headers: {<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'X-Requested-With': 'XMLHttpRequest'<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;})<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;.then(response =&gt; response.json())<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;.then(data =&gt; {<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;if (data.success) {<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;location.reload();<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;} else {<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;alert(data.message || 'Error al eliminar');<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;})<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;.catch(error =&gt; {<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;console.error('Error:', error);<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;alert('Error al eliminar el elemento');<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;});<br>";
echo "}<br>";
echo "&lt;/script&gt;";
echo "</div>";

echo "<h3>4. Métodos Útiles del PermissionManager</h3>";

echo "<div class='info'>";
echo "<strong>Métodos principales:</strong><br><br>";
echo "• <code>canCreate()</code> - Verifica si puede crear<br>";
echo "• <code>canRead()</code> - Verifica si puede leer<br>";
echo "• <code>canUpdate()</code> - Verifica si puede actualizar<br>";
echo "• <code>canDelete()</code> - Verifica si puede eliminar<br>";
echo "• <code>hasPermission('create')</code> - Verifica un permiso específico<br>";
echo "• <code>hasAllPermissions(['read', 'update'])</code> - Verifica múltiples permisos<br>";
echo "• <code>hasAnyPermission(['create', 'delete'])</code> - Verifica al menos un permiso<br>";
echo "• <code>requirePermission('read')</code> - Requiere un permiso o redirige<br>";
echo "• <code>requirePermissionAjax('delete')</code> - Requiere un permiso para AJAX<br>";
echo "• <code>getUserEffectivePermissions()</code> - Obtiene todos los permisos<br>";
echo "• <code>getUserPermissionInfo()</code> - Obtiene información completa<br>";
echo "</div>";

echo "<h2>🎯 Beneficios del Sistema</h2>";

echo "<div class='info'>";
echo "<strong>✅ Seguridad:</strong> Verificación automática de permisos en cada acción<br>";
echo "<strong>✅ Flexibilidad:</strong> Permisos granulares por rol (CRUD)<br>";
echo "<strong>✅ Mantenibilidad:</strong> Lógica centralizada en PermissionManager<br>";
echo "<strong>✅ Escalabilidad:</strong> Fácil agregar nuevos permisos<br>";
echo "<strong>✅ UX:</strong> Botones y opciones se muestran/ocultan según permisos<br>";
echo "<strong>✅ AJAX:</strong> Respuestas JSON automáticas para peticiones AJAX<br>";
echo "</div>";

echo "<h2>🔧 Próximos Pasos</h2>";

echo "<div class='warning'>";
echo "<strong>Para implementar completamente:</strong><br><br>";
echo "1. <strong>Actualizar controladores existentes</strong> para usar PermissionManager<br>";
echo "2. <strong>Modificar vistas</strong> para mostrar/ocultar elementos según permisos<br>";
echo "3. <strong>Proteger rutas AJAX</strong> con requirePermissionAjax()<br>";
echo "4. <strong>Crear middleware</strong> para verificación automática de rutas<br>";
echo "5. <strong>Documentar</strong> los permisos requeridos para cada funcionalidad<br>";
echo "</div>";

echo "<div class='success'>";
echo "<strong>🎉 El sistema de permisos está listo para usar!</strong><br>";
echo "Los permisos ahora se basan en la tabla role_permissions y se aplican dinámicamente según el rol del usuario.";
echo "</div>";
?> 