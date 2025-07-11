# Solución: Problema de Búsqueda de Directores y Coordinadores en Modal de Crear Escuela

## Problema Identificado

Cuando un usuario intentaba buscar un director o coordinador en el modal de crear escuela, el sistema se devolvía al "inicio" del dashboard en lugar de manejar el error apropiadamente.

## Causa Raíz

1. **Manejo inadecuado de errores**: Los métodos de búsqueda lanzaban excepciones genéricas sin información detallada.

2. **JavaScript no manejaba errores HTTP**: Las peticiones AJAX no verificaban el estado de la respuesta HTTP antes de intentar parsear JSON.

3. **Falta de logging detallado**: No había suficiente información de debug para identificar el problema.

4. **Restricciones de permisos**: Los directores no pueden buscar otros directores, y los coordinadores no pueden buscar otros coordinadores, pero el error no se manejaba correctamente en el frontend.

## Soluciones Implementadas

### 1. Mejora del Modelo UserModel

**Archivo**: `app/models/userModel.php`

- **Validación de parámetros**: Se agregó validación para parámetros vacíos
- **Manejo de errores PDO**: Separación de errores de base de datos de otros errores
- **Logging detallado**: Se agregó logging para debug
- **Mensajes de error específicos**: Cada tipo de error tiene un mensaje descriptivo

```php
public function searchUsersByRoleAndDocument($roleType, $credentialNumber, $currentUserRole = null)
{
    try {
        // Validación de permisos
        if ($currentUserRole === 'director' && in_array($roleType, ['root', 'director'])) {
            throw new Exception('No tienes permisos para buscar usuarios con ese rol.');
        }
        
        // Validación de parámetros
        if (empty($roleType) || empty($credentialNumber)) {
            throw new Exception('Tipo de rol y número de documento son requeridos.');
        }
        
        // ... resto del código con mejor manejo de errores
    } catch (PDOException $e) {
        error_log("UserModel::searchUsersByRoleAndDocument PDO Error: " . $e->getMessage());
        throw new Exception('Error de base de datos al buscar usuarios: ' . $e->getMessage());
    } catch (Exception $e) {
        error_log("UserModel::searchUsersByRoleAndDocument Error: " . $e->getMessage());
        throw new Exception('Error al buscar usuarios por rol y documento: ' . $e->getMessage());
    }
}
```

### 2. Mejora del Proceso AJAX

**Archivo**: `app/processes/assignProcess.php`

- **Logging detallado**: Se agregó logging para cada paso del proceso
- **Manejo específico de errores**: Diferentes tipos de error tienen mensajes específicos
- **Stack trace**: Se incluye información de debug completa

```php
case 'search_users_by_role':
    try {
        error_log("DEBUG assignProcess - Iniciando búsqueda por rol: role_type=$roleType, search_type=$searchType, query=$query");
        
        // ... lógica de búsqueda
        
    } catch (Exception $e) {
        error_log("DEBUG assignProcess - Excepción en búsqueda por rol: " . $e->getMessage());
        error_log("DEBUG assignProcess - Stack trace: " . $e->getTraceAsString());
        
        // Determinar el tipo de error para dar una respuesta más específica
        $errorMessage = $e->getMessage();
        if (strpos($errorMessage, 'No tienes permisos') !== false) {
            $errorMessage = 'No tienes permisos para buscar usuarios con ese rol.';
        } elseif (strpos($errorMessage, 'Error de base de datos') !== false) {
            $errorMessage = 'Error de conexión con la base de datos.';
        }
        
        echo json_encode([
            'status' => 'error',
            'msg' => $errorMessage
        ]);
    }
    break;
```

### 3. Mejora del JavaScript Frontend

**Archivos**: 
- `app/views/school/createSchool.php`
- `app/views/school/editSchool.php`
- `app/views/director/dashboard.php`

