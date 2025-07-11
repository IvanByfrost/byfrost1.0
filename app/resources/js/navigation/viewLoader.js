import {
    showLoadingIndicator,
    hideLoadingIndicator,
    showError,
    executeScriptsInContent
} from './uiHelpers.js';

/**
 * Obtiene la URL base (sin archivo) para construir rutas.
 * Ej.: https://example.com/app/
 */
const baseUrl = (function () {
    const path = window.location.pathname;
    // Quita archivo al final, si existe (index.php, etc.)
    return window.location.origin + path.replace(/[^/]*$/, '');
})();

let currentView = '';
let isLoading = false;

/**
 * Construye una URL para cargar una vista.
 *
 * @param {string} viewName
 * @param {string|null} action
 * @returns {string}
 */
export function buildViewUrl(viewName, action = null) {
    let url = baseUrl + '?view=' + encodeURIComponent(viewName);
    if (action) {
        url += '&action=' + encodeURIComponent(action);
    }
    return url;
}

/**
 * Carga una vista (completa o parcial) vía AJAX y la inserta en el DOM.
 *
 * @param {string} viewName
 * @param {string|null} action
 * @param {string} targetElement
 * @param {boolean} partial
 * @param {boolean} scrollTop
 */
export async function loadView(viewName, action = null, targetElement = '#mainContent', partial = false, scrollTop = true) {
    if (isLoading) {
        console.log('Ya hay una carga en progreso. Esperando que termine...');
        return;
    }

    isLoading = true;

    try {
        if (!partial) currentView = viewName;

        showLoadingIndicator(targetElement, partial);

        let url = buildViewUrl(viewName, action);
        if (partial) {
            url += '&partialView=true';
        }

        console.log(`${partial ? 'Cargando vista parcial:' : 'Cargando vista completa:'} ${url}`);

        const response = await fetch(url, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html, application/xhtml+xml, application/xml;q=0.9, */*;q=0.8',
                'Cache-Control': 'no-cache'
            }
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const html = await response.text();
        const target = document.querySelector(targetElement);

        if (target) {
            target.innerHTML = html;
            executeScriptsInContent(target);

            if (!partial) {
                updateBrowserUrl(viewName, action);
                document.dispatchEvent(new CustomEvent('viewLoaded', {
                    detail: { view: viewName, action: action }
                }));
                console.log(`Vista "${viewName}" cargada exitosamente.`);
            } else {
                console.log(`Vista parcial "${viewName}" cargada exitosamente.`);
            }

            if (scrollTop) {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        } else {
            console.error('Elemento objetivo no encontrado:', targetElement);
        }
    } catch (error) {
        console.error('Error cargando vista:', error);
        showError(targetElement, 'Error al cargar la vista: ' + error.message);
    } finally {
        isLoading = false;
        hideLoadingIndicator();
    }
}

/**
 * Actualiza la URL en la barra del navegador (sin recargar la página).
 *
 * @param {string} view
 * @param {string|null} action
 */
export function updateBrowserUrl(view, action = null) {
    currentView = view;
    const url = buildViewUrl(view, action);
    if (window.history && window.history.pushState) {
        window.history.pushState({ view, action }, '', url);
    }
}
