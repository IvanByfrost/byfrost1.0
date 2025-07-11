function searchSchoolAJAX(e) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);

    fetch(form.action || window.location.href, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(r => r.text())
    .then(html => {
        const target = document.querySelector('#mainContent');
        if (target) {
            target.innerHTML = html;
            if (typeof executeScriptsInContent === 'function') {
                executeScriptsInContent(target);
            }
        }
    })
    .catch(err => {
        console.error(err);
        if (typeof Swal !== "undefined") {
            Swal.fire('Error', 'No se pudo procesar la búsqueda.', 'error');
        } else {
            alert('Error al procesar la búsqueda.');
        }
    });

    return false;
}
