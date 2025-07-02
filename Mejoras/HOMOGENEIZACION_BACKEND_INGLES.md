# Propuesta de Homogeneización del Idioma del Backend a Inglés

## Objetivo
Homogeneizar todo el backend (modelos, controladores, variables, métodos, rutas internas, base de datos, etc.) al idioma inglés, siguiendo el estándar internacional de desarrollo de software. Esto facilita la mantenibilidad, escalabilidad y colaboración con equipos internacionales.

---

## ¿Qué implica?

1. **Variables y métodos:**
   - Usar nombres en inglés: `first_name`, `last_name`, `getUsers()`, `deleteUser()`, `roleType`, etc.
   - Evitar nombres en español: `nombre`, `eliminarUsuario()`, `tipoDoc`, etc.

2. **Rutas y endpoints internos:**
   - Usar rutas como `/user/getUsersAjax`, `/user/deleteUserAjax`, etc.

3. **Mensajes internos, comentarios y documentación técnica:**
   - En inglés para el código, en español solo si es documentación para usuarios finales.

4. **Base de datos:**
   - Nombres de tablas y columnas en inglés: `users`, `roles`, `credential_type`, etc.

5. **Constantes y enums:**
   - Usar valores en inglés: `role = 'student'`, `role = 'professor'`, etc.

---

## ¿Qué NO se debe traducir?
- Textos visibles para el usuario final (UI, alertas, emails, etc.) pueden y deben seguir en español.
- Documentación de usuario, manuales, etc.

---

## Pasos sugeridos para la migración

1. **Inventario:**
   - Identificar todos los nombres en español en el backend (modelos, controladores, variables, métodos, rutas, base de datos).

2. **Planificación por módulos:**
   - Migrar por módulos (ejemplo: primero usuarios, luego roles, etc.).
   - Refactorizar variables, métodos y rutas.
   - Actualizar formularios y JS para enviar/recibir los nuevos nombres.

3. **Base de datos:**
   - Actualizar nombres de tablas y columnas si es necesario (con migraciones controladas).

4. **Pruebas:**
   - Probar exhaustivamente cada módulo tras la migración.

5. **Documentación:**
   - Documentar el nuevo estándar para el equipo.

---

## Ejemplo de homogeneización

| Español (actual)      | Inglés (propuesto)      |
|-----------------------|------------------------|
| `nombre`              | `first_name`           |
| `apellido`            | `last_name`            |
| `tipoDoc`             | `credential_type`      |
| `numeroDoc`           | `credential_number`    |
| `eliminarUsuario()`   | `deleteUser()`         |
| `consultarUsuario()`  | `getUser()`            |
| `rol`                 | `role`                 |
| `agregarUsuario()`    | `createUser()`         |

---

## Beneficios
- Código más profesional y estándar.
- Facilita la colaboración internacional.
- Reduce errores y ambigüedades.
- Mejora la mantenibilidad y escalabilidad.

---

## Tarea sugerida para el equipo
- Revisar y homogeneizar todos los nombres del backend a inglés.
- Actualizar rutas, variables, métodos y base de datos según corresponda.
- Mantener la UI y mensajes de usuario en español.
- Documentar el nuevo estándar y comunicarlo a todo el equipo. 