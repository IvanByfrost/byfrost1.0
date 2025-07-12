console.log('✅ Toggles.js cargado');

/**
 * Toggles.js - Maneja el menú de usuario
 * Compatible con SPA
 */

document.addEventListener('viewLoaded', initUserToggles);

function initUserToggles() {
    console.log('Toggles.js: Ejecutando initUserToggles()');

    const userMenuTrigger = document.querySelector('.user-menu-trigger');
    const userMenuContainer = document.querySelector('.user-menu-container');

    if (userMenuTrigger) {
        console.log('Toggles.js: User menu trigger encontrado');
        userMenuTrigger.addEventListener('click', toggleUserMenu);
    } else {
        console.log('Toggles.js: User menu trigger NO encontrado');
    }

    if (userMenuContainer) {
        console.log('Toggles.js: User menu container encontrado');
    } else {
        console.log('Toggles.js: User menu container NO encontrado');
    }
}

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

document.addEventListener('click', function(event) {
    const trigger = document.querySelector('.user-menu-trigger');
    const menu = document.querySelector('.user-menu-container');
    if (trigger && menu && !trigger.contains(event.target) && !menu.contains(event.target)) {
        menu.style.display = 'none';
        console.log('Toggles.js: Menú cerrado por clic fuera');
    }
});

function closeUserMenu() {
    const menu = document.querySelector('.user-menu-container');
    if (menu) {
        menu.style.display = 'none';
        console.log('Toggles.js: Menú de usuario cerrado programáticamente');
    }
}


function toggleSearchFields() {
    const searchType = document.getElementById('search_type')?.value;

    // Ocultar todos
    document.getElementById('document_type_field')?.style.setProperty('display', 'none');
    document.getElementById('document_number_field')?.style.setProperty('display', 'none');
    document.getElementById('role_type_field')?.style.setProperty('display', 'none');
    document.getElementById('name_search_field')?.style.setProperty('display', 'none');

    if (searchType === 'document') {
        document.getElementById('document_type_field')?.style.setProperty('display', 'block');
        document.getElementById('document_number_field')?.style.setProperty('display', 'block');
    } else if (searchType === 'role') {
        document.getElementById('role_type_field')?.style.setProperty('display', 'block');
    } else if (searchType === 'name') {
        document.getElementById('name_search_field')?.style.setProperty('display', 'block');
    }
}

// ✅ Exponer globalmente (por si tu HTML usa el atributo `onchange`)
window.toggleSearchFields = toggleSearchFields;


window.closeUserMenu = closeUserMenu;
