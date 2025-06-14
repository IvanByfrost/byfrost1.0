document.getElementById('login-form').addEventListener('submit', function (e) {
    e.preventDefault();

    const user = document.getElementById('usuario').value;
    const pass = document.getElementById('contrasena').value;

    // Simulación: solo para pruebas
    if (user === 'admin' && pass === '1234') {
        window.location.href = 'admin.html';
    } else if (user === 'cliente' && pass === 'abcd') {
        window.location.href = 'cliente.html';
    } else {
        alert('Credenciales incorrectas');
    }
});

const errorSpan = document.getElementById('password-error');

if (!usuario) {
    errorSpan.textContent = 'Documento o contraseña incorrectos';
    return;
}

errorSpan.textContent = ''; // Limpia mensaje si entra bien