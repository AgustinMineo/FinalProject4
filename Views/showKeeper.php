<?php
namespace Views;
include ("ownerNav.php");
require_once(VIEWS_PATH."validate-session.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
     <meta charset="UTF-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
 
     <title>Keeper disponibles</title>
</head>
<body>
     <main class="py-5">
          <section id="listado" class="mb-5">
               <div class="container">
                    <h2 class="mb-4">Listado de Keepers</h2>
                    <table class="table bg-light-alpha">
                         <thead>
                              <th>Last Name</th>
                              <th>First Name</th>
                              <th>Cellphone</th>
                              <th>birthDate</th>
                              <th>email</th>
                              <th>First availability Days</th>
                              <th>Last availability Days</th>
                              <th>animalSize</th>
                              <th>Price</th>
                         </thead>
                         <tbody>
                              <?php
                                   foreach($listKeepers as $keeper)
                                   {
                                        ?>
                                             <tr>
                                                  <td><?php echo $keeper->getLastName() ?></td>
                                                  <td><?php echo $keeper->getfirstName() ?></td>
                                                  <td><?php echo $keeper->getCellPhone() ?></td>
                                                  <td><?php echo $keeper->getbirthDate() ?></td>
                                                  <td><?php echo $keeper->getEmail() ?></td>
                                                  <td> <?php  if($keeper->getFirstAvailabilityDays()){echo $keeper->getFirstAvailabilityDays();}else{echo "No disponible";}?></td>
                                                  <td> <?php if($keeper->getLastAvailabilityDays()){echo $keeper->getLastAvailabilityDays();}else{echo "No disponible";}?></td>
                                                  <td><?php echo $keeper->getAnimalSize() ?></td>
                                                  <td>$<?php echo $keeper->getPrice() ?></td>
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
</body>
</html>