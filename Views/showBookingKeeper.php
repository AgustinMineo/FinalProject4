<?php
namespace Views;
require_once(VIEWS_PATH."keeperNav.php");
require_once(VIEWS_PATH."validate-session.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
     <meta charset="UTF-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Document</title>
</head>
<body>
     <main class="py-5">
          <section id="listado" class="mb-5">
               <div class="container">
                    <h2 class="mb-4">Listado de reservas</h2>
                    <table class="table bg-light-alpha">
                         <thead>
                         <th>Booking id</th>
                              <th>First date</th>
                              <th>Last Date</th>
                              <th>KeeperID</th>
                              <th>Pet Name</th>
                              <th>Total value</th>
                              <th>Status</th>
                              <th>Aceptar</th>
                              <th>Rechazar</th>
                         </thead>
                         <tbody>
                              <?php
                              if($bookingListKeeper){
                                   foreach($bookingListKeeper as $booking)
                                   {
                                        ?>
                                             <tr class=" table table-info table-hover table align-middle" >
                                                  <td><?php echo $booking->getBookingID(); $value=$booking->getBookingID();?></td>
                                                  <td><?php $date=date_create($booking->getFirstDate()); echo date_format($date,"d/m/Y"); ?></td>
                                                  <td><?php $date=date_create($booking->getLastDate()); echo date_format($date,"d/m/Y"); ?></td>
                                                  <td><?php echo $booking->getKeeperID() ?></td> <!-- CAMBIAR A OWNER NAME PARA SABER DUEÑO DEL PERRO VER -->
                                                  <td><?php echo $booking->getPetID()?></td>
                                                  <td><?php echo $booking->getTotalValue()?></td>
                                                  <td><?php if($booking->getStatus() == '1'){echo "<h6>Pending</h6>";} elseif($booking->getStatus() == 2){echo "<h6>Rejected</h6>";}elseif($booking->getStatus() == 3){echo "<h6>Waiting for payment</h6>";}elseif($booking->getStatus() == 4){echo "<h6>Waiting for confirmation</h6>";}elseif($booking->getStatus() == 5){echo "<h6>Confirmed</h6>";}else{echo "<h6>Finish</h6>";}?></td>
                                                  <td><form action='<?php echo FRONT_ROOT ?> Booking/updateBookingStatus' method='post'><?php if($booking->getStatus() == '1'){ echo "<div class='d-flex'><input type='hidden' name='id' value='$value'><input type='hidden' name='status' value='3'>
                                                       <button type='submit' class='btn btn-outline-primary w-auto p-1 m-1'>Aceptar</button>
                                                       </div>";}elseif($booking->getStatus() == '4'){echo "<div class='d-flex'><input type='hidden' name='id' value='$value'><input type='hidden' name='status' value='5'>
                                                            <button type='submit' class='btn btn-outline-primary w-auto p-1 m-1'>Confirmar</button>";}?>
                                                       </form></td>
                                                       <td>
                                                       <form action='<?php echo FRONT_ROOT ?> Booking/updateBookingStatus' method='post'><?php if($booking->getStatus() == '1'){ echo "<div class='d-flex'>
                                                            <input type='hidden' name='id' value='$value'><input type='hidden' name='status' value='2'>
                                                            <button type='submit' value='$value' class='btn btn-outline-danger w-auto p-1 m-1'>Rechazar</button></div>";}?></form></td>
                                             </tr>
                                             
                                        <?php
                                   }}else{
                                        echo "<div class='alert alert-danger'>You don't have any booking for the moment!</div>";
                                   }
                              ?>
                              </tr>
                         </tbody>
                    </table>
               </div>
          </section>
     </main>
</body>
</html>
