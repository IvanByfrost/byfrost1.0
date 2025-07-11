/**
 * Sidebar Toggle - Maneja los submenús expandibles/colapsables y el toggle del sidebar en móviles
 */

document.addEventListener('DOMContentLoaded', function() {
    console.log('SidebarToggle: Inicializando...');
    
    // Función para manejar el toggle del sidebar en móviles
    function initializeSidebarToggle() {
        // Crear botón de toggle si no existe
        if (!document.querySelector('.sidebar-toggle')) {
            const toggleButton = document.createElement('button');
            toggleButton.className = 'sidebar-toggle';
            toggleButton.innerHTML = '<i class="fas fa-bars"></i>';
            toggleButton.setAttribute('aria-label', 'Toggle Sidebar');
            document.body.appendChild(toggleButton);
            
            // Agregar evento de click
            toggleButton.addEventListener('click', function() {
                const dashboardContainer = document.querySelector('.dashboard-container');
                if (dashboardContainer) {
                    dashboardContainer.classList.toggle('sidebar-open');
                    console.log('SidebarToggle: Toggle del sidebar ejecutado');
                }
            });
            
            console.log('SidebarToggle: Botón de toggle creado');
        }
        
        // Cerrar sidebar al hacer click fuera de él en móviles
        document.addEventListener('click', function(e) {
            const dashboardContainer = document.querySelector('.dashboard-container');
            const sidebar = document.querySelector('.root-sidebar, .coordinator-sidebar, .treasurer-sidebar, .student-sidebar, .teacher-sidebar');
            const toggleButton = document.querySelector('.sidebar-toggle');
            
            if (dashboardContainer && sidebar && toggleButton && 
                window.innerWidth <= 768 && 
                dashboardContainer.classList.contains('sidebar-open') &&
                !sidebar.contains(e.target) && 
                !toggleButton.contains(e.target)) {
                
                dashboardContainer.classList.remove('sidebar-open');
                console.log('SidebarToggle: Sidebar cerrado por click fuera');
            }
        });
    }
    
    // Inicializar toggle del sidebar
    initializeSidebarToggle();
    
    // Función para inicializar todos los submenús
    function initializeSubmenus() {
        const submenus = document.querySelectorAll('.has-submenu');
        console.log('SidebarToggle: Encontrados', submenus.length, 'submenús');
        
        submenus.forEach(function(submenu) {
            const link = submenu.querySelector('a');
            const submenuList = submenu.querySelector('.submenu');
            
            if (link && submenuList) {
                // Remover eventos previos para evitar duplicados
                const existingHandler = link._submenuHandler;
                if (existingHandler) {
                    link.removeEventListener('click', existingHandler);
                }
                
                // Crear nuevo handler
                const clickHandler = function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    console.log('SidebarToggle: Click en submenú:', link.textContent.trim());
                    toggleSubmenu(submenu);
                };
                
                // Guardar referencia al handler
                link._submenuHandler = clickHandler;
                
                // Agregar evento de click
                link.addEventListener('click', clickHandler);
                
                // Asegurar que el submenú esté cerrado inicialmente
                submenu.classList.remove('active');
                submenuList.style.maxHeight = '0px';
                submenuList.style.opacity = '0';
                submenuList.style.paddingTop = '0px';
                submenuList.style.paddingBottom = '0px';
                
                console.log('SidebarToggle: Submenú inicializado:', link.textContent.trim());
            } else {
                console.warn('SidebarToggle: Submenú sin enlace o submenu:', submenu);
            }
        });
    }
    
    // Función para alternar submenú
    function toggleSubmenu(submenu) {
        const isActive = submenu.classList.contains('active');
        const submenuList = submenu.querySelector('.submenu');
        const link = submenu.querySelector('a');
        
        console.log('SidebarToggle: Alternando submenú:', link ? link.textContent.trim() : 'Sin enlace');
        
        if (isActive) {
            // Cerrar submenú
            submenu.classList.remove('active');
            if (submenuList) {
                submenuList.style.maxHeight = '0px';
                submenuList.style.opacity = '0';
                submenuList.style.paddingTop = '0px';
                submenuList.style.paddingBottom = '0px';
            }
        } else {
            // Cerrar otros submenús abiertos (opcional)
            const activeSubmenus = document.querySelectorAll('.has-submenu.active');
            activeSubmenus.forEach(function(activeSubmenu) {
                if (activeSubmenu !== submenu) {
                    activeSubmenu.classList.remove('active');
                    const activeSubmenuList = activeSubmenu.querySelector('.submenu');
                    if (activeSubmenuList) {
                        activeSubmenuList.style.maxHeight = '0px';
                        activeSubmenuList.style.opacity = '0';
                        activeSubmenuList.style.paddingTop = '0px';
                        activeSubmenuList.style.paddingBottom = '0px';
                    }
                }
            });
            
            // Abrir submenú
            submenu.classList.add('active');
            if (submenuList) {
                // Calcular altura real del submenú
                submenuList.style.maxHeight = 'none';
                submenuList.style.opacity = '1';
                submenuList.style.paddingTop = '8px';
                submenuList.style.paddingBottom = '8px';
                
                // Obtener altura real y establecer max-height
                const realHeight = submenuList.scrollHeight;
                submenuList.style.maxHeight = realHeight + 'px';
            }
        }
    }
    
    // Inicializar submenús cuando el DOM esté listo
    initializeSubmenus();
    
    // Reinicializar submenús cuando se cargue contenido dinámicamente
    document.addEventListener('DOMContentLoaded', initializeSubmenus);
    
    // También reinicializar después de un tiempo para contenido cargado dinámicamente
    setTimeout(initializeSubmenus, 1000);
    setTimeout(initializeSubmenus, 2000);
    
    // Observar cambios en el DOM para contenido cargado dinámicamente
    if (typeof MutationObserver !== 'undefined') {
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
                    // Verificar si se agregaron elementos con submenús
                    mutation.addedNodes.forEach(function(node) {
                        if (node.nodeType === 1 && node.querySelector) {
                            const newSubmenus = node.querySelectorAll('.has-submenu');
                            if (newSubmenus.length > 0) {
                                console.log('SidebarToggle: Nuevos submenús detectados, reinicializando...');
                                setTimeout(initializeSubmenus, 100);
                            }
                        }
                    });
                }
            });
        });
        
        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    }
    
    console.log('SidebarToggle: Inicialización completada');
});

