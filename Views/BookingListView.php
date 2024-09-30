<?php
namespace Views;
require_once("validate-session.php");

$page = 1; 
$totalPages = 5;
$requiredRole=$userRole;
$alterRole=$userRole;

     $statusFilter = $_GET['status'] ?? null;
     $priceFilter = $_GET['price'] ?? null;
     $bookingIDFilter = $_GET['bookingID'] ?? null;
     $emailFilter = $_GET['email'] ?? null;
     $startDateFilter = $_GET['startDate'] ?? null;
     $endDateFilter = $_GET['endDate'] ?? null;
     $requiredRole = $_GET['userRole'] ?? null;


    $originalBookingList = $bookingList ?? []; //Creo una copia para tener la info si saca filtros.

    // Aplico filtros si es el caso, sino retorno la copia con toda la info.
     if ($statusFilter || $priceFilter || $bookingIDFilter || $emailFilter || $startDateFilter || $endDateFilter) {
          $bookingList = array_filter($originalBookingList, function($booking) use ($statusFilter, $priceFilter, $bookingIDFilter, $emailFilter, $startDateFilter, $endDateFilter) {
               $matches = true;

          if ($statusFilter) {
               $matches &= $booking->getStatus() == $statusFilter;
          }
          if ($priceFilter) {
               $matches &= $booking->getTotalValue() <= $priceFilter; 
          }
          if ($bookingIDFilter) {
               $matches &= $booking->getBookingID() == $bookingIDFilter;
          }
          if ($emailFilter) {
               $ownerEmail = $booking->getPetID()->getOwnerID()->getEmail(); 
               $keeperEmail = $booking->getKeeperID()->getEmail(); 
               $matches &= ($ownerEmail === $emailFilter || $keeperEmail === $emailFilter);
          }
          if ($startDateFilter) {
               $matches &= $booking->getStartDate() >= $startDateFilter;
          }
          if ($endDateFilter) {
               $matches &= $booking->getEndDate() <= $endDateFilter;
          }

          return $matches;
     });
     } else {
          $bookingList = $originalBookingList;
     }

    // Paginación
     $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
     $sortOrder = isset($_GET['sort']) ? $_GET['sort'] : 'asc';

    //Filtro Ordenar por ID
     if ($sortOrder === 'desc') {
          usort($bookingList, function($a, $b) { //usort se usa para generar ordenamientos personalizados.
            return $b->getBookingID() <=> $a->getBookingID(); // Ordena el array de forma desc
     });
     } else {
     usort($bookingList, function($a, $b) {
          return $a->getBookingID() <=> $b->getBookingID(); // Ordena el array de forma asc
     });
     }

    // Configuración de la paginación
     $itemsPerPage = 5; //Cantida de paginas
     $totalItems = count($bookingList); // Usa la lista filtrada
     $totalPages = $totalItems > 0 ? ceil($totalItems / $itemsPerPage) : 1; // Evita division por cero

     $startIndex = max(0, ($page - 1) * $itemsPerPage); // Asegura que no sea negativo

     // Obtener los ítems para la página actual
     $bookingList = array_slice($bookingList, $startIndex, $itemsPerPage);


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
     
