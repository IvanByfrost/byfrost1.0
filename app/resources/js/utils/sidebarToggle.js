/**
 * SidebarToggle.js - ByFrost
 * Maneja el toggle del sidebar y los submenús
 */

document.addEventListener('viewLoaded', function() {
    console.log('SidebarToggle.js: viewLoaded → inicializando');

    initializeSidebarToggle();
    initializeSubmenus();
});

/**
 * Inicializa el botón toggle del sidebar
 */
function initializeSidebarToggle() {
    let toggleButton = document.querySelector('.sidebar-toggle');
    if (!toggleButton) {
        toggleButton = document.createElement('button');
        toggleButton.className = 'sidebar-toggle';
        toggleButton.innerHTML = '<i class="fas fa-bars"></i>';
        toggleButton.setAttribute('aria-label', 'Toggle Sidebar');
        document.body.appendChild(toggleButton);
    }

    toggleButton.removeEventListener('click', handleSidebarToggle);
    toggleButton.addEventListener('click', handleSidebarToggle);

    console.log('SidebarToggle.js: Botón toggle listo');
}

/**
 * Maneja el click en el botón toggle del sidebar
 */
function handleSidebarToggle() {
    const container = document.querySelector('.dashboard-container');
    if (container) {
        container.classList.toggle('sidebar-open');
        console.log('SidebarToggle.js: Sidebar toggled');
    }
}

/**
 * Inicializa los submenús del sidebar
 */
function initializeSubmenus() {
    const submenus = document.querySelectorAll('.has-submenu');
    console.log(`SidebarToggle.js: Inicializando ${submenus.length} submenús`);

    submenus.forEach(submenu => {
        const link = submenu.querySelector('a');
        const submenuList = submenu.querySelector('.submenu');

        if (!link || !submenuList) return;

        // limpiar listeners duplicados
        link.removeEventListener('click', link._submenuHandler);

        const clickHandler = e => {
            e.preventDefault();
            toggleSubmenu(submenu);
        };

        link._submenuHandler = clickHandler;
        link.addEventListener('click', clickHandler);

        // cerrar inicialmente
        submenu.classList.remove('active');
        submenuList.style.maxHeight = '0px';
        submenuList.style.opacity = '0';
        submenuList.style.paddingTop = '0px';
        submenuList.style.paddingBottom = '0px';
    });
}

/**
 * Abre/cierra un submenú
 */
function toggleSubmenu(submenu) {
    const isActive = submenu.classList.contains('active');
    const submenuList = submenu.querySelector('.submenu');

    if (!submenuList) return;

    if (isActive) {
        submenu.classList.remove('active');
        submenuList.style.maxHeight = '0px';
        submenuList.style.opacity = '0';
        submenuList.style.paddingTop = '0px';
        submenuList.style.paddingBottom = '0px';
        console.log('SidebarToggle.js: Submenú cerrado');
    } else {
        // cerrar otros
        document.querySelectorAll('.has-submenu.active').forEach(active => {
            if (active !== submenu) {
                active.classList.remove('active');
                const activeList = active.querySelector('.submenu');
                if (activeList) {
                    activeList.style.maxHeight = '0px';
                    activeList.style.opacity = '0';
                    activeList.style.paddingTop = '0px';
                    activeList.style.paddingBottom = '0px';
                }
            }
        });

        submenu.classList.add('active');
        submenuList.style.maxHeight = submenuList.scrollHeight + 'px';
        submenuList.style.opacity = '1';
        submenuList.style.paddingTop = '8px';
        submenuList.style.paddingBottom = '8px';
        console.log('SidebarToggle.js: Submenú abierto');
    }
}

// Funciones globales
window.toggleSidebar = handleSidebarToggle;
window.reinitializeSidebarSubmenus = initializeSubmenus;
window.closeSidebar = function () {
    const container = document.querySelector('.dashboard-container');
    if (container) {
        container.classList.remove('sidebar-open');
        console.log('SidebarToggle.js: Sidebar cerrado programáticamente');
    }
};
