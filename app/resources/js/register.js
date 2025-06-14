	console.log("JS conectado correctamente");

document.addEventListener('DOMContentLoaded', function () {
  document.getElementById('register-form').addEventListener('submit', function(event) {
    event.preventDefault(); // Para pruebas, evitar envío

    const documentType = document.getElementById('document-type').value.trim();
    const credentialNumber = document.getElementById('credential-number').value.trim();
    const nameUser = document.getElementById('name_user').value.trim();
    const lastnameUser = document.getElementById('lastname_user').value.trim();

    console.log("Formulario capturado");
    console.log({ documentType, credentialNumber, nameUser, lastnameUser });

    if (!documentType || !credentialNumber || !nameUser || !lastnameUser) {
      alert('Por favor, complete todos los campos correctamente.');
    } else {
      // Simulamos el "Siguiente"
      window.location.href = "register-contact.php";
    }
  });
});

document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById('contact-form');

  form.addEventListener('submit', function (event) {
    event.preventDefault();

    // Entradas
    const phone = document.getElementById('phone-user');
    const email = document.getElementById('email_user');
    const confEmail = document.getElementById('conf-email_user');
    const password = document.getElementById('password_user');
    const confPassword = document.getElementById('conf-password_user');
    const errorList = document.getElementById('form-errors');

    // Limpiar errores anteriores
    errorList.innerHTML = '';
    [phone, email, confEmail, password, confPassword].forEach(input => input.classList.remove('is-invalid'));

    let hasError = false;

    // Validar teléfono
    if (phone.value.trim().length !== 10 || !/^\d{10}$/.test(phone.value.trim())) {
      const li = document.createElement('li');
      li.textContent = 'El número de teléfono debe tener exactamente 10 dígitos.';
      errorList.appendChild(li);
      phone.classList.add('is-invalid');
      hasError = true;
    }

    // Validar correos
    if (email.value.trim() !== confEmail.value.trim()) {
      const li = document.createElement('li');
      li.textContent = 'Los correos electrónicos no coinciden.';
      errorList.appendChild(li);
      confEmail.classList.add('is-invalid');
      hasError = true;
    }

    // Validar contraseñas
    if (password.value.trim() !== confPassword.value.trim()) {
      const li = document.createElement('li');
      li.textContent = 'Las contraseñas no coinciden.';
      errorList.appendChild(li);
      confPassword.classList.add('is-invalid');
      hasError = true;
    }

    // Si no hay errores, redirige
    if (!hasError) {
      window.location.href = "login.php";
    }
  });
});



  document.getElementById('terms-form').addEventListener('submit', function(event) {
    const checkbox = document.getElementById('acceptTerms');
    const errorMsg = document.getElementById('terms-error');

    if (!checkbox.checked) {
      event.preventDefault(); // Detiene el envío del formulario
      errorMsg.style.display = 'block';
    } else {
      errorMsg.style.display = 'none';
    }
  });
