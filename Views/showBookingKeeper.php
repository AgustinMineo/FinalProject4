<?php
namespace Views;
require_once(VIEWS_PATH."keeperNav.php");
?>
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
                         <th>Pet id</th>
                         <th>Total value</th>
                         <th>Status</th>
                         <th>Opcion</th>
                    </thead>
                    <tbody>
                         <?php
                              foreach($bookingListKeeper as $booking)
                              {
                                   //$value=$booking->getBookingID();
                                   ?>
                                        <tr class=" table table-info table-hover table align-middle" >
                                             <td><?php echo $booking->getBookingID(); $value=$booking->getBookingID();?></td>
                                             <td><?php echo $booking->getFirstDate() ?></td>
                                             <td><?php echo $booking->getLastDate() ?></td>
                                             <td><?php echo $booking->getKeeperID() ?></td>
                                             <td><?php echo $booking->getPetID()?></td>
                                             <td><?php echo $booking->getTotalValue()?></td>
                                             <td><?php if($booking->getStatus() == '1'){echo "<h6>Pending</h6>";} elseif($booking->getStatus() == 2){echo "<h6>Rejected</h6>";}elseif($booking->getStatus() == 3){echo "<h6>Waiting for payment</h6>";}elseif($booking->getStatus() == 4){echo "<h6>Confirmed</h6>";}else{echo "<h6>Finish</h6>";}?></td>
                                             <td><form action='<?php echo FRONT_ROOT ?> Booking/updateBookingStatus' method='post'><?php if($booking->getStatus() == '1'){ echo "<div class='d-flex'><input type='hidden' name='id' value='$value'><input type='hidden' name='status' value='3'>
                                                  <button type='submit' class='btn btn-outline-primary w-auto p-1 m-1'>Aceptar</button>
                                                  </div><div> <div class='d-flex'><input type='hidden' name='id' value='$value'><input type='hidden' name='status' value='2'><button type='submit' class='btn btn-outline-danger w-auto p-1 m-1'>Rechazar</button></div>";}?></form></td>
                                        </tr>
                                        
                                   <?php
                              }
                         ?>
                         </tr>
                    </tbody>
               </table>
          </div>
     </section>
</main>
<script>
    // function updateStatus(valor,data){
   //      // <//?php $valor = valor; $data = data;
          // $booking->updateupdateBookingStatus($valor,$data)?//>;
    // }
</script>