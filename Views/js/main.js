document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('password');
    const password1Input = document.getElementById('password1');
    const passwordButton = document.querySelector('#password .modal-footer .btn-primary');

    function validatePassword() {
        const password = passwordInput.value;
        const password1 = password1Input.value;

        // Comprobar si las contraseñas coinciden y si tienen al menos 6 caracteres
        if (password.length >= 6 && password === password1) {
            passwordButton.disabled = false; // Habilitar el botón si todo es correcto
        } else {
            passwordButton.disabled = true; // Deshabilitar el botón si no se cumplen las condiciones
        }
    }

    // Añadir eventos para comprobar la validación en tiempo real
    passwordInput.addEventListener('input', validatePassword);
    password1Input.addEventListener('input', validatePassword);
});
