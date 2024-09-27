<?php
namespace Views;
require_once("validate-session.php");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <title>Keeper disponibles</title>
    <style>
        #keeperAvailabilityModal .modal-body {
            min-height: 400px; /* Ajusta esta altura según sea necesario */
        }
    </style>
</head>
<body>
<main class="py-5">
<section id="listado" class="mb-5">
    <div class="container">
        <h2 class="mb-4 text-center">Listado de Keepers</h2>
        <table class="table table-striped table-hover bg-light-alpha">
            <thead class="table-primary">
                <tr>
                    <th>Last Name</th>
                    <th>First Name</th>
                    <th>Cellphone</th>
                    <th>Birth Date</th>
                    <th>Email</th>
                    <th>Disponibilidad</th>
                    <th>Animal Size</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($listKeepers as $keeper) { ?>
                    <tr>
                        <td><?php echo $keeper->getLastName(); ?></td>
                        <td><?php echo $keeper->getFirstName(); ?></td>
                        <td><?php echo $keeper->getCellPhone(); ?></td>
                        <td><?php $date = date_create($keeper->getBirthDate()); echo date_format($date, "d/m/Y"); ?></td>
                        <td><?php echo $keeper->getEmail(); ?></td>
                        <td>
                            <button type="button" class="btn btn-info animate__animated animate__pulse" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#availabilityModal<?php echo $keeper->getKeeperId(); ?>" 
                                    onclick='initializeCalendar(<?php echo $keeper->getKeeperId(); ?>, <?php echo json_encode($keeper->getAvailability()); ?>)'>
                                Ver Disponibilidad
                            </button>
                            <!-- Modal -->
                            <div class="modal fade" id="availabilityModal<?php echo $keeper->getKeeperId(); ?>" tabindex="-1" aria-labelledby="availabilityModalLabel<?php echo $keeper->getKeeperId(); ?>" aria-hidden="true">
                                <div class="modal-dialog modal-xl">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="availabilityModalLabel<?php echo $keeper->getKeeperId(); ?>">Disponibilidad de <?php echo $keeper->getFirstName() . ' ' . $keeper->getLastName(); ?></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div id="calendar<?php echo $keeper->getKeeperId(); ?>"></div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
<script>
function convertAvailability(data) {
    return data.map(item => {
        return {
            title: item.available === "1" ? 'Disponible' : 'No disponible',
            start: item.day,
            end: item.available === "1" ? item.day : null,
            allDay: true,
            backgroundColor: item.available === "1" ? 'green' : 'red'
        };
    });
}

let isInitialRender = true; 

function initializeCalendar(keeperId, availabilityData) {
    var calendarEl = document.getElementById('calendar' + keeperId);
    calendarEl.innerHTML = '';
    if (!calendarEl) {
        console.error("Calendar element not found");
        return;
    }

    console.log("Initializing calendar for keeperId: " + keeperId);

    
    var events = convertAvailability(availabilityData);

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        height: 'auto',
        events: events,
        eventRender: function(info) {
            console.log("Rendering event: ", info.event);
        },
        datesSet: function(dateInfo) {
            console.log("Calendar dates updated: ", dateInfo);

            if (isInitialRender) {
                setTimeout(() => {
                    calendar.gotoDate(new Date());
                    isInitialRender = false;
                }, 70); 
            }
        }
    });

    calendar.render();
    calendar.updateSize();


    setTimeout(() => {
        calendar.gotoDate(new Date(new Date().getFullYear(), new Date().getMonth() - 1, 1));
        setTimeout(() => {
            calendar.gotoDate(new Date()); 
        }, 500); 
    }, 0); 
}


</script>
</body>
</html>
