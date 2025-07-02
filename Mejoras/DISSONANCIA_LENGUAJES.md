# Disonancia de Lenguajes en el Sistema BYFROST

Este documento identifica y ejemplifica las inconsistencias de lenguaje, nombres y convenciones en el sistema BYFROST, para que el equipo pueda trabajar en su homogeneización.

---

## 1. Roles y Tipos de Usuario
- **Backend/Base de datos:**
  - `student`, `parent`, `professor`, `coordinator`, `director`, `treasurer`, `root`, `administrative`
- **Frontend/UI:**
  - `Estudiante`, `Docente`, `Administrativo`, `Rector`, `Coordinador`
- **Problema:**
  - Se mezclan valores en inglés (para lógica) y español (para UI), pero a veces se envía el valor en español al backend, causando errores o confusión.

**Ejemplo:**
```html
<option value="student">Estudiante</option>
```

---

## 2. Nombres de Variables y Campos
- **Backend/modelo:**
  - `first_name`, `last_name`, `email`, `credential_type`, `credential_number`
- **Frontend/formularios:**
  - `nombre`, `usuario`, `tipoDoc`, `numeroDoc`, `clave`
- **Problema:**
  - El campo `usuario` en el formulario es realmente el `email` en la base de datos.
  - El campo `nombre` se mapea a `first_name`, pero no hay campo para `last_name`.

---

## 3. Rutas y Nombres de Vistas
- **Rutas en inglés:**
  - `user/settingsRoles`, `user/assignRole`, `user/consultUser`
- **Rutas en español:**
  - `school/consultSchool`, etc.
- **Problema:**
  - Inconsistencia en el idioma de las rutas y nombres de archivos.

---

## 4. Mensajes y Textos
- **Mensajes de error y éxito:** Español
- **Variables y métodos:** Inglés
- **Textos de botones y títulos:** Español
- **Problema:**
  - Mezcla de idiomas en el código y la UI.

---

## 5. Convenciones de Código
- **PHP:** Mezcla de camelCase y snake_case.
- **JS:** Mayormente camelCase.
- **HTML:** id/class en inglés y español mezclados.

---

## 6. Otros Ejemplos
- El rol `professor` en backend, pero `Docente` en frontend.
- En la tabla de usuarios, se muestra `Nombre`, pero en la base de datos está dividido en `first_name` y `last_name`.

---

# Recomendaciones para Homogeneizar

1. **Unificar los valores de roles:**
   - Usar SIEMPRE los mismos valores (`student`, `professor`, etc.) en backend, frontend y base de datos.
   - Mostrar el nombre traducido solo en la UI, pero enviar el valor en inglés al backend.
2. **Mapeo claro de campos:**
   - En los formularios, usar nombres de campos que correspondan a los del backend, o hacer el mapeo explícito en JS.
3. **Consistencia en rutas y archivos:**
   - Elegir un idioma para rutas y nombres de archivos (preferiblemente inglés para código, español para UI).
4. **Mensajes y textos:**
   - Mantener los mensajes de usuario en español, pero los nombres de variables y métodos en inglés.
5. **Documentar el mapeo:**
   - Tener una tabla de equivalencias entre los términos en español e inglés usados en el sistema.

---

# Tarea sugerida para el equipo
- Revisar y homogeneizar los valores de roles y campos en todo el sistema.
- Unificar el idioma de rutas, archivos y variables.
- Documentar el mapeo de términos clave (roles, campos de usuario, etc.).
- Mantener la UI en español, pero la lógica y los datos en inglés para consistencia técnica. 