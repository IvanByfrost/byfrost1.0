export function showLoadingIndicator(targetElement = '#mainContent') {
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

export function hideLoadingIndicator() {
    const loadingContainer = document.querySelector('.loading-container');
    if (loadingContainer) loadingContainer.remove();
}

export function showError(targetElement, message) {
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

export function executeScriptsInContent(container) {
    const scripts = container.querySelectorAll('script');
    scripts.forEach(script => {
        const newScript = document.createElement('script');
        if (script.src) {
            newScript.src = script.src;
            document.head.appendChild(newScript);
        } else {
            try {
                newScript.text = script.innerHTML;
                document.head.appendChild(newScript);
            } catch (e) {
                console.error('Error ejecutando script:', e);
            }
        }
    });
}

export function showSuccessMessage(message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-success alert-dismissible fade show';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    const container = document.querySelector('#mainContent');
    if (container) container.insertBefore(alertDiv, container.firstChild);
}

export function showErrorMessage(message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-danger alert-dismissible fade show';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    const container = document.querySelector('#mainContent');
    if (container) container.insertBefore(alertDiv, container.firstChild);
}
