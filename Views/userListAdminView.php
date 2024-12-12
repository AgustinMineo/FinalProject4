<?php
namespace Views;
require_once("validate-session.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PET HERO - Administrar Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container mt-5">
        <div class="text-center mb-4">
            <h1 class="display-4">Administrar Usuarios</h1>
        </div>
        <div class="container d-flex align-items-end justify-content-end">
        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#newUserModal">
            New User
        </button>
        </div>
        <div class="container">
            <div class="mb-4">
                <h2>Filtrar Usuarios</h2>
                <input type="text" id="filterName" placeholder="Buscar por email" class="form-control mb-2" />
            </div>
        </div>
        <div id="noResultsMessage" class="alert alert-danger" style="display: none;">
            <p>No existen usuarios que coincidan con la búsqueda.</p>
        </div>
        <!--Admin Section--> 
        <section class="mb-5">
            <h3 class="mb-3">Administradores</h3>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">
                <?php foreach ($adminUsers as $admin): ?>
                    <div class="col">
                    <div class="card shadow-sm h-100 <?php if($admin->getStatus() === '0'){ echo 'bg-danger'; } ?>">
                        <div class="card-body d-flex flex-column align-items-center">
                            <p id="userEmail-<?php echo $admin->getOwnerId(); ?>" class="m-0 text-center">
                                <strong>Email:</strong> <?php echo $admin->getEmail(); ?>
                            </p>
                            <div class="d-flex flex-row justify-content-center mt-2">
                                <form action="<?php echo FRONT_ROOT ?>User/goEditView" method="POST" class="me-2">
                                    <input type="hidden" name="email" value="<?php echo $admin->getEmail(); ?>">
                                    <input type="hidden" name="role" value="1">
                                    <button type="submit" class="btn btn-warning btn-sm">
                                        <i class="bi bi-pencil-fill"></i> Modificar
                                    </button>
                                </form>
                                <?php if($admin->getStatus() === '0'): ?>
                                    <form action="<?php echo FRONT_ROOT ?>User/deleteUser" method="POST">
                                        <input type="hidden" name="email" class="userEmail" value="<?php echo $admin->getEmail(); ?>">
                                        <input type="hidden" name="status" value="<?php echo intval($admin->getStatus()); ?>">
                                        <button type="submit" class="btn btn-success btn-sm">
                                            <i class="bi bi-check-circle"></i> Reactivar
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <?php endforeach; ?>
            </div>
            <hr>
        </section>
        <!--Admin Section-->
        <!-- Owner Section -->
        <section class="mb-5">
            <h3 class="mb-3">Dueños</h3>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">
                <?php foreach ($ownerUsers as $owner): ?>
                    <div class="col">
                        <div class="card shadow-sm h-100 <?php if($owner->getStatus() === '0'){ echo 'bg-danger'; } ?>">
                            <div class="card-body d-flex flex-column align-items-center">
                                <p id="userEmail-<?php echo $owner->getOwnerId(); ?>" class="m-0 text-center">
                                    <strong>Email:</strong> <?php echo $owner->getEmail(); ?>
                                </p>
                                <div class="d-flex flex-row justify-content-center mt-2">
                                    <form action="<?php echo FRONT_ROOT ?>User/goEditView" method="POST" class="me-2">
                                        <input type="hidden" name="email" value="<?php echo $owner->getEmail(); ?>">
                                        <input type="hidden" name="role" value="1">
                                        <button type="submit" class="btn btn-warning btn-sm">
                                            <i class="bi bi-pencil-fill"></i> Modificar
                                        </button>
                                    </form>
                                    <?php if($owner->getStatus() === '0'): ?>
                                        <form action="<?php echo FRONT_ROOT ?>User/deleteUser" method="POST">
                                            <input type="hidden" name="email" class="userEmail" value="<?php echo $owner->getEmail(); ?>">
                                            <input type="hidden" name="status" value="<?php echo intval($owner->getStatus()); ?>">
                                            <button type="submit" class="btn btn-success btn-sm">
                                                <i class="bi bi-check-circle"></i> Reactivar
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <hr>
        </section>

        <!-- Keeper Section -->
        <section>
            <hr>
            <h3 class="mb-3">Cuidadores</h3>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">
                <?php foreach ($keeperUsers as $keeper): ?>
                    <div class="col">
                        <div class="card shadow-sm h-100 <?php if($keeper->getStatus() === '0'){ echo 'bg-danger'; } ?>">
                            <div class="card-body d-flex flex-column align-items-center">
                                <p id="userEmail-<?php echo $keeper->getKeeperId(); ?>" class="m-0 text-center">
                                    <strong>Email:</strong> <?php echo $keeper->getEmail(); ?>
                                </p>
                                <div class="d-flex flex-row justify-content-center mt-2">
                                    <form action="<?php echo FRONT_ROOT ?>User/goEditView" method="POST" class="me-2">
                                        <input type="hidden" name="email" value="<?php echo $keeper->getEmail(); ?>">
                                        <input type="hidden" name="role" value="1">
                                        <button type="submit" class="btn btn-warning btn-sm">
                                            <i class="bi bi-pencil-fill"></i> Modificar
                                        </button>
                                    </form>
                                    <?php if($keeper->getStatus() === '0'): ?>
                                        <form action="<?php echo FRONT_ROOT ?>User/deleteUser" method="POST">
                                            <input type="hidden" name="email" class="userEmail" value="<?php echo $keeper->getEmail(); ?>">
                                            <input type="hidden" name="status" value="<?php echo intval($keeper->getStatus()); ?>">
                                            <button type="submit" class="btn btn-success btn-sm">
                                                <i class="bi bi-check-circle"></i> Reactivar
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <hr>
        </section>
                <!--Modal new User-->
    <div class="modal fade" id="newUserModal" tabindex="-1" aria-labelledby="newUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newUserModalLabel">New User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                <form id="newUserForm" action="<?php echo FRONT_ROOT ?>" method="post" enctype="multipart/form-data">
                    <!-- Select para elegir el tipo de usuario -->
                    <div class="mb-3">
                        <label for="userRole" class="form-label">User Role</label>
                        <select class="form-select form-select-lg mb-3" id="userRole" name="role" required>
                            <option value="1">Admin</option>
                            <option value="2">Owner</option>
                            <option value="3">Keeper</option>
                        </select>
                    </div>
                    <!-- Campos generales para todos los usuarios -->
                    <div class="mb-3">
                        <label for="lastName" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="lastName" name="lastName" required>
                    </div>
                    <div class="mb-3">
                        <label for="firstName" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="firstName" name="firstName" required>
                    </div>
                    <div class="mb-3">
                        <label for="cellPhone" class="form-label">Cell Phone</label>
                        <input type="tel" class="form-control" id="cellPhone" name="cellPhone">
                    </div>
                    <div class="mb-3">
                        <label for="birthDate" class="form-label">Birth Date</label>
                        <input type="date" class="form-control" id="birthDate" name="birthDate">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                        <input type="password" class="form-control" id="password" name="password" minlength="6" required>
                            <button id="togglePassword1" class="btn btn-outline-primary" type="button">
                                <i class="bi bi-eye-slash-fill"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mb-3 w-auto">
                        <label for="password" class="form-label">Repeat Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" minlength="6" required>
                            <button id="togglePassword2" class="btn btn-outline-primary" type="button">
                                <i class="bi bi-eye-slash-fill"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="userDescription" class="form-label">User Description</label>
                        <textarea class="form-control" id="userDescription" name="userDescription" rows="3" maxlength="254"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="QuestionRecovery">Recovery Question</label>
                        <select name="QuestionRecovery" id="QuestionRecovery" class="form-control" required>
                            <option value="">Select a question</option>
                            <option value="What was your first pet called?" name="QuestionRecovery">What was your first pet called?</option>
                            <option value="What is your favorite breed of dog?" name="QuestionRecovery">What is your favorite breed of dog?</option>
                            <option value="What is your pet's favorite place?" name="QuestionRecovery">What is your pet's favorite place?</option>
                            <option value="What is your pet's favorite toy?" name="QuestionRecovery">What is your pet's favorite toy?</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="answerRecovery">Answer</label>
                        <textarea name="answerRecovery" id="answerRecovery" rows="2" maxlength="120" class="form-control" required></textarea>
                    </div>
                    <!-- Campos para Owner -->
                    <div id="ownerFields" class="d-none">
                        <div class="mb-3">
                            <label for="petAmount" class="form-label">Pet Amount</label>
                            <input type="number" class="form-control" value='0' id="petAmount" disabled>
                        </div>
                    </div>
                    <!-- Campos para Keeper -->
                    <div id="keeperFields" class="d-none">
                        <div class="mb-3">
                            <label for="animalSize" class="form-label">Animal Size</label>
                            <select name="animalSize" class="form-select" id="animalSize" required>
                                <option value="Small">Small</option>
                                <option value="Medium">Medium</option>
                                <option value="Big">Big</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">Price</label>
                            <input type="decimal" class="form-control" id="price" name="price" required>
                        </div>
                        <div class="mb-3">
                            <label for="CBU" class="form-label">CBU</label>
                            <input type="text" class="form-control" id="CBU" name="CBU" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="mb-3">
                            <label for="image">Imagen</label>
                            <input type="file" name="image[]" id="image" class="form-control" accept=".jpg, .jpeg, .png, .webp">
                        </div> 
                        <div class="container d-flex aling-items-center justify-content-center" id="previewBlock">
                            <img id="preview" src="#" alt="Vista previa de la imagen" style="max-width: 200px; display: none;">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="saveUser">Save User</button>
                    </div>
                </form>
                </div>
            </div>
        </div>
                <!--Modal new User-->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</body>
<script>
//Filtro de correo
document.addEventListener('DOMContentLoaded', function() {
    const filterInput = document.getElementById('filterName');
    const cols = document.querySelectorAll('.col');
    const noResultsMessage = document.getElementById('noResultsMessage');


    filterInput.addEventListener('input', function() {
        const filterValue = filterInput.value.toLowerCase();
        let countUsers = 0;

        cols.forEach(col => {
            const card = col.querySelector('.card');
            const emailElement = card.querySelector('[id^="userEmail-"]'); 
            const emailText = emailElement.textContent.toLowerCase().trim(); 

            // Si el filtro corresponde la deja, sino la esconde a la columna
            if (emailText.includes(filterValue)) {
                col.style.display = '';
                countUsers++;
            } else {
                col.style.display = 'none';
            }
        });
        
        if (countUsers === 0) {
            noResultsMessage.style.display = 'block'; // Muestra el mensaje de error
        } else {
            noResultsMessage.style.display = 'none'; // Oculta el mensaje de error
        }
        // Ocultar o mostrar secciones
        const sections = document.querySelectorAll('section');
        sections.forEach(section => {
            const sectionCols = section.querySelectorAll('.col'); 
            const visibleCols = Array.from(sectionCols).some(col => col.style.display !== 'none'); 
            
            // Muestra o oculta la secicon en base a la visiblidad del col que tenga
            section.style.display = visibleCols ? '' : 'none';
        });
    });
});
//New User filtros
document.addEventListener('DOMContentLoaded', function () {
    const userRole = document.getElementById('userRole');
    const newUserForm = document.getElementById('newUserForm');
    const image = document.getElementById('image');
    const keeperFields = document.getElementById('keeperFields');
    const ownerFields = document.getElementById('ownerFields');
    const animalSizeInput = document.getElementById('animalSize');
    const cbuInput = document.getElementById('CBU');
    const priceInput = document.getElementById('price');
    const preview = document.getElementById('preview');
    const previewBlock = document.getElementById('previewBlock');

    userRole.addEventListener('change', function () {
        handleRoleChange(this.value);
    });

    // Llamada inicial para configurar el estado del formulario
    handleRoleChange(userRole.value);

    function handleRoleChange(role) {
        if (role === '1') { // Admin
            ownerFields.classList.add('d-none');
            keeperFields.classList.add('d-none');
            animalSizeInput.disabled = true;
            resetFields();
            newUserForm.action = '<?php echo FRONT_ROOT ?>Owner/newOwner';
            setImageInputAttributes('imageOwner[]', 'imageOwner');
        } else if (role === '2') { 
            ownerFields.classList.remove('d-none');
            keeperFields.classList.add('d-none');
            animalSizeInput.disabled = true;
            resetFields();
            newUserForm.action = '<?php echo FRONT_ROOT ?>Owner/newOwner';
            setImageInputAttributes('imageOwner[]', 'imageOwner');
        } else if (role === '3') { 
            ownerFields.classList.add('d-none');
            keeperFields.classList.remove('d-none');
            animalSizeInput.disabled = false;
            cbuInput.disabled = false;
            priceInput.disabled = false;
            cbuInput.setAttribute('required', true);
            animalSizeInput.setAttribute('required', true);
            priceInput.setAttribute('required', true);
            newUserForm.action = '<?php echo FRONT_ROOT ?>Keeper/newKeeper';
            setImageInputAttributes('imageKeeper[]', 'imageKeeper');
        }

        const updatedImageInput = document.getElementById(image.id);
        updatedImageInput.addEventListener('change', showPreview);
    }

    function setImageInputAttributes(newName, newId) {
        image.setAttribute('name', newName);
        image.setAttribute('id', newId);
    }

    // Función para mostrar la vista previa de la imagen
    function showPreview(event) {
        const file = event.target.files[0];
        const allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
        const fileExtension = file.name.split('.').pop().toLowerCase();

        if (!allowedExtensions.includes(fileExtension)) {
            alert('Solo se permiten archivos con las siguientes extensiones: ' + allowedExtensions.join(', '));
            event.target.value = '';
            preview.style.display = 'none';
            previewBlock.style.display = 'none';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            previewBlock.style.display = 'block';
            preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
    function resetFields() {
        // Deshabilitar todos los campos y limpiar atributos requeridos
        animalSizeInput.disabled = true;
        cbuInput.disabled = true;
        priceInput.disabled = true;
        cbuInput.removeAttribute('required');
        animalSizeInput.removeAttribute('required');
        priceInput.removeAttribute('required');
    }
});
document.addEventListener("DOMContentLoaded", function() {
    const today = new Date();
    const minDate = new Date();
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

    
    document.getElementById('cellPhone').addEventListener('keydown', restrictInvalidCharacters);
    document.getElementById('email').addEventListener('keydown', restrictInvalidCharacters);
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirmPassword');
    
    //Cambio para visualizar la password.
    const togglePassword1 = document.getElementById('togglePassword1');
    const togglePassword2 = document.getElementById('togglePassword2');

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

    document.getElementById('saveUser').addEventListener('click', function(event) {

        const lastName = document.getElementById('lastName').value.trim();
        const firstName = document.getElementById('firstName').value.trim();
        const cellPhone = document.getElementById('cellPhone').value.trim();
        const birthDate = document.getElementById('birthDate').value.trim();
        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value.trim();
        const confirmPassword = document.getElementById('confirmPassword').value.trim();
        const questionRecovery = document.getElementById('QuestionRecovery').value.trim();
        const answerRecovery = document.getElementById('answerRecovery').value.trim();
        const role = document.getElementById('userRole').value;
        const animalSize = document.getElementById('animalSize') ? document.getElementById('animalSize').value.trim() : null;
        const price = document.getElementById('price') ? document.getElementById('price').value.trim() : null;
        const CBU = document.getElementById('CBU') ? document.getElementById('CBU').value.trim() : null;

        if (!lastName || !firstName || !cellPhone || !birthDate || !email || !password || !questionRecovery || !answerRecovery) {
            event.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Debe completar todos los campos!'
            });
            return; 
        }

        if (!validateInput(lastName) || !validateInput(firstName) || !validateInput(cellPhone) || 
            !validateInput(email) || !validateInput(password) ) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'No se permiten espacios ni saltos de línea en los campos!'
            });
            return;
        }

        const passwordRegex = /^\S+$/;  //Evitar espacios en la password
        if (!passwordRegex.test(password)) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'La contraseña no debe contener espacios!'
            });
            return;
        }

        if(password.length <6 || confirmPassword.length <6){
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Las contraseñas ingresadas no pueden tener menos de 6 caracteres!'
            });
            return;
        }

        if (password !== confirmPassword) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Las contraseñas ingresadas no son iguales!'
            });
            return; 
        }
        const emailRegex = /^(?!.*\s)(?!.*\.\.)([a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,})$/;
        if (!emailRegex.test(email)) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Formato de correo electrónico inválido o contiene espacios o puntos consecutivos!'
            });
            return;
        }


        const validExtensions = ['.com', '.net', '.org', '.edu', '.mx','.ar','.gob','.edu','.pethero.com'];
        if (!validExtensions.some(ext => email.endsWith(ext))) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'La extensión del correo debe ser .com, .net, .org, .edu, .mx, .ar, .gob, pethero.com y .edu!'
            });
            return; 
        }

        if (role === '3') { // Keeper
            if (!animalSize || !price || !CBU) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Los campos de tamaño de animal, precio y CBU son obligatorios para Keeper!'
                });
                return; 
            }
        }


        Swal.fire({
            position: 'top-end',
            icon: 'success',
            title: 'Usuario creado!',
            showConfirmButton: false,
            timer: 1500
        });
    });
});

</script>
</html>