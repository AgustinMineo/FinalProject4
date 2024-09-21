<?php
namespace Views;
require_once("validate-session.php");
include ("ownerNav.php");
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet"  crossorigin="anonymous">
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
                    <th>Disponibilidad</th>
                    <th>animalSize</th>
                    <th>Price</th>
                    </thead>
                    <tbody>
                    <?php foreach ($listKeepers as $keeper) { ?>
                         <tr>
                              <td><?php echo $keeper->getLastName(); ?></td>
                              <td><?php echo $keeper->getFirstName(); ?></td>
                              <td><?php echo $keeper->getCellPhone(); ?></td>
                              <td><?php $date=date_create($keeper->getBirthDate()); echo date_format($date,"d/m/Y"); ?></td>
                              <td><?php echo $keeper->getEmail(); ?></td>
                              <td>
                                   <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#availabilityModal<?php echo $keeper->getKeeperId(); ?>">
                                        Ver Disponibilidad
                                   </button>
                                   <!-- Inicio de modal Modal -->
                                   <div class="modal fade" id="availabilityModal<?php echo $keeper->getKeeperId(); ?>" tabindex="-1" aria-labelledby="availabilityModalLabel<?php echo $keeper->getKeeperId(); ?>" aria-hidden="true">
                                        <div class="modal-dialog">
                                        <div class="modal-content">
                                             <div class="modal-header">
                                                  <h5 class="modal-title" id="availabilityModalLabel<?php echo $keeper->getKeeperId(); ?>">Disponibilidad de <?php echo $keeper->getFirstName() . ' ' . $keeper->getLastName(); ?></h5>
                                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                             </div>
                                             <div class="modal-body">
                                                  <div class="mb-3">
                                                       <label for="startDate<?php echo $keeper->getKeeperId(); ?>" class="form-label">Fecha de inicio</label>
                                                       <input type="date" class="form-control" id="startDate<?php echo $keeper->getKeeperId(); ?>" oninput="filterAvailability(<?php echo $keeper->getKeeperId(); ?>)">
                                                  </div>
                                                  <div class="mb-3">
                                                       <label for="endDate<?php echo $keeper->getKeeperId(); ?>" class="form-label">Fecha de fin</label>
                                                       <input type="date" class="form-control" id="endDate<?php echo $keeper->getKeeperId(); ?>" oninput="filterAvailability(<?php echo $keeper->getKeeperId(); ?>)">
                                                  </div>
                                                  <ul class="list-group">
                                                       <?php 
                                                       $availability = $keeper->getAvailability();
                                                       if (!empty($availability)) {
                                                            foreach ($availability as $day) {
                                                                // Convertir fecha de d/m/Y a Y-m-d para la comparación
                                                            $dateFormatted = date('d/m/Y', strtotime($day['day']));
                                                                // Mostrar solo fechas futuras
                                                            if (strtotime($day['day']) >= strtotime(date('Y-m-d'))) {
                                                                 $availabilityClass = $day['available'] ? 'list-group-item-success' : 'list-group-item-danger';
                                                                 echo '<li class="list-group-item '.$availabilityClass.'" data-date="'.$dateFormatted.'">'.$dateFormatted.'</li>';
                                                            }
                                                            }
                                                       } else {
                                                            echo '<li class="list-group-item">No tiene disponibilidad</li>';
                                                       }
                                                       ?>
                                                  </ul>
                                             </div>
                                             <div class="modal-footer">
                                                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                             </div>
                                        </div>
                                        </div>
                                   </div>
                                                  <!--Fin de modal --> 
                              </td>
                              <td><?php echo $keeper->getAnimalSize(); ?></td>
                              <td>$<?php echo $keeper->getPrice(); ?></td>
                         </tr>
                    <?php } ?>
                    </tbody>
               </table>
          </div>
     </section>
</main>
<script src="../Views/js/datepicker.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
function filterAvailability(keeperId) {
var startDate = document.getElementById('startDate' + keeperId).value;
var endDate = document.getElementById('endDate' + keeperId).value;
var listItems = document.querySelectorAll('#availabilityModal' + keeperId + ' .list-group-item');

    //Valido que sea un date
     if (startDate) {
          startDate = new Date(startDate);
          startDate.setHours(0, 0, 0, 0); // Para que arranque a las 00:00:00
     }

     if (endDate) {
          endDate = new Date(endDate);
          endDate.setHours(23, 59, 59, 999); //Para que tome hasta las 23:59:59
     }

listItems.forEach(function(item) {
     var dateText = item.textContent.trim();
     var [day, month, year] = dateText.split('/').map(Number);
     var itemDate = new Date(year, month - 1, day-1); // Convertir d/m/Y a Date

     var showItem = true;

        // comparo con la fecha de inicio
     if (startDate && itemDate < startDate) {
          showItem = false;
     }

        // comparo con fecha de fin
     if (endDate && itemDate >= endDate) {
          showItem = false;
     }

     item.style.display = showItem ? '' : 'none';
});
}


</script>
</body>
</html>
