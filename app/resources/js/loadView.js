console.log("Script cargado");
function loadView(viewName) {
    fetch(`<?= url . rq ?>app/scripts/viewRouter.php?view=${viewName}`)
        .then(response => {
            if (!response.ok) throw new Error("Vista no encontrada.");
            return response.text();
        })
        .then(html => {
            document.getElementById("main-content").innerHTML = html;
        })
        .catch(err => {
            console.error(err);
            Swal.fire({
                title: 'Error',
                text: 'No se pudo cargar la vista.',
                icon: 'error',
                timer: 4000
            });
        });
}