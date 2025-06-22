function assignRole(userId, roleId) {
    $.post('assignRoleAjax.php', { userId, roleId }, function(response) {
        Swal.fire({
            title: response.status === 'ok' ? 'Rol asignado' : 'Error',
            text: response.msg,
            icon: response.status === 'ok' ? 'success' : 'error',
        });
    }, 'json');
}