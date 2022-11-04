<?php
namespace Views;
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
        <div style="width: 10%; background:gray; color:gray;">
            <li class="nav-item">
                <a class="nav-link" href="<?php echo FRONT_ROOT ?>Views/updateAvailabilityDays.php">Update Availability Days</a>
            </li>   
            <li class="nav-item">
                <a class="nav-link" href="<?php echo FRONT_ROOT ?>Booking/showBookings">Shop reservations</a>
            </li>  
        </div>
      </header>
      
  </body>
</html>