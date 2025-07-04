# 🛠️ Plan de Refactorización y Mejora de Código - Byfrost

Este documento centraliza las mejoras de calidad, legibilidad y mantenibilidad recomendadas para el proyecto Byfrost. Cada sección puede ser abordada en uno o varios commits independientes.

---

## 1. Estandarización de Nombres y Autoloading

### Problemas detectados
- Nombres de clases y archivos inconsistentes (ej: `mainController`, `userModel`).
- Inclusión manual de archivos con `require_once` en muchos lugares.

### Mejoras propuestas
- Renombrar todas las clases y archivos a PascalCase (ej: `MainController.php`, `UserModel.php`).
- Implementar y mejorar el autoloader para seguir el estándar PSR-4.
- Eliminar inclusiones manuales innecesarias.

### Ejemplo de commit
```
refactor: estandariza nombres de clases y archivos a PascalCase y mejora el autoloader
```

---

## 2. Centralización de Configuración

### Problemas detectados
- Parámetros de conexión a base de datos y rutas hardcodeados en varios archivos.

### Mejoras propuestas
- Crear un archivo de configuración centralizado (ej: `config/database.php`).
- Usar variables de entorno (`.env`) para datos sensibles.
- Acceder a la configuración desde un solo punto.

### Ejemplo de commit
```
refactor: centraliza la configuración de base de datos y variables sensibles
```

---

## 3. Manejo de Errores y Excepciones

### Problemas detectados
- Uso de `die()`, `echo` y manejo inconsistente de errores.

### Mejoras propuestas
- Crear una clase `ErrorHandler` para centralizar el manejo de errores y excepciones.
- Mostrar páginas de error amigables y registrar errores en logs.
- Usar excepciones en vez de `die()`.

### Ejemplo de commit
```
refactor: implementa ErrorHandler para manejo centralizado de errores y excepciones
```

---

## 4. Validación y Sanitización de Datos

### Problemas detectados
- Validación básica y repetitiva en controladores.

### Mejoras propuestas
- Crear una clase `Validator` para centralizar la validación de datos.
- Usar filtros de PHP para emails, contraseñas, etc.
- Validar y sanear todos los datos de entrada antes de procesarlos.

### Ejemplo de commit
```
refactor: centraliza y mejora la validación de datos de entrada
```

---

## 5. Respuestas JSON Consistentes

### Problemas detectados
- Respuestas JSON con formatos y claves inconsistentes.

### Mejoras propuestas
- Crear una clase `ApiResponse` para unificar el formato de las respuestas.
- Incluir siempre claves como `status`, `message`, `data`, `timestamp`.

### Ejemplo de commit
```
refactor: unifica el formato de respuestas JSON con ApiResponse
```

---

## 6. Seguridad en Consultas SQL

### Problemas detectados
- Uso de consultas directas y potenciales riesgos de SQL Injection.

### Mejoras propuestas
- Usar siempre prepared statements con parámetros.
- Revisar y corregir todos los métodos de acceso a base de datos.

### Ejemplo de commit
```
refactor: refuerza la seguridad de las consultas SQL usando prepared statements
```

---

## 7. Manejo de Sesiones

### Problemas detectados
- Manejo manual y repetitivo de sesiones.

### Mejoras propuestas
- Crear una clase `SessionManager` para iniciar, validar y destruir sesiones.
- Centralizar la lógica de autenticación y autorización.

### Ejemplo de commit
```
refactor: centraliza el manejo de sesiones y autenticación
```

---

## 8. Refactorización de Modelos

### Problemas detectados
- Métodos incompletos o con lógica repetitiva en los modelos.

### Mejoras propuestas
- Completar y estandarizar los métodos CRUD en los modelos.
- Usar herencia y métodos reutilizables en el modelo base.

### Ejemplo de commit
```
refactor: estandariza y completa los métodos CRUD en los modelos
```

---

## 9. Mejoras en el Enrutador

### Problemas detectados
- Enrutamiento básico y poco flexible.

### Mejoras propuestas
- Mejorar el sistema de rutas para soportar métodos HTTP y middlewares.
- Documentar las rutas disponibles.

### Ejemplo de commit
```
refactor: mejora el sistema de enrutamiento y documenta las rutas
```

---

## 10. Documentación y Comentarios

### Problemas detectados
- Comentarios escasos o poco descriptivos.

### Mejoras propuestas
- Añadir PHPDoc a todas las clases y métodos públicos.
- Explicar la lógica compleja y los puntos de entrada del sistema.

