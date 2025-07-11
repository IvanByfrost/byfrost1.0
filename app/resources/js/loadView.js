// loadView.js - Sistema de carga dinámica de vistas
// ByFrost - Navegación SPA + carga modular de scripts

import { loadViewScript } from './navigation/viewScriptsLoader.js';

// Construye la base URL quitando archivo al final (index.php, etc.)
const baseUrl = (function () {
    const path = window.location.pathname;
    return window.location.origin + path.replace(/[^/]*$/, '');
})();

let currentView = '';
let isLoading = false;

/**
 * Construye la URL para una vista
 * @param {string} viewName
 * @param {string|null} action
 * @returns {string}
 */
function buildViewUrl(viewName, action = null) {
    let url = baseUrl + '?view=' + encodeURIComponent(viewName);
    if (action) url += '&action=' + encodeURIComponent(action);
    return url;
}

/**
 * Carga una vista completa o parcial
 * @param {string} viewName
 * @param {string|null} action
 * @param {string} targetElement
 * @param {boolean} partial
 */
async function loadView(viewName, action = null, targetElement = '#mainContent', partial = false) {
    if (isLoading) {
        console.log('Ya hay una carga en progreso. Esperando...');
        return;
    }

    isLoading = true;
    currentView = viewName;

    showLoadingIndicator(targetElement);

    try {
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

            // 🔥 Carga dinámica de scripts para esta vista
            await loadViewScript(viewName);

            if (!partial) {
                updateBrowserUrl(viewName, action);
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
        if (script.src) {
            const newScript = document.createElement('script');
            newScript.src = script.src;
            document.head.appendChild(newScript);
        } else {
            try {
                eval(script.textContent);
            } catch (error) {
                console.error('Error ejecutando script inline:', error);
            }
        }
    });
}

/**
 * Muestra un indicador de carga
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
 * Muestra un mensaje de error en el target
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
 * Actualiza la URL en la barra de navegación
 * @param {string} view
 * @param {string|null} action
 */
function updateBrowserUrl(view, action = null) {
    let url = buildViewUrl(view, action);
    if (window.history && window.history.pushState) {
        window.history.pushState({ view, action }, '', url);
    }
}

/**
 * Navega programáticamente a una vista
 * @param {string} view
 * @param {string|null} action
 */
function navigateTo(view, action = null) {
    loadView(view, action);
}

/**
 * Inicializa la navegación SPA
 */
function initializeNavigation() {
    document.addEventListener('click', function (e) {
        const link = e.target.closest('[data-view]');
        if (link) {
            e.preventDefault();
            const view = link.getAttribute('data-view');
            const action = link.getAttribute('data-action');
            loadView(view, action);
        }
    });

    document.addEventListener('submit', function (e) {
        const form = e.target;
        if (form.hasAttribute('data-ajax')) {
            e.preventDefault();
            handleAjaxForm(form);
        }
    });

    console.log('Sistema de navegación SPA inicializado.');
}

/**
 * Procesa formularios AJAX
 * @param {HTMLFormElement} form
 */
function handleAjaxForm(form) {
    const formData = new FormData(form);
    const url = form.action || baseUrl;

    fetch(url, {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (data.redirect) {
                    loadView(data.redirect.view, data.redirect.action);
                } else if (data.message) {
                    showSuccessMessage(data.message);
                }
            } else {
                showErrorMessage(data.message || 'Error en el formulario');
            }
        })
        .catch(error => {
            console.error('Error en formulario AJAX:', error);
            showErrorMessage('Error al procesar el formulario');
        });
}

/**
 * Muestra un mensaje de éxito
 * @param {string} message
 */
function showSuccessMessage(message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-success alert-dismissible fade show';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    const container = document.querySelector('#mainContent');
    if (container) container.insertBefore(alertDiv, container.firstChild);
}

/**
 * Muestra un mensaje de error
 * @param {string} message
 */
function showErrorMessage(message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-danger alert-dismissible fade show';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    const container = document.querySelector('#mainContent');
    if (container) container.insertBefore(alertDiv, container.firstChild);
}

// Inicializar al cargar la página
document.addEventListener('DOMContentLoaded', function () {
    initializeNavigation();

    window.addEventListener('popstate', function (e) {
        if (e.state && e.state.view) {
            loadView(e.state.view, e.state.action);
        }
    });
});

// Exportar globalmente (opcional)
window.loadView = loadView;
window.loadPartialView = loadView;
window.navigateTo = navigateTo;
