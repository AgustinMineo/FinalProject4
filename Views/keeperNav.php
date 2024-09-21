<?php
namespace Views;
require_once("validate-session.php");
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
        
            <div class="navbar navbar-dark shadow-sm">
                <div class="container d-flex flex-nowrap justify-content-center mt-2">
                    <ul style="list-style-type: none; box-shadow: 5px 6px 6px 2px #e9ecef;transform: scale(1.1);" class="d-flex flex-nowrap py-2">
                        <li class="nav-item m-3" style="width:auto; border-style:solid; border-width:2px; border-color:white; ">
                        <a class="nav-link text-black  list-group-item-action" href="<?php echo FRONT_ROOT ?>Keeper/showCurrentKeeper">My profile</a>
                        </li>
                        <li class="nav-item" style="width:auto; border-style:solid; border-width:2px; border-color:white; ">
                            <a class="nav-link m-3 " href="<?php echo FRONT_ROOT ?>Keeper/updateDaysAvailables">Update Availability Days</a>
                        </li>   
                        <li class="nav-item" style="width:auto; border-style:solid; border-width:2px; border-color:white; ">
                            <a class="nav-link m-3" href="<?php echo FRONT_ROOT ?>Booking/showBookings">Show reservations</a>
                        </li>
                        <li class="nav-item " style="width:auto; border-style:solid; border-width:2px; border-color:white; ">
                            <a class="nav-link m-3" href="<?php echo FRONT_ROOT ?>User/logOut">Log Out</a>
                        </li>  
                    </ul>  
                </div>
            </div>
       
      </header>
      
  </body>
</html>