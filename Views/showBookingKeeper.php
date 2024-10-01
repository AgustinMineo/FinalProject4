<?php
namespace Views;
require_once("validate-session.php");
require_once(VIEWS_PATH."keeperNav.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
     <meta charset="UTF-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Pet Hero</title>
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
                              <th>Pet</th>
                              <th>Total value</th>
                              <th>Reserva</th>
                              <th>Status</th>
                              <th>Aceptar</th>
                              <th>Rechazar</th>
                         </thead>
                         <tbody>
                              <?php
                              if($bookingList){
                                   foreach($bookingList as $booking)
                                   {
                                        ?>
                                             <tr class="
                                        <?php 
                                        if($booking->getStatus() == 1 ){echo "bg-secondary";}
                                        elseif($booking->getStatus() == 2){echo "bg-danger";}
                                        elseif($booking->getStatus() == 8){echo "bg-danger";}
                                        elseif($booking->getStatus() == 3){echo "bg-primary";}
                                        elseif($booking->getStatus() == 4){echo "bg-primary";}
                                        else{echo "bg-success";}
                                        ?> 
                                        
                                        table  table-hover table align-middle text-center text-white" >
                                                  <td><?php echo $booking->getBookingID(); $value=$booking->getBookingID();?></td>
                                                  <td><?php $date=date_create($booking->getStartDate()); echo date_format($date,"d/m/Y"); ?></td>
                                                  <td><?php $date=date_create($booking->getEndDate()); echo date_format($date,"d/m/Y"); ?></td>
                                                  <th><button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#petModal<?php echo $booking->getPetID()->getPetID(); ?>"><?php echo $booking->getPetID()->getPetName() ?></button></th>
                                                  <td><?php echo $booking->getTotalValue()?></td>
                                                  <td><?php echo $booking->getTotalValue() * 0.5?></td>
                                                  <td><?php if($booking->getStatus() == '1'){echo "<h6>Pending</h6>";} elseif($booking->getStatus() == 2){echo "<h6>Rejected</h6>";}elseif($booking->getStatus() == 3){echo "<h6>Waiting for payment</h6>";}elseif($booking->getStatus() == 4){echo "<h6>Waiting for confirmation</h6>";}elseif($booking->getStatus() == 5){echo "<h6>Confirmed</h6>";}elseif($booking->getStatus() == 6){echo "<h6>Finish</h6>";}elseif($booking->getStatus() == 7){echo "<h6>Completed</h6>";}else{echo "<h6>Overdue</h6>";}?></td>
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
                                             <!-- Modal pet -->
                                        <div class="modal fade" id="petModal<?php echo $booking->getPetID()->getPetID(); ?>" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                  <div class="modal-header">
                                                       <h5 class="modal-title" id="modalLabel">Detalles de <?php echo $booking->getPetID()->getPetName(); ?> </h5>
                                                       <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                  </div>
                                                  <div class="modal-body bg-light d-flex flex-nowarp align-items-center justify-content-center">
                                                  <div class="card m-3" style="width: 75%;">
                                                  
                                                  <?php 
                                                  if ($image = $booking->getPetID()->getPetImage()) {
                                                       $imageData = base64_encode(file_get_contents($image));
                                                       echo '<img src="data:image/jpeg;base64,'.$imageData.'" class="card-img-top" alt="Imagen de '. $booking->getPetID()->getPetName() .'">';
                                                  } else {
                                                       echo '<img src="'.IMG_PATH.'default-pet.jpg" class="card-img-top" alt="Imagen por defecto">';
                                                  }
                                                  ?>
                                                  <div class="card-body">
                                                       <!-- Nombre de la mascota -->
                                                       <h5 class="card-title text-center"><?php echo $booking->getPetID()->getPetName(); ?></h5>
                                                       <hr>
                                                       <ul class="list-group list-group-flush">
                                                            
                                                            <li class="list-group-item"><strong>Breed:</strong> <?php echo $booking->getPetID()->getPetBreedByText(); ?></li>
                                                            
                                                            <li class="list-group-item"><strong>Weight:</strong> <?php echo $booking->getPetID()->getPetWeight(); ?> kg</li>
                                                            
                                                            <li class="list-group-item"><strong>Size:</strong> <?php echo $booking->getPetID()->getPetSize(); ?></li>
                                                            
                                                            <li class="list-group-item"><strong>Age:</strong> <?php echo $booking->getPetID()->getPetAge(); ?> years</li>

                                                            <li class="list-group-item"><strong>Detalles:</strong> <?php echo $booking->getPetID()->getPetDetails(); ?></li>
                                                       </ul>
                                                       <hr>
                                                       <div class="text-center">
                                                            
                                                            <h6>Vaccination Plan</h6>
                                                            <?php 
                                                            if (!$booking->getPetID()->getPetVaccinationPlan()) {
                                                                 echo "<p>Not available</p>";
                                                            } else {
                                                                 $VaccinationPlan = $booking->getPetID()->getPetVaccinationPlan();
                                                                 $VaccinationPlanData = base64_encode(file_get_contents($VaccinationPlan));
                                                                 echo '<img src="data:image/jpeg;base64,'.$VaccinationPlanData.'" class="img-fluid" alt="Plan de vacunación">';
                                                            }
                                                            ?>
                                                       </div>
                                                       <hr>
                                                       <div class="text-center">
                                                            
                                                            <h6>Video</h6>
                                                            <?php 
                                                            if (!$booking->getPetID()->getPetVideo()) {
                                                                 echo "<p>Not available</p>";
                                                            } else {
                                                                 echo '<video width="100%" height="240" controls>';
                                                                 echo '<source src="' . FRONT_ROOT . $booking->getPetID()->getPetVideo() . '" type="video/mp4">';
                                                                 echo 'Your browser does not support the video tag.';
                                                                 echo '</video>';
                                                            }
                                                            ?>
                                                       </div>
                                                  </div>
                                                  </div>

                                                  </div>

                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Modal pet -->
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