### Ejemplo de commit
```
docs: añade PHPDoc y comentarios explicativos en todo el código
```

---

## 11. Otros Detalles

- Eliminar código muerto o comentado innecesario.
- Unificar el estilo de código (PSR-12 recomendado).
- Añadir ejemplos de uso y convenciones en el README.

---

## Mejoras Implementadas en el Código

## 1. Optimización de Conexiones a Base de Datos

### Problema Identificado
- Se estaban creando múltiples conexiones a la base de datos en cada modelo
- Esto generaba un uso ineficiente de recursos y podía causar problemas de rendimiento
- Cada instancia de modelo creaba su propia conexión PDO

### Solución Implementada

#### 1.1 Patrón Singleton para Conexión a BD
**Archivo:** `app/scripts/connection.php`

Se implementó el patrón Singleton para la conexión a la base de datos:

```php
class DatabaseConnection {
    private static $instance = null;
    private $connection;
    
    private function __construct() {
        // Configuración de conexión
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->connection;
    }
}
```

**Beneficios:**
- Una sola conexión por aplicación
- Mejor rendimiento
- Menor uso de memoria
- Prevención de conexiones múltiples innecesarias

#### 1.2 Refactorización del MainModel
**Archivo:** `app/models/mainModel.php`

Se modificó el modelo principal para usar automáticamente la conexión singleton:

```php
class mainModel {
    protected $dbConn;
    
    public function __construct()
    {
        $this->dbConn = DatabaseConnection::getInstance()->getConnection();
    }
}
```

#### 1.3 Actualización de Todos los Modelos
Se actualizaron todos los modelos para heredar correctamente del `mainModel`:

**Modelos actualizados:**
- `activityModel.php`
- `userModel.php`
- `teacherModel.php`
- `CoordinatorModel.php`
- `subjectModel.php`
- `hmasterModel.php`
- `rootModel.php`
- `studentModel.php`
- `schoolModel.php`
- `scheduleModel.php`
- `parentModel.php`

**Cambios realizados:**
- Eliminación del parámetro `$dbConn` en constructores
- Llamada a `parent::__construct()` en todos los modelos
- Eliminación de la propiedad `$dbConn` redundante

#### 1.4 Actualización de Controladores
Se actualizaron todos los controladores para instanciar modelos sin parámetros:

**Controladores actualizados:**
- `teacherController.php`
- `registerController.php`
- `coordinatorController.php`
- `subjectController.php`
- `assignRoleController.php`

**Cambios realizados:**
- Cambio de `new ModelName($dbConn)` a `new ModelName()`
- Eliminación de la necesidad de pasar la conexión como parámetro

#### 1.5 Actualización de Procesos
Se actualizaron los archivos de procesos:

**Archivos actualizados:**
- `assignProcess.php`
- `profileProcess.php`

### Beneficios de la Implementación

1. **Rendimiento Mejorado:**
   - Una sola conexión a la base de datos por aplicación
   - Reducción significativa en el uso de memoria
   - Menor tiempo de respuesta en consultas

2. **Código Más Limpio:**
   - Eliminación de parámetros innecesarios en constructores
   - Mejor organización del código
   - Patrón de herencia más claro

3. **Mantenibilidad:**
   - Configuración centralizada de la conexión
   - Fácil modificación de parámetros de conexión
   - Código más fácil de entender y mantener

4. **Escalabilidad:**
   - Preparado para futuras mejoras
   - Fácil implementación de pool de conexiones si es necesario
   - Estructura más robusta

### Compatibilidad
- Se mantiene la función `getConnection()` para compatibilidad con código existente
- Todos los modelos existentes funcionan sin cambios en su lógica de negocio
- No se requieren cambios en las vistas o archivos de presentación

### Próximos Pasos Recomendados
1. Implementar logging de consultas para monitoreo
2. Considerar implementación de pool de conexiones para aplicaciones de alto tráfico
3. Agregar manejo de transacciones en el mainModel
4. Implementar cache de consultas frecuentes

---

## 2. CRUD Completo para Actividades

### Descripción
Se implementó un sistema completo de gestión de actividades académicas con todas las operaciones CRUD (Create, Read, Update, Delete).

### Archivos Creados/Modificados

#### 2.1 Modelo de Actividades
**Archivo:** `app/models/activityModel.php`

