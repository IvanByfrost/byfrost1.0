const usuarios = [
        { documento: '1031180139', contrasena: 'rector123', rol: 'rector' },
        { documento: '87654321', contrasena: 'cliente456', rol: 'estudiante' },
        { documento: '1031180133', contrasena: 'editor789', rol: 'docente' }
    ];

    document.getElementById('login-form').addEventListener('submit', function (e) {
        e.preventDefault();

        const doc = document.getElementById('documento').value.trim();
        const pass = document.getElementById('contrasena').value.trim();

        const usuario = usuarios.find(u => u.documento === doc && u.contrasena === pass);

        const errorSpan = document.getElementById('password-error'); // Obtén el span

        if (!usuario) {
            // Muestra el mensaje de error en el span
            errorSpan.textContent = 'Usuario o contraseña incorrectos';
            return;
        }
        
        // Si el usuario es encontrado, limpia el mensaje de error
        errorSpan.textContent = '';

        // Redirigir según el rol
        switch (usuario.rol) {
            case 'rector':
                window.location.href = 'rector.html';
                break;
            case 'estudiante':
                window.location.href = 'estudiante.html';
                break;
            case 'docente':
                window.location.href = 'docente.html';
                break;
            default:
                alert('Rol no reconocido');
        }
        console.log(`Bienvenido, rol detectado: ${usuario.rol}`);
    });