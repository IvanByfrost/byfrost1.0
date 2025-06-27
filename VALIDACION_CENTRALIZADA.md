# ⚠️ Informe: Falta de Validación Centralizada en Byfrost 1.0

## ¿Qué es la validación centralizada?
La validación centralizada es una práctica donde toda la lógica de validación y sanitización de datos de entrada (formularios, peticiones, etc.) se gestiona desde un único lugar o clase, en vez de estar dispersa y repetida en múltiples controladores o scripts.

---

## 🕵️‍♂️ Ejemplos encontrados en el proyecto

### 1. Validación repetitiva en controladores
```php
// app/controllers/loginController.php
if (empty($_POST['subject']) || empty($_POST['credType']) || empty($_POST['userDocument']) || empty($_POST['userPassword'])) {
    echo json_encode([
        "status" => "error",
        "msg" => "Faltan uno o más datos obligatorios."
    ]);
    exit;
}
```
- **Problema:** Este tipo de validación se repite en muchos controladores, lo que genera código duplicado y difícil de mantener.

### 2. Falta de validación de tipos y formatos
- No se valida si los datos cumplen con el formato esperado (ej: email válido, fecha válida, etc.).
- No se sanitizan los datos para evitar inyecciones o XSS.

### 3. Ausencia de una clase o helper de validación
- No existe una clase `Validator` o similar que centralice la lógica de validación y sanitización.

---

## 🚨 Impacto de la falta de validación centralizada

- **Mayor riesgo de errores y vulnerabilidades** (SQL Injection, XSS, datos corruptos).
- **Código duplicado** y difícil de mantener.
- **Dificultad para actualizar reglas de validación** (hay que cambiar en muchos lugares).
- **Inconsistencia** en la experiencia del usuario y en la seguridad.

---

## ✅ Buenas prácticas y estándar recomendado

### 1. Crear una clase `Validator`
- Centralizar todas las reglas y funciones de validación.
- Ejemplo de métodos: `isEmail()`, `isDate()`, `isRequired()`, `sanitizeString()`, etc.

### 2. Usar filtros y funciones nativas de PHP
- `filter_var($email, FILTER_VALIDATE_EMAIL)`
- `filter_var($string, FILTER_SANITIZE_STRING)`

### 3. Validar y sanear **todos** los datos de entrada
- Antes de procesar o guardar cualquier dato, validarlo y sanearlo.

### 4. Reutilizar la lógica de validación
- Llamar a la clase `Validator` desde los controladores, en vez de repetir la lógica.

---

## 🛠️ Recomendaciones para el proyecto

1. **Crear una clase `Validator`** en `app/library/Validator.php`.
2. **Reemplazar la validación repetida** en los controladores por llamadas a esta clase.
3. **Agregar validaciones de tipo y formato** (email, fechas, números, etc.).
4. **Sanear todos los datos** antes de usarlos en consultas o vistas.
5. **Documentar** las reglas de validación y su uso.

---

## 📈 Beneficios de aplicar estas prácticas

- Mayor seguridad y robustez.
- Código más limpio y fácil de mantener.
- Facilidad para actualizar reglas de validación.
- Menos errores y vulnerabilidades.
- Mejor experiencia de usuario.

---

¿Dudas o sugerencias? ¡Hablemos para implementar un sistema de validación centralizado en Byfrost 1.0! 