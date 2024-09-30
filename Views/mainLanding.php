<?php
namespace Views;
// include("header.php");
// require_once(VIEWS_PATH."validate-session.php");
// include("footer.php");
?>

<!doctype html>
<html lang="en" class="h-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        body {
            background-color: #E0dfff;
        }
        .cover-container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
        }
        .btn-custom {
            background-color: #B1adfb;
            border: none;
            transition: background-color 0.3s;
        }
        .btn-custom:hover {
            background-color: #a39cdf;
        }
        footer {
            font-size: 0.9rem;
        }
    </style>
</head>
<body class="d-flex h-100 text-center text-black">
    
<div class="cover-container d-flex w-100  p-4 mx-auto flex-column">
    <header class="mb-auto">
        <div>
            <h3 class="float-md-start mb-0">PET HERO</h3>
        </div>
    </header>

    <main class="px-3">
        <h1 class="display-4">Pet Hero</h1>
        <p class="lead">The best place to let your pets while you're out working or traveling</p>
        <div class="container col-lg-6 d-flex justify-content-center mt-4">
            <button type="button" class="btn btn-custom me-3 p-4">
                <a href="<?php echo FRONT_ROOT?>User/newOwner" class="text-decoration-none text-white">Sign-up Owner</a>
            </button>
            <button type="button" class="btn btn-custom me-3 p-4">
                <a href="<?php echo FRONT_ROOT?>Views/loginUser.php" class="text-decoration-none text-white">Login</a>
            </button>
            <button type="button" class="btn btn-custom p-4">
                <a href="<?php echo FRONT_ROOT?>User/newKeeper" class="text-decoration-none text-white">Sign-up Keeper</a>
            </button>
        </div>
    </main>

    <footer class="mt-auto text-black-50">
        <p>&copy; PET HERO 2022 - 2024.</p>
    </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkEdd4t5iF7GA8qlFh59tOaC7UuVj2D7L5H3qjs6MD2T+6q9g0Xk" crossorigin="anonymous"></script>
    
</body>
</html>
