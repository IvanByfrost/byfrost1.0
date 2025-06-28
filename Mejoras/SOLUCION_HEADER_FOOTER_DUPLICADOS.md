# Solución: Headers y Footers Duplicados en Vistas

## Problema Identificado

El problema consistía en que las vistas aparecían con header y footer duplicados cuando se cargaban en dashboards o mediante AJAX. Esto ocurría porque:

1. **Clase `Views.php`**: Incluía automáticamente `head.php` y `footer.php` en todas las vistas
2. **`MainController.php`**: Los métodos `render()` y `loadView()` incluían automáticamente `head.php`, `header.php` y `footer.php`
3. **Vistas de dashboard**: Ya tenían su propio header y footer específico (`dashHeader.php` y `dashFooter.php`)

## Problema Secundario: Detección de Peticiones AJAX

Durante la implementación, se encontró un problema adicional: el método `isAjaxRequest()` no detectaba correctamente las peticiones AJAX modernas, causando el error:

```json
{"success":false,"message":"Solo se permiten peticiones AJAX","data":[]}
```

## Solución Implementada

### 1. Mejora del Método `isAjaxRequest()`

Se mejoró el método para detectar peticiones AJAX usando múltiples estrategias:

```php
/**
 * Verifica si la petición es AJAX
 * 
 * @return bool
 */
protected function isAjaxRequest()
{
    // Método 1: Verificar HTTP_X_REQUESTED_WITH
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        return true;
    }
    
    // Método 2: Verificar si es una petición fetch moderna
    if (!empty($_SERVER['HTTP_ACCEPT']) && 
        strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) {
        return true;
    }
    
    // Método 3: Verificar si hay parámetros específicos de AJAX
    if (isset($_POST['ajax']) || isset($_GET['ajax'])) {
        return true;
    }
    
    // Método 4: Verificar Content-Type para peticiones POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && 
        !empty($_SERVER['CONTENT_TYPE']) && 
        strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
        return true;
    }
    
    return false;
}
```

### 2. Flexibilización del Método `loadPartial()`

Se modificó para permitir peticiones no-AJAX también:

```php
/**
 * Carga una vista parcial vía AJAX
 * Útil para cargar contenido en dashboards sin header y footer
 */
public function loadPartial()
{
    // Obtener parámetros
    $view = $_POST['view'] ?? $_GET['view'] ?? '';
    $action = $_POST['action'] ?? $_GET['action'] ?? 'index';
    $force = isset($_POST['force']) || isset($_GET['force']); // Permitir forzar la carga
    
    // Verificar que sea una petición AJAX o esté forzada
    if (!$this->isAjaxRequest() && !$force) {
        // Si no es AJAX, mostrar un mensaje de error más informativo
        if (empty($view)) {
            echo '<div class="alert alert-warning">Vista no especificada. Use: ?view=index&action=loadPartial&view=nombreVista&action=accion</div>';
            return;
        }
        
        // Si se especifica una vista, cargarla directamente
        $viewPath = $view . '/' . $action;
        $fullPath = ROOT . "/app/views/{$viewPath}.php";
        
        if (!file_exists($fullPath)) {
            echo '<div class="alert alert-danger">Vista no encontrada: ' . htmlspecialchars($viewPath) . '</div>';
            return;
        }
        
        // Cargar la vista parcial directamente
        try {
            $this->loadPartialView($viewPath);
        } catch (Exception $e) {
            echo '<div class="alert alert-danger">Error al cargar la vista: ' . htmlspecialchars($e->getMessage()) . '</div>';
        }
        return;
    }
    
    // ... resto del código para peticiones AJAX
}
```

### 3. Actualización de `loadView.js`

Se agregó el header `X-Requested-With` para asegurar la detección AJAX:

```javascript
fetch(url, {
    method: 'POST',
    headers: {
        'X-Requested-With': 'XMLHttpRequest'
    },
    body: formData
})
```

### 4. Nuevos Métodos en `Views.php`

Se agregaron dos nuevos métodos:

