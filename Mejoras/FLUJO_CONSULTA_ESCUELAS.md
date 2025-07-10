# Flujo de Consulta de Escuelas - Dashboard del Director

## Resumen del Flujo

Cuando un director hace clic en "Consultar Colegios" en el dashboard, el sistema ejecuta el siguiente flujo:

### 1. Interacción del Usuario
- El director hace clic en "Consultar Colegios" en el sidebar
- Se ejecuta: `loadView('school/consultSchool')`

### 2. Procesamiento JavaScript
- La función `loadView()` en `loadView.js` detecta que es una vista de acción directa
- Construye la URL: `?view=school&action=consultSchool`
- Hace una petición AJAX al servidor

### 3. Procesamiento del Servidor
- El `SchoolController` recibe la petición
- Ejecuta el método `consultSchool()`
- El método obtiene todas las escuelas de la base de datos
- Carga la vista `school/consultSchool.php` con los datos

### 4. Respuesta al Cliente
- El servidor devuelve el HTML de la vista
- JavaScript actualiza el contenido del `#mainContent`
- Se muestra la lista completa de escuelas

## Archivos Involucrados

### Frontend
- `app/views/director/directorSidebar.php` - Enlace en el sidebar
- `app/resources/js/loadView.js` - Función de carga de vistas
- `app/views/school/consultSchool.php` - Vista de la lista de escuelas

### Backend
- `app/controllers/SchoolController.php` - Controlador principal
- `app/models/SchoolModel.php` - Modelo de datos
- `app/controllers/MainController.php` - Controlador base

## Modificaciones Realizadas

### 1. loadView.js
Se modificó la función `buildViewUrl()` para detectar automáticamente las vistas que deben usar acción directa:

```javascript
// Lista de vistas que deben usar acción directa en lugar de loadPartial
const directActionViews = ['school/consultSchool', 'user/consultUser', 'user/assignRole'];

// Si la vista está en la lista de acciones directas
if (directActionViews.includes(viewName)) {
    return `${baseUrl}?view=${module}&action=${partialView}`;
}
```

### 2. SchoolController.php
El método `consultSchool()` ya estaba correctamente implementado:

```php
public function consultSchool()
{
    $this->protectSchool();
    
    $search = $_GET['search'] ?? '';
    $schools = $this->schoolModel->getAllSchools();
    
    // Filtrado por búsqueda si es necesario
    if (!empty($search)) {
        $schools = array_filter($schools, function($school) use ($search) {
            return stripos($school['school_name'], $search) !== false ||
                   stripos($school['school_dane'], $search) !== false ||
                   stripos($school['school_document'], $search) !== false;
        });
    }
    
    $this->loadPartialView('school/consultSchool', [
        'schools' => $schools,
        'search' => $search,
        'success' => $_GET['success'] ?? false,
        'message' => $_GET['msg'] ?? ''
    ]);
}
```

## Verificación del Flujo

Para verificar que el flujo funciona correctamente:

1. **Acceder al dashboard del director**
2. **Hacer clic en "Consultar Colegios"**
3. **Verificar que se muestre la lista de escuelas**

Si hay problemas, verificar:

- ✅ Que el archivo `loadView.js` esté cargado
- ✅ Que el elemento `#mainContent` exista en el DOM
- ✅ Que el usuario tenga permisos de director
- ✅ Que la base de datos tenga escuelas registradas
- ✅ Que no haya errores en la consola del navegador

## Funcionalidades de la Vista

La vista `consultSchool.php` incluye:

- **Lista completa de escuelas** en formato tabla
- **Búsqueda** por nombre, código DANE o NIT
- **Acciones** para cada escuela (ver, editar, eliminar)
- **Mensajes de éxito/error** después de operaciones
- **Botón para crear nueva escuela**

## URLs Generadas

- **Consulta general**: `?view=school&action=consultSchool`
- **Búsqueda**: `?view=school&action=consultSchool&search=termino`
- **Con mensaje de éxito**: `?view=school&action=consultSchool&success=1&msg=mensaje`

## Pruebas

Se puede ejecutar el archivo `test-school-flow.php` para verificar que todos los componentes funcionan correctamente. 