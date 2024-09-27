<?php 
namespace Views;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <title>404 - Page Not Found</title>    
    <script>
        var userRole = <?php echo isset($userRole) ? json_encode($userRole) : 0; ?>; // Asignar el rol o undefined
    </script>
</head>
<body>
    <nav class="navbar navBar404 navbar-expand-lg d-flex navbar-dark bg-dark" id="navbar">
        <span class="navbar-text">
            <strong>Pet Hero</strong>
        </span>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="<?php echo '/FinalProject4/' ?>index.php">Home</a>
            </li>          
        </ul>
    </nav> 
    <div class="container my-5 text-center">
        <div class="container">
            <p class="lead">Oops! We can't find that page.</p>
        </div>
        <img src="<?php echo '/FinalProject4/' ?>Views/img/404image.webp" alt="Doggo looking confused" class="w-50">
        <br><br> 
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (userRole === 0) {
                navbar.classList.add('d-block');
            }else{
                navbar.classList.add('d-none'); 
            }
        });
    </script>
</body>
</html>
