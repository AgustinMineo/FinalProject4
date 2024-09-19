<?php
namespace Views;
require_once(VIEWS_PATH."validate-session.php");
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
        <!-- CSS only -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    </head>
<body>
    <header>
    <div class="navbar navbar-dark shadow-sm">
      <div class="container d-flex flex-nowrap justify-content-center mt-3">
        <ul style="list-style-type: none; box-shadow: 5px 6px 6px 2px #e9ecef;transform: scale(1.1);" class="d-flex flex-nowrap py-2">
              <li class="nav-item m-2" style="width:auto; border-style:solid; border-width:2px; border-color:white; ">
                  <a class="nav-link text-black  list-group-item-action" href="<?php echo FRONT_ROOT ?>Owner/showCurrentOwner">My profile</a>
              </li>
              <li class="nav-item m-2" style="width:auto; border-style:solid; border-width:2px; border-color:white; ">
                  <a class="nav-link text-black  list-group-item-action" href="<?php echo FRONT_ROOT ?>Pet/goNewPet">New pet</a>
              </li>   
              <li class="nav-item m-2" style="width:auto; border-style:solid; border-width:2px; border-color:white; ">
                  <a class="nav-link text-black  list-group-item-action" href="<?php echo FRONT_ROOT ?>Pet/showPets">Show my pet</a>
              </li>
              <li class="nav-item m-2" style="width:auto; border-style:solid; border-width:2px; border-color:white; ">
                  <a class="nav-link text-black  list-group-item-action" href="<?php echo FRONT_ROOT ?>Keeper/showKeepers">Show Keepers</a>
              </li> 
              <li class="nav-item m-2" style="width:auto; border-style:solid; border-width:2px; border-color:white; ">
                  <a class="nav-link text-black  list-group-item-action" href="<?php echo FRONT_ROOT ?>Booking/showBookingsOwner">Show Bookings</a>
              </li> 
              <li class="nav-item m-2" style="width:auto; border-style:solid; border-width:2px; border-color:white; ">
                  <a class="nav-link text-black  list-group-item-action" href="<?php echo FRONT_ROOT ?>Booking/searchBooking">Booking with a Keepers</a>
              </li>
              <li class="nav-item m-2" style="width:auto; border-style:solid; border-width:2px; border-color:white; ">
                  <a class="nav-link text-black  list-group-item-action" href="<?php echo FRONT_ROOT ?>User/logOut">Log Out</a>
              </li> 
        </ul>
          </div>
    </div>
  </div>
</header>
  </body>
</html>