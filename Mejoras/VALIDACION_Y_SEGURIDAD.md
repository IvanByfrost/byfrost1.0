# VALIDACIÓN Y SEGURIDAD EN BYFROST

## 1. Librería centralizada: `Validator.php`

- Todas las validaciones y sanitizaciones deben hacerse usando la clase `Validator`.
- Ejemplos de uso:

```php
$email = Validator::sanitizePost('email');
$valid = Validator::validateEmail($email);
if (!$valid['valid']) {
    // Manejo de error
}
```

- Métodos disponibles: `validateEmail`, `validateString`, `validateInt`, `validateFloat`, `validateDate`, `validatePassword`, `validatePhone`, `validateDocument`, `validateUrl`, `validateFile`, `validateArray`, `validateJson`, `sanitizeGet`, `sanitizePost`, `sanitizeRequest`, `validateSession`, `validateRole`, `validatePermission`, `generateCSRFToken`, `validateCSRFToken`, `logValidationError`.

## 2. Validaciones recomendadas
- **Tipo de dato**: Usa `validateInt`, `validateFloat`, `validateString`, etc.
- **Longitud y rango**: Siempre valida la longitud de strings y el rango de números.
- **Formato**: Usa `validateEmail`, `validateDate`, etc.
- **Sanitización**: Siempre usa `sanitizePost`, `sanitizeGet`, etc. antes de validar.

## 3. Protección CSRF
- En cada formulario sensible, agrega:

```php
<input type="hidden" name="csrf_token" value="<?= Validator::generateCSRFToken() ?>">
```

- En el controlador/proceso, valida:

```php
if (!Validator::validateCSRFToken($_POST['csrf_token'] ?? '')) {
    $_SESSION['error'] = 'Token CSRF inválido';
    header('Location: ...');
    exit();
}
```

## 4. Manejo de errores y excepciones
- Usa bloques `try/catch` en operaciones críticas (DB, archivos, etc.).
- No muestres detalles internos al usuario. Usa mensajes genéricos y registra detalles en logs.
- Ejemplo:

```php
try {
    // Operación crítica
} catch (Exception $e) {
    Validator::logValidationError('campo', $e->getMessage(), $_SESSION['user'] ?? null);
    $_SESSION['error'] = 'Ocurrió un error inesperado.';
    header('Location: ...');
    exit();
}
```

## 5. Monitoreo de logs
- Todos los errores de validación se registran en `app/logs/validation_errors.log`.
- Revisa periódicamente este archivo para detectar patrones de ataque o mal uso.

## 6. Pruebas y recomendaciones
- Implementa pruebas unitarias para cada validación crítica.
- Realiza pruebas de inyección SQL/XSS y casos edge.
- Documenta cualquier validación especial o lógica de negocio.

---

**¡Con estas prácticas, ByFrost será mucho más seguro, robusto y profesional!** 