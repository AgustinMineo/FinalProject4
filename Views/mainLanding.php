<?php
namespace Views;
//include("header.php");
//require_once(VIEWS_PATH."validate-session.php");
//include("footer.php");
?>

<!doctype html>
<html lang="en" class="h-100">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

  </head>
  <body style="background:#E0dfff;" class="d-flex h-100 text-center text-black ">
    
<div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column ">
  <header class="mb-auto">
    <div>
      <h3 class="float-md-start mb-0">PET HERO</h3>
      <nav class="nav nav-masthead justify-content-center float-md-end">
      
      </nav>
    </div>
  </header>

  <main class="px-3">
    <h1>Pet Hero</h1>
    <p class="lead">The best place to let your pets while you're out working or traveling</p>
    <p class="lead">
    <div class=" container col-lg-6 d-flex justify-content-center w-75 p-5">
        <button type="button" class="btn border border-1 p-4 " style="background:#B1adfb">
          <a href="<?php echo FRONT_ROOT?>User/addOwnerView" class="text-decoration-none ">Sign-up owner</a>
        </button>
        <button type="button" class="btn border border-1 p-4" style="background:#B1adfb">
          <a href="<?php echo FRONT_ROOT?>Views/loginUser.php" class="text-decoration-none">Login</a>
        </button>
        <button type="button" class="btn border border-1 p-4" style="background:#B1adfb">
          <a href="<?php echo FRONT_ROOT?>User/addKeeperView" class="text-decoration-none">Sign-up Keeper</a>
          </button>
        </div>
    </p>
  </main>

  <footer class="mt-auto text-white-50">
    <p>PET HERO 2022-2022.</p>
  </footer>
</div>


    
  </body>
</html>