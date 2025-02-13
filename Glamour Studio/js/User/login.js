function login() {
    const email = document.querySelector('input[type="email"]').value;
    const password = document.querySelector('input[type="password"]').value;
    const errorMessage = document.getElementById('error-message');
    const emailPattern = /^[^@]+@[^@]+\.[a-zA-Z]{2,}$/; 

    errorMessage.textContent = '';

    if (!emailPattern.test(email)) {
        errorMessage.textContent = 'El correo debe incluir un "@" y un dominio ".com".';
        return;
    }

    const storedEmail = localStorage.getItem('userEmail');
    const storedPassword = localStorage.getItem('userPassword');


    if (email === storedEmail && password === storedPassword) {
        window.location.href = '../../index.html';
    } else {
        errorMessage.textContent = 'Usuario o contraseña incorrectos';
    }
}

const togglePasswordIcons = document.querySelectorAll('.toggle-password');

togglePasswordIcons.forEach(icon => {
icon.addEventListener('click', function () {
    const targetId = this.getAttribute('data-target');
    const passwordInput = document.getElementById(targetId);

    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput.setAttribute('type', type);

    if (type === 'password') {
        this.innerHTML = '<img src="/img/ojo.png" alt="Mostrar Contraseña" width="15" height="15">';
    } else {
        this.innerHTML = '<img src="/img/ver.png" alt="Ocultar Contraseña" width="15" height="15">';
    }
});
});