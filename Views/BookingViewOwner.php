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
    <style>
     
    </style>
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
                                             <td><?php echo $booking->getBookingID(); $value=$booking->getBookingID();?></td>
                                             <td><?php echo $booking->getFirstDate() ?></td>
                                             <td><?php echo $booking->getLastDate() ?></td>
                                             <td><?php echo $booking->getAmountReservation() ?></td> <!-- CAMBIAR A OWNER NAME PARA SABER DUEÑO DEL PERRO VER -->
                                             <td><?php echo $booking->getTotalValue()?></td>
                                             <td><?php if($booking->getStatus() == '1'){echo "<h6>Pending</h6>";} elseif($booking->getStatus() == 2){echo "<h6>Rejected</h6>";}elseif($booking->getStatus() == 3){echo "<h6>Waiting for payment</h6>";}elseif($booking->getStatus() == 4){echo "<h6>Confirmed</h6>";}else{echo "<h6>Finish</h6>";}?></td>
                                             <td><form action='<?php echo FRONT_ROOT ?> Payment/generatePaymentBooking' method='post'><?php if($booking->getStatus() == '3'){ echo "

                                                       <button type='button' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#exampleModal'>
                                                       Realizar Pago
                                                       </button>

                                                       <!-- Modal -->
                                                       <div class='modal fade' id='exampleModal' tabindex='-1' aria-labelledby='exampleModalLabel' aria-hidden='true'>
                                                       <div class='modal-dialog modal-xl'>
                                                       <div class='modal-content '>
                                                            <div class='modal-header'>
                                                            <h5 class='modal-title' id='exampleModalLabel'>Modal title</h5>
                                                            <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                                            </div>
                                                            <div class='modal-body'>
                                                            <div class=''>
                                                            <div class='wrapper'>
                                                            <div class='container text-black'>
                                                                 <article class='part card-details'>
                                                                      <h1 style='color:black;'>
                                                                           Credit Card Details
                                                                      </h1>
                                                                      <form action='' if='cc-form' autocomplete='off'>
                                                                           <div class='group card-number'>
                                                                                <label for='first'>Card Number</label>
                                                                                <input type='text' id='first' class='cc-num' type='text' maxlength='4' placeholder='&#9679;&#9679;&#9679;&#9679;'>
                                                                                <input type='text' id='second' class='cc-num' type='text' maxlength='4' placeholder='&#9679;&#9679;&#9679;&#9679;'>
                                                                                <input type='text' id='third' class='cc-num' type='text' maxlength='4' placeholder='&#9679;&#9679;&#9679;&#9679;'>
                                                                                <input type='text' id='fourth' class='cc-num' type='text' maxlength='4' placeholder='&#9679;&#9679;&#9679;&#9679;'>
                                                                           </div>
                                                                           <div class='group card-name'>
                                                                                <label for='name'>Name On Card</label>
                                                                                <input type='text' id='name' class='' type='text' maxlength='20' placeholder='Name Surname'>
                                                                           </div>
                                                                           <div class='group card-expiry'>
                                                                                <div class='input-item expiry'>
                                                                                <label for='expiry'>Expiry Date</label>
                                                                                <input type='text' class='month' id='expiry' placeholder='02'>
                                                                                <input type='text' class='year' id='' placeholder='2022'>
                                                                                </div>
                                                                                <div class='input-item csv'>
                                                                                <label for='csv'>CSV No.</label><a href=''>?</a>
                                                                                <input type='text' class='csv'>
                                                                                </div>
                                                                           </div>
                                                                           <div class='grup submit-group'>
                                                                                <span class='arrow'></span>
                                                                                <input type='' 
                                                                                <input type='submit' class='submit' value='Continue to payment'>
                                                                           </div>
                                                                      </form>
                                                                 </article>
                                                                 <div class='part bg'></div>
                                                            </div>
                                                            </div></div>
                                                            </div>
                                                            <div class='modal-footer'>
                                                            <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>
                                                            </div>
                                                       </div>
                                                       </div>
                                                       </div>
                                                  
                                                  
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