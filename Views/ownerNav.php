<?php
namespace Views;
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
       
    </head>
<body>
    
    <header>
        <span>Bienvenido <strong><?php echo $_SESSION["loggedUser"]->getEmail() ?></strong> </span>
        <div style="width:auto; display:flex; flex-wrap:nowrap; background-color:gray; color:black; alight-items: center; justify-content:center;">
            <li class="nav-item" style="width:auto; border-style:solid; border-width:2px; border-color:white; ">
                <a class="nav-link" href="<?php echo FRONT_ROOT ?>Views/pet-add.php">New pet</a>
            </li>   
            <li class="nav-item" style="width:auto; border-style:solid; border-width:2px; border-color:white; ">
                <a class="nav-link" href="<?php echo FRONT_ROOT ?>Pet/showPets">Show my pet</a>
            </li>
            <li class="nav-item" style="width:auto; border-style:solid; border-width:2px; border-color:white; ">
                <a class="nav-link" href="<?php echo FRONT_ROOT ?>Keeper/showKeepers">Show Keepers</a>
            </li> 
            <li class="nav-item" style="width:auto; border-style:solid; border-width:2px; border-color:white; ">
                <a class="nav-link" href="<?php echo FRONT_ROOT ?>Views/searchAvailabilityDays.php">Show Keepers By Availability</a>
            </li> 
            <li class="nav-item" style="width:auto; border-style:solid; border-width:2px; border-color:white; ">
                <a class="nav-link" href="<?php echo FRONT_ROOT ?>Views/searchByDateBooking.php">Booking with a Keepers</a>
            </li>
            <li class="nav-item" style="width:auto; border-style:solid; border-width:2px; border-color:white; ">
                <a class="nav-link" href="<?php echo FRONT_ROOT ?>User/logOut">Log Out</a>
            </li>  
        </div>
      </header>
      
  </body>
</html>