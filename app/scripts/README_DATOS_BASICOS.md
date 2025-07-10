# 📊 DATOS BÁSICOS BYFROST

## 📋 Descripción

Scripts para configurar una base de datos ByFrost limpia con datos básicos de ejemplo para desarrollo y pruebas.

## 📁 Archivos Creados

### 1. **`ByFrost_Basic_Queries.sql`**
- **30 consultas útiles** para operaciones comunes
- **Consultas por módulo**: usuarios, académico, horarios, calificaciones, etc.
- **Consultas estadísticas** para reportes
- **Consultas de verificación** para debugging

### 2. **`ByFrost_Basic_Inserts.sql`**
- **Datos de ejemplo completos** para todas las tablas
- **Usuarios de prueba**: administradores, profesores, estudiantes, padres
- **Estructura académica**: escuelas, grados, materias, grupos
- **Datos operativos**: horarios, actividades, calificaciones, asistencia
- **Datos financieros**: pagos, nómina, eventos

### 3. **`setup_clean_database.php`**
- **Script interactivo** para configurar la base de datos
- **Opciones de limpieza** y inserción de datos
- **Verificación automática** del estado
- **Consultas de prueba** integradas

## 🚀 Cómo Usar

### **Opción 1: Script Interactivo (Recomendado)**

```bash
php app/scripts/setup_clean_database.php
```

**Opciones disponibles:**
1. **Verificar estado actual** - Revisa qué datos existen
2. **Limpiar base de datos** - Elimina todos los datos
3. **Insertar datos básicos** - Agrega datos de ejemplo
4. **Ejecutar consultas de prueba** - Prueba las consultas básicas
5. **Configuración completa** - Limpia e inserta todo
6. **Salir** - Termina el script

### **Opción 2: Ejecución Manual**

```bash
# 1. Limpiar base de datos (opcional)
mysql -u root -p byfrost_db -e "SET FOREIGN_KEY_CHECKS = 0; TRUNCATE TABLE notifications; TRUNCATE TABLE parent_meetings; TRUNCATE TABLE conduct_reports; TRUNCATE TABLE academic_reports; TRUNCATE TABLE student_scores; TRUNCATE TABLE attendance; TRUNCATE TABLE activities; TRUNCATE TABLE student_enrollment; TRUNCATE TABLE student_parents; TRUNCATE TABLE class_groups; TRUNCATE TABLE professor_subjects; TRUNCATE TABLE schedules; TRUNCATE TABLE academic_terms; TRUNCATE TABLE student_payments; TRUNCATE TABLE user_roles; TRUNCATE TABLE users; SET FOREIGN_KEY_CHECKS = 1;"

# 2. Insertar datos básicos
mysql -u root -p byfrost_db < app/scripts/ByFrost_Basic_Inserts.sql

# 3. Probar consultas
mysql -u root -p byfrost_db < app/scripts/ByFrost_Basic_Queries.sql
```

## 📊 Datos Incluidos

### **👥 Usuarios de Prueba**

#### **Administradores (4 usuarios)**
- Admin Sistema (root)
- Director General (director)
- Coordinador Académico (coordinator)
- Tesorero Financiero (treasurer)

#### **Profesores (5 usuarios)**
- María González (Matemáticas, Educación Física)
- Carlos Rodríguez (Español, Arte)
- Ana López (Ciencias Naturales, Tecnología)
- Luis Martínez (Ciencias Sociales)
- Sofia Hernández (Inglés)

#### **Estudiantes (10 usuarios)**
- Juan Pérez, María García, Carlos López, etc.
- Todos con documentos TI de ejemplo

#### **Padres (5 usuarios)**
- Roberto Pérez, Carmen García, Miguel López, etc.
- Asignados a estudiantes

### **🏫 Estructura Académica**

#### **Escuelas**
- Colegio ByFrost - Sede Principal (500 cupos)
- Colegio ByFrost - Sede Norte (300 cupos)

#### **Grados**
- Primaria: 1ro a 5to
- Secundaria: 6to a 11mo

#### **Materias**
- Matemáticas, Español, Ciencias Naturales
- Ciencias Sociales, Inglés, Educación Física
- Arte, Tecnología

#### **Grupos de Clase**
- 6A, 6B, 7A, 7B, 8A
- Con profesores asignados

### **📚 Datos Operativos**

#### **Horarios**
- Lunes a Viernes
- 8:00 AM - 12:00 PM
- Materias distribuidas por día

#### **Actividades**
- Exámenes parciales
- Tareas y proyectos
- Laboratorios

