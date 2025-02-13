function signUp() {
    const email = document.querySelector('#email').value;
    const password = document.querySelector('#password').value;
    const repeatPassword = document.querySelector('#repeat-password').value;
    const errorMessage = document.getElementById('error-message');
    const emailPattern = /^[^@]+@[^@]+\.[a-zA-Z]{2,}$/;


    errorMessage.textContent = '';


    if (!emailPattern.test(email)) {
        errorMessage.textContent = 'El correo debe incluir un "@" y un dominio ".com".';
        return;
    }


    if (password !== repeatPassword) {
        errorMessage.textContent = 'Las contraseñas no coinciden.';
        return;
    }


    localStorage.setItem('userEmail', email);
    localStorage.setItem('userPassword', password);


    window.location.href = '../../index.html';
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
