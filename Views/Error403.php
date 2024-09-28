<?php 
namespace Views;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <title>403 - Permisos Insuficientes</title>    
    <script>
        var userRole = <?php echo isset($userRole) ? json_encode($userRole) : 0; ?>; // Asignar el rol o undefined
    </script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark" id="navbar">
        <span class="navbar-text">
            <strong>Pet Hero</strong>
        </span>
        <ul class="navbar-nav ms-auto">
            <li class="nav-item">
                <a class="nav-link" href="<?php echo '/FinalProject4/' ?>index.php">Inicio</a>
            </li>          
        </ul>
    </nav> 

    <div class="container my-5 text-center">
        <div class="container">
            <h1 class="display-4 text-danger">¡Acceso Denegado!</h1>
            <p class="lead">Lo sentimos, no tienes permiso para acceder a esta página.</p>
            <img src="<?php echo '/FinalProject4/' ?>Views/img/403Error.webp" alt="Doggo looking sad" class="w-50 mb-4">
        </div>
        <?php if(!$userRole):?>
            <a href="<?php echo '/FinalProject4/' ?>index.php" class="btn btn-primary">Volver a la Página Principal</a>
        <?php endif;?>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (userRole === 0) {
                navbar.classList.add('d-block');
            } else {
                navbar.classList.add('d-none'); 
            }
        });
    </script>
</body>
</html>
