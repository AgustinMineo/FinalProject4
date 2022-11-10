<?php
namespace Views;
?>

<!doctype html>
<html lang="en">
    <head>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
<!-- CSS only -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
    </head>
<body>
    
    <header>
        <div class="container d-flex ">
            <span>Bienvenido <strong><?php echo $_SESSION["loggedUser"]->getEmail() ?></strong> </span>
            <div class="container d-flex flex-nowrap align-content-center flex-nowrap h-1 p-1 w-auto ">
                    <li class="nav-item w-25 m-5 border border-dark">
                        <a class="nav-link" href="<?php echo FRONT_ROOT ?>Views/updateAvailabilityDays.php">Update Availability Days</a>
                    </li>   
                    <li class="nav-item w-25 m-5 border border-dark">
                        <a class="nav-link" href="<?php echo FRONT_ROOT ?>Booking/showBookings">Show reservations</a>
                    </li>
                    <li class="nav-item w-25 m-5 border border-dark">
                        <a class="nav-link" href="<?php echo FRONT_ROOT ?>User/logOut">Log Out</a>
                    </li>    
            </div>
        </div>
      </header>
      
  </body>
</html>