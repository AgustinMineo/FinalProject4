<?php
namespace Views;
require_once("validate-session.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
     <meta charset="UTF-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"  crossorigin="anonymous">
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"  crossorigin="anonymous"></script>
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
          <style>
               .table >td{
                    height:5px; 
                    width:5px;
                    background-color:gray;
               }
               </style>
     <title>My Pets</title>
</head>
<body>
     <main class="py-2">
          <div class="container d-flex flex-wrap bg-light">

               <section id="listado" class="mb-5 justify-content-center w-100">
                    <div class="container d-flex flex-wrap justify-content-center w-100">
                         <h2 class="mb-4 mt-5">Listado de Pets</h2>
                         <div class="container d-flex flex-wrap">
                              <?php
                              if(!$petList){
                                   echo "<div class='d-flex flex-wrap justify-content-center w-100'> <h1>No tiene pets cargados!</h1> </div>";
                              } else {
                                   foreach($petList as $pets) {
                                        ?>
                                        <div class="card m-3" style="width: 20rem;">
                                             <div class="container  " style="max-width:100% height:10%">

                                                  <?php 
                                             if ($image = $pets->getPetImage()) {
                                                  $imageData = base64_encode(file_get_contents($image));
                                                  echo '<img src="data:image/jpeg;base64,'.$imageData.'" class="img-fluid d-block mx-auto" width="100%" height="20%" >';
                                             } else {
                                                  echo '<img src="'.IMG_PATH.'default-pet.jpg" class="card-img-top">';
                                             }
                                             ?>
                                             </div>
                                             <div class="card-body d-flex flex-wrap justify-content-center align-items-center w-100">
                                                  <h5 class="card-title d-flex flex-wrap justify-content-center w-100"><?php echo $pets->getPetName() ?></h5>
                                                  <span class="w-100"><hr></span>
                                                  <p class="card-text d-flex flex-wrap justify-content-center w-100"><?php echo $pets->getPetBreedByText() ?></p>
                                                  <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal<?php echo $pets->getPetID()?>">Ver más info de <?php echo $pets->getPetName() ?></button>
                                                  <!-- Modal -->
                                                  <div class="modal fade" id="exampleModal<?php echo $pets->getPetID() ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                                                       <div class="modal-content shadow-lg border-0 rounded-3" style="animation: fadeIn 0.5s;">
                                                            <!-- Modal Header estilizado -->
                                                            <div class="modal-header bg-primary text-white rounded-top-3">
                                                                 <h5 class="modal-title" id="exampleModalLabel"><?php echo $pets->getPetName() ?></h5>
                                                                 <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>

                                                            <!-- Modal Body -->
                                                            <div class="modal-body bg-light">
                                                                 <!-- Tabla con información de la mascota -->
                                                                 <table class="table table-bordered text-center">
                                                                      <thead class="table-primary">
                                                                      <tr>
                                                                           <th scope="col">Nombre</th>
                                                                           <th scope="col">Raza</th>
                                                                           <th scope="col">Peso</th>
                                                                           <th scope="col">Tamaño</th>
                                                                           <th scope="col">Edad (Años)</th>
                                                                      </tr>
                                                                      </thead>
                                                                      <tbody>
                                                                      <tr>
                                                                           <td><?php echo $pets->getPetName() ?></td>
                                                                           <td><?php echo $pets->getPetBreedByText() ?></td>
                                                                           <td><?php echo $pets->getPetWeight() ?></td>
                                                                           <td><?php echo $pets->getPetSize() ?></td>
                                                                           <td><?php echo $pets->getPetAge() ?></td>
                                                                      </tr>
                                                                      </tbody>
                                                                 </table>

                                                                 <!-- Imagen de la mascota -->
                                                                 <div class="d-flex flex-wrap justify-content-center w-100 mt-2">
                                                                      <h5>Imagen de la Mascota</h5>
                                                                 </div>
                                                                 <div class="d-flex flex-wrap justify-content-center w-100 card-img">
                                                                      <?php
                                                                      if ($image = $pets->getPetImage()) {
                                                                      $imageData = base64_encode(file_get_contents($image));
                                                                      echo '<img src="data:image/jpeg;base64,' . $imageData . '" class="img-fluid rounded shadow-sm" width="300" height="auto">';
                                                                      } else {
                                                                      echo "<h3>No tiene imagen</h3>";
                                                                      }
                                                                      ?>
                                                                 </div>
                                                                 <hr>

                                                                 <!-- Plan de vacunación -->
                                                                 <div class="d-flex flex-wrap justify-content-center w-100 mt-2">
                                                                      <h5>Plan de Vacunación</h5>
                                                                 </div>
                                                                 <div class="d-flex flex-wrap justify-content-center w-100">
                                                                      <?php 
                                                                      if (!$pets->getPetVaccinationPlan()) {
                                                                      echo "<h3>Sin imagen</h3>";
                                                                      } else {
                                                                      $VaccinationPlan = $pets->getPetVaccinationPlan();
                                                                      $VaccinationPlanData = base64_encode(file_get_contents($VaccinationPlan));
                                                                      echo '<img src="data:image/jpeg;base64,' . $VaccinationPlanData . '" class="img-fluid rounded shadow-sm">';
                                                                      }
                                                                      ?>
                                                                 </div>
                                                                 <hr>

                                                                 <!-- Detalles adicionales -->
                                                                 <div class="d-flex flex-wrap justify-content-center w-100 mt-2">
                                                                      <h5>Detalles</h5>
                                                                 </div>
                                                                 <div class="d-flex flex-wrap justify-content-center w-100">
                                                                      <p class="text-break "><?php echo $pets->getPetDetails() ?></p>
                                                                 </div>
                                                                 <hr>

                                                                 <!-- Video de la mascota -->
                                                                 <h5 class="text-center">Video</h5>
                                                                 <div class="d-flex flex-wrap justify-content-center w-100">
                                                                      <?php 
                                                                      if (!$pets->getPetVideo()) {
                                                                      echo "<h3>Sin video</h3>";
                                                                      } else {
                                                                      echo '<video width="320" height="240" controls class="rounded shadow-sm">';
                                                                      echo '<source src="' .FRONT_ROOT . $pets->getPetVideo() . '" type="video/mp4">';
                                                                      echo 'Your browser does not support the video tag.';
                                                                      echo '</video>';
                                                                      }
                                                                      ?>
                                                                 </div>
                                                                 <?php if($userRole===1):?>
                                                                 <div class="container mt-5">
                                                                      <div class="card-header text-center">
                                                                           <h2>Dueño de <?php echo $pets->getPetName() ?></h2>
                                                                      </div>
                                                                      <div class="card">
                                                                           
                                                                           <div class="list-group">
                                                                                <div class="list-group-item list-group-item-action list-group-item-success">
                                                                                     <strong>Nombre:</strong> <span class="text-muted"><?php echo $pets->getOwnerID()->getFirstName(); ?></span>
                                                                                </div>
                                                                                <div class="list-group-item list-group-item-action list-group-item-success">
                                                                                     <strong>Apellido:</strong> <span class="text-muted"><?php echo $pets->getOwnerID()->getLastName(); ?></span>
                                                                                </div>
                                                                                <div class="list-group-item list-group-item-action list-group-item-success">
                                                                                     <strong>Email:</strong> <span class="text-muted"><?php echo $pets->getOwnerID()->getEmail(); ?></span>
                                                                                </div>
                                                                                <div class="list-group-item list-group-item-action list-group-item-success">
                                                                                     <strong>Teléfono:</strong> <span class="text-muted"><?php echo $pets->getOwnerID()->getCellPhone(); ?></span>
                                                                                </div>
                                                                                <div class="list-group-item list-group-item-action list-group-item-success">
                                                                                     <strong>Fecha de Nacimiento:</strong> <span class="text-muted"><?php echo $pets->getOwnerID()->getBirthDate(); ?></span>
                                                                                </div>
                                                                                <div class="list-group-item list-group-item-action list-group-item-success">
                                                                                     <strong>Descripción:</strong> <span class="text-muted"><?php echo $pets->getOwnerID()->getDescription(); ?></span>
                                                                                </div>
                                                                                <div class="list-group-item list-group-item-action list-group-item-success">
                                                                                     <strong>Cantidad de Mascotas:</strong> <span class="text-muted"><?php echo $pets->getOwnerID()->getPetAmount(); ?></span>
                                                                                </div>
                                                                           </div>
                                                                      </div>
                                                                 </div>
                                                                 
                                                                 <?php endif;?>
                                                            </div>
                                                            <div class="modal-footer d-flex flex-wrap justify-content-center bg-secondary rounded-bottom-3">
                                                                 <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                            </div>
                                                       </div>
                                                  </div>
                                                  </div>

                                             </div>
                                        </div>
                                        <?php
                                   }
                              }
                              ?>
                         </div>
                    </div>
               </section>
          </div>
     </main>
</body>
</html>