</head>
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
     <div class=" mb-4 d-flex container">
               <form method="get" action="showBookings" class="d-flex flex-wrap gap-2">
               <input type="hidden" id="requiredRole" value ="<?php echo $userRole?>">
                    <!-- Filtro de Estado -->
                    <label for="status">Estados</label>
                    <select name="status" class="form-select">
                         <option value="">Filtrar por Estado</option>
                         <option value="1">1 - Peding</option>
                         <option value="2">2 - Rejected</option>
                         <option value="3">3 - Waiting for Payment</option>
                         <option value="4">4 - Waiting for confirmation</option>
                         <option value="5">5 - Confirmed</option>
                         <option value="6">6 - Finish</option>
                         <option value="7">7 - Completed</option>
                         <option value="8">8 - Overdue</option>
                    </select>

                    <!-- Filtro de Precio -->
                    <label for="price">Precio máximo:</label>
                    <input type="number" name="price" class="form-control" placeholder="Filtrar por Precio">

                    <!-- Filtro por ID -->
                    <label for="bookingID">ID de Reserva:</label>
                    <input type="number" name="bookingID" class="form-control" placeholder="Buscar por ID">

                    <!-- Filtro por Correo -->
                    <label for="email">Email:</label>
                    <input type="email" name="email" class="form-control" placeholder="Filtrar por Correo">

                    <!-- Filtro por Fechas -->
                    <label for="startDate">Fecha de Inicio:</label>
                    <input type="date" name="startDate" class="form-control" placeholder="Fecha de Inicio">
                    <label for="endDate">Fecha de Fin:</label>
                    <input type="date" name="endDate" class="form-control" placeholder="Fecha de Fin">

                    <button type="submit" class="btn btn-primary">Aplicar Filtros</button>
               </form>
          </div>
          <div class="container d-flex text-center">
               <div class="container">
                    <form id="filterForm">
                         <select id="sort" name="sort">
                              <option value="asc" <?= $sortOrder === 'asc' ? 'selected' : ''; ?>>ID Ascendente</option>
                              <option value="desc" <?= $sortOrder === 'desc' ? 'selected' : ''; ?>>ID Descendente</option>
                         </select>
                         <button type="submit" class="btn btn-primary">Filtrar</button>
                    </form>
               </div>
          </div>
     </div>

     <?php if($userRole === 2 || $alterRole === 2 || $userRole ===1 && $alterRole!=3): ?>

          <section id="listado" class="mb-5">
               <div class="container">
                    <h2 class="mb-4 text-center">Listado de reservas <?php if($userRole ===1 || $alterRole===1 ){echo '- Vista owner';}?></h2>
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
                                             if ($booking->getStatus() == 1) {
                                             echo "table-secondary";
                                             } elseif ($booking->getStatus() == 2 || $booking->getStatus() == 8) {
                                             echo "table-danger";
                                             } elseif ($booking->getStatus() == 3 || $booking->getStatus() == 4) {
                                             echo "table-primary";
                                             } else {
                                             echo "table-success";
                                             }
                                             ?>"
                                             
                                             table  table-hover table align-middle text-center text-white" >
                                                  
                                                  <td><?php echo $value=$booking->getBookingID(); ?></td>
                                                  
                                                  <th><button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#keeperModal<?php echo $booking->getKeeperID()->getKeeperId(); ?>">Info cuidador</button></th>
                                                  <th><button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#petModal<?php echo $booking->getPetID()->getPetID(); ?>"><?php echo $booking->getPetID()->getPetName() ?></button></th>
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
                                                       <button type='button' class='btn btn-secondary' data-bs-toggle='modal' data-bs-target='#paymentModal$value'>
                                                            Realizar Pago
                                                            </button>
                                                       ";}?></form></td>
                                             </tr>
                                             <!-- Modal keeper -->
                                             <div class="modal fade" id="keeperModal<?php echo $booking->getKeeperID()->getKeeperId(); ?>" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
                                             <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                                                  <div class="modal-content shadow-lg border-0 rounded-3" style="animation: fadeIn 0.5s;">
                                                       <div class="modal-header bg-primary text-white rounded-top-3">
                                                            <h5 class="modal-title" id="modalLabel">Detalles del Cuidador</h5>
                                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                       </div>
                                                       <div class="modal-body bg-light p-4">
                                                            <h3 class="text-center">Información del Cuidador</h3>
                                                            <hr>
                                                            <div class="row mb-3">
                                                                 <div class="col-md-6">
                                                                 <div class="border rounded p-3 hover-effect">
                                                                      <p><strong>Correo:</strong> <?php echo $booking->getKeeperID()->getEmail(); ?></p>
                                                                 </div>
                                                                 <div class="border rounded p-3 hover-effect">
                                                                      <p><strong>Nombre completo:</strong> <?php echo $booking->getKeeperID()->getLastName(); ?> <?php echo $booking->getKeeperID()->getFirstName(); ?></p>
                                                                 </div>
                                                                 <div class="border rounded p-3 hover-effect">
                                                                      <p><strong>Descripción:</strong> <?php echo $booking->getKeeperID()->getDescription(); ?></p>
                                                                 </div>
                                                                 </div>
                                                                 <div class="col-md-6">
                                                                 <div class="border rounded p-3 hover-effect">
                                                                      <p><strong>Fecha de nacimiento:</strong> <?php echo date("d/m/Y", strtotime($booking->getKeeperID()->getBirthDate())); ?></p>
                                                                 </div>
                                                                 <div class="border rounded p-3 hover-effect">
                                                                      <p><strong>Celular:</strong> <?php echo $booking->getKeeperID()->getCellPhone(); ?></p>
                                                                 </div>
                                                                 <div class="border rounded p-3 hover-effect">
                                                                      <p><strong>Tamaño del perro aceptado:</strong> <?php echo $booking->getKeeperID()->getAnimalSize(); ?></p>
                                                                 </div>
                                                                 </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                 <div class="col-md-6">
                                                                 <div class="border rounded p-3 hover-effect">
                                                                      <p><strong>Precio por día:</strong> $<?php echo number_format($booking->getKeeperID()->getPrice(), 2); ?></p>
                                                                 </div>
                                                                 </div>
                                                                 <div class="col-md-6">
                                                                 <div class="border rounded p-3 hover-effect">
                                                                      <p><strong>CBU:</strong> <?php echo $booking->getKeeperID()->getCBU(); ?></p>
                                                                 </div>
                                                                 </div>
                                                            </div>
                                                            <div class="row">
                                                                 <div class="col-md-12">
                                                                 <div class="border rounded p-3 hover-effect">
                                                                      <p><strong>Puntos:</strong> <?php echo $booking->getKeeperID()->getPoints(); ?></p>
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
                                                  <div class="modal-content border-0 shadow">
                                                       <div class="modal-header bg-success text-white">
                                                            <h5 class="modal-title" id="modalLabel">Detalles de <?php echo $booking->getPetID()->getPetName(); ?></h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                       </div>

                                                       <div class="modal-body bg-light p-4">
                                                            <div class="card shadow-sm">
                                                                 <div class="text-center">
                                                                 <?php 
                                                                 if ($image = $booking->getPetID()->getPetImage()) {
                                                                      $imageData = base64_encode(file_get_contents($image));
                                                                      echo '<img src="data:image/jpeg;base64,'.$imageData.'" class="card-img-top img-fluid rounded" style="width:50vh; heigth:20vh" alt="Imagen de '. $booking->getPetID()->getPetName() .'">';
                                                                 } else {
                                                                      echo '<img src="'.IMG_PATH.'default-pet.jpg" class="card-img-top img-fluid rounded" alt="Imagen por defecto">';
                                                                 }
                                                                 ?>
                                                                 </div>

                                                                 <div class="card-body">
                                                                 <h5 class="card-title text-center"><?php echo $booking->getPetID()->getPetName(); ?></h5>
                                                                 <ul class="list-group list-group-flush">
                                                                      <li class="list-group-item"><strong>Raza:</strong> <?php echo $booking->getPetID()->getPetBreedByText(); ?></li>
                                                                      <li class="list-group-item"><strong>Peso:</strong> <?php echo $booking->getPetID()->getPetWeight(); ?></li>
                                                                      <li class="list-group-item"><strong>Tamaño:</strong> <?php echo $booking->getPetID()->getPetSize(); ?></li>
                                                                      <li class="list-group-item"><strong>Edad:</strong> <?php echo $booking->getPetID()->getPetAge(); ?> años</li>
                                                                      <li class="list-group-item"><strong>Detalles:</strong> <?php echo $booking->getPetID()->getPetDetails(); ?></li>
                                                                 </ul>
                                                                 </div>
                                                            </div>

                                                            <!-- Plan de vacunación -->
                                                            <div class="mt-4">
                                                                 <h5 class="text-center">Plan de Vacunación</h5>
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
                                                                 <h6>Video</h6>
                                                                 <?php 
                                                                      if (!$booking->getPetID()->getPetVideo()) {
                                                                      echo "<p>No disponible</p>";
                                                                 } else {
                                                                      echo '<div class="ratio ratio-4x3">';
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
                                                       <div class="modal-footer d-flex bg-success justify-content-center aling-items-center">
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
                                                                 <h5 class="modal-title" id="paymentModalModalLabel">Subir Comprobantes de Pago</h5>
                                                                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                 <form id="paymentModalForm<?php echo $value ?>" action="<?php echo FRONT_ROOT ?>Payment/generatePaymentBooking" method="post" enctype="multipart/form-data">
                                                                      <div class="mb-3">
                                                                      <label for="paymentReceiptLabel" class="form-label">Subir comprobante(PDF o imagen)</label>
                                                                      <input type="file" name="paymentReceipt" class="form-control" accept=".pdf, .png, .jpg, .jpeg">
                                                                      <input type="hidden" name="booking" value="<?php echo $value; ?>"> <!-- ID de reserva -->
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
     <?php if($userRole === 2 && $totalPages>1 || $userRole=== 1 && $alterRole=== 2 && $totalPages>1 || $totalPages>1 && $userRole === 2 ):?>
          <div class="container">
               <nav aria-label="Owner Paginate">
                    <ul class="pagination" id="pagination">
                         <?php if ($page > 1): ?>
                              <li class="page-item">
                                   <a class="page-link" href="?page=<?= $page - 1; ?>&requiredRole=<?= $userRole; ?>" aria-label="Previous">« Anterior</a>
                              </li>
                         <?php endif; ?>

                         <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                              <li class="page-item <?= $i == $page ? 'active' : ''; ?>">
                                   <a class="page-link" href="?page=<?= $i; ?>&requiredRole=<?= $userRole; ?>"><?= $i; ?></a>
                              </li>
                         <?php endfor; ?>

                         <?php if ($page < $totalPages): ?>
                              <li class="page-item">
                                   <a class="page-link" href="?page=<?= $page + 1; ?>&requiredRole=<?= $userRole; ?>" aria-label="Next">Siguiente »</a>
                              </li>
                         <?php endif; ?>
                    </ul>
               </nav>
          </div>
     <?php endif; ?><!--Cierro if paginado-->

     <?php endif; ?><!--Cierro if owner view-->

     <!--Vista keeper-->
     <?php if($userRole ===3 || $alterRole===3 || $userRole ===1 && $alterRole!=2):?>
          <section id="listado" class="mb-5">
               <div class="container">
                    <h2 class="mb-4 text-center">Listado de reservas <?php if($userRole === 1 || $alterRole===3 && $userRole===1){echo '- Vista Keeper';}?></h2>
                    <table class="table table-striped table-bordered text-center">
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
                                                       <h5 class="modal-title" id="modalLabel">Detalles de <?php echo $booking->getPetID()->getPetName(); ?></h5>
                                                       <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                  </div>
                                                  <div class="modal-body bg-light d-flex flex-nowrap align-items-center justify-content-center">
                                                       <div class="card m-3" style="width: 75%;">
                                                            <?php 
                                                            if ($image = $booking->getPetID()->getPetImage()) {
                                                                 $imageData = base64_encode(file_get_contents($image));
                                                                 echo '<img src="data:image/jpeg;base64,' . $imageData . '" class="card-img-top" alt="Imagen de ' . $booking->getPetID()->getPetName() . '">';
                                                            } else {
                                                                 echo '<img src="' . IMG_PATH . 'default-pet.jpg" class="card-img-top" alt="Imagen por defecto">';
                                                            }
                                                            ?>
                                                            <div class="card-body">
                                                                 <h5 class="card-title text-center"><?php echo $booking->getPetID()->getPetName(); ?></h5>
                                                                 <hr>
                                                                 <ul class="list-group list-group-flush">
                                                                 <li class="list-group-item border rounded hover-effect"><strong>Raza:</strong> <?php echo $booking->getPetID()->getPetBreedByText(); ?></li>
                                                                 <li class="list-group-item border rounded hover-effect"><strong>Peso:</strong> <?php echo $booking->getPetID()->getPetWeight(); ?> kg</li>
                                                                 <li class="list-group-item border rounded hover-effect"><strong>Tamaño:</strong> <?php echo $booking->getPetID()->getPetSize(); ?></li>
                                                                 <li class="list-group-item border rounded hover-effect"><strong>Edad:</strong> <?php echo $booking->getPetID()->getPetAge(); ?> años</li>
                                                                 <li class="list-group-item border rounded hover-effect"><strong>Detalles:</strong> <?php echo $booking->getPetID()->getPetDetails(); ?></li>
                                                                 </ul>
                                                                 <hr>
                                                                 <!-- Plan de vacunación -->
                                                                 <div class="mt-4">
                                                                      <h5 class="text-center">Plan de Vacunación</h5>
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
                                                                 <hr>
                                                                 <div class="text-center">
                                                                 <h6>Video</h6>
                                                                 <?php 
                                                                 if (!$booking->getPetID()->getPetVideo()) {
                                                                      echo "<p>No disponible</p>";
                                                                 } else {
                                                                      echo '<video width="100%" height="240" controls>';
                                                                      echo '<source src="' . FRONT_ROOT . $booking->getPetID()->getPetVideo() . '" type="video/mp4">';
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

          <?php if($userRole===3 && $totalPages>1  || $userRole===1 && $alterRole===3 && $totalPages>1 ):?>
          <div class="container">
               <nav aria-label="Page navigation example">
                    <ul class="pagination" id="pagination">
                         <?php if ($page > 1): ?>
                              <li class="page-item">
                                   <a class="page-link" href="?page=<?= $page - 1; ?>&requiredRole=<?= $userRole; ?>" aria-label="Previous">« Anterior</a>
                              </li>
                         <?php endif; ?>

                         <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                              <li class="page-item <?= $i == $page ? 'active' : ''; ?>">
                                   <a class="page-link" href="?page=<?= $i; ?>&requiredRole=<?= $userRole; ?>"><?= $i; ?></a>
                              </li>
                         <?php endfor; ?>

                         <?php if ($page < $totalPages): ?>
                              <li class="page-item">
                                   <a class="page-link" href="?page=<?= $page + 1; ?>&requiredRole=<?= $userRole; ?>" aria-label="Next">Siguiente »</a>
                              </li>
                         <?php endif; ?>
                    </ul>
               </nav>
          </div>
          <?php endif;?><!--Cierro navbar-->
     <?php endif; ?> <!--Cierro vista keeper-->
     <!--If paginado admin-->
     <?php if($userRole===1 && $alterRole=== 1 && $totalPages>1):?>
                    <div class="container">
                         <nav aria-label="Page navigation Admin">
                              <ul class="pagination" id="pagination">
                                   <?php if ($page > 1): ?>
                                        <li class="page-item">
                                             <a class="page-link" href="?page=<?= $page - 1; ?>&requiredRole=<?= $userRole; ?>" aria-label="Previous">« Anterior</a>
                                        </li>
                                   <?php endif; ?>

                                   <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                        <li class="page-item <?= $i == $page ? 'active' : ''; ?>">
                                             <a class="page-link" href="?page=<?= $i; ?>&requiredRole=<?= $userRole; ?>"><?= $i; ?></a>
                                        </li>
                                   <?php endfor; ?>

                                   <?php if ($page < $totalPages): ?>
                                        <li class="page-item">
                                             <a class="page-link" href="?page=<?= $page + 1; ?>&requiredRole=<?= $userRole; ?>" aria-label="Next">Siguiente »</a>
                                        </li>
                                   <?php endif; ?>
                              </ul>
                         </nav>
                    </div>
               <?php endif; ?><!--Cierro If paginado admin-->
</main>
</body>
<script>
const currentUserRole = document.getElementById('currentUserRole').value;
paginationLinks.forEach(link => {
     link.addEventListener('click', function (e) {
          e.preventDefault();
          const page = this.getAttribute('data-page');
          const currentSortOrder = '<?= $sortOrder ?>'; // Obtengo el valor actual de sortOrder

          // cargo datos en el ajax
          loadData(page, currentSortOrder, currentUserRole);
     });
});


function loadData(page, sort = '', requiredRole) {
    // recopilo filtros
const statusFilter = document.querySelector('input[name="status"]').value;
const priceFilter = document.querySelector('input[name="price"]').value;
const bookingIDFilter = document.querySelector('input[name="bookingID"]').value;
const emailFilter = document.querySelector('input[name="email"]').value;
const startDateFilter = document.querySelector('input[name="startDate"]').value;
const endDateFilter = document.querySelector('input[name="endDate"]').value;
const requiredRole = document.getElementById('userRoleID').value;//Role
    //const requiredRole = document.getElementById('userRoleID').value;
$.ajax({
     url: 'BookingListView.php',
     type: 'GET',
     data: {
          page: page,
          sort: sort,
          status: statusFilter,
          price: priceFilter,
          bookingID: bookingIDFilter,
          email: emailFilter,
          startDate: startDateFilter,
          endDate: endDateFilter,
            requiredRole: requiredRole // Envio el role por tema de sessionHelper
          },
          success: function(data) {
               $('#listado').html(data); // Actualiza el contenido de listado
          }
     });
}
</script>
</html>