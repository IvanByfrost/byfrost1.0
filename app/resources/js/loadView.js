// loadView.js - Sistema de carga dinámica de vistas
// ByFrost - Navegación SPA + carga modular de scripts

// ✅ Import de scripts dinámicos (solo si lo tienes modularizado)
//// import { loadViewScript } from './navigation/viewScriptsLoader.js';

// Construye la base URL quitando archivo al final (index.php, etc.)
window.baseUrl = window.baseUrl || window.BASE_URL || '/';
window.currentView = window.currentView || '';
window.isLoading = window.isLoading || false;

/**
 * Construye la URL de la vista, incluyendo parámetros
 * @param {string} viewName
 * @param {string|null} action
 * @param {Object} params
 * @returns {string}
 */
function buildViewUrl(viewName, action = null, params = {}) {
    let url = `?view=${encodeURIComponent(viewName)}`;
    console.log("📡 URL construida:", url);
    if (action) {
        url += `&action=${encodeURIComponent(action)}`;
    }
    for (const [key, value] of Object.entries(params)) {
        url += `&${encodeURIComponent(key)}=${encodeURIComponent(value)}`;
    }
    return url;
}

/**
 * Carga una vista completa o parcial
 * @param {string} viewName
 * @param {string|null} action
 * @param {string} targetElement
 * @param {boolean} partial
 * @param {Object} params
 */
async function loadView(viewName, action = null, targetElement = '#mainContent', partial = false, params = {}) {
    if (isLoading) {
        console.log('Ya hay una carga en progreso. Esperando...');
        return;
    }

    isLoading = true;
    currentView = viewName;

    showLoadingIndicator(targetElement);

    try {
        let url = buildViewUrl(viewName, action, params);
        if (partial) {
            url += '&partialView=true';
        }

        console.log(`${partial ? 'Cargando vista parcial:' : 'Cargando vista completa:'} ${url}`);
        console.log('URL que se está solicitando:', url);
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

            // ✅ Si quieres cargar scripts dinámicos:
            //// await loadViewScript(viewName);

            if (!partial) {
                updateBrowserUrl(viewName, action, params);
                document.dispatchEvent(new CustomEvent('viewLoaded', {
                    detail: { view: viewName, action: action }
                }));
                console.log(`Vista "${viewName}" cargada exitosamente.`);
            } else {
                console.log(`Vista parcial "${viewName}" cargada exitosamente.`);
            }

            window.scrollTo({ top: 0, behavior: 'smooth' });
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
 * Ejecuta scripts inline o externos embebidos en HTML
 * @param {HTMLElement} container
 */
function executeScriptsInContent(container) {
    const scripts = container.querySelectorAll('script');
    scripts.forEach(script => {
        const newScript = document.createElement('script');

        if (script.src) {
            newScript.src = script.src;
            newScript.type = script.type || 'text/javascript';
        } else {
            newScript.textContent = script.textContent;
            newScript.type = script.type || 'text/javascript';
        }

        document.head.appendChild(newScript);
    });
}

/**
 * Muestra indicador de carga
 * @param {string} targetElement
 */
function showLoadingIndicator(targetElement = '#mainContent') {
    const target = document.querySelector(targetElement);
    if (target) {
        target.innerHTML = `
            <div class="loading-container text-center p-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
                <p class="mt-3 text-muted">Cargando contenido...</p>
            </div>
        `;
    }
}

/**
 * Oculta el indicador de carga
 */
function hideLoadingIndicator() {
    const loadingContainer = document.querySelector('.loading-container');
    if (loadingContainer) {
        loadingContainer.remove();
    }
}

/**
 * Muestra error en la vista
 * @param {string} targetElement
 * @param {string} message
 */
function showError(targetElement, message) {
    const target = document.querySelector(targetElement);
    if (target) {
        target.innerHTML = `
            <div class="alert alert-danger" role="alert">
                <h4 class="alert-heading">Error</h4>
                <p>${message}</p>
                <hr>
                <p class="mb-0">
                    <button class="btn btn-primary" onclick="location.reload()">Recargar página</button>
                </p>
            </div>
        `;
    }
}

/**
 * Actualiza la URL en el navegador
 * @param {string} view
 * @param {string|null} action
 * @param {Object} params
 */
function updateBrowserUrl(view, action = null, params = {}) {
    let url = buildViewUrl(view, action, params);
    if (window.history && window.history.pushState) {
        window.history.pushState({ view, action }, '', url);
    }
}

/**
 * Función rápida para navegar
 * @param {string} view
 * @param {string|null} action
 * @param {Object} params
 */
function navigateTo(view, action = null, params = {}) {
    loadView(view, action, '#mainContent', false, params);
}

// ✅ Exponer funciones globalmente
window.loadView = loadView;
window.loadPartialView = (view, action, target, params = {}) =>
    loadView(view, action, target, true, params);
window.navigateTo = navigateTo;
