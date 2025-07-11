import { loadView } from './viewLoader.js';
import { handleAjaxForm } from './formHandler.js';

/**
 * Inicializa la navegación y manejo de formularios AJAX
 */
export function initializeNavigation() {
    document.addEventListener('click', function (e) {
        const link = e.target.closest('[data-view]');
        if (link) {
            e.preventDefault();
            const view = link.getAttribute('data-view');
            const action = link.getAttribute('data-action');
            const partial = link.getAttribute('data-partial') === 'true';
            loadView(view, action, '#mainContent', partial);
        }
    });

    document.addEventListener('submit', function (e) {
        const form = e.target;
        if (form.hasAttribute('data-ajax')) {
            e.preventDefault();
            handleAjaxForm(form);
        }
    });

    window.addEventListener('popstate', function (e) {
        if (e.state && e.state.view) {
            loadView(e.state.view, e.state.action, '#mainContent', true);
        }
    });

    console.log('Sistema de navegación inicializado');
}

// Exportar funciones si se necesitan en otros módulos o en window
export { loadView };
window.loadView = loadView;
