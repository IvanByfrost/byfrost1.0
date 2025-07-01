# SweetAlert2 para Manejo de Sesión Expirada

## Descripción
Se ha implementado un sistema de alertas visuales atractivas usando SweetAlert2 para reemplazar los mensajes simples de sesión expirada.

## Archivos Modificados

### 1. SessionMiddleware.php
- **Ubicación**: `app/library/SessionMiddleware.php`
- **Cambios**:
  - Modificado `handleSessionExpired()` para incluir configuración de SweetAlert2
  - Modificado `handleSessionError()` para incluir configuración de SweetAlert2
  - Agregados campos `showSwal` y `swalConfig` en las respuestas JSON

### 2. sessionHandler.js
- **Ubicación**: `app/resources/js/sessionHandler.js`
- **Funcionalidad**:
  - Intercepta todas las respuestas AJAX
  - Detecta respuestas de sesión expirada
  - Muestra alertas de SweetAlert2 automáticamente
  - Maneja redirecciones después de cerrar la alerta

### 3. Layouts
- **dashFooter.php**: Agregado `sessionHandler.js`
- **footer.php**: Agregado `sessionHandler.js`

## Configuración de Alertas

### Sesión Expirada
```json
{
    "success": false,
    "message": "Sesión expirada. Por favor, inicia sesión nuevamente.",
    "redirect": "/?view=index&action=login",
    "showSwal": true,
    "swalConfig": {
        "icon": "warning",
        "title": "¡Sesión Expirada!",
        "text": "Tu sesión ha expirado por inactividad. Por favor, inicia sesión nuevamente.",
        "confirmButtonText": "Entendido",
        "confirmButtonColor": "#3085d6"
    }
}
```

### Error de Sesión
```json
{
    "success": false,
    "message": "Error de sesión. Por favor, inicia sesión nuevamente.",
    "redirect": "/?view=index&action=login",
    "showSwal": true,
    "swalConfig": {
        "icon": "error",
        "title": "¡Error de Sesión!",
        "text": "Ha ocurrido un error con tu sesión. Por favor, inicia sesión nuevamente.",
        "confirmButtonText": "Entendido",
        "confirmButtonColor": "#d33"
    }
}
```

## Características

### Alertas Automáticas
- Se muestran automáticamente cuando se detecta una sesión expirada
- No requieren intervención manual del desarrollador
- Compatibles con todas las peticiones AJAX existentes

### Personalización
- Iconos diferentes para sesión expirada (warning) y error de sesión (error)
- Colores personalizados para los botones
- Textos descriptivos y amigables

### Experiencia de Usuario
- Alertas no se pueden cerrar haciendo clic fuera
- No se pueden cerrar con la tecla Escape
- Redirección automática después de confirmar

## Compatibilidad

### Respuestas Existentes
El sistema es compatible con respuestas existentes que no incluyen `showSwal`:
```json
{
    "success": false,
    "message": "Sesión expirada. Por favor, inicia sesión nuevamente.",
    "redirect": "/?view=index&action=login"
}
```

### Nuevas Respuestas
Para respuestas nuevas, se recomienda usar la configuración completa:
```json
{
    "success": false,
    "message": "Sesión expirada. Por favor, inicia sesión nuevamente.",
    "redirect": "/?view=index&action=login",
    "showSwal": true,
    "swalConfig": {
        "icon": "warning",
        "title": "¡Sesión Expirada!",
        "text": "Tu sesión ha expirado por inactividad. Por favor, inicia sesión nuevamente.",
        "confirmButtonText": "Entendido",
        "confirmButtonColor": "#3085d6"
    }
}
```

## Pruebas

### Archivos de Prueba
- `tests/test-session-swal.php`: Simula respuesta de sesión expirada
- `tests/test-swal-alert.html`: Página de prueba para verificar funcionamiento

### Cómo Probar
1. Abrir `tests/test-swal-alert.html` en el navegador
2. Hacer clic en "Probar Sesión Expirada" para ver la alerta
3. Hacer clic en "Probar Respuesta AJAX" para simular una petición AJAX

## Beneficios

1. **Mejor UX**: Alertas visuales atractivas en lugar de mensajes simples
2. **Consistencia**: Mismo estilo de alertas en toda la aplicación
3. **Automatización**: No requiere cambios en código existente
4. **Personalización**: Fácil de personalizar colores, textos e iconos
5. **Compatibilidad**: Funciona con código existente sin modificaciones

## Notas Técnicas

- Requiere jQuery y SweetAlert2 (ya incluidos en los layouts)
- Intercepta todas las respuestas AJAX automáticamente
- Maneja errores de parsing JSON de forma segura
- Compatible con todas las versiones de navegador modernas 