// Función global para reinicializar submenús (útil para contenido cargado dinámicamente)
window.reinitializeSidebarSubmenus = function() {
    console.log('SidebarToggle: Reinicializando submenús...');
    const submenus = document.querySelectorAll('.has-submenu');
    
    submenus.forEach(function(submenu) {
        const link = submenu.querySelector('a');
        const submenuList = submenu.querySelector('.submenu');
        
        if (link && submenuList) {
            // Remover eventos previos
            link.removeEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const isActive = submenu.classList.contains('active');
                
                if (isActive) {
                    submenu.classList.remove('active');
                    submenuList.style.maxHeight = '0px';
                    submenuList.style.opacity = '0';
                } else {
                    submenu.classList.add('active');
                    submenuList.style.maxHeight = submenuList.scrollHeight + 'px';
                    submenuList.style.opacity = '1';
                }
            });
            
            // Agregar nuevo evento
            link.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const isActive = submenu.classList.contains('active');
                
                if (isActive) {
                    submenu.classList.remove('active');
                    submenuList.style.maxHeight = '0px';
                    submenuList.style.opacity = '0';
                } else {
                    submenu.classList.add('active');
                    submenuList.style.maxHeight = submenuList.scrollHeight + 'px';
                    submenuList.style.opacity = '1';
                }
            });
        }
    });
    
    console.log('SidebarToggle: Reinicialización completada');
};

// Función global para toggle del sidebar
window.toggleSidebar = function() {
    const dashboardContainer = document.querySelector('.dashboard-container');
    if (dashboardContainer) {
        dashboardContainer.classList.toggle('sidebar-open');
        console.log('SidebarToggle: Toggle del sidebar ejecutado desde función global');
    }
};

// Función global para cerrar sidebar
window.closeSidebar = function() {
    const dashboardContainer = document.querySelector('.dashboard-container');
    if (dashboardContainer) {
        dashboardContainer.classList.remove('sidebar-open');
        console.log('SidebarToggle: Sidebar cerrado desde función global');
    }
}; 