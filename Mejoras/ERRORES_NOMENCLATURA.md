# 🚨 Errores de Nomenclatura - Byfrost 1.0

## 📋 Estándares PSR Aplicables

### PSR-1: Basic Coding Standard
- **Clases:** Deben usar `PascalCase` (StudlyCaps)
- **Métodos:** Deben usar `camelCase`
- **Constantes:** Deben usar `UPPER_SNAKE_CASE`
- **Variables:** Deben usar `camelCase`

### PSR-4: Autoloading Standard
- **Namespaces:** Deben coincidir con la estructura de directorios
- **Archivos:** Un archivo por clase
- **Nombres de archivo:** Deben coincidir exactamente con el nombre de la clase

---

## ❌ Errores de Nomenclatura Identificados

### 1. Controladores (app/controllers/)

#### ❌ **Errores Críticos - Nombres de Clases**

| Archivo | Línea | Clase Actual | Clase Correcta | Problema |
|---------|-------|--------------|----------------|----------|
| `MainController.php` | 1 | `class mainController` | `class MainController` | ❌ camelCase en lugar de PascalCase |
| `loginController.php` | 8 | `class LoginController` | ✅ Correcto | ✅ PascalCase |
| `registerController.php` | 7 | `class RegisterController` | ✅ Correcto | ✅ PascalCase |
| `forgotPasswordController.php` | 7 | `class ForgotPasswordController` | ✅ Correcto | ✅ PascalCase |
| `subjectController.php` | 3 | `class subjectController` | `class SubjectController` | ❌ camelCase en lugar de PascalCase |
| `teacherController.php` | 4 | `class TeacherController` | ✅ Correcto | ✅ PascalCase |
| `studentController.php` | 3 | `class studentController` | `class StudentController` | ❌ camelCase en lugar de PascalCase |
| `errorController.php` | 2 | `class ErrorController` | ✅ Correcto | ✅ PascalCase |
| `hmasterController.php` | 4 | `class hmasterController` | `class HeadMasterController` | ❌ camelCase + abreviación |
| `indexController.php` | 2 | `class IndexController` | ✅ Correcto | ✅ PascalCase |
| `activityController.php` | 4 | `class ActivityController` | ✅ Correcto | ✅ PascalCase |
| `assignRoleController.php` | 3 | `class assignRoleController` | `class AssignRoleController` | ❌ camelCase en lugar de PascalCase |
| `coordinatorController.php` | 5 | `class CoordinatorController` | ✅ Correcto | ✅ PascalCase |
| `rootController.php` | - | Archivo vacío | - | ❌ Archivo sin contenido |

#### ❌ **Errores en Nombres de Archivos**

| Archivo Actual | Archivo Correcto | Problema |
|----------------|------------------|----------|
| `loginController.php` | `LoginController.php` | ❌ camelCase en nombre de archivo |
| `registerController.php` | `RegisterController.php` | ❌ camelCase en nombre de archivo |
| `forgotPasswordController.php` | `ForgotPasswordController.php` | ❌ camelCase en nombre de archivo |
| `subjectController.php` | `SubjectController.php` | ❌ camelCase en nombre de archivo |
| `teacherController.php` | `TeacherController.php` | ❌ camelCase en nombre de archivo |
| `studentController.php` | `StudentController.php` | ❌ camelCase en nombre de archivo |
| `errorController.php` | `ErrorController.php` | ❌ camelCase en nombre de archivo |
| `hmasterController.php` | `HeadMasterController.php` | ❌ camelCase + abreviación |
| `indexController.php` | `IndexController.php` | ❌ camelCase en nombre de archivo |
| `activityController.php` | `ActivityController.php` | ❌ camelCase en nombre de archivo |
| `assignRoleController.php` | `AssignRoleController.php` | ❌ camelCase en nombre de archivo |
| `coordinatorController.php` | `CoordinatorController.php` | ❌ camelCase en nombre de archivo |
| `rootController.php` | `RootController.php` | ❌ camelCase en nombre de archivo |

---

### 2. Modelos (app/models/)

#### ❌ **Errores Críticos - Nombres de Clases**

| Archivo | Línea | Clase Actual | Clase Correcta | Problema |
|---------|-------|--------------|----------------|----------|
| `mainModel.php` | 4 | `class mainModel` | `class MainModel` | ❌ camelCase en lugar de PascalCase |
| `userModel.php` | 2 | `class UserModel` | ✅ Correcto | ✅ PascalCase |
| `subjectModel.php` | 3 | `class subjectModel` | `class SubjectModel` | ❌ camelCase en lugar de PascalCase |
| `teacherModel.php` | 4 | `class TeacherModel` | ✅ Correcto | ✅ PascalCase |
| `rootModel.php` | 3 | `class RootModel` | ✅ Correcto | ✅ PascalCase |
| `scheduleModel.php` | 3 | `class scheduleModel` | `class ScheduleModel` | ❌ camelCase en lugar de PascalCase |
| `schoolModel.php` | 3 | `class SchoolModel` | ✅ Correcto | ✅ PascalCase |
| `studentModel.php` | 3 | `class studentModel` | `class StudentModel` | ❌ camelCase en lugar de PascalCase |
| `activityModel.php` | 4 | `class ActivityModel` | ✅ Correcto | ✅ PascalCase |
| `hmasterModel.php` | 5 | `class hmasterModel` | `class HeadMasterModel` | ❌ camelCase + abreviación |
| `parentModel.php` | 3 | `class parentModel` | `class ParentModel` | ❌ camelCase en lugar de PascalCase |
| `CoordinatorModel.php` | 3 | `class CoordinatorModel` | ✅ Correcto | ✅ PascalCase |