**Funcionalidades implementadas:**
- `createActivity($data)` - Crear nueva actividad
- `getActivities()` - Obtener todas las actividades con información relacionada
- `getActivityById($activityId)` - Obtener actividad específica
- `getActivitiesByGroup($classGroupId)` - Obtener actividades por grupo
- `getActivitiesByProfessor($professorUserId)` - Obtener actividades por profesor
- `updateActivity($activityId, $data)` - Actualizar actividad existente
- `deleteActivity($activityId)` - Eliminar actividad (con validación de calificaciones)
- `getActivityTypes()` - Obtener tipos de actividad
- `getClassGroups()` - Obtener grupos de clase
- `getAcademicTerms()` - Obtener períodos académicos
- `getProfessorSubjects($professorUserId)` - Obtener materias de un profesor

**Características:**
- Consultas optimizadas con JOINs para obtener información relacionada
- Validación de integridad referencial antes de eliminar
- Manejo de errores con excepciones
- Consultas preparadas para seguridad

#### 2.2 Controlador de Actividades
**Archivo:** `app/controllers/activityController.php`

**Métodos implementados:**
- `showDashboard()` - Vista principal con tabla de actividades
- `showCreateForm()` - Formulario para crear actividad
- `createActivity()` - Procesar creación de actividad
- `showEditForm($activityId)` - Formulario para editar actividad
- `updateActivity($activityId)` - Procesar actualización de actividad
- `deleteActivity($activityId)` - Procesar eliminación de actividad
- `viewActivity($activityId)` - Ver detalles de actividad
- Métodos AJAX para obtener datos dinámicos

**Características:**
- Validación de datos en el servidor
- Respuestas JSON para operaciones AJAX
- Manejo de errores con try-catch
- Redirección automática después de operaciones exitosas

#### 2.3 Vistas de Actividades
**Directorio:** `app/views/activity/`

**Vistas creadas:**
- `dashboard.php` - Vista principal con tabla de actividades y modal de creación
- `create.php` - Formulario dedicado para crear actividades
- `edit.php` - Formulario para editar actividades existentes
- `view.php` - Vista detallada de una actividad específica

**Características de las vistas:**
- Diseño responsivo con Bootstrap
- Tabla con DataTables para paginación y búsqueda
- Modales para operaciones rápidas
- Validación de formularios en JavaScript
- Indicadores visuales de estado (vencidas, pendientes, etc.)
- Integración con SweetAlert2 para notificaciones

#### 2.4 Procesos AJAX
**Archivo:** `app/processes/activityProcess.php`

**Funcionalidades:**
- Procesamiento de operaciones CRUD vía AJAX
- Obtención de datos para selectores dinámicos
- Respuestas JSON estructuradas
- Manejo de errores y excepciones

### Estructura de Datos

#### Tabla `activities`
```sql
CREATE TABLE activities (
    activity_id INT AUTO_INCREMENT PRIMARY KEY,
    activity_name VARCHAR(100) NOT NULL,
    professor_subject_id INT NOT NULL,
    activity_type_id INT NOT NULL,
    class_group_id INT NOT NULL,
    term_id INT NOT NULL,
    max_score DECIMAL(5,2) NOT NULL,
    due_date DATETIME,
    description TEXT,
    created_by_user_id INT NOT NULL
);
```

#### Relaciones
- `professor_subject_id` → `professor_subjects`
- `activity_type_id` → `activity_types`
- `class_group_id` → `class_groups`
- `term_id` → `academic_terms`
- `created_by_user_id` → `users`

### Funcionalidades del CRUD

#### 2.1 Crear Actividad
- Formulario con validación de campos obligatorios
- Selectores dinámicos para grupos, períodos y tipos
- Validación de fecha límite (no puede ser anterior a hoy)
- Integración con el sistema de usuarios

#### 2.2 Leer Actividades
- Vista principal con tabla paginada
- Filtros por grupo, profesor y período
- Indicadores visuales de estado temporal
- Vista detallada con información completa

#### 2.3 Actualizar Actividad
- Formulario pre-poblado con datos existentes
- Validación de cambios
- Preservación de relaciones existentes
- Actualización en tiempo real

#### 2.4 Eliminar Actividad
- Validación de integridad referencial
- Verificación de calificaciones asociadas
- Confirmación antes de eliminar
- Manejo de errores de dependencias

### Características Técnicas

#### 2.1 Seguridad
- Consultas preparadas para prevenir SQL injection
- Validación de datos en servidor y cliente
- Escape de HTML para prevenir XSS
- Control de acceso basado en roles

