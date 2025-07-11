/**
 * Toggles.js - Maneja el menú de usuario y otros toggles específicos
 * NOTA: Los submenús del sidebar ahora se manejan en sidebarToggle.js
 */

document.addEventListener('DOMContentLoaded', function () {
    console.log('Toggles.js: Inicializando toggles de usuario...');
    
    // Verificar que el menú de usuario existe
    const userMenuTrigger = document.querySelector('.user-menu-trigger');
    const userMenuContainer = document.querySelector('.user-menu-container');
    
    if (userMenuTrigger) {
        console.log('Toggles.js: User menu trigger encontrado');
    } else {
        console.log('Toggles.js: User menu trigger NO encontrado');
    }
    
    if (userMenuContainer) {
        console.log('Toggles.js: User menu container encontrado');
    } else {
        console.log('Toggles.js: User menu container NO encontrado');
    }
    

});

/**
 * Toggle del menú de usuario (avatar del usuario)
 * Esta función se llama desde el HTML del header
 */
function toggleUserMenu() {
    console.log('Toggles.js: toggleUserMenu llamado');
    const menu = document.querySelector('.user-menu-container');
    if (menu) {
        const currentDisplay = menu.style.display;
        const newDisplay = currentDisplay === 'block' ? 'none' : 'block';
        menu.style.display = newDisplay;
        console.log('Toggles.js: Menu display cambiado a:', newDisplay);
    } else {
        console.log('Toggles.js: Menu container no encontrado');
    }
}

/**
 * Cerrar menú de usuario al hacer clic fuera
 */
document.addEventListener('click', function(event) {
    const trigger = document.querySelector('.user-menu-trigger');
    const menu = document.querySelector('.user-menu-container');
    if (trigger && menu && !trigger.contains(event.target) && !menu.contains(event.target)) {
        menu.style.display = 'none';
        console.log('Toggles.js: Menú cerrado por clic fuera');
    }
});

/**
 * Función para cerrar el menú de usuario programáticamente
 * Útil cuando se navega a otra página o se ejecuta una acción
 */
function closeUserMenu() {
    const menu = document.querySelector('.user-menu-container');
    if (menu) {
        menu.style.display = 'none';
        console.log('Toggles.js: Menú de usuario cerrado programáticamente');
    }
}

// Hacer la función disponible globalmente
window.closeUserMenu = closeUserMenu;