#### **Calificaciones**
- Ejemplos para 3 estudiantes
- Diferentes niveles de rendimiento
- Comentarios de profesores

#### **Asistencia**
- Registros de ejemplo para hoy
- Diferentes estados: presente, ausente, tarde

### **💰 Datos Financieros**

#### **Pagos de Estudiantes**
- Diferentes estados: pagado, pendiente, atrasado
- Métodos de pago variados
- Fechas de vencimiento

#### **Nómina**
- 5 empleados registrados
- Períodos de nómina
- Conceptos básicos

### **📅 Eventos y Notificaciones**

#### **Eventos Escolares**
- Reunión de Padres
- Torneo de Fútbol
- Festival de Arte
- Exámenes Finales
- Día de la Familia

#### **Notificaciones**
- Para estudiantes, padres y profesores
- Diferentes tipos: académico, pago, reunión

## 🔍 Consultas Incluidas

### **Consultas de Usuarios (4)**
- Listar usuarios activos
- Buscar por documento
- Usuarios por rol
- Conteo por rol

### **Consultas Académicas (6)**
- Escuelas activas
- Grados por escuela
- Materias activas
- Grupos de clase
- Conteo de estudiantes
- Relaciones estudiante-padres

### **Consultas de Horarios (2)**
- Horarios por grupo
- Horarios por profesor

### **Consultas de Actividades (3)**
- Actividades por grupo
- Calificaciones por estudiante
- Promedios por estudiante y materia

### **Consultas de Asistencia (2)**
- Asistencia por fecha
- Resumen por grupo

### **Consultas de Pagos (2)**
- Estado de pagos
- Pagos pendientes

### **Consultas de Nómina (3)**
- Empleados activos
- Períodos de nómina
- Conceptos de nómina

### **Consultas de Eventos (2)**
- Eventos próximos
- Eventos por tipo

### **Consultas de Notificaciones (1)**
- Notificaciones no leídas

### **Consultas Estadísticas (2)**
- Estadísticas generales
- Rendimiento por escuela

### **Consultas de Reportes (3)**
- Reportes académicos
- Reportes de conducta
- Reuniones con padres

## ✅ Beneficios

### **Para Desarrollo**
- ✅ Datos realistas para pruebas
- ✅ Estructura completa funcional
- ✅ Consultas útiles para debugging
- ✅ Base limpia para desarrollo

### **Para Pruebas**
- ✅ Datos variados para testing
- ✅ Diferentes roles y permisos
- ✅ Casos de uso completos
- ✅ Escenarios realistas

### **Para Demostración**
- ✅ Sistema funcional completo
- ✅ Datos de ejemplo atractivos
- ✅ Consultas para presentaciones
- ✅ Estadísticas reales

## 🎯 Casos de Uso

### **1. Desarrollo Nuevo**
```bash
# Configuración completa para desarrollo
php app/scripts/setup_clean_database.php
# Seleccionar opción 5
```

### **2. Pruebas Específicas**
```bash
# Solo verificar estado
php app/scripts/setup_clean_database.php
# Seleccionar opción 1
```

### **3. Limpieza y Reinicio**
```bash
# Limpiar y reinsertar datos
php app/scripts/setup_clean_database.php
# Seleccionar opción 2, luego 3
```

### **4. Consultas de Prueba**
```bash
# Ejecutar consultas de prueba
php app/scripts/setup_clean_database.php
# Seleccionar opción 4
```

## ⚠️ Consideraciones

### **Antes de Usar**
- Hacer respaldo de datos importantes
- Verificar que la base de datos unificada esté creada
- Tener permisos de escritura en la base de datos

### **Datos de Prueba**
- Las contraseñas son dummy (no funcionales)
- Los documentos son ficticios
- Los datos son solo para desarrollo

### **Personalización**
- Modificar `ByFrost_Basic_Inserts.sql` para datos específicos
- Agregar consultas personalizadas en `ByFrost_Basic_Queries.sql`
- Adaptar el script de configuración según necesidades

## 🔄 Mantenimiento

### **Actualización de Datos**
- Modificar los archivos SQL según cambios en estructura
- Mantener consistencia entre consultas e inserciones
- Actualizar documentación cuando sea necesario

### **Nuevas Consultas**
- Agregar consultas útiles al archivo de consultas
- Documentar el propósito de cada consulta
- Mantener organización por módulos

---

**Fecha de creación**: 2025-01-27  
**Versión**: 1.0  
**Estado**: ✅ Completado  
**Uso**: Desarrollo, pruebas y demostración 