#### 2.2 Rendimiento
- Consultas optimizadas con JOINs apropiados
- Índices en campos de búsqueda frecuente
- Paginación para grandes volúmenes de datos
- Carga lazy de datos relacionados

#### 2.3 Usabilidad
- Interfaz intuitiva y responsiva
- Feedback visual inmediato
- Validación en tiempo real
- Navegación fluida entre vistas

### Integración con el Sistema

#### 2.1 Sistema de Usuarios
- Asociación con profesores creadores
- Control de acceso por roles
- Auditoría de cambios

#### 2.2 Sistema Académico
- Integración con grupos de clase
- Asociación con períodos académicos
- Relación con materias y profesores

#### 2.3 Sistema de Calificaciones
- Preparado para integración con calificaciones
- Validación de dependencias
- Estructura para futuras funcionalidades

### Próximas Mejoras Sugeridas

1. **Sistema de Calificaciones:**
   - Integración completa con `student_scores`
   - Vista de calificaciones por actividad
   - Reportes de rendimiento

2. **Notificaciones:**
   - Alertas de actividades próximas a vencer
   - Notificaciones a estudiantes y padres
   - Recordatorios automáticos

3. **Reportes:**
   - Estadísticas de actividades por período
   - Reportes de cumplimiento
   - Análisis de rendimiento

4. **Funcionalidades Avanzadas:**
   - Adjuntar archivos a actividades
   - Plantillas de actividades
   - Copiar actividades entre grupos

### Compatibilidad
- Compatible con la estructura de base de datos existente
- Integrado con el sistema de autenticación
- Funciona con el patrón MVC establecido
- Preparado para futuras expansiones

---

## 3. Separación de Responsabilidades JavaScript

### Descripción
Se implementó una separación clara de responsabilidades moviendo todo el código JavaScript de las vistas a archivos independientes, siguiendo las mejores prácticas de desarrollo web.

### Archivos JavaScript Creados

#### 3.1 Dashboard de Actividades
**Archivo:** `app/resources/js/activityDashboard.js`

**Funcionalidades:**
- Inicialización de DataTable con configuración en español
- Carga dinámica de datos para selectores (tipos, grupos, períodos)
- Manejo de formulario de creación con validación
- Operaciones CRUD vía AJAX (crear, ver, editar, eliminar)
- Filtros y exportación de datos
- Manejo de estados de loading y errores

**Características:**
- Configuración responsive de DataTable
- Validación en tiempo real de formularios
- Integración con SweetAlert2 para notificaciones
- Manejo de errores con feedback visual
- Carga lazy de datos relacionados

#### 3.2 Formularios de Actividades
**Archivo:** `app/resources/js/activityForm.js`

**Funcionalidades:**
- Validación en tiempo real de campos requeridos
- Validación especial para puntaje máximo (0-100)
- Validación de fecha límite (no anterior a hoy)
- Carga dinámica de materias según grupo seleccionado
- Manejo de formularios de creación y edición
- Confirmación de salida sin guardar cambios
- Estados de loading durante envío

**Características:**
- Validación progresiva (campo por campo)
- Feedback visual inmediato de errores
- Prevención de envío con datos inválidos
- Scroll automático al primer error
- Preservación de estado original del formulario

#### 3.3 Vista de Detalles de Actividad
**Archivo:** `app/resources/js/activityView.js`

**Funcionalidades:**
- Operaciones de edición, eliminación y visualización
- Funcionalidades adicionales (imprimir, compartir, descargar)
- Envío de recordatorios a estudiantes
- Marcado de actividades como completadas
- Duplicación de actividades
- Contador de tiempo restante en tiempo real
- Atajos de teclado (Ctrl+P para imprimir, Ctrl+S para compartir)

**Características:**
- Integración con Web Share API (navegadores modernos)
- Fallback para copiar al portapapeles
- Tooltips y popovers informativos
- Actualización automática de contadores
- Confirmaciones antes de acciones destructivas

### Beneficios de la Separación

#### 3.1 Mantenibilidad
- **Código organizado:** Cada archivo tiene una responsabilidad específica
- **Fácil debugging:** Errores localizados en archivos específicos
- **Reutilización:** Funciones pueden ser reutilizadas en otras vistas
- **Escalabilidad:** Fácil agregar nuevas funcionalidades

