<?php namespace Views;
?>
<!DOCTYPE html>
<html lang="en">
     <head>
          <meta charset="UTF-8">
          <meta name="viewport" content="width=device-width, initial-scale=1.0">
          <title>New Keeper Registration</title>
          <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
          <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
          <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
          <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
     </head>
<style>
     body {
          background-color: #f8f9fa;
     }
     .navbar {
          margin-bottom: 30px;
     }
     .container {
          background-color: white;
          border-radius: 8px;
          box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
          padding: 30px;
          transition: transform 0.3s;
     }
     .container:hover {
          transform: translateY(-5px);
     }
     .form-control {
          border-radius: 4px;
          transition: border-color 0.3s;
     }
     .form-control:focus {
          border-color: #007bff;
          box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
     }
     .btn-dark {
          background-color: #343a40;
          border-color: #343a40;
     }
     .btn-dark:hover {
          background-color: #23272b;
     }
     h2 {
          color: #343a40;
          margin-bottom: 20px;
     }
     .password-group {
          display: flex;
          gap: 10px; 
     }
     .password-group input {
          flex: 1;
     }
</style>
<body>
<nav class="navbar navbar-expand-lg  navbar-dark bg-dark">
     <span class="navbar-text">
          <strong>Pet Hero</strong>
     </span>
     <ul class="navbar-nav ml-auto">
          <li class="nav-item">
               <a class="nav-link" href="<?php echo '/FinalProject4/' ?>index.php">Home</a>
          </li>          
     </ul>
</nav>
<main class="py-5">
     <section id="listado" class="mb-5">
     <div class="container">
          <h2 class="text-center">New Keeper Registration</h2>
          <form action="<?php echo FRONT_ROOT ?>Keeper/newKeeper" method="post" class="p-4" enctype="multipart/form-data">
               <input type="hidden" name="rol" value="0">
               <div class="mb-3">
                    <label for="lastName">Last Name</label>
                    <input type="text" name="lastName" id="lastName" placeholder="Last name" class="form-control" required>
               </div>
               <div class="mb-3">
                    <label for="firstName">First Name</label>
                    <input type="text" name="firstName" id="firstName" placeholder="First Name" class="form-control" required>
               </div>
               <div class="mb-3">
                    <label for="cellPhone">Cell Phone</label>
                    <input type="tel" name="cellPhone" id="cellPhone" placeholder="Cellphone" class="form-control" required>
               </div>
               <div class="mb-3">
                    <label for="birthDate">Birth Date</label>
                    <input type="date" name="birthDate" id="birthDate" class="form-control" required>
               </div>
               <div class="mb-3">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" placeholder="E-mail" class="form-control" required>
               </div>
               <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-group">
                         <input type="password" id="password" name="password" class="form-control" minlength="6" required>
                         <button id="togglePassword1" class="btn btn-outline-secondary" type="button">
                              <i class="bi bi-eye-slash-fill"></i>
                         </button>
                    </div>
               </div>
               <div class="form-group">
                    <label for="confirmPassword">Confirm Password</label>
                    <div class="input-group">
                         <input type="password" id="confirmPassword" name="password1" class="form-control" required>
                         <button id="togglePassword2" class="btn btn-outline-secondary" type="button">
                              <i class="bi bi-eye-slash-fill"></i>
                         </button>
                    </div>
               </div>
               <div class="mb-3">
                    <label for="userDescription">Description</label>
                    <textarea name="userDescription" rows="2" maxlength="255" class="form-control"></textarea>
               </div>
               <div class="mb-3">
                    <label for="QuestionRecovery">Recovery Question</label>
                    <select name="QuestionRecovery" class="form-control" required>
                         <option value="">Select a question</option>
                         <option value="What was your first pet called?" name="QuestionRecovery">What was your first pet called?</option>
                         <option value="What is your favorite breed of dog?" name="QuestionRecovery">What is your favorite breed of dog?</option>
                         <option value="What is your pet's favorite place?" name="QuestionRecovery">What is your pet's favorite place?</option>
                         <option value="What is your pet's favorite toy?" name="QuestionRecovery">What is your pet's favorite toy?</option>
                    </select>
               </div>
               <div class="mb-3">
                    <label for="answerRecovery">Answer</label>
                    <textarea name="answerRecovery" rows="2" maxlength="120" class="form-control" required></textarea>
               </div>
               <div class="mb-3">
                    <label for="animalSize">Animal Size</label>
                    <select name="animalSize" id="animalSize" class="form-control" required>
                         <option value="Small">Small</option>
                         <option value="Medium">Medium</option>
                         <option value="Big">Big</option>
                    </select>
               </div>
               <div class="mb-3">
                    <label for="price">Price</label>
                    <input type="decimal" name="price" id="price" placeholder="Price" class="form-control" required>
               </div>
               <div class="mb-3">
                    <label for="cbu">CBU</label>
                    <input type="text" name="cbu" id="cbu" class="form-control" required>
               </div>
               <div class="mb-3">
                    <div class="mb-3">
                         <label for="imageKeeper">Imagen</label>
                         <input type="file" name="imageKeeper[]" id="imageKeeper" class="form-control" accept=".jpg, .jpeg, .png, .webp">
                    </div> 
                    <div class="container d-flex aling-items-center justify-content-center" id="previewBlock">
                         <img id="preview" src="#" alt="Vista previa de la imagen" style="max-width: 200px; display: none;">
                    </div>
               </div>
               <div class="d-flex justify-content-center mt-4">
                    <button type="submit" id="saveUser" class="btn btn-dark w-25">Sign Up</button>
               </div>
          </form>
     </div>
     </section>
