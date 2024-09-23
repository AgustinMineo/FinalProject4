<?php
namespace Views;
require_once("validate-session.php");
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

<link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
     <style>
input {
  border: 0;
  width: 1px;
  height: 1px;
  overflow: hidden;
  position: absolute !important;
  clip: rect(1px 1px 1px 1px);
  clip: rect(1px, 1px, 1px, 1px);
  opacity: 0;
}
label {
  position: relative;
  float: right;
  color: #C8C8C8;
}
label:before {
  margin: 5px;
  content: "\f005";
  font-family: FontAwesome;
  display: inline-block;
  font-size: 1.5em;
  color: #ccc;
  -webkit-user-select: none;
  -moz-user-select: none;
  user-select: none;
}
input:checked ~ label:before {
  color: #FFC107;
}
label:hover ~ label:before {
  color: #ffdb70;
}
label:hover:before {
  color: #FFC107;
}
     </style>
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
                         <th>Cuidador</th>
                         <th>Pet Name</th>
                         <th>First date</th>
                         <th>Last Date</th>
                         <th>Reserva a pagar</th>
                         <th>Total value</th>
                         <th>Status</th>
                         <th>Actividad</th>
                    </thead>
                    <tbody>
                         <div class="container d-flex flex-wrap flex-row w-100 ">
                         <?php
                         if($bookingList){
                              foreach($bookingList as $booking)
                              {
                                   
                                   ?> 
                                        <tr class="
                                        <?php 
                                        if($booking->getStatus() == 1 ){echo "bg-primary";}
                                        elseif($booking->getStatus() == 2){echo "bg-danger";}
                                        elseif($booking->getStatus() == 8){echo "bg-danger";}
                                        elseif($booking->getStatus() == 3){echo "bg-primary";}
                                        elseif($booking->getStatus() == 4){echo "bg-primary";}
                                        else{echo "bg-success";}
                                        ?> 
                                        
                                        table  table-hover table align-middle text-center text-white" >
                                             
                                             <td><?php echo $value=$booking->getBookingID(); ?></td>
                                             
                                             <th><button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#keeperModal<?php echo $booking->getKeeperID()->getKeeperId(); ?>">Info cuidador</button></th>
                                             <th><button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#petModal<?php echo $booking->getPetID()->getPetID(); ?>"><?php echo $booking->getPetID()->getPetName() ?></button></th>
                                             <td><?php $date=date_create($booking->getStartDate()); echo date_format($date,"d/m/Y");  ?></td>
                                             <td><?php $date=date_create($booking->getEndDate()); echo date_format($date,"d/m/Y");  ?></td>
                                             <td>$<?php echo $booking->getAmountReservation() ?></td>
                                             <td>$<?php echo $booking->getTotalValue()?></td>
                                             <td><?php if($booking->getStatus() == '1'){echo "<h6>Pending</h6>";} elseif($booking->getStatus() == 2){echo "<h6>Rejected</h6>";}elseif($booking->getStatus() == 3){echo "<h6>Waiting for payment</h6>";}
                                             elseif($booking->getStatus() == 4){echo "<h6>Waiting for confirmation</h6>";}elseif($booking->getStatus() == 5){echo "<h6>Confirmed</h6>";}
                                             elseif($booking->getStatus() == 6){echo "<h6>Finish</h6>";}elseif($booking->getStatus() == 7){echo "<h6>Completed</h6>";}else{echo "<h6>Overdue</h6>";}?></td>
                                             <td><form action='<?php echo FRONT_ROOT ?>Review/newReview' method='post'><?php if($booking->getStatus() == '6'){ echo " 
                                                     <button type='button' class='btn btn-secondary' data-bs-toggle='modal' data-bs-target='#exampleModal$value' data-bs-whatever='@getbootstrap'>New Review</button>
                                                     <div class='modal fade' id='exampleModal$value' tabindex='-1' aria-labelledby='exampleModalLabel' aria-hidden='true'>
                                                     <div class='modal-dialog modal-xl'>
                                                     <div class='modal-content'>
                                                          <div class='modal-header'>
                                                          <h5 class='modal-title'></h5>
                                                          <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                                          </div>
                                                          <div class='modal-body'>
                                                         
                                                          <div class='wrapper'>
                                                          <div class='container text-black'>
                                                  <article class='part card-details'>
                                                       <div class='modal-body'>
                                                       <form action='FRONT_ROOT.Booking/generatePaymentBooking' method='post'>
                                                       <div class='w-100 d-flex flex-wrap text-center'>Your Valoration</div>
                                                       <div class='d-flex flex-start'>
                                                            <div style='position:left;'>
                                                                <input type='radio' id='st5' name='rate' value='5' />
                                                                 <label for='st5'></label>
                                                                 <input type='radio' id='st4' name='rate' value='4' />
                                                                 <label for='st4'></label>
                                                                 <input type='radio' id='st3' name='rate' value='3'/>
                                                                 <label for='st3'></label>
                                                                 <input type='radio' id='st2' name='rate' value='2' />
                                                                 <label for='st2'></label>
                                                                 <input type='radio' id='st1' name='rate' value='1' checked/>
                                                                 <label for='st1'></label>
                                                            </div>
                                                       </div>
                                                                 <input type='hidden' name='booking' value='$value'>
                                                                 <div class='d-flex flex-wrap w-100'>
                                                                   <h6 for='message-text' >Feedback</h6>
                                                                   <textarea class='form-control' name='feedback' id='feedback' maxlength='255'></textarea>
                                                                 </div>
                                                       </div>
                                                  </article>
                                                                 
                                                                 </div>
                                                               
                                                                 </div>
                                                                 </div>
                                                                 <div class='modal-footer d-flex flex-wrap justify-content-center alight-items-center'>
                                                                 <button type='button' class='btn btn-danger' data-bs-dismiss='modal'>Close</button>
                                                                 <button type='submit' class='btn btn-success' data-bs-dismiss='modal'>Send Review</button>
                                                                 </div>
                                                            
                                                     </div>
                                                     </div>
                                                     </div>
                                                  ";}?></form></td>
                                             <td><form action='<?php echo FRONT_ROOT ?> Payment/generatePaymentBooking' method='post'>
                                             <?php if($booking->getStatus() == '3'){ echo "
                                                      <input type='hidden' name='booking' value='$value'>
                                                      <button type='submit' class='btn btn-secondary' data-bs-toggle='modal' data-bs-target='#'>
                                                       Realizar Pago
                                                       </button>
                                                  ";}?></form></td>
                                        </tr>
                                        <!-- Modal keeper -->
                                        <div class="modal fade" id="keeperModal<?php echo $booking->getKeeperID()->getKeeperId(); ?>" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalLabel">Detalles del cuidador</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body bg-light">
                                                       <p><strong>Correo:</strong> <?php echo $booking->getKeeperID()->getEmail(); ?></p>
                                                       <p><strong>Nombre completo:</strong> <?php echo $booking->getKeeperID()->getLastName();?> <?php echo $booking->getKeeperID()->getfirstName();?></p>
                                                       <p><strong>Descripción:</strong><?php echo $booking->getKeeperID()->getDescription();?></p>
                                                       <p><strong>Fecha de nacimiento:</strong> <?php echo $booking->getKeeperID()->getbirthDate(); ?></p>
                                                       <p><strong>Celular:</strong> <?php echo $booking->getKeeperID()->getCellPhone();?></p>
                                                       <p><strong>Tamaño del perro:</strong> <?php echo $booking->getKeeperID()->getAnimalSize(); ?></p>
                                                       <p><strong>Precio por dia:</strong> <?php echo $booking->getKeeperID()->getPrice(); ?></p>
                                                       <p><strong>CBU:</strong> <?php echo $booking->getKeeperID()->getCBU(); ?></p>
                                                       <p><strong>Putos:</strong> <?php echo $booking->getKeeperID()->getPoints(); ?></p>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Modal Keeper -->
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