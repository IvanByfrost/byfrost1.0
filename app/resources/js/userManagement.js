/**
 * JavaScript para manejar la gestión de usuarios (asignación de roles, consulta e historial)
 * Módulo principal que coordina los sub-módulos
 */

// Definir la URL base solo si no está definida
if (typeof window.USER_MANAGEMENT_BASE_URL === 'undefined') {
    window.USER_MANAGEMENT_BASE_URL = window.location.origin + window.location.pathname.replace(/\/[^\/]*$/, '/');
}

// Función para mostrar errores
function showError(message) {
    console.error('Error:', message);
    
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Error',
            text: message,
            icon: 'error',
            confirmButtonText: 'OK'
        });
    } else {
        alert('Error: ' + message);
    }
}

// Función para esperar a que el DOM esté listo
function waitForDOM() {
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeUserManagement);
    } else {
        initializeUserManagement();
    }
}

// Función para inicializar cuando el DOM esté listo
function initializeUserManagement() {
    console.log('Inicializando sistema de gestión de usuarios...');
    
    // Verificar que jQuery esté disponible
    if (typeof $ === 'undefined') {
        console.error('jQuery no está disponible. El sistema de gestión de usuarios no funcionará.');
        return;
    }
    
    // Verificar que los elementos necesarios existan
    const searchForm = document.getElementById('searchUserForm');
    const roleHistoryForm = document.getElementById('roleHistoryForm');
    const usersTable = document.getElementById('usersWithoutRoleTable');
    const modal = document.getElementById('assignRoleModal');
    
    console.log('Elementos encontrados:', {
        searchForm: !!searchForm,
        roleHistoryForm: !!roleHistoryForm,
        usersTable: !!usersTable,
        modal: !!modal
    });
    
    // Si no encontramos ningún formulario, probablemente no estamos en la página correcta
    if (!searchForm && !roleHistoryForm) {
        console.log('No estamos en una página de gestión de usuarios. Saltando inicialización.');
        return;
    }
    
    // Configurar formularios para usar AJAX
    if (searchForm) {
        console.log('Configurando eventos del formulario de búsqueda...');
        searchForm.removeEventListener('submit', handleSearchSubmit);
        searchForm.addEventListener('submit', handleSearchSubmit);
    }
    
    if (roleHistoryForm) {
        console.log('Configurando eventos del formulario de historial de roles...');
        roleHistoryForm.removeEventListener('submit', handleRoleHistorySubmit);
        roleHistoryForm.addEventListener('submit', handleRoleHistorySubmit);
    }
    
    // Determinar qué tipo de página es y inicializar específicamente
    if (usersTable && modal) {
        // Es la página de asignación de roles
        console.log('Detectada página de asignación de roles...');
        initializeAssignRole();
    } else if (roleHistoryForm) {
        // Es la página de historial de roles
        console.log('Detectada página de historial de roles...');
        initializeRoleHistory();
    } else {
        // Es la página de consulta de usuarios
        console.log('Detectada página de consulta de usuarios...');
        initializeConsultUser();
    }
}

// Función para inicializar después de que loadViews.js cargue el contenido
function initUserManagementAfterLoad() {
    console.log('Inicializando gestión de usuarios después de carga de vista...');
    
    // Pequeño delay para asegurar que el DOM esté actualizado
    setTimeout(() => {
        initializeUserManagement();
    }, 100);
}

// Asegurar que la función esté disponible globalmente
window.initUserManagementAfterLoad = initUserManagementAfterLoad;

// Inicializar específicamente para asignación de roles
function initializeAssignRole() {
    console.log('Inicializando funcionalidad de asignación de roles...');
    
    const usersTable = document.getElementById('usersWithoutRoleTable');
    const modal = document.getElementById('assignRoleModal');
    
    if (!usersTable) {
        console.error('Tabla de usuarios sin rol no encontrada');
        return;
    }
    
    if (!modal) {
        console.error('Modal de asignación no encontrado');
    }
    
    // Cargar usuarios sin rol al cargar la página
    console.log('Cargando usuarios sin rol...');
    loadUsersWithoutRole();
}

// Inicializar específicamente para historial de roles
function initializeRoleHistory() {
    console.log('Inicializando funcionalidad de historial de roles...');
    console.log('Formulario de historial de roles configurado correctamente');
}

// Inicializar específicamente para consulta de usuarios
function initializeConsultUser() {
    console.log('Inicializando funcionalidad de consulta de usuarios...');
    
    // Configurar campos dinámicos para búsqueda
    const searchType = document.getElementById('search_type');
    if (searchType) {
        searchType.addEventListener('change', toggleSearchFields);
    }
    
    console.log('Formulario de consulta configurado correctamente');
}

// Manejador del envío del formulario de búsqueda general
function handleSearchSubmit(e) {
    e.preventDefault(); // Prevenir envío normal del formulario
    console.log('Formulario enviado, procesando búsqueda...');
    
    // Determinar qué tipo de página es
    const usersTable = document.getElementById('usersWithoutRoleTable');
    const modal = document.getElementById('assignRoleModal');
    
    if (usersTable && modal) {
        // Es asignación de roles - búsqueda por documento
        const credentialType = document.getElementById('credential_type').value;
        const credentialNumber = document.getElementById('credential_number').value;
        
        if (!credentialType || !credentialNumber) {
            showError('Por favor, selecciona el tipo de documento e ingresa el número.');
            return;
        }
        
        console.log('Realizando búsqueda para asignación de roles...');
        searchUsersByDocument(credentialType, credentialNumber);
    } else {
        // Es consulta de usuarios - puede ser por documento o por rol
        const searchType = document.getElementById('search_type').value;
        
        if (!searchType) {
            showError('Por favor, selecciona el tipo de búsqueda.');
            return;
        }
        
        if (searchType === 'document') {
            const credentialType = document.getElementById('credential_type').value;
            const credentialNumber = document.getElementById('credential_number').value;
            
            if (!credentialType || !credentialNumber) {
                showError('Por favor, selecciona el tipo de documento e ingresa el número.');
                return;
            }
            
            console.log('Realizando búsqueda por documento para consulta...');
            searchUsersForConsult(credentialType, credentialNumber);
        } else if (searchType === 'role') {
            const roleType = document.getElementById('role_type').value;
            
            if (!roleType) {
                showError('Por favor, selecciona un rol.');
                return;
            }
            
            console.log('Realizando búsqueda por rol para consulta...');
            searchUsersByRole(roleType);
        }
    }
}

// Manejador del envío del formulario de historial de roles
function handleRoleHistorySubmit(e) {
    e.preventDefault(); // Prevenir envío normal del formulario
    console.log('Formulario de historial enviado, procesando búsqueda...');
    
    const credentialType = document.getElementById('credential_type').value;
    const credentialNumber = document.getElementById('credential_number').value;
    
    if (!credentialType || !credentialNumber) {
        showError('Por favor, selecciona el tipo de documento e ingresa el número.');
        return;
    }
    
    console.log('Realizando búsqueda de historial de roles...');
    searchRoleHistory(credentialType, credentialNumber);
}

// Función para refrescar iconos (si es necesario)
function refreshIcons() {
    // Esta función puede ser útil para refrescar iconos de FontAwesome
    // si se cargan dinámicamente
    if (typeof FontAwesome !== 'undefined') {
        FontAwesome.dom.i2svg();
    }
}

// Inicializar cuando el DOM esté listo
waitForDOM();