</main>
</body>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const today = new Date();
    const minDate = new Date();
    // 18 años atrás
    minDate.setFullYear(today.getFullYear() - 18);
    
    const minDateStr = `${minDate.getFullYear()}-${String(minDate.getMonth() + 1).padStart(2, '0')}-${String(minDate.getDate()).padStart(2, '0')}`;
    document.getElementById('birthDate').setAttribute('max', minDateStr);

    function validateInput(value) {
        const invalidRegex = /[\s\n\r]/;
        return !invalidRegex.test(value);
    }

    function restrictInvalidCharacters(event) {
        const invalidCharacters = /[^\w@.-]/; 
        if (invalidCharacters.test(event.key)) {
            event.preventDefault();
        }
    }

    const togglePassword1 = document.getElementById('togglePassword1');
    const togglePassword2 = document.getElementById('togglePassword2');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirmPassword');

    togglePassword1.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        this.querySelector('i').classList.toggle('fa-eye-slash');
    });

    togglePassword2.addEventListener('click', function() {
        const type = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        confirmPasswordInput.setAttribute('type', type);
        this.querySelector('i').classList.toggle('fa-eye-slash');
    });

    document.getElementById('cellPhone').addEventListener('keydown', restrictInvalidCharacters);
    document.getElementById('email').addEventListener('keydown', restrictInvalidCharacters);
    document.getElementById('answerRecovery').addEventListener('keydown', restrictInvalidCharacters);

    document.querySelector('saveUser').addEventListener('submit', function(event) {
        event.preventDefault();

        const lastName = document.getElementById('lastName').value.trim();
        const firstName = document.getElementById('firstName').value.trim();
        const cellPhone = document.getElementById('cellPhone').value.trim();
        const birthDate = document.getElementById('birthDate').value.trim();
        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value.trim();
        const confirmPassword = document.getElementById('confirmPassword').value.trim();
        const questionRecovery = document.getElementById('QuestionRecovery').value.trim();
        const answerRecovery = document.getElementById('answerRecovery').value.trim();
        const animalSize = document.getElementById('animalSize').value.trim();
        const price = document.getElementById('price').value.trim();
        const CBU = document.getElementById('cbu').value.trim();

        // Validar campos vacíos
        if (!lastName || !firstName || !cellPhone || !birthDate || !email || 
            !password || !confirmPassword || !questionRecovery || !answerRecovery ||
            !animalSize || !price || !CBU) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Debe completar todos los campos!'
            });
            return;
        }

        // Validar caracteres no permitidos
        if (!validateInput(lastName) || !validateInput(firstName) || !validateInput(cellPhone) || 
            !validateInput(email) || !validateInput(password) || !validateInput(confirmPassword) || 
            !validateInput(price) || !validateInput(CBU)) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'No se permiten espacios ni saltos de línea en los campos!'
            });
            return;
        }

        // Validar que las contraseñas coincidan
        if (password !== confirmPassword) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Las contraseñas ingresadas no son iguales!'
            });
            return; 
        }

        // Validar formato de email
        const emailRegex = /^(?!.*\.\.)([a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,})$/;
        if (!emailRegex.test(email)) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Formato de correo electrónico inválido o contiene puntos consecutivos!'
            });
            return;
        }

        // Validar extensión del correo
        const validExtensions = ['.com', '.net', '.org', '.edu'];
        if (!validExtensions.some(ext => email.endsWith(ext))) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'La extensión del correo debe ser .com, .net, .org o .edu!'
            });
            return;
        }

        // Mensaje de éxito
        Swal.fire({
            position: 'top-end',
            icon: 'success',
            title: 'Usuario creado!',
            showConfirmButton: false,
            timer: 1500
        });
    });
});
//Pre-view imagen
document.getElementById('imageKeeper').addEventListener('change', function(event) {
     const file = event.target.files[0];
     const allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
     const fileExtension = file.name.split('.').pop().toLowerCase();
     if (!allowedExtensions.includes(fileExtension)) {
          alert('Solo se permiten archivos con las siguientes extensiones: ' + allowedExtensions.join(', '));
          event.target.value = '';
          document.getElementById('preview').style.display = 'none';
          document.getElementById('previewBlock').style.display = 'none';
     return;
     }
     const reader = new FileReader();
     reader.onload = function(e) {
          document.getElementById('preview').src = e.target.result;
          document.getElementById('previewBlock').style.display = 'block';
          document.getElementById('preview').style.display = 'block';
     };
     reader.readAsDataURL(file);
});
</script>
</html>

