/**
 * loadView.js - Sistema de carga dinámica de vistas
 * ByFrost - Sistema unificado de navegación
 */

// Configuración global
const baseUrl = window.location.origin + window.location.pathname;
let currentView = '';
let isLoading = false;

/**
 * Construye una URL para cargar una vista
 */
function buildViewUrl(viewName, action = null) {
    let url = baseUrl + '?view=' + encodeURIComponent(viewName);
    
    if (action) {
        url += '&action=' + encodeURIComponent(action);
    }
    
    return url;
}

/**
 * Carga una vista en el contenedor principal
 */
function loadView(viewName, action = null, targetElement = '#mainContent') {
    if (isLoading) {
        console.log('Ya hay una carga en progreso, esperando...');
        return;
    }
    
    isLoading = true;
    currentView = viewName;
    
    // Mostrar indicador de carga
    showLoadingIndicator(targetElement);
    
    const url = buildViewUrl(viewName, action);
    
    console.log('Cargando vista:', url);
    
    fetch(url, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'text/html, application/xhtml+xml, application/xml;q=0.9, */*;q=0.8'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.text();
    })
    .then(html => {
        const target = document.querySelector(targetElement);
        if (target) {
            target.innerHTML = html;
            
            // Ejecutar scripts en el contenido cargado
            executeScriptsInContent(target);
            
            // Actualizar URL sin recargar la página
            updateBrowserUrl(viewName, action);
            
            // Disparar evento personalizado
            document.dispatchEvent(new CustomEvent('viewLoaded', {
                detail: { view: viewName, action: action }
            }));
            
            console.log('Vista cargada exitosamente:', viewName);
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

/**
 * Carga una vista parcial (solo el contenido sin layout)
 */
function loadPartialView(viewName, action = null, targetElement = '#mainContent') {
    if (isLoading) {
        console.log('Ya hay una carga en progreso, esperando...');
        return;
    }
    
    isLoading = true;
    
    // Mostrar indicador de carga
    showLoadingIndicator(targetElement);
    
    let url = buildViewUrl(viewName, action);
    url += '&partialView=true';
    
    console.log('Cargando vista parcial:', url);
    
    fetch(url, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'text/html, application/xhtml+xml, application/xml;q=0.9, */*;q=0.8'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.text();
    })
    .then(html => {
        const target = document.querySelector(targetElement);
        if (target) {
            target.innerHTML = html;
            
            // Ejecutar scripts en el contenido cargado
            executeScriptsInContent(target);
            
            console.log('Vista parcial cargada exitosamente:', viewName);
        } else {
            console.error('Elemento objetivo no encontrado:', targetElement);
        }
    })
    .catch(error => {
        console.error('Error cargando vista parcial:', error);
        showError(targetElement, 'Error al cargar la vista parcial: ' + error.message);
    })
    .finally(() => {
        isLoading = false;
        hideLoadingIndicator();
    });
}

/**
 * Ejecuta scripts encontrados en el contenido cargado
 */
function executeScriptsInContent(container) {
    const scripts = container.querySelectorAll('script');
    scripts.forEach(script => {
        if (script.src) {
            // Script externo
            const newScript = document.createElement('script');
            newScript.src = script.src;
            document.head.appendChild(newScript);
        } else {
            // Script inline
            try {
                eval(script.innerHTML);
            } catch (error) {
                console.error('Error ejecutando script inline:', error);
            }
        }
    });
}

/**
 * Muestra un indicador de carga
 */
function showLoadingIndicator(targetElement = '#mainContent') {
    const target = document.querySelector(targetElement);
    if (target) {
        target.innerHTML = `
            <div class="loading-container" style="text-align: center; padding: 50px;">
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
 * Muestra un mensaje de error
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
                    <button class="btn btn-primary" onclick="location.reload()">
                        Recargar página
                    </button>
                </p>
            </div>
        `;
    }
}

/**
 * Actualiza la URL del navegador sin recargar la página
 */
function updateBrowserUrl(view, action = null) {
    let url = baseUrl + '?view=' + encodeURIComponent(view);
    if (action) {
        url += '&action=' + encodeURIComponent(action);
    }
    
    if (window.history && window.history.pushState) {
        window.history.pushState({ view: view, action: action }, '', url);
    }
}

/**
 * Navega a una vista específica
 */
function navigateTo(view, action = null) {
    loadView(view, action);
}

/**
 * Inicializa el sistema de navegación
 */
function initializeNavigation() {
    // Configurar eventos para enlaces de navegación
    document.addEventListener('click', function(e) {
        const link = e.target.closest('[data-view]');
        if (link) {
            e.preventDefault();
            const view = link.getAttribute('data-view');
            const action = link.getAttribute('data-action');
            loadView(view, action);
        }
    });
    
    // Configurar eventos para formularios AJAX
    document.addEventListener('submit', function(e) {
        const form = e.target;
        if (form.hasAttribute('data-ajax')) {
            e.preventDefault();
            handleAjaxForm(form);
        }
    });
    
    console.log('Sistema de navegación inicializado');
}

/**
 * Maneja formularios AJAX
 */
function handleAjaxForm(form) {
    const formData = new FormData(form);
    const url = form.action || baseUrl;
    
    fetch(url, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
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
 */
function showSuccessMessage(message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-success alert-dismissible fade show';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    const container = document.querySelector('#mainContent');
    if (container) {
        container.insertBefore(alertDiv, container.firstChild);
    }
}

/**
 * Muestra un mensaje de error
 */
function showErrorMessage(message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-danger alert-dismissible fade show';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    const container = document.querySelector('#mainContent');
    if (container) {
        container.insertBefore(alertDiv, container.firstChild);
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    initializeNavigation();
    
    // Configurar manejo del botón atrás/adelante del navegador
    window.addEventListener('popstate', function(e) {
        if (e.state && e.state.view) {
            loadView(e.state.view, e.state.action);
        }
    });
});

// Exportar funciones para uso global
window.loadView = loadView;
window.loadPartialView = loadPartialView;
window.navigateTo = navigateTo;