# Byfrost

**Byfrost** es un sistema de gestión educativa desarrollado con HTML, PHP y MySQL. Está diseñado para fomentar la inclusión y la innovación en el aula, con especial énfasis en la accesibilidad para estudiantes sordos.

## 🎯 Objetivo

Brindar una plataforma educativa completa e inclusiva, con módulos especializados que permiten:

- Interpretación en Lengua de Señas Colombiana (LSC) en tiempo real durante clases virtuales.
- Acceso a recursos educativos adaptados para estudiantes sordos.
- Gestión eficiente de usuarios, contenidos y procesos educativos.

## 🚀 Cómo empezar

### Prerrequisitos

- **Servidor Web**: Apache/Nginx con PHP 7.4 o superior
- **Base de Datos**: MySQL 5.7 o MariaDB 10.3+
- **PHP**: Extensión PDO habilitada
- **Navegador**: Chrome, Firefox, Safari o Edge (versiones recientes)

### Instalación

1. **Clona el repositorio:**
   ```bash
   git clone https://github.com/IvanByfrost/byfrost.git
   cd byfrost
   ```

2. **Configura el servidor web:**
   - Coloca los archivos en tu directorio web (ej: `htdocs/` para XAMPP)
   - Asegúrate de que el servidor tenga permisos de lectura/escritura

3. **Configura la base de datos:**
   ```bash
   # Importa el esquema de la base de datos
   mysql -u root -p < app/scripts/Baldur.sql
   
   # Importa los datos de prueba (opcional)
   mysql -u root -p < app/scripts/insert_sample_data.sql
   ```

4. **Configura la conexión:**
   - Edita `app/scripts/connection.php` con tus credenciales de base de datos
   - Verifica que `config.php` tenga la URL correcta de tu servidor

5. **Accede a la aplicación:**
   ```
   http://localhost/byfrost/
   ```

## 📁 Estructura del Proyecto

```
byfrost/
├── app/
│   ├── controllers/          # Controladores MVC
│   │   ├── MainController.php
│   │   ├── UserController.php
│   │   ├── CoordinatorController.php
│   │   └── ...
│   ├── models/              # Modelos de datos
│   │   ├── userModel.php
│   │   ├── coordinatorModel.php
│   │   └── ...
│   ├── views/               # Vistas y templates
│   │   ├── layouts/         # Layouts principales
│   │   ├── index/           # Páginas públicas
│   │   ├── dashboard/       # Paneles de control
│   │   └── ...
│   ├── library/             # Librerías y utilidades
│   │   ├── SessionManager.php
│   │   ├── SecurityMiddleware.php
│   │   └── ...
│   ├── resources/           # Recursos estáticos
│   │   ├── css/            # Hojas de estilo
│   │   ├── js/             # JavaScript
│   │   └── img/            # Imágenes
│   ├── scripts/            # Scripts de configuración
│   │   ├── connection.php
│   │   ├── routerView.php
│   │   └── Baldur.sql
│   └── processes/          # Procesos de formularios
├── config.php              # Configuración principal
├── index.php               # Punto de entrada
└── README.md
```

## 🔧 Configuración

### Variables de Entorno

Edita `config.php` para configurar:

```php
// URL base de la aplicación
define('url', 'http://localhost:8000/');

// Configuración de la base de datos
define('DB_HOST', 'localhost');
define('DB_NAME', 'byfrost_db');
define('DB_USER', 'tu_usuario');
define('DB_PASS', 'tu_password');
```

### Base de Datos

El sistema utiliza las siguientes tablas principales:

- **users**: Información de usuarios
- **user_roles**: Roles y permisos de usuarios
- **schools**: Información de instituciones educativas
- **activities**: Actividades académicas
- **schedules**: Horarios y programación

## 👥 Roles y Permisos

El sistema implementa un sistema de roles jerárquico:

- **Root**: Acceso completo al sistema
- **Director**: Gestión de coordinadores y profesores
- **Coordinador**: Gestión de estudiantes y actividades
- **Profesor**: Gestión de materias y calificaciones
- **Estudiante**: Acceso a recursos educativos
- **Acudiente**: Seguimiento del estudiante
- **Tesorero**: Gestión financiera

## 🎨 Características Principales

### Gestión de Usuarios
- Registro y autenticación segura
- Asignación de roles y permisos
- Perfiles personalizables
- Historial de cambios de roles

### Gestión Académica
- Creación y gestión de actividades
- Programación de horarios
- Seguimiento de calificaciones
- Recursos educativos adaptados

### Accesibilidad
- Interfaz optimizada para usuarios sordos
- Soporte para Lengua de Señas Colombiana
- Recursos multimedia inclusivos
- Navegación intuitiva

### Seguridad
- Autenticación basada en sesiones
- Validación de entrada de datos
- Protección contra inyección SQL
- Control de acceso por roles

## 🛠️ Desarrollo

### Tecnologías Utilizadas

- **Backend**: PHP 7.4+, MySQL
- **Frontend**: HTML5, CSS3, JavaScript (ES6+)
- **Frameworks**: Bootstrap 5, jQuery
- **Patrón**: MVC (Model-View-Controller)

### Convenciones de Código

- **Variables y funciones**: Nombres en inglés
- **Comentarios**: Español para documentación
- **Indentación**: 4 espacios
- **Nomenclatura**: camelCase para variables, PascalCase para clases

### Estructura MVC

```php
// Ejemplo de controlador
class UserController extends MainController {
    public function index() {
        // Lógica del controlador
        $this->view('user/index', $data);
    }
}

// Ejemplo de modelo
class UserModel {
    public function getUser($id) {
        // Consulta a la base de datos
    }
}
```

## 🧪 Testing

El proyecto incluye archivos de prueba en el directorio `tests/`:

```bash
# Ejecutar pruebas de conexión
php tests/test-database-structure.php

# Probar funcionalidades de usuario
php tests/test-user-model.php

# Verificar permisos y roles
php tests/test-permission-system.php
```

## 📚 Documentación Adicional

- **Guías de Mejora**: Ver directorio `Mejoras/`
- **Scripts SQL**: Ver directorio `app/scripts/`
- **Ejemplos de Uso**: Ver archivos de prueba en `tests/`

## 🤝 Contribución

1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

## 📄 Licencia

Este proyecto está bajo la Licencia MIT. Ver el archivo `LICENSE` para más detalles.

## 📞 Soporte

Para soporte técnico o consultas:
- **Email**: soporte@byfrost.edu.co
- **Documentación**: [Wiki del proyecto](https://github.com/IvanByfrost/byfrost/wiki)
- **Issues**: [GitHub Issues](https://github.com/IvanByfrost/byfrost/issues)

## 🔄 Changelog

### v1.0.0 (2024-12-19)
- ✅ Sistema de autenticación completo
- ✅ Gestión de roles y permisos
- ✅ CRUD de usuarios y coordinadores
- ✅ Interfaz de administración
- ✅ Sistema de rutas y controladores
- ✅ Base de datos optimizada
- ✅ Documentación técnica completa

---

**Byfrost** - Transformando la educación inclusiva 🎓
