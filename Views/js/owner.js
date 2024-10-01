document.addEventListener("DOMContentLoaded", function() {
    // Configurar el campo de fecha de nacimiento para permitir solo hasta 100 años atrás
    const today = new Date();
    alert(today);
    const hundredYearsAgo = today.getFullYear() - 100;
    const maxDate = `${hundredYearsAgo}-${String(today.getMonth() + 1).padStart(2, '0')}-${String(today.getDate()).padStart(2, '0')}`;
    document.getElementById('birthDate').setAttribute('max', maxDate);

    document.getElementById('btnRegister').addEventListener('click', async function(event) {
        event.preventDefault(); // Previene el envío del formulario

        var lastName = document.getElementById('lastName').value;
        var firstName = document.getElementById('firstName').value;
        var cellPhone = document.getElementById('cellPhone').value;
        var birthDate = document.getElementById('birthDate').value;
        var email = document.getElementById('email').value;
        var password = document.getElementById('password').value;
        var confirmPassword = document.getElementById('confirmPassword').value;

        // Validar si las contraseñas coinciden
        if (password !== confirmPassword) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Las contraseñas ingresadas no son iguales!'
            });
            return; // Detener la ejecución
        }

        // Validar campos vacíos
        if (!lastName || !firstName || !cellPhone || !birthDate || !email) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Debe completar todos los campos!'
            });
            return; // Detener la ejecución
        }

        // Validar el formato del correo electrónico
        const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        if (!emailRegex.test(email)) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Formato de correo electrónico inválido!'
            });
            return; // Detener la ejecución
        }

        // Validar la extensión del correo electrónico
        const validExtensions = ['.com', '.net', '.org', '.edu'];
        if (!validExtensions.some(ext => email.endsWith(ext))) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'La extensión del correo debe ser .com, .net, .org o .edu!'
            });
            return; // Detener la ejecución
        }

        // Si todo está bien, enviar el formulario
        Swal.fire({
            position: 'top-end',
            icon: 'success',
            title: 'Usuario creado!',
            showConfirmButton: false,
            timer: 1500
        });

        // Envío del formulario puede ser manejado aquí si es necesario
        // document.forms[0].submit();
    });
});
