// Función para mostrar/ocultar contraseña
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(inputId + '_icon');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// Función para confirmar cambio de contraseña
function confirmPasswordChange() {
    return confirm('¿Estás seguro de que deseas cambiar la contraseña de este usuario?');
}

// Validación del formulario
document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
    const newPassword = document.getElementById('new_password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    
    // Validar que las contraseñas coincidan
    if (newPassword !== confirmPassword) {
        e.preventDefault();
        alert('Las contraseñas no coinciden.');
        return false;
    }
    
    // Validar longitud mínima
    if (newPassword.length < 8) {
        e.preventDefault();
        alert('La contraseña debe tener al menos 8 caracteres.');
        return false;
    }
    
    // Validar complejidad
    const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/;
    if (!passwordRegex.test(newPassword)) {
        e.preventDefault();
        alert('La contraseña debe contener al menos una letra mayúscula, una minúscula, un número y un carácter especial.');
        return false;
    }
    
    return true;
});

// Validación en tiempo real
document.getElementById('new_password').addEventListener('input', function() {
    const password = this.value;
    const confirmPassword = document.getElementById('confirm_password').value;
    
    // Actualizar indicadores de validación
    updatePasswordStrength(password);
    
    // Verificar coincidencia
    if (confirmPassword && password !== confirmPassword) {
        document.getElementById('confirm_password').classList.add('is-invalid');
    } else {
        document.getElementById('confirm_password').classList.remove('is-invalid');
    }
});

document.getElementById('confirm_password').addEventListener('input', function() {
    const newPassword = document.getElementById('new_password').value;
    const confirmPassword = this.value;
    
    if (confirmPassword && newPassword !== confirmPassword) {
        this.classList.add('is-invalid');
    } else {
        this.classList.remove('is-invalid');
    }
});

// Función para mostrar fortaleza de contraseña
function updatePasswordStrength(password) {
    let strength = 0;
    let feedback = '';
    
    if (password.length >= 8) strength++;
    if (/[a-z]/.test(password)) strength++;
    if (/[A-Z]/.test(password)) strength++;
    if (/\d/.test(password)) strength++;
    if (/[@$!%*?&]/.test(password)) strength++;
    
    const input = document.getElementById('new_password');
    input.classList.remove('is-valid', 'is-invalid', 'is-warning');
    
    if (strength >= 5) {
        input.classList.add('is-valid');
        feedback = 'Contraseña fuerte';
    } else if (strength >= 3) {
        input.classList.add('is-warning');
        feedback = 'Contraseña moderada';
    } else {
        input.classList.add('is-invalid');
        feedback = 'Contraseña débil';
    }
}