document.addEventListener('DOMContentLoaded', function () {
    console.log('DOM loaded, initializing toggles...');
    
    document.querySelectorAll('.has-submenu > a').forEach(function (menuLink) {
        menuLink.addEventListener('click', function (e) {
            e.preventDefault(); // evita navegación si href="#"
            const parentLi = this.parentElement;
            parentLi.classList.toggle('active');
        });
    });

    // Verificar que el menú de usuario existe
    const userMenuTrigger = document.querySelector('.user-menu-trigger');
    const userMenuContainer = document.querySelector('.user-menu-container');
    
    if (userMenuTrigger) {
        console.log('User menu trigger found');
    } else {
        console.log('User menu trigger NOT found');
    }
    
    if (userMenuContainer) {
        console.log('User menu container found');
    } else {
        console.log('User menu container NOT found');
    }
});

function toggleUserMenu() {
    console.log('toggleUserMenu called');
    const menu = document.querySelector('.user-menu-container');
    if (menu) {
        const currentDisplay = menu.style.display;
        const newDisplay = currentDisplay === 'block' ? 'none' : 'block';
        menu.style.display = newDisplay;
        console.log('Menu display changed to:', newDisplay);
    } else {
        console.log('Menu container not found');
    }
}

// Opcional: cerrar al hacer clic fuera
document.addEventListener('click', function(event) {
    const trigger = document.querySelector('.user-menu-trigger');
    const menu = document.querySelector('.user-menu-container');
    if (trigger && menu && !trigger.contains(event.target) && !menu.contains(event.target)) {
        menu.style.display = 'none';
    }
});

