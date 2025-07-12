/**
 * Role History Navigation Functions
 * ByFrost - User Management Module
 */

/**
 * Función para obtener el color del badge según el tipo de rol
 * @param {string} roleType - Tipo de rol
 * @returns {string} - Clase CSS del color
 */
function getRoleBadgeColor(roleType) {
    const colors = {
        'root': 'danger',
        'director': 'primary',
        'coordinator': 'info',
        'professor': 'warning',
        'treasurer': 'success',
        'student': 'secondary',
        'parent': 'light'
    };
    return colors[roleType] || 'secondary';
}

/**
 * Función para volver atrás con múltiples opciones de navegación
 */
function goBack() {
    // Opción 1: Intentar usar history.back() primero
    if (window.history && window.history.length > 1) {
        window.history.back();
        return;
    }
    
    // Opción 2: Si hay función loadView disponible, usarla
    if (typeof loadView === 'function') {
        loadView('user', 'consultUser');
        return;
    }
    
    // Opción 3: Redirección directa a la consulta de usuarios
    window.location.href = '?view=user&action=consultUser';
}

/**
 * Función para ver el usuario
 * @param {number} userId - ID del usuario
 */
function viewUser(userId) {
    // Opción 1: Si hay función loadView disponible, usarla
    if (typeof loadView === 'function') {
        loadView('user', 'view', '#mainContent', false, {id: userId});
        return;
    }
    
    // Opción 2: Redirección directa
    window.location.href = `?view=user&action=view&id=${userId}`;
}

/**
 * Función para asignar un nuevo rol
 * @param {number} userId - ID del usuario
 */
function assignNewRole(userId) {
    // Opción 1: Si hay función loadView disponible, usarla
    if (typeof loadView === 'function') {
        loadView('user', 'assignRole', '#mainContent', false, {id: userId});
        return;
    }
    
    // Opción 2: Redirección directa
    window.location.href = `?view=user&action=assignRole&id=${userId}`;
}

/**
 * Función para desactivar un rol
 * @param {number} userId - ID del usuario
 * @param {string} roleType - Tipo de rol
 */
function deactivateRole(userId, roleType) {
    if (confirm('¿Estás seguro de que quieres desactivar este rol?')) {
        // Implementar lógica de desactivación
        console.log('Desactivando rol:', roleType, 'para usuario:', userId);
    }
}

/**
 * Función para activar un rol
 * @param {number} userId - ID del usuario
 * @param {string} roleType - Tipo de rol
 */
function activateRole(userId, roleType) {
    if (confirm('¿Estás seguro de que quieres activar este rol?')) {
        // Implementar lógica de activación
        console.log('Activando rol:', roleType, 'para usuario:', userId);
    }
}

// Exponer funciones globalmente
window.getRoleBadgeColor = getRoleBadgeColor;
window.goBack = goBack;
window.viewUser = viewUser;
window.assignNewRole = assignNewRole;
window.deactivateRole = deactivateRole;
window.activateRole = activateRole;
  