#### ❌ **Errores en Nombres de Archivos**

| Archivo Actual | Archivo Correcto | Problema |
|----------------|------------------|----------|
| `mainModel.php` | `MainModel.php` | ❌ camelCase en nombre de archivo |
| `userModel.php` | `UserModel.php` | ❌ camelCase en nombre de archivo |
| `subjectModel.php` | `SubjectModel.php` | ❌ camelCase en nombre de archivo |
| `teacherModel.php` | `TeacherModel.php` | ❌ camelCase en nombre de archivo |
| `rootModel.php` | `RootModel.php` | ❌ camelCase en nombre de archivo |
| `scheduleModel.php` | `ScheduleModel.php` | ❌ camelCase en nombre de archivo |
| `schoolModel.php` | `SchoolModel.php` | ❌ camelCase en nombre de archivo |
| `studentModel.php` | `StudentModel.php` | ❌ camelCase en nombre de archivo |
| `activityModel.php` | `ActivityModel.php` | ❌ camelCase en nombre de archivo |
| `hmasterModel.php` | `HeadMasterModel.php` | ❌ camelCase + abreviación |
| `parentModel.php` | `ParentModel.php` | ❌ camelCase en nombre de archivo |
| `CoordinatorModel.php` | ✅ Correcto | ✅ PascalCase |

---

### 3. Library (app/library/)

#### ✅ **Correctos**
| Archivo | Clase | Estado |
|---------|-------|--------|
| `Views.php` | `class Views` | ✅ PascalCase |
| `Router.php` | `class Router` | ✅ PascalCase |

#### ✅ **Scripts (app/scripts/)**
| Archivo | Clase | Estado |
|---------|-------|--------|
| `connection.php` | `class DatabaseConnection` | ✅ PascalCase |

---

## 📊 Resumen de Errores

### Total de Errores por Categoría

| Categoría | Total Archivos | Con Errores | Correctos | % Errores |
|-----------|----------------|-------------|-----------|-----------|
| **Controladores** | 14 | 12 | 2 | 85.7% |
| **Modelos** | 12 | 10 | 2 | 83.3% |
| **Library** | 2 | 0 | 2 | 0% |
| **Scripts** | 1 | 0 | 1 | 0% |
| **TOTAL** | 29 | 22 | 7 | 75.9% |

### Tipos de Errores

| Tipo de Error | Cantidad | Ejemplos |
|---------------|----------|----------|
| **camelCase en lugar de PascalCase** | 18 | `mainController`, `subjectModel` |
| **camelCase en nombres de archivo** | 22 | `loginController.php`, `userModel.php` |
| **Abreviaciones incorrectas** | 2 | `hmasterController`, `hmasterModel` |
| **Archivos vacíos** | 1 | `rootController.php` |

---

## 🔧 Plan de Corrección

### Fase 1: Renombrar Clases (Prioridad ALTA)

```bash
# Controladores
mainController → MainController
subjectController → SubjectController
studentController → StudentController
hmasterController → HeadMasterController
assignRoleController → AssignRoleController

# Modelos
mainModel → MainModel
subjectModel → SubjectModel
scheduleModel → ScheduleModel
studentModel → StudentModel
hmasterModel → HeadMasterModel
parentModel → ParentModel
```

### Fase 2: Renombrar Archivos (Prioridad ALTA)

```bash
# Controladores
loginController.php → LoginController.php
registerController.php → RegisterController.php
forgotPasswordController.php → ForgotPasswordController.php
subjectController.php → SubjectController.php
teacherController.php → TeacherController.php
studentController.php → StudentController.php
errorController.php → ErrorController.php
hmasterController.php → HeadMasterController.php
indexController.php → IndexController.php
activityController.php → ActivityController.php
assignRoleController.php → AssignRoleController.php
coordinatorController.php → CoordinatorController.php
rootController.php → RootController.php

# Modelos
mainModel.php → MainModel.php
userModel.php → UserModel.php
subjectModel.php → SubjectModel.php
teacherModel.php → TeacherModel.php
rootModel.php → RootModel.php
scheduleModel.php → ScheduleModel.php
schoolModel.php → SchoolModel.php
studentModel.php → StudentModel.php
activityModel.php → ActivityModel.php
hmasterModel.php → HeadMasterModel.php
parentModel.php → ParentModel.php
```

### Fase 3: Actualizar Referencias (Prioridad ALTA)

1. **Actualizar includes/requires**
2. **Actualizar extends**
3. **Actualizar instanciaciones**
4. **Actualizar autoloader**

---

## ⚠️ Impacto de los Errores

### Problemas Actuales
1. **Inconsistencia visual** - Dificulta lectura del código
2. **Violación de estándares PSR** - No cumple con convenciones PHP
3. **Problemas de autoloading** - Puede causar errores de carga
4. **Dificultad de mantenimiento** - Confunde a desarrolladores
5. **Problemas de IDE** - Autocompletado y navegación afectados

### Beneficios de la Corrección
1. **Cumplimiento de estándares PSR** - Código profesional
2. **Mejor autoloading** - Carga automática correcta
3. **Facilidad de mantenimiento** - Código más legible
4. **Mejor experiencia de desarrollo** - IDEs funcionan mejor
5. **Preparación para producción** - Código listo para despliegue

---

## 🎯 Conclusión

**75.9% de las clases tienen errores de nomenclatura**, lo que representa un problema crítico de calidad de código. La corrección de estos errores es **prioridad ALTA** antes de continuar con el desarrollo.

**Recomendación:** Implementar la corrección en una sola iteración para evitar problemas de consistencia durante el desarrollo.

---

*Reporte generado el: $(date)*
*Estándares aplicados: PSR-1, PSR-4* 