#### 3.2 Rendimiento
- **Carga selectiva:** Solo se cargan los scripts necesarios por vista
- **Caché del navegador:** Archivos JavaScript pueden ser cacheados
- **Minificación:** Posibilidad de minificar archivos independientes
- **Lazy loading:** Carga de datos solo cuando es necesario

#### 3.3 Desarrollo
- **Separación de responsabilidades:** Frontend y backend claramente separados
- **Trabajo en equipo:** Diferentes desarrolladores pueden trabajar en diferentes archivos
- **Versionado:** Cambios en JavaScript no afectan las vistas PHP
- **Testing:** Facilita la implementación de pruebas unitarias

#### 3.4 Experiencia de Usuario
- **Interactividad mejorada:** Validación en tiempo real
- **Feedback inmediato:** Estados de loading y confirmaciones
- **Navegación fluida:** Transiciones suaves entre operaciones
- **Accesibilidad:** Atajos de teclado y tooltips informativos

### Estructura de Archivos

```
app/resources/js/
├── activityDashboard.js    # Dashboard principal
├── activityForm.js         # Formularios de creación/edición
└── activityView.js         # Vista de detalles
```

### Patrones Implementados

#### 3.1 Patrón Module
- Cada archivo encapsula funcionalidades relacionadas
- Variables y funciones privadas cuando es necesario
- Interfaz pública clara para cada módulo

#### 3.2 Patrón Observer
- Eventos de cambio en formularios
- Actualización automática de contadores
- Reacción a cambios de estado

#### 3.3 Patrón Factory
- Creación dinámica de elementos DOM
- Generación de opciones de selectores
- Construcción de mensajes de error

### Integración con el Sistema

#### 3.1 Dependencias
- **jQuery:** Manipulación del DOM y AJAX
- **Bootstrap:** Componentes de UI y modales
- **DataTables:** Tablas interactivas
- **SweetAlert2:** Notificaciones y confirmaciones

#### 3.2 Compatibilidad
- **Navegadores modernos:** Soporte para APIs modernas
- **Fallbacks:** Funcionalidad degradada en navegadores antiguos
- **Responsive:** Adaptación a diferentes tamaños de pantalla
- **Accesibilidad:** Soporte para lectores de pantalla

### Próximas Mejoras Sugeridas

1. **Optimización:**
   - Minificación y compresión de archivos
   - Implementación de Service Workers para caché
   - Lazy loading de componentes pesados

2. **Funcionalidades Avanzadas:**
   - Drag & drop para reordenar actividades
   - Autocompletado en campos de texto
   - Filtros avanzados con múltiples criterios

3. **Testing:**
   - Implementación de pruebas unitarias con Jest
   - Pruebas de integración con Cypress
   - Pruebas de accesibilidad automatizadas

4. **Monitoreo:**
   - Tracking de errores JavaScript
   - Métricas de rendimiento
   - Análisis de uso de funcionalidades

### Compatibilidad
- Compatible con la estructura MVC existente
- No requiere cambios en el backend
- Mantiene la funcionalidad existente
- Preparado para futuras expansiones

---

## [INTERNACIONALIZACIÓN] Nombres de campos en español en el flujo de creación de usuarios

Se detectaron los siguientes usos de nombres de campos en español en el flujo de creación de usuarios (de frontend a backend):

### Backend

**Archivo:** `app/controllers/UserController.php`

```php
$userData = [
    'first_name' => $data['nombre'] ?? '',
    'last_name' => '',
    'email' => $data['usuario'] ?? '',
    'password' => $data['clave'] ?? '',
    'credential_type' => $data['tipoDoc'] ?? '',
    'credential_number' => $data['numeroDoc'] ?? '',
    'date_of_birth' => null,
    'phone' => null,
    'address' => null
];
```

### Frontend (búsqueda en archivos JS)

No se encontraron objetos enviados por AJAX con estos nombres de campo en los archivos JS principales, pero sí aparecen en comentarios, mensajes y tablas. Es probable que el envío se realice en algún archivo JS o HTML que construya el objeto para el registro de usuario.

**Recomendación:**
- Cambiar los nombres de los campos enviados desde el frontend a inglés (`first_name`, `email`, `password`, `credential_type`, `credential_number`, etc.)
- Actualizar el backend para recibir directamente los nombres en inglés y eliminar el mapeo.

**Nota:**
- Revisar especialmente los formularios de registro y los archivos JS que envían datos al endpoint de creación de usuario.

---

**Sigue este documento para organizar tus commits y facilitar la revisión y el mantenimiento del proyecto.**
