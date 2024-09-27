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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="text-center mb-4">
            <h1 class="display-4">Administrar Usuarios</h1>
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
                        <div class="card shadow-sm h-100">
                            <div class="card-body d-flex justify-content-between align-items-center">
                            <p id="userEmail-<?php echo $admin->getOwnerId(); ?>"><strong>Email:</strong> <?php echo $admin->getEmail(); ?></p>
                                <form action="<?php echo FRONT_ROOT ?>User/goEditView" method="POST">
                                    <input type="hidden" name="email" value="<?php echo $admin->getEmail(); ?>">
                                    <input type="hidden" name="role" value="1">
                                    <button type="submit" class="btn btn-warning btn-sm">
                                        <i class="bi bi-pencil-fill"></i> Modificar
                                    </button>
                                </form>
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
                        <div class="card shadow-sm h-100">
                            <div class="card-body d-flex justify-content-between align-items-center ">
                                <p id="userEmail-<?php echo $owner->getOwnerId(); ?>"><strong>Email:</strong> <?php echo $owner->getEmail(); ?></p>
                                <form action="<?php echo FRONT_ROOT ?>User/goEditView" method="POST">
                                    <input type="hidden" name="email" value="<?php echo $owner->getEmail(); ?>">
                                    <input type="hidden" name="role" value="2">
                                    <button type="submit" class="btn btn-warning ">
                                        <i class="bi bi-pencil-fill"></i> Modificar
                                    </button>
                                </form>
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
                        <div class="card shadow-sm h-100">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <p id="userEmail-<?php echo $keeper->getKeeperId(); ?>"><strong>Email:</strong> <?php echo $keeper->getEmail(); ?></p>
                                <form action="<?php echo FRONT_ROOT ?>User/goEditView" method="POST">
                                    <input type="hidden" name="email" class="userEmail" value="<?php echo $keeper->getEmail(); ?>">
                                    <input type="hidden" name="role" value="3">
                                    <button type="submit" class="btn btn-warning btn-sm">
                                        <i class="bi bi-pencil-fill"></i> Modificar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <hr>
        </section>
        <div class="container">
        <div id="pagination" class="mt-4"></div>

        </div>
    </div>

   
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</body>
<script>
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
            
            // Muestra o oculta la secicon en base a la visiblidad del col
            section.style.display = visibleCols ? '' : 'none';
        });
    });
});
</script>
</html>
