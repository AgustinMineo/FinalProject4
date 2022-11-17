<?php
namespace Views;
require_once(VIEWS_PATH."ownerNav.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
<!-- CSS only -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">

    <title>Booking Owner</title>
</head>
<body>
<main class="py-5">
     <section id="listado" class="mb-5">
          <div class="container">
               <h2 class="mb-4">Listado de reservas</h2>
               <table class="table bg-light-alpha text-center">
                    <thead>
                    <th>Booking id</th>
                         <th>First date</th>
                         <th>Last Date</th>
                         <th>Reserva a pagar</th>
                         <th>Total value</th>
                         <th>Status</th>
                         <th>Pagar</th>
                    </thead>
                    <tbody>
                         <div class="container d-flex flex-wrap flex-row w-100 bg-light ">
                         <?php
                         if($bookingListKeeper){
                              foreach($bookingListKeeper as $booking)
                              {
                                   
                                   ?>
                                        <tr class=" table bg-secondary table-hover table align-middle text-center text-white" >
                                             <td><?php echo $value=$booking->getBookingID(); ?></td>
                                             <td><?php $date=date_create($booking->getFirstDate()); echo date_format($date,"d/m/Y");  ?></td>
                                             <td><?php $date=date_create($booking->getLastDate()); echo date_format($date,"d/m/Y");  ?></td>
                                             <td><?php echo $booking->getAmountReservation() ?></td> <!-- CAMBIAR A OWNER NAME PARA SABER DUEÑO DEL PERRO VER -->
                                             <td><?php echo $booking->getTotalValue()?></td>
                                             <td><?php if($booking->getStatus() == '1'){echo "<h6>Pending</h6>";} elseif($booking->getStatus() == 2){echo "<h6>Rejected</h6>";}elseif($booking->getStatus() == 3){echo "<h6>Waiting for payment</h6>";}elseif($booking->getStatus() == 4){echo "<h6>Waiting for confirmation</h6>";}elseif($booking->getStatus() == 5){echo "<h6>Confirmed</h6>";}else{echo "<h6>Finish</h6>";}?></td>
                                             <td><form action='<?php echo FRONT_ROOT ?> Payment/generatePaymentBooking' method='post'>
                                             <?php if($booking->getStatus() == '3'){ echo "
                                                      <input type='hidden' name='booking' value='$value'>
                                                      <button type='submit' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#'>
                                                       Realizar Pago
                                                       </button>
                                                  ";}?></form></td>
                                        </tr>
                                        
                                   <?php
                              }}else{
                                   echo "<div class='alert alert-danger'>No tiene bookings realizados!</div>";
                              }
                         ?>
                         </tr>
                         </div>
                    </tbody>
               </table>
          </div>
     </section>
</main>
</body>

</html>