// schoolDirectorSearch.js

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('searchDirectorForm');
    if (!form) return;

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const query = document.getElementById('search_director_query').value.trim();
        if (!query) return;

        fetch('?view=school&action=searchDirectorAjax', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'document=' + encodeURIComponent(query)
        })
        .then(r => r.json())
        .then(data => {
            const resultsDiv = document.getElementById('searchDirectorResults');
            if (data.status === 'ok' && data.data.length > 0) {
                resultsDiv.innerHTML = data.data.map(d =>
                    `<li class="list-group-item d-flex justify-content-between align-items-center">
                        ${d.first_name} ${d.last_name} (${d.email})
                        <button type="button" class="btn btn-outline-primary select-director-btn"
                            data-user-id="${d.user_id}"
                            data-name="${d.first_name} ${d.last_name}">
                            Seleccionar
                        </button>
                    </li>`
                ).join('');
            } else {
                resultsDiv.innerHTML = '<div class="alert alert-warning">No se encontraron directores con ese documento.</div>';
            }
        });
    });
}); 