- **Verificación de respuesta HTTP**: Se verifica `response.ok` antes de parsear JSON
- **Manejo específico de errores**: Diferentes tipos de error se muestran apropiadamente
- **Validación de entrada**: Se valida que el usuario ingrese datos antes de buscar
- **Headers mejorados**: Se incluye `X-Requested-With: XMLHttpRequest`

```javascript
fetch('app/processes/assignProcess.php', {
    method: 'POST',
    headers: { 
        'Content-Type': 'application/x-www-form-urlencoded',
        'X-Requested-With': 'XMLHttpRequest'
    },
    body: 'subject=search_users_by_role&role_type=coordinator&search_type=document&query=' + encodeURIComponent(query)
})
.then(response => {
    if (!response.ok) {
        throw new Error(`Error HTTP: ${response.status}`);
    }
    return response.json();
})
.then(data => {
    console.log('Respuesta de búsqueda de coordinador:', data);
    
    if (data.status === 'ok' && data.data && data.data.length > 0) {
        // Mostrar resultados
    } else if (data.status === 'error') {
        resultsDiv.innerHTML = `<div class="alert alert-danger">${data.msg || 'Error al buscar coordinadores.'}</div>`;
    } else {
        resultsDiv.innerHTML = '<div class="alert alert-warning">No se encontraron coordinadores con ese documento.</div>';
    }
})
.catch(error => {
    console.error('Error en búsqueda de coordinador:', error);
    resultsDiv.innerHTML = '<div class="alert alert-danger">Error de conexión al buscar coordinadores. Intente nuevamente.</div>';
});
```

## Resultados Esperados

1. **No más redirecciones inesperadas**: Los errores se manejan en el modal sin afectar la navegación
2. **Mensajes de error claros**: El usuario ve exactamente qué salió mal
3. **Mejor experiencia de usuario**: Los errores se muestran apropiadamente en el contexto
4. **Debugging mejorado**: Los logs proporcionan información detallada para resolver problemas
5. **Consistencia**: Tanto directores como coordinadores tienen el mismo manejo de errores

## Archivos Modificados

1. `app/models/userModel.php` - Mejora del método `searchUsersByRoleAndDocument`
2. `app/processes/assignProcess.php` - Mejora del manejo de errores en AJAX
3. `app/views/school/createSchool.php` - Mejora del JavaScript de búsqueda
4. `app/views/school/editSchool.php` - Mejora del JavaScript de búsqueda
5. `app/views/director/dashboard.php` - Mejora del JavaScript de búsqueda
6. `test-director-search.php` - Script de prueba actualizado para incluir coordinadores

## Pruebas

Para verificar que la solución funciona:

1. Ejecutar `test-director-search.php` para probar la funcionalidad
2. Intentar buscar un director o coordinador en el modal de crear escuela
3. Verificar que los errores se muestran apropiadamente sin redirecciones
4. Revisar los logs para información de debug

## Notas Importantes

- **Directores**: No pueden buscar otros directores por restricciones de seguridad
- **Coordinadores**: No pueden buscar otros coordinadores por restricciones de seguridad
- **Profesores**: Pueden ser buscados por directores y coordinadores
- **Estudiantes y Padres**: Pueden ser buscados por directores y coordinadores
- Todos los errores ahora se manejan apropiadamente en el frontend
- Se mantiene la funcionalidad existente mientras se mejora la robustez

## Casos de Uso Cubiertos

1. **Director buscando coordinador**: ✅ Permitido
2. **Director buscando profesor**: ✅ Permitido
3. **Director buscando otro director**: ❌ Bloqueado con mensaje claro
4. **Coordinador buscando profesor**: ✅ Permitido
5. **Coordinador buscando otro coordinador**: ❌ Bloqueado con mensaje claro
6. **Búsqueda con parámetros vacíos**: ❌ Bloqueado con mensaje claro
7. **Errores de conexión**: ✅ Manejados apropiadamente
8. **Errores de base de datos**: ✅ Manejados apropiadamente 