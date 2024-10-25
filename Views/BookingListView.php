<?php
namespace Views;
require_once("validate-session.php");

$requiredRole=$userRole;
$alterRole=$userRole;
/*Para cambiar el alterRole y mostrar 1 vista u otra. SOLO ADMIN! */
if($userRole===1){
     if (isset($_POST['alterRole'])) {
          $alterRole = intval($_POST['alterRole']);  
     } else {
          $alterRole = $alterRole ?? $requiredRole; 
     }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
     <meta charset="UTF-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
     <link rel="stylesheet" href="./css/bookingList.css">
     <link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
     <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

     <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
     <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>

</head>
<style>
     .loader {
          position: absolute;
          top: 50%;
          left: 50%;
          transform: translate(-50%, -50%);
          width: 50px;
          height: 50px;
          border: 5px solid #f3f3f3; 
          border-top: 5px solid #3498db;
          border-radius: 50%;
          animation: spin 1s linear infinite;
          z-index: 10;
     }

     /* Animación del loader */
     @keyframes spin {
          0% { transform: rotate(0deg); }
          100% { transform: rotate(360deg); }
     }
     .rating-icons {
          display: flex;
          gap: 10px; 
     }

     .rating-label {
          cursor: pointer;
          font-size: 30px;
          transition: transform 0.3s;
     }

     input[type="radio"]:checked + .rating-label i {
          color: gold; 
          transform: scale(1.5);
     }

     .rating-label i {
          color: #ccc; 
     }

</style>
     <title>Booking <?php if($userRole ===2){echo 'Owner';}elseif($userRole===3){echo 'Keeper';}else{echo 'Admin';}?></title>
<body>
     <main class="py-5">
          <input type="hidden" id="currentUserRole" value="<?php $userRole; ?>">
          <?php if($userRole === 1):?>
     <div class="admin-menu text-center">
          <form id="roleForm" method="post" action="" class="mb-3">
               <h3>Menú Admin</h3>
               <div class="btn-group" role="group">
                    <input type="hidden" id="userRoleID" value ="<?php echo $userRole?>">
                    <button type="submit" name="alterRole" value="2" class="btn <?php if($alterRole === 2){echo 'btn-primary';}else{echo 'btn-secondary';}?>">Simular como Owner</button>
                    <button type="submit" name="alterRole" value="3" class="btn <?php if($alterRole === 3){echo 'btn-primary';}else{echo 'btn-secondary';}?>" >Simular como Keeper</button>
                    <button type="submit" name="alterRole" value="1" class="btn <?php if($alterRole === 1 && $alterRole=== 1){echo 'btn-primary';}else{echo 'btn-secondary';}?>" >Volver a Admin</button>
               </div>
          </form>
     </div>

     <?php endif;?>
     <?php if($userRole === 2 || $alterRole === 2 || $userRole ===1 && $alterRole!=3): ?>

          <section id="table-container" class="mb-5" id="mainContent">
          <div id="loaderOwner" class="loader"></div>
               <div class="container">
                    <h2 class="mb-4 text-center">Listado de reservas <?php if($userRole ===1 || $alterRole===1 ){echo '- Vista owner';}?></h2>
                    <table class="table table-striped table-bordered"id="bookingsTableOwner">
                    <thead class="thead-light">
                         <tr>
                              <th>Booking id</th>
                              <th>Cuidador</th>
                              <th>Pet Name</th>
                              <th>First date</th>
                              <th>Last Date</th>
                              <th>Reserva a pagar</th>
                              <th>Total value</th>
                              <th>Status</th>
                              <th>Actividad</th>
                         </tr>
                         </thead>
                         <tbody>
                         <?php
                         if ($bookingList) {
                              foreach ($bookingList as $booking) { 
                                   ?>
                                   <tr class="<?php 
                                        if ($booking->getStatus() == 1) {
                                             echo "table-secondary";
                                        } elseif ($booking->getStatus() == 2 || $booking->getStatus() == 8) {
                                             echo "table-danger";
                                        } elseif ($booking->getStatus() == 3 || $booking->getStatus() == 4) {
                                             echo "table-primary";
                                        } else {
                                             echo "table-success";
                                        }
                                   ?> table-hover text-center text-white">
                                        
                                        <td><?php echo $value =$booking->getBookingID(); ?></td>
                                        
                                        <!-- Info cuidador -->
                                        <td>
                                             <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#keeperModal<?php echo $booking->getKeeperID()->getKeeperId(); ?>">Info cuidador</button>
                                        </td>

                                        <!-- Pet Name -->
                                        <td>
                                             <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#petModal<?php echo $booking->getPetID()->getPetID(); ?>"><?php echo $booking->getPetID()->getPetName() ?></button>
                                        </td>

                                        <!-- First date -->
                                        <td><?php $date = date_create($booking->getStartDate()); echo date_format($date, "d/m/Y"); ?></td>

                                        <!-- Last Date -->
                                        <td><?php $date = date_create($booking->getEndDate()); echo date_format($date, "d/m/Y"); ?></td>

                                        <!-- Reserva a pagar -->
                                        <td>$<?php echo $booking->getAmountReservation(); ?></td>

                                        <!-- Total value -->
                                        <td>$<?php echo $booking->getTotalValue(); ?></td>

                                        <!-- Status -->
                                        <td><?php 
                                             $status = $booking->getStatus();
                                             if ($status == '1') {
                                             echo "<h6>Pending</h6>";
                                             } elseif ($status == 2) {
                                             echo "<h6>Rejected</h6>";
                                             } elseif ($status == 3) {
                                             echo "<h6>Waiting for payment</h6>";
                                             } elseif ($status == 4) {
                                             echo "<h6>Waiting for confirmation</h6>";
                                             } elseif ($status == 5) {
                                             echo "<h6>Confirmed</h6>";
                                             } elseif ($status == 6) {
                                             echo "<h6>Finish</h6>";
                                             } elseif ($status == 7) {
                                             echo "<h6>Completed</h6>";
                                             } else {
                                             echo "<h6>Overdue</h6>";
                                             }
                                        ?></td>

                                        <!-- Actividad -->
                                        <td>
                                             <!-- Review button for status '6' -->
                                             <?php if ($booking->getStatus() == '6') { ?>
                                                  
                                                       <form action='<?php echo FRONT_ROOT ?>Review/newReview' method='post'>
                                                            <button type='button' class='btn btn-secondary' data-bs-toggle='modal' data-bs-target='#exampleModal<?php echo $value; ?>' data-bs-whatever='@getbootstrap'>New Review</button>
                                                            <div class='modal fade' id='exampleModal<?php echo $value; ?>' tabindex='-1' aria-labelledby='exampleModalLabel' aria-hidden='true'>
                                                                 <div class='modal-dialog modal-xl'>
                                                                      <div class='modal-content'>
                                                                      <div class='modal-header'>
                                                                           <h5 class='modal-title'>New Review</h5>
                                                                           <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                                                      </div>
                                                                      <div class='modal-body'>
                                                                           <div class='wrapper'>
                                                                                <div class='container text-black'>
                                                                                <article class='part card-details'>
                                                                                     <div class='modal-body'>
                                                                                          <form action='<?php echo FRONT_ROOT; ?>Booking/generatePaymentBooking' method='post'>
                                                                                               <div class='w-100 text-center mb-4'>
                                                                                                    <h5>Your Valoration</h5>
                                                                                               </div>
                                                                                               <div class='d-flex flex-start mb-3 justify-content-center'>
                                                                                                    <div class='rating-icons'>
                                                                                                         <input type='radio' id='st1' name='rate' value='1' class='d-none' checked />
                                                                                                         <label for='st1' class='rating-label' title='1 Star'>
                                                                                                         <i class="fas fa-star"></i>
                                                                                                         </label>

                                                                                                         <input type='radio' id='st2' name='rate' value='2' class='d-none' />
                                                                                                         <label for='st2' class='rating-label' title='2 Stars'>
                                                                                                         <i class="fas fa-star"></i>
                                                                                                         </label>

                                                                                                         <input type='radio' id='st3' name='rate' value='3' class='d-none' />
                                                                                                         <label for='st3' class='rating-label' title='3 Stars'>
                                                                                                         <i class="fas fa-star"></i>
                                                                                                         </label>

                                                                                                         <input type='radio' id='st4' name='rate' value='4' class='d-none' />
                                                                                                         <label for='st4' class='rating-label' title='4 Stars'>
                                                                                                         <i class="fas fa-star"></i>
                                                                                                         </label>

                                                                                                         <input type='radio' id='st5' name='rate' value='5' class='d-none' />
                                                                                                         <label for='st5' class='rating-label' title='5 Stars'>
                                                                                                         <i class="fas fa-star"></i>
                                                                                                         </label>
                                                                                                    </div>
                                                                                               </div>
                                                                                               <div class='w-100 text-center mb-3'>
                                                                                                    <h6>Selected Rating: <span id="selected-rating" style="font-weight: bold;">1</span> Star</h6>
                                                                                               </div>
                                                                                               <input type='hidden' name='booking' value='<?php echo $value; ?>'>
                                                                                               <div class='d-flex flex-wrap w-100 mb-3'>
                                                                                                    <h6 for='message-text'>Feedback</h6>
                                                                                                    <textarea class='form-control' name='feedback' id='feedback' maxlength='255'></textarea>
                                                                                               </div>
                                                                                          </form>
                                                                                     </div>
                                                                                </article>
                                                                                </div>
                                                                           </div>
                                                                      </div>
                                                                      <div class='modal-footer d-flex flex-wrap justify-content-center align-items-center'>
                                                                           <button type='button' class='btn btn-danger' data-bs-dismiss='modal'>Close</button>
                                                                           <button type='submit' class='btn btn-success'>Send Review</button>
                                                                      </div>
                                                                      </div>
                                                                 </div>
                                                            </div>
                                                       </form>
                                                  </td>
                                                  <?php } ?>
                                             <!-- Payment button for status '3' -->
                                             <?php if ($booking->getStatus() == '3') { ?>
                                             <form action='<?php echo FRONT_ROOT ?>Payment/generatePaymentBooking' method='post'>
                                                  <input type='hidden' name='booking' value='<?php echo $booking->getBookingID(); ?>'>
                                                  <button type='button' class='btn btn-secondary' data-bs-toggle='modal' data-bs-target='#paymentModal<?php echo $booking->getBookingID(); ?>'>Realizar Pago</button>
                                             </form>
                                             <?php } ?>
                                        </td>
                                             <!-- Modal keeper -->
                                             <div class="modal fade" id="keeperModal<?php echo $booking->getKeeperID()->getKeeperId(); ?>" tabindex="-1" aria-labelledby="modalLabel<?php echo $booking->getKeeperID()->getKeeperId(); ?>" aria-hidden="true">
                                                  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                                                       <div class="modal-content shadow-lg border-0 rounded-3 animate__animated animate__fadeIn">
                                                            <div class="modal-header bg-primary text-white rounded-top-3">
                                                                 <h5 class="modal-title" id="modalLabel<?php echo $booking->getKeeperID()->getKeeperId(); ?>">
                                                                      <i class="fas fa-info-circle"></i> Detalles del Cuidador
                                                                 </h5>
                                                                 <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body bg-light p-4">
                                                                 <div class="text-center mb-4">
                                                                      <img src="<?php 
                                                                      echo $booking->getKeeperID()->getImage() ? FRONT_ROOT . $booking->getKeeperID()->getImage() : FRONT_ROOT . USER_PATH . 'userDefaultImage.jpg'; ?>" 
                                                                      alt="Imagen del Cuidador" 
                                                                      class="rounded-circle img-fluid" 
                                                                      style="width: 150px; height: 150px; object-fit: cover;">
                                                                 </div>
                                                                 <h3 class="text-center mb-3"><i class="fas fa-user"></i> Información del Cuidador</h3>
                                                                 <hr>
                                                                 <div class="row mb-3">
                                                                      <div class="col-md-6">
                                                                      <div class="border rounded p-3 hover-effect">
                                                                           <p><i class="fas fa-envelope"></i> <strong>Correo:</strong> <?php echo $booking->getKeeperID()->getEmail(); ?></p>
                                                                      </div>
                                                                      <div class="border rounded p-3 hover-effect">
                                                                           <p><i class="fas fa-user"></i> <strong>Nombre completo:</strong> <?php echo $booking->getKeeperID()->getLastName() . ' ' . $booking->getKeeperID()->getFirstName(); ?></p>
                                                                      </div>
                                                                      <div class="border rounded p-3 hover-effect">
                                                                           <p><i class="fas fa-info-circle"></i> <strong>Descripción:</strong> <?php echo $booking->getKeeperID()->getDescription(); ?></p>
                                                                      </div>
                                                                      </div>
                                                                      <div class="col-md-6">
                                                                      <div class="border rounded p-3 hover-effect">
                                                                           <p><i class="fas fa-calendar-alt"></i> <strong>Fecha de nacimiento:</strong> <?php echo date("d/m/Y", strtotime($booking->getKeeperID()->getBirthDate())); ?></p>
                                                                      </div>
                                                                      <div class="border rounded p-3 hover-effect">
                                                                           <p><i class="fas fa-mobile-alt"></i> <strong>Celular:</strong> <?php echo $booking->getKeeperID()->getCellPhone(); ?></p>
                                                                      </div>
                                                                      <div class="border rounded p-3 hover-effect">
                                                                           <p><i class="fas fa-paw"></i> <strong>Tamaño del perro aceptado:</strong> <?php echo $booking->getKeeperID()->getAnimalSize(); ?></p>
                                                                      </div>
                                                                      </div>
                                                                 </div>
                                                                 <div class="row mb-3">
                                                                      <div class="col-md-6">
                                                                      <div class="border rounded p-3 hover-effect">
                                                                           <p><i class="fas fa-dollar-sign"></i> <strong>Precio por día:</strong> $<?php echo number_format($booking->getKeeperID()->getPrice(), 2); ?></p>
                                                                      </div>
                                                                      </div>
                                                                      <div class="col-md-6">
                                                                      <div class="border rounded p-3 hover-effect">
                                                                           <p><i class="fas fa-credit-card"></i> <strong>CBU:</strong> <?php echo $booking->getKeeperID()->getCBU(); ?></p>
                                                                      </div>
                                                                      </div>
                                                                 </div>
                                                                 <div class="row">
                                                                      <div class="col-md-12">
                                                                      <div class="border rounded p-3 hover-effect">
                                                                           <p><i class="fas fa-star"></i> <strong>Puntos:</strong> <?php echo $booking->getKeeperID()->getPoints(); ?></p>
                                                                      </div>
                                                                      </div>
                                                                 </div>
                                                                 <hr>
                                                            </div>
                                                            <div class="modal-footer bg-secondary bg-opacity-10 rounded-bottom-3">
                                                                 <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                            </div>
                                                       </div>
                                                  </div>
                                             </div>
                                             <!-- Modal Keeper -->
                                             <!-- Modal pet -->
                                             <div class="modal fade" id="petModal<?php echo $booking->getPetID()->getPetID(); ?>" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
                                                  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                                                       <div class="modal-content border-0 shadow-lg rounded-3">
                                                            <!-- Modal Header -->
                                                            <div class="modal-header bg-success text-white">
                                                                 <h5 class="modal-title" id="modalLabel">
                                                                      <i class="fas fa-paw"></i> Detalles de <?php echo $booking->getPetID()->getPetName(); ?>
                                                                 </h5>
                                                                 <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>

                                                            <!-- Modal Body -->
                                                            <div class="modal-body bg-light p-4">
                                                                 <!-- Tarjeta de información de la mascota -->
                                                                 <div class="card shadow-sm mb-4">
                                                                      <div class="text-center">
                                                                      <?php 
                                                                      if ($image = $booking->getPetID()->getPetImage()) {
                                                                           $imageData = base64_encode(file_get_contents($image));
                                                                           echo '<img src="data:image/jpeg;base64,'.$imageData.'" class="card-img-top img-fluid rounded" style="max-width: 400px;" alt="Imagen de '. $booking->getPetID()->getPetName() .'">';
                                                                      } else {
                                                                           echo '<img src="'.IMG_PATH.'default-pet.jpg" class="card-img-top img-fluid rounded" alt="Imagen por defecto">';
                                                                      }
                                                                      ?>
                                                                      </div>

                                                                      <div class="card-body">
                                                                      <h5 class="card-title text-center"><?php echo $booking->getPetID()->getPetName(); ?></h5>
                                                                      <ul class="list-group list-group-flush">
                                                                           <li class="list-group-item">
                                                                                <i class="fas fa-dog"></i> <strong>Raza:</strong> <?php echo $booking->getPetID()->getPetBreedByText(); ?>
                                                                           </li>
                                                                           <li class="list-group-item">
                                                                                <i class="fas fa-weight"></i> <strong>Peso:</strong> <?php echo $booking->getPetID()->getPetWeight(); ?> kg
                                                                           </li>
                                                                           <li class="list-group-item">
                                                                                <i class="fas fa-ruler-combined"></i> <strong>Tamaño:</strong> <?php echo $booking->getPetID()->getPetSize(); ?>
                                                                           </li>
                                                                           <li class="list-group-item">
                                                                                <i class="fas fa-calendar-alt"></i> <strong>Edad:</strong> <?php echo $booking->getPetID()->getPetAge(); ?> años
                                                                           </li>
                                                                           <li class="list-group-item">
                                                                                <i class="fas fa-info-circle"></i> <strong>Detalles:</strong> <?php echo $booking->getPetID()->getPetDetails(); ?>
                                                                           </li>
                                                                      </ul>
                                                                      </div>
                                                                 </div>

                                                                 <!-- Plan de vacunación -->
                                                                 <div class="mt-4">
                                                                      <h5 class="text-center"><i class="fas fa-syringe"></i> Plan de Vacunación</h5>
                                                                      <div class="d-flex flex-wrap justify-content-center">
                                                                      <?php 
                                                                      if (!$booking->getPetID()->getPetVaccinationPlan()) {
                                                                           echo "<h3>Sin plan de vacunación disponible</h3>";
                                                                      } else {
                                                                           $vaccinationPlanPath = $booking->getPetID()->getPetVaccinationPlan();
                                                                           $fileType = pathinfo($vaccinationPlanPath, PATHINFO_EXTENSION);

                                                                           if ($fileType === 'pdf') {
                                                                                echo '<iframe src="' . FRONT_ROOT . $vaccinationPlanPath . '" class="w-100 px-3" style="height: 60vh;" frameborder="0"></iframe>';
                                                                           } elseif (in_array(strtolower($fileType), ['jpg', 'jpeg', 'png'])) {
                                                                                $vaccinationPlanData = base64_encode(file_get_contents($vaccinationPlanPath));
                                                                                echo '<img src="data:image/' . $fileType . ';base64,' . $vaccinationPlanData . '" class="img-fluid rounded shadow-sm" style="max-height: 800px;">';
                                                                           } else {
                                                                                echo "<h3>Formato de archivo no compatible</h3>";
                                                                           }
                                                                      }
                                                                      ?>
                                                                      </div>
                                                                 </div>

                                                                 <!-- Video de la mascota -->
                                                                 <div class="text-center mt-4">
                                                                      <h6><i class="fas fa-video"></i> Video</h6>
                                                                      <?php 
                                                                      if (!$booking->getPetID()->getPetVideo()) {
                                                                      echo "<p>No disponible</p>";
                                                                      } else {
                                                                      echo '<div class="ratio ratio-16x9">';
                                                                      echo '<video controls>';
                                                                      echo '<source src="' . FRONT_ROOT . $booking->getPetID()->getPetVideo() . '" type="video/mp4">';
                                                                      echo 'Tu navegador no soporta el tag de video.';
                                                                      echo '</video>';
                                                                      echo '</div>';
                                                                      }
                                                                      ?>
                                                                 </div>
                                                            </div>

                                                            <!-- Modal Footer -->
                                                            <div class="modal-footer bg-success d-flex justify-content-center align-items-center">
                                                                 <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Cerrar</button>
                                                            </div>
                                                       </div>
                                                  </div>
                                                  </div>

                                             <!-- Modal pet -->
                                             <!-- Modal payment -->
                                             <div class="modal fade" id='paymentModal<?php echo $value;?>' tabindex="-1" aria-labelledby="paymentUploadModalLabel" aria-hidden="true">
                                                  <div class="modal-dialog modal-dialog-centered">
                                                       <div class="modal-content">
                                                            <div class="modal-header">
                                                                 <h5 class="modal-title" id="paymentModalLabel">Subir Comprobantes de Pago</h5>
                                                                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                 <form id="paymentModalForm<?php echo $value ?>" action="<?php echo FRONT_ROOT ?>Payment/generatePaymentBooking" method="post" enctype="multipart/form-data">
                                                                      <div class="mb-3">
                                                                      <label for="paymentReceiptLabel" class="form-label">Subir comprobante (PDF o imagen)</label>
                                                                      <input type="file" id="paymentReceipt<?php echo $value ?>" name="paymentReceipt" class="form-control" accept=".pdf, .png, .jpg, .jpeg" onchange="previewReceipt(<?php echo $value; ?>)">
                                                                      <input type="hidden" name="booking" value="<?php echo $value; ?>"> <!-- ID de reserva -->
                                                                      </div>
                                                                      <!-- Espacio para la previsualización -->
                                                                      <div id="previewContainer<?php echo $value; ?>" class="mt-3" style="display:none;">
                                                                      <p class="fw-bold">Previsualización:</p>
                                                                      <div id="preview<?php echo $value; ?>" class="border p-3 text-center">
                                                                           <!-- Aquí se cargará la previsualización -->
                                                                      </div>
                                                                      </div>
                                                                 </form>
                                                            </div>
                                                            <div class="modal-footer">
                                                                 <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                                 <button type="submit" class="btn btn-primary" form="paymentModalForm<?php echo $value ?>">Subir Comprobantes</button>
                                                            </div>
                                                       </div>
                                                  </div>
                                                  </div>

                                             <!-- Modal payment-->
                                        <?php
                                   }}else{
                                        if($userRole!=1){

                                             echo "<div class='alert alert-danger w-100'>No tiene bookings disponibles!</div>";
                                        }else{
                                             echo "<div class='alert alert-danger w-100'>No existen registros disponibles!!</div>";
                                        }
                                   }
                              ?>
                              </tr>
                         </div>
                    </tbody>
               </table>
          </div>
     </section>

     <?php endif; ?><!--Cierro if owner view-->

     <!--Vista keeper-->
     <?php if($userRole ===3 || $alterRole===3 || $userRole ===1 && $alterRole!=2):?>
          <section id="table-container" class="mb-5">
               <div class="container">
                    <div id="loaderKeeper" class="loader"></div>
                    <h2 class="mb-4 text-center">Listado de reservas <?php if($userRole === 1 || $alterRole===3 && $userRole===1){echo '- Vista Keeper';}?></h2>
                    <table class="table table-striped table-bordered text-center" id="bookingsTableKeeper">
                         <thead class="table-primary">
                              <tr>
                                   <th>Booking ID</th>
                                   <th>First Date</th>
                                   <th>Last Date</th>
                                   <th>Pet</th>
                                   <th>Total Value</th>
                                   <th>Reservation Value</th>
                                   <th>Status</th>
                                   <th>Accept</th>
                                   <th>Reject</th>
                              </tr>
                         </thead>
                         <tbody>
                              <?php
                              if ($bookingList) {
                                   foreach ($bookingList as $booking) {
                                        ?>
                                        <tr class="<?php 
                                             if ($booking->getStatus() == 1) {
                                             echo "table-secondary";
                                             } elseif ($booking->getStatus() == 2 || $booking->getStatus() == 8) {
                                             echo "table-danger";
                                             } elseif ($booking->getStatus() == 3 || $booking->getStatus() == 4) {
                                             echo "table-primary";
                                             } else {
                                             echo "table-success";
                                             }
                                        ?>">
                                             <td><?php echo $booking->getBookingID(); $value = $booking->getBookingID(); ?></td>
                                             <td><?php $date = date_create($booking->getStartDate()); echo date_format($date, "d/m/Y"); ?></td>
                                             <td><?php $date = date_create($booking->getEndDate()); echo date_format($date, "d/m/Y"); ?></td>
                                             <td>
                                             <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#petModal<?php echo $booking->getPetID()->getPetID(); ?>">
                                                  <?php echo $booking->getPetID()->getPetName(); ?>
                                             </button>
                                             </td>
                                             <td><?php echo $booking->getTotalValue(); ?></td>
                                             <td><?php echo $booking->getTotalValue() * 0.5; ?></td>
                                             <td>
                                             <?php 
                                             if ($booking->getStatus() == '1') {
                                                  echo "<h6>Pending</h6>";
                                             } elseif ($booking->getStatus() == 2) {
                                                  echo "<h6>Rejected</h6>";
                                             } elseif ($booking->getStatus() == 3) {
                                                  echo "<h6>Waiting for Payment</h6>";
                                             } elseif ($booking->getStatus() == 4) {
                                                  echo "<h6>Waiting for Confirmation</h6>";
                                             } elseif ($booking->getStatus() == 5) {
                                                  echo "<h6>Confirmed</h6>";
                                             } elseif ($booking->getStatus() == 6) {
                                                  echo "<h6>Finished</h6>";
                                             } elseif ($booking->getStatus() == 7) {
                                                  echo "<h6>Completed</h6>";
                                             } else {
                                                  echo "<h6>Overdue</h6>";
                                             }
                                             ?>
                                             </td>
                                             <td>
                                             <form action='<?php echo FRONT_ROOT ?>Booking/updateBookingStatus' method='post'>
                                                  <?php 
                                                       if ($booking->getStatus() == '1') {
                                                            echo "<input type='hidden' name='id' value='$value'>
                                                                 <input type='hidden' name='status' value='3'>
                                                                 <button type='submit' class='btn btn-outline-primary w-auto p-1 m-1'>Accept</button>";
                                                       } elseif ($booking->getStatus() == '4') {
                                                            // Botón que abre el modal
                                                            echo "<button type='button' class='btn btn-outline-primary w-auto p-1 m-1' data-bs-toggle='modal' data-bs-target='#confirmModal$value'>
                                                                 Confirm
                                                                 </button>";
                                                       }
                                                  ?>
                                             </form>
                                             </td>
                                             <td>
                                             <form action='<?php echo FRONT_ROOT ?>Booking/updateBookingStatus' method='post'>
                                                  <?php 
                                                  if ($booking->getStatus() == '1') {
                                                       echo "<input type='hidden' name='id' value='$value'>
                                                            <input type='hidden' name='status' value='2'>
                                                            <button type='submit' class='btn btn-outline-danger w-auto p-1 m-1'>Reject</button>";
                                                  }
                                                  ?>
                                             </form>
                                             </td>
                                        </tr>
                                        <!-- Modal confirm payment-->
                                        <div class="modal fade" id='confirmModal<?php echo $value;?>' tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
                                             <div class="modal-dialog modal-xl">
                                             <div class="modal-content ">
                                                  <div class="modal-header">
                                                  <h5 class="modal-title" id="confirmModalLabel">Confirmar Reserva</h5>
                                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                  </div>
                                                  <div class="modal-body">
                                                       <p>¿Estás seguro de que deseas confirmar esta reserva?</p>
                                                       <div class="container">
                                                            <h5 class="text-center">Comprobante de pago</h5>
                                                       </div>
                                                       <div class="container">
                                                            <?php 
                                                                 if (!$booking->getPayment()) {
                                                                      echo "<h3>Sin plan de vacunación disponible</h3>";
                                                                 } else {
                                                                 $paymentPath = $booking->getPayment();
                                                                 $fileType = pathinfo($paymentPath, PATHINFO_EXTENSION);

                                                                 if ($fileType === 'pdf') {
                                                                      echo '<iframe src="' . FRONT_ROOT . $paymentPath . '" class="w-100 px-3" style="height: 60vh;" frameborder="0"></iframe>';
                                                                 } elseif (in_array(strtolower($fileType), ['jpg', 'jpeg', 'png'])) {
                                                                      $paymentData = base64_encode(file_get_contents($paymentPath));
                                                                      echo '<img src="data:image/' . $fileType . ';base64,' . $paymentData . '" class="img-fluid rounded shadow-sm" style="max-height: 800px;">';
                                                                 } else {
                                                                      echo "<h3>Formato de archivo no compatible</h3>";
                                                                 }
                                                                 }
                                                            ?>
                                                       </div>
                                                  </div>
                                                  <div class="modal-footer d-flex align-items-center justify-content-center">
                                                       <form action='<?php echo FRONT_ROOT ?>Booking/updateBookingStatus' method='post'>
                                                            <input type='hidden' name='id' value='<?php echo $value; ?>'>
                                                            <input type='hidden' name='status' value='5'>
                                                            <div class="cotainer text-center">
                                                                 <button type="button" class="btn btn-outline-secondary mx-3" data-bs-dismiss="modal">Cancelar</button>
                                                                 <button type="submit" class="btn btn-outline-success mx-3">Confirmar</button>
                                                            </div>
                                                       </form>
                                                  </div>
                                             </div>
                                             </div>
                                             </div>
                                        <!-- Modal confirm payment-->
                                         
                                        <!-- Modal for pet details -->
                                        <div class="modal fade" id="petModal<?php echo $booking->getPetID()->getPetID(); ?>" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
                                             <div class="modal-dialog modal-xl">
                                                  <div class="modal-content shadow-lg border-0 rounded-3" style="animation: fadeIn 0.5s;">
                                                       <div class="modal-header bg-primary text-white rounded-top-3">
                                                            <h5 class="modal-title" id="modalLabel">
                                                                 <i class="fas fa-paw me-2"></i>Detalles de <?php echo $booking->getPetID()->getPetName(); ?>
                                                            </h5>
                                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                       </div>
                                                       <div class="modal-body bg-light d-flex flex-nowrap align-items-center justify-content-center">
                                                            <div class="card m-3" style="width: 75%;">
                                                                 <!-- Imagen de la mascota -->
                                                                 <div class="text-center my-3">
                                                                 <?php 
                                                                 $image = $booking->getPetID()->getPetImage();
                                                                 if ($image) {
                                                                      $imageData = base64_encode(file_get_contents($image));
                                                                      echo '<img src="data:image/jpeg;base64,' . $imageData . '" class="img-fluid shadow-sm" style="max-width: 650px; height: 650px;" alt="Imagen de ' . $booking->getPetID()->getPetName() . '">';
                                                                 } else {
                                                                      echo '<img src="' . IMG_PATH . 'default-pet.jpg" class="img-fluid shadow-sm" style="max-width: 650px; height: 650px;" alt="Imagen por defecto">';
                                                                 }
                                                                 ?>
                                                                 </div>

                                                                 <div class="card-body">
                                                                 <h5 class="card-title text-center"><?php echo $booking->getPetID()->getPetName(); ?></h5>
                                                                 <hr>
                                                                 <ul class="list-group list-group-flush">
                                                                      <li class="list-group-item"><strong><i class="fas fa-dog me-2"></i>Raza:</strong> <?php echo $booking->getPetID()->getPetBreedByText(); ?></li>
                                                                      <li class="list-group-item"><strong><i class="fas fa-weight-hanging me-2"></i>Peso:</strong> <?php echo $booking->getPetID()->getPetWeight(); ?></li>
                                                                      <li class="list-group-item"><strong><i class="fas fa-arrows-alt-v me-2"></i>Tamaño:</strong> <?php echo $booking->getPetID()->getPetSize(); ?></li>
                                                                      <li class="list-group-item"><strong><i class="fas fa-birthday-cake me-2"></i>Edad:</strong> <?php echo $booking->getPetID()->getPetAge(); ?> años</li>
                                                                      <li class="list-group-item"><strong><i class="fas fa-info-circle me-2"></i>Detalles:</strong> <?php echo $booking->getPetID()->getPetDetails(); ?></li>
                                                                 </ul>
                                                                 <hr>

                                                                 <!-- Plan de vacunación -->
                                                                 <div class="mt-4">
                                                                      <h5 class="text-center"><i class="fas fa-syringe me-2"></i>Plan de Vacunación</h5>
                                                                      <div class="d-flex flex-wrap justify-content-center mb-3">
                                                                           <?php 
                                                                           $vaccinationPlan = $booking->getPetID()->getPetVaccinationPlan();
                                                                           if (!$vaccinationPlan) {
                                                                                echo "<h4>Sin plan de vacunación disponible</h4>";
                                                                           } else {
                                                                                $fileType = pathinfo($vaccinationPlan, PATHINFO_EXTENSION);
                                                                                if ($fileType === 'pdf') {
                                                                                     echo '<iframe src="' . FRONT_ROOT . $vaccinationPlan . '" class="w-100 px-3" style="height: 60vh;" frameborder="0"></iframe>';
                                                                                } elseif (in_array(strtolower($fileType), ['jpg', 'jpeg', 'png'])) {
                                                                                     $vaccinationPlanData = base64_encode(file_get_contents($vaccinationPlan));
                                                                                     echo '<img src="data:image/' . $fileType . ';base64,' . $vaccinationPlanData . '" class="img-fluid rounded shadow-sm mt-2" style="max-height: 400px;">';
                                                                                } else {
                                                                                     echo "<h4>Formato de archivo no compatible</h4>";
                                                                                }
                                                                           }
                                                                           ?>
                                                                      </div>
                                                                 </div>

                                                                 <!-- Video de la mascota -->
                                                                 <hr>
                                                                 <div class="text-center mt-4">
                                                                      <h6><i class="fas fa-video me-2"></i>Video</h6>
                                                                      <?php 
                                                                      $petVideo = $booking->getPetID()->getPetVideo();
                                                                      if (!$petVideo) {
                                                                           echo "<p>No disponible</p>";
                                                                      } else {
                                                                           echo '<video width="100%" height="240" controls>';
                                                                           echo '<source src="' . FRONT_ROOT . $petVideo . '" type="video/mp4">';
                                                                           echo 'Tu navegador no soporta la etiqueta de video.';
                                                                           echo '</video>';
                                                                      }
                                                                      ?>
                                                                 </div>
                                                                 </div>
                                                            </div>
                                                       </div>
                                                       <div class="modal-footer bg-secondary bg-opacity-10 rounded-bottom-3">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                       </div>
                                                  </div>
                                             </div>
                                             </div>

                                        <!-- End of modal for pet details -->
                                        <?php
                                   }
                              } else {
                                   if($userRole!=1){
                                        echo "<div class='alert alert-danger'>No tienes ninguna reserva en este momento!</div>";
                                   }else{
                                        echo "<div class='alert alert-danger'>No existen registros disponibles!</div>";
                                   }
                              }
                              ?>
                         </tbody>
                         </table>
               </div>
          </section>

     <?php endif; ?> <!--Cierro vista keeper-->

</main>
</body>
<script>
     //Pre-view del comprobante
     function previewReceipt(value) {
          const fileInput = document.getElementById(`paymentReceipt${value}`);
          const previewContainer = document.getElementById(`previewContainer${value}`);
          const preview = document.getElementById(`preview${value}`);
          
          const file = fileInput.files[0];
          const fileType = file.type;
          
          if (file) {
               const reader = new FileReader();
               previewContainer.style.display = 'block';
               
               if (fileType.includes("image")) {
                    reader.onload = function (e) {
                         preview.innerHTML = `<img src="${e.target.result}" class="img-fluid" alt="Comprobante de Pago">`;
                    };
                    reader.readAsDataURL(file);
               } else if (fileType.includes("pdf")) {

                    reader.onload = function (e) {
                         preview.innerHTML = `<embed src="${e.target.result}" type="application/pdf" width="100%" height="500px" />`;
                    };
                    reader.readAsDataURL(file);
               } else {
                    previewContainer.style.display = 'none'; 
                    preview.innerHTML = '';
               }
          } else {
               previewContainer.style.display = 'none'; 
               preview.innerHTML = '';
          }
     }

     $(document).ready(function() {
     // Mostrar el loader antes de que las tablas se carguen
     $('#loaderOwner').show();
     $('#loaderKeeper').show();

     // Inicialización de las tablas
     var tableBookings = $('#bookingsTableOwner').DataTable({
          "paging": true,
          "searching": true,
          "ordering": true,
          "pageLength": 5,
          "lengthMenu": [5, 10, 25, 50],
          "language": {
               "lengthMenu": "Mostrar _MENU_ registros por página",
               "zeroRecords": "No se encontraron resultados",
               "info": "Mostrando página _PAGE_ de _PAGES_",
               "infoEmpty": "No hay registros disponibles",
               "infoFiltered": "(filtrado de _MAX_ registros totales)",
               "search": "Buscar:",
               "paginate": {
                    "first": "Primero",
                    "last": "Último",
                    "next": "Siguiente",
                    "previous": "Anterior"
               }
          },
          //  Inicia el loader
          "initComplete": function(settings, json) {
               // Ocultar el loader una vez que las tablas esten cargadas
               $('#loaderOwner').fadeOut();
          }
     });

     var tableBookingsKeeper = $('#bookingsTableKeeper').DataTable({
          "paging": true,
          "searching": true,
          "ordering": true,
          "pageLength": 5,
          "lengthMenu": [5, 10, 25, 50],
          "language": {
               "lengthMenu": "Mostrar _MENU_ registros por página",
               "zeroRecords": "No se encontraron resultados",
               "info": "Mostrando página _PAGE_ de _PAGES_",
               "infoEmpty": "No hay registros disponibles",
               "infoFiltered": "(filtrado de _MAX_ registros totales)",
               "search": "Buscar:",
               "paginate": {
                    "first": "Primero",
                    "last": "Último",
                    "next": "Siguiente",
                    "previous": "Anterior"
               }
          },
          // Inicia el loader
          "initComplete": function(settings, json) {
               // Ocultar el loader una vez que las tablas esten cargadas
               $('#loaderKeeper').fadeOut();
          }
     });
     });
     //Para setear el ranking en review
     document.querySelectorAll('input[name="rate"]').forEach((radio) => {
          radio.addEventListener('change', function() {
               document.getElementById('selected-rating').innerText = this.value;
          });
     });
</script>

</html>