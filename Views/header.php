<?php namespace Views; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PetHeros</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        body {
            background-color: white;
        }
        header {
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .btn-custom {
            color: #007BFF;
            border: 2px solid #007BFF;
            transition: background-color 0.3s, color 0.3s;
        }
        .btn-custom:hover {
            background-color: #007BFF;
            color: #fff;
        }
        .logo-container {
            width: 50%;
        }
    </style>
</head>
<body>
    <div class="container">
        <header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
            <a href="#" class="d-flex align-items-center col-md-3 mb-2 mb-md-0 text-dark text-decoration-none logo-container">
                <img src="<?php echo '/FinalProject4/' ?>logoOwner.svg" alt="Logo Pet" class="img-fluid">
            </a>
            <div class="col-lg-6 d-flex justify-content-center justify-content-md-left mt-3 mt-md-0">
                <button type="button" class="btn btn-custom me-3 p-2">
                    <a href="<?php echo '/FinalProject4/' ?>User/addOwnerView" class="text-decoration-none">Sign-up Owner</a>
                </button>
                <button type="button" class="btn btn-custom me-3 p-2">
                    <a href="<?php echo '/FinalProject4/' ?>Views/loginUser.php" class="text-decoration-none">Login</a>
                </button>
                <button type="button" class="btn btn-custom p-2">
                    <a href="<?php echo '/FinalProject4/' ?>User/addKeeperView" class="text-decoration-none">Sign-up Keeper</a>
                </button>
            </div>
        </header>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkEdd4t5iF7GA8qlFh59tOaC7UuVj2D7L5H3qjs6MD2T+6q9g0Xk" crossorigin="anonymous"></script>
</body>
</html>
