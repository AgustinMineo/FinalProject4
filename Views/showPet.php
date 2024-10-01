<?php
namespace Views;
require_once("validate-session.php");
include ("ownerNav.php");
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
                                        <div class="card m-3" style="width: 12rem;">
                                             <?php 
                                             if ($image = $pets->getPetImage()) {
                                                  $imageData = base64_encode(file_get_contents($image));
                                                  echo '<img src="data:image/jpeg;base64,'.$imageData.'" class="card-img-top">';
                                             } else {
                                                  echo '<img src="'.IMG_PATH.'default-pet.jpg" class="card-img-top">';
                                             }
                                             ?>
                                             <div class="card-body d-flex flex-wrap justify-content-center align-items-center w-100">
                                                  <h5 class="card-title d-flex flex-wrap justify-content-center w-100"><?php echo $pets->getPetName() ?></h5>
                                                  <span class="w-100"><hr></span>
                                                  <p class="card-text d-flex flex-wrap justify-content-center w-100"><?php echo $pets->getPetBreedByText() ?></p>
                                                  <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal<?php echo $pets->getPetID()?>">Ver más info de <?php echo $pets->getPetName() ?></button>
                                                  <!-- Modal -->
                                                  <div class="modal fade" id="exampleModal<?php echo $pets->getPetID() ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                       <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">
                                                                 <div class="modal-header">
                                                                      <h5 class="modal-title" id="exampleModalLabel"><?php echo $pets->getPetName() ?></h5>
                                                                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                 </div>
                                                                 <div class="modal-body bg-light">
                                                                      <table class="table text-center">
                                                                           <thead>
                                                                                <tr>
                                                                                     <th scope="col">Name</th>
                                                                                     <th scope="col">Breed</th>
                                                                                     <th scope="col">Weight</th>
                                                                                     <th scope="col">Size</th>
                                                                                     <th scope="col">Age (Years)</th>
                                                                                </tr>
                                                                           </thead>
                                                                           <tbody>
                                                                                <tr>
                                                                                     <td><div class="d-flex flex-wrap justify-content-center w-100"><?php echo $pets->getPetName()?></div></td>
                                                                                     <td><div class="d-flex flex-wrap justify-content-center w-100"><?php echo $pets->getPetBreedByText() ?></div></td>
                                                                                     <td><div class="d-flex flex-wrap justify-content-center w-100"><?php echo $pets->getPetWeight() ?></div></td>
                                                                                     <td><div class="d-flex flex-wrap justify-content-center w-100"><?php echo $pets->getPetSize() ?></div></td>
                                                                                     <td><div class="d-flex flex-wrap justify-content-center w-100"><?php echo $pets->getPetAge() ?></div></td>
                                                                                </tr>
                                                                           </tbody>
                                                                      </table>
                                                                      <div class="d-flex flex-wrap justify-content-center w-100 mt-2">
                                                                           <h5>Pet Image</h5>
                                                                      </div>
                                                                      <div class="d-flex flex-wrap justify-content-center w-100">
                                                                           <?php
                                                                           if ($image = $pets->getPetImage()) {
                                                                                $imageData = base64_encode(file_get_contents($image));
                                                                                echo '<img src="data:image/jpeg;base64,'.$imageData.'" class="img-fluid">';
                                                                           } else {
                                                                                echo "<h3>No tiene imagen</h3>";
                                                                           }
                                                                           ?>
                                                                      </div>
                                                                      <hr>
                                                                      <div class="d-flex flex-wrap justify-content-center w-100 mt-2">
                                                                           <h5>Vaccination Plan</h5>
                                                                      </div>
                                                                      <div class="d-flex flex-wrap justify-content-center w-100">
                                                                           <?php 
                                                                           if (!$pets->getPetVaccinationPlan()) {
                                                                                echo "<h3>Sin imagen</h3>";
                                                                           } else {
                                                                                $VaccinationPlan = $pets->getPetVaccinationPlan();
                                                                                $VaccinationPlanData = base64_encode(file_get_contents($VaccinationPlan));
                                                                                echo '<img src="data:image/jpeg;base64,'.$VaccinationPlanData.'" class="img-fluid">';
                                                                           }
                                                                           ?>
                                                                      </div>
                                                                      <hr>
                                                                      <div class="d-flex flex-wrap justify-content-center w-100 mt-2">
                                                                           <h5>Details</h5>
                                                                      </div>
                                                                      <div class="d-flex flex-wrap justify-content-center w-100">
                                                                           <?php echo $pets->getPetDetails() ?>
                                                                      </div>
                                                                      <hr>
                                                                      <h5 class="text-center">Video</h5>
                                                                      <div class="d-flex flex-wrap justify-content-center w-100">
                                                                           <?php 
                                                                           if (!$pets->getPetVideo()) {
                                                                                echo "<h3>Sin video</h3>";
                                                                           } else {
                                                                                echo '<object width="425" height="350" data="http://www.youtube.com/v/' . substr($pets->getPetVideo(), -11) . '" type="application/x-shockwave-flash"></object>';
                                                                           }
                                                                           ?>
                                                                      </div>
                                                                 </div>
                                                                 <div class="modal-footer d-flex flex-wrap justify-content-center">
                                                                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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