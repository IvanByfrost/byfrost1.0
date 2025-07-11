import { showLoadingIndicator, hideLoadingIndicator, showError } from './uiHelpers.js';
import { executeScriptsInContent } from './uiHelpers.js'; // o separarlo si deseas

const baseUrl = window.location.origin + window.location.pathname;
let currentView = '';
let isLoading = false;

export function buildViewUrl(viewName, action = null) {
    let url = baseUrl + '?view=' + encodeURIComponent(viewName);
    if (action) url += '&action=' + encodeURIComponent(action);
    return url;
}

export function loadView(viewName, action = null, targetElement = '#mainContent', partial = false) {
    if (isLoading) {
        console.log('Ya hay una carga en progreso, esperando...');
        return;
    }

    isLoading = true;
    if (!partial) currentView = viewName;
    showLoadingIndicator(targetElement);

    let url = buildViewUrl(viewName, action);
    if (partial) url += '&partialView=true';

    console.log(partial ? 'Cargando vista parcial:' : 'Cargando vista completa:', url);

    fetch(url, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'text/html, application/xhtml+xml, application/xml;q=0.9, */*;q=0.8'
        }
    })
    .then(response => {
        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
        return response.text();
    })
    .then(html => {
        const target = document.querySelector(targetElement);
        if (target) {
            target.innerHTML = html;
            executeScriptsInContent(target);

            if (!partial) {
                updateBrowserUrl(viewName, action);
                document.dispatchEvent(new CustomEvent('viewLoaded', {
                    detail: { view: viewName, action: action }
                }));
                console.log('Vista cargada exitosamente:', viewName);
            } else {
                console.log('Vista parcial cargada exitosamente:', viewName);
            }
        } else {
            console.error('Elemento objetivo no encontrado:', targetElement);
        }
    })
    .catch(error => {
        console.error('Error cargando vista:', error);
        showError(targetElement, 'Error al cargar la vista: ' + error.message);
    })
    .finally(() => {
        isLoading = false;
        hideLoadingIndicator();
    });
}

export function updateBrowserUrl(view, action = null) {
    let url = buildViewUrl(view, action);
    if (window.history && window.history.pushState) {
        window.history.pushState({ view: view, action: action }, '', url);
    }
}
