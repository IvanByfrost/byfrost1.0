$(document).ready(function() {
    $('#roleHistoryForm').on('submit', function(e) {
        e.preventDefault();
        var credentialType = $('#credential_type').val();
        var credentialNumber = $('#credential_number').val();
        
        if (!credentialType || !credentialNumber) {
            alert('Por favor, completa ambos campos.');
            return false;
        }
        
        $('#searchResultsCard').show();
        $('#searchResultsContainer').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Buscando...</div>');
        
        $.ajax({
            url: '<?= url ?>index.php',
            method: 'GET',
            data: {
                controller: 'User',
                action: 'showRoleHistory',
                credential_type: credentialType,
                credential_number: credentialNumber,
                ajax: 1
            },
            dataType: 'html',
            success: function(response) {
                // Mostrar la respuesta completa en el contenedor
                $('#searchResultsContainer').html(response);
            },
            error: function(xhr, status, error) {
                console.error('Error AJAX:', error);
                $('#searchResultsContainer').html('<div class="alert alert-danger">Error al buscar el usuario. Detalles: ' + error + '</div>');
            }
        });
    });
});