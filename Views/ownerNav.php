<?php
namespace Views;
include("nav.php");
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/album/">
    </head>
<body>
    
    <header>
        <span>Bienvenido <strong><?php echo $_SESSION["loggedUser"]->getEmail() ?></strong> </span>
        <li class="nav-item">
               <a class="nav-link" href="<?php echo FRONT_ROOT ?>Views/pet-add.php">New pet</a>
          </li>   
          <li class="nav-item">
               <a class="nav-link" href="<?php echo FRONT_ROOT ?>Pet/showPets">Show my pet</a>
          </li>
          <li class="nav-item">
               <a class="nav-link" href="<?php echo FRONT_ROOT ?>User/showKeepers">Show Keepers</a>
          </li> 
      </header>
      
  </body>
</html>