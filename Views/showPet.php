<?php
namespace Views;
include ("ownerNav.php");
?>
<main class="py-2">
     <style>
          .table >td{
               height:5px; 
               width:5px;
               background-color:gray;
          }
     </style>
<section id="listado" class="mb-5">
     <div class="container">
          <h2 class="mb-4">Listado de Pets</h2>
          <table class="table bg-light-alpha">
               <thead>
                    <th>Name</th>
                    <th>Pet Image</th>
                    <th>Breed</th>
                    <th>Size</th>
                    <th>Weight</th>
                    <th>Vaccination Plan</th>
                    <th>Pet Details</th>
                    <th>Pet video</th>
               </thead>
               <tbody>
                    <?php
                         foreach($petList as $pets)
                         {
                              ?>
                                   <tr class="table">
                                        <td><?php echo $pets->getPetName() ?></td>
                                        <td><?php
                                        
                                        $image = $pets->getPetImage();
                                        $imageData = base64_encode(file_get_contents($image));
                                        echo '<img src="data:image/jpeg;base64,'.$imageData.'">';?></td>
                                        <td ><?php echo $pets->getBreed() ?></td>
                                        <td ><?php echo $pets->getPetSize() ?></td>
                                        <td ><?php echo $pets->getPetWeight() ?></td>
                                        <td><?php 
                                        $VaccinationPlan = $pets->getVaccinationPlan();
                                        $VaccinationPlanData = base64_encode(file_get_contents($VaccinationPlan));
                                        echo '<img src="data:image/jpeg;base64,'.$VaccinationPlanData.'">';?></td>
                                        <td><?php echo $pets->getPetDetails()?></td>
                                        <td><object width="425" height="350" data="http://www.youtube.com/v/<?php echo substr($pets->getPetVideo(),-11)?>" type="application/x-shockwave-flash"></object></td>
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