```php
/**
 * Renderiza una vista parcial sin header y footer
 * Útil para cargar contenido en dashboards o AJAX
 */
public function RenderPartial($folder, $file, $data = null) {
    $viewPath = ROOT . '/app/views/' . $folder . '/' . $file . '.php';
    
    if (file_exists($viewPath)) {
        if ($data) extract($data);
        require $viewPath;
    } else {
        echo "Vista parcial no encontrada: $viewPath";
    }
}

/**
 * Renderiza solo el contenido de la vista sin ningún layout
 */
public function RenderContent($folder, $file, $data = null) {
    $viewPath = ROOT . '/app/views/' . $folder . '/' . $file . '.php';
    
    if (file_exists($viewPath)) {
        if ($data) extract($data);
        ob_start();
        require $viewPath;
        $content = ob_get_clean();
        return $content;
    } else {
        return "Vista no encontrada: $viewPath";
    }
}
```

### 5. Nuevos Métodos en `MainController.php`

Se agregaron tres nuevos métodos:

```php
/**
 * Renderiza una vista parcial sin header y footer
 */
protected function renderPartial($folder, $file = 'index', $data = [])
{
    $viewPath = ROOT . "/app/views/{$folder}/{$file}.php";
    
    if (file_exists($viewPath)) {
        extract($data);
        require $viewPath;
    } else {
        echo "Vista parcial no encontrada: $viewPath";
    }
}

/**
 * Renderiza solo el contenido de la vista sin ningún layout
 */
protected function renderContent($folder, $file = 'index', $data = [])
{
    $viewPath = ROOT . "/app/views/{$folder}/{$file}.php";
    
    if (file_exists($viewPath)) {
        extract($data);
        ob_start();
        require $viewPath;
        $content = ob_get_clean();
        return $content;
    } else {
        return "Vista no encontrada: $viewPath";
    }
}

/**
 * Carga una vista parcial sin header y footer
 */
protected function loadPartialView($viewPath, $data = [])
{
    $viewPath = ROOT . "/app/views/{$viewPath}.php";
    
    if (file_exists($viewPath)) {
        extract($data);
        require $viewPath;
    } else {
        echo "Vista parcial no encontrada: $viewPath";
    }
}
```

## Cómo Usar la Solución

### Para Vistas Completas (con header y footer)
```php
// En un controlador
$this->render('index', 'about'); // Carga vista completa
$this->loadView('index/about');  // Carga vista completa
```

### Para Vistas Parciales (sin header y footer)
```php
// En un controlador
$this->renderPartial('index', 'about');     // Carga vista parcial
$this->loadPartialView('index/about');      // Carga vista parcial
$content = $this->renderContent('index', 'about'); // Retorna contenido como string
```

### Para Cargas AJAX desde JavaScript
```javascript
// Usar la función loadView que ahora carga vistas parciales
loadView('index/about');
loadView('school/consultSchool');
```

### Para Cargas Directas (sin AJAX)
```php
// URL directa para cargar vista parcial
http://localhost:8000/?view=index&action=loadPartial&view=index&action=about&force=1
```

## Archivos de Prueba

Se crearon dos archivos de prueba:

1. **`tests/test-partial-views.php`**: Verifica que las vistas parciales se cargan sin header y footer
2. **`tests/test-ajax-loadPartial.php`**: Verifica que el endpoint AJAX funciona correctamente

## Beneficios de la Solución

1. **Eliminación de duplicación**: No más headers y footers duplicados
2. **Flexibilidad**: Diferentes métodos para diferentes necesidades
3. **Compatibilidad**: Los métodos existentes siguen funcionando
4. **Mantenibilidad**: Código más limpio y organizado
5. **Performance**: Menos HTML innecesario en cargas AJAX
6. **Robustez**: Detección mejorada de peticiones AJAX
7. **Flexibilidad**: Permite cargas tanto AJAX como directas

## Notas Importantes

- Los métodos existentes (`render()`, `loadView()`) siguen funcionando igual
- Los nuevos métodos son opcionales y no afectan el código existente
- Las vistas de dashboard ya no tendrán headers duplicados
- El archivo `loadView.js` ahora carga vistas parciales por defecto
- El endpoint `loadPartial` funciona tanto con AJAX como con peticiones directas
- Se agregó el parámetro `force=1` para forzar cargas no-AJAX 