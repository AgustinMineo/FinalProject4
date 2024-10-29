<?php
namespace Views;
require_once("validate-session.php");

// Obtener mes y año seleccionados
$selectedMonth = isset($_GET['month']) ? intval($_GET['month']) : date('n');
$selectedYear = isset($_GET['year']) ? intval($_GET['year']) : date('Y');

//$generatedDays=refreshDays();

// Inicializar bookings y days para evitar errores si están en null
$bookings = isset($bookings) ? $bookings : [];
$days = isset($days) ? $days : [];

// Asegurarse de que el array 'days' tenga la estructura correcta
$cleanedDays = array_map(function($day) {
    return [
        'day' => $day['day'],
        'available' => $day['available']
    ];
}, $days);

// Generar los días del mes seleccionado
$numDaysInMonth = cal_days_in_month(CAL_GREGORIAN, $selectedMonth, $selectedYear);
$generatedDays = [];

// Inicializamos todos los días como no disponibles
for ($i = 1; $i <= $numDaysInMonth; $i++) {
    $currentDay = "$selectedYear-$selectedMonth-" . str_pad($i, 2, '0', STR_PAD_LEFT); // Asegurarse de tener el formato correcto
    $generatedDays[] = [
        'day' => $currentDay,
        'available' => '0' // Todos los días no disponibles por defecto
    ];
}

// Actualizamos la disponibilidad con los datos de cleanedDays
foreach ($cleanedDays as $cleanedDay) {
    // Convertir a timestamp para una comparación más robusta
    $cleanedDayTimestamp = strtotime($cleanedDay['day']);

    // Buscar el día en generatedDays usando un bucle for (más control)
    foreach ($generatedDays as $key => $generatedDay) {
        $generatedDayTimestamp = strtotime($generatedDay['day']);
        if ($cleanedDayTimestamp === $generatedDayTimestamp) {
            $generatedDays[$key]['available'] = $cleanedDay['available'];
            break; // Salir del bucle interno si se encontró una coincidencia
        }
    }
}

// Filtramos los bookings para el mes y año seleccionados
$filteredBookings = array_filter($bookings, function($booking) use ($selectedMonth, $selectedYear) {
    $startDate = new \DateTime($booking->getStartDate());
    $endDate = new \DateTime($booking->getEndDate());
    return ($startDate->format('n') == $selectedMonth && $startDate->format('Y') == $selectedYear) ||
           ($endDate->format('n') == $selectedMonth && $endDate->format('Y') == $selectedYear);
});

// Función para verificar si un día está ocupado
function isBooked($day, $bookings) {
    if (empty($bookings)) {
        return false; // Si no hay reservas, el día no está ocupado
    }

    $currentDay = new \DateTime($day['day']);
    
    foreach ($bookings as $booking) {
        $startDate = new \DateTime($booking->getStartDate());
        $endDate = new \DateTime($booking->getEndDate());
        $status = $booking->getStatus();
        
        // Verificamos si la reserva cae dentro del rango del día y no está rechazada
        if ($currentDay >= $startDate && $currentDay <= $endDate && $status != '2') {
            //var_dump($booking);
            return $booking; // Devuelvo la booking para mostrar la info en el modal.
        }
    }
    return false; // El día no está ocupado
}

?>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <title>Calendario de disponibilidad</title>
</head>
<body>
<div class="container mt-4">
    <div class="container">
        <!-- Filtros de mes y año -->
        <form method="GET" action="" class="bg-light p-4 rounded shadow-sm mb-5">
            <h2 class="mb-5">Calendario de Disponibilidad</h2>
            <div class="row mb-4 ">
                <div class="col-md-4">
                    <label for="month" class="form-label">Mes:</label>
                    <select id="month" name="month" class="form-select">
                        <?php
                        for ($m = 1; $m <= 12; $m++) {
                            $selected = ($m == $selectedMonth) ? 'selected' : '';
                            echo "<option value='$m' $selected>" . date('F', mktime(0, 0, 0, $m, 1)) . "</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="year" class="form-label">Año:</label>
                    <select id="year" name="year" class="form-select">
                        <?php
                        $today = date('d/m/Y');
                        $currentYear = date('Y');
                        for ($y = $currentYear - 5; $y <= $currentYear + 5; $y++) {
                            $selected = ($y == $selectedYear) ? 'selected' : '';
                            echo "<option value='$y' $selected>$y</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end justify-content-end">
                    <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                </div>
            </div>
            <div class="d-flex flex-wrap">
                <div class="">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="form-action" onchange="saveCheckboxState()" name="form-action">
                        <label class="form-check-label" id="form-action">
                            Activar opciones adicionales
                        </label>
                    </div>
                    <div class="form-check options " id="checkboxLabel" style="display: none;" >
                        <p>Las opciones adicionales permiten <strong>activar o desactivar</strong> el estado de los días. 
                        Únicamente los días que están en <strong>"No Configurados"</strong> o <strong>"Disponibles"</strong>.
                        </p>
                    </div>
                </div>
            </div>
        </form>

    <div class="row" id="calendarContainer">
        <?php
        foreach ($generatedDays as $day) {
            $date = new \DateTime($day['day']);
            $formattedDate = $date->format('d/m/Y');
            $isAvailable = $day['available'] === '1';
            $isBooked = isBooked($day, $filteredBookings); // Usar el bookings filtrado para el mes actual
            $cursorPointer='pointer';
            //Casteo las fechas para que se comparen correctamente en el if de las card
            $CastFormattedDate = \DateTime::createFromFormat('d/m/Y', $formattedDate);
            $CastToday = \DateTime::createFromFormat('d/m/Y', $today);
            
            // Determina la clase de la card
            if ($isBooked) {
                $cardClass = 'bg-warning'; // Ocupado
                $bookingID = $isBooked->getBookingID();
                $bookingDetail=$isBooked;
                $tooltip = 'Reserva generada';
            } elseif ($isAvailable) {
                $bookingID=null;
                $formStatus=null;
                $cardClass = 'bg-success'; // Disponible
                $tooltip='Dia disponible para reservar';
            } elseif($CastFormattedDate <=$CastToday) {
                $bookingID=null;
                $formStatus = 'data-modal="true"'; //Reuso función para que no se pueda cambiar el estado.
                $cardClass = 'bg-secondary'; //Fuera de fecha
                $tooltip='Dia no disponible para actualizar';
                $cursorPointer='default';
            }else {
                $bookingID=null;
                $formStatus=null;
                $cardClass = 'bg-danger'; // No disponible
                $tooltip= 'Dia disponible pero no registrado';
                //$cursorPointer='default';
            }
            ?>
            <div class="col-4 col-md-2 mb-3"  >
            <?php if ($isBooked) { ?>
                <div class="card <?php echo $cardClass; ?>" data-date="<?php echo $day['day']; ?>" data-bs-toggle="modal" data-modal="true" type="button" data-bs-target="#reservationModal<?php echo $isBooked ? $isBooked->getBookingID() : ''; ?>">
                <div class="card-body text-center text-white">
                <h5 class="card-title"><?php echo $formattedDate; ?></h5>
                    <p class="card-text">
                        <?php
                            if ($isBooked->getStatus()=='1') {
                                echo 'Pending';
                            } elseif ($isBooked->getStatus()=='3') {
                                echo 'Waiting for Payment';
                            } elseif($isBooked->getStatus()=='4') {
                                echo 'Waiting for confirmation'; 
                            } elseif($isBooked->getStatus()=='5') {
                                echo 'Confirmed'; 
                            } elseif($isBooked->getStatus()=='6') {
                                echo 'Finish'; 
                            } elseif($isBooked->getStatus()=='7') {
                                echo 'Completed'; 
                            }else {
                                echo 'OverDue';
                            }
                        ?>
                        <span class="tooltip-icon" data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo $tooltip ?>"> <i class="bi bi-question-circle"></i></span>
                    </p>
                </div>
                </div>
            <?php } else { ?>
                <form id="form-<?php echo $day['day']; ?>"
                    <?php echo $formStatus; ?>
                    data-status=""
                    method="post"
                    class="card <?php echo $cardClass; ?> mb-3"
                    data-date="<?php echo $day['day']; ?>"
                >
                    <input type="hidden" name="date" value="<?php echo $day['day']; ?>">
                    <input type="hidden" name="available" value="<?php echo $day['available']; ?>">
                    
                    <div class="card-body text-center text-white" style="cursor: <?php echo $cursorPointer; ?>">
                        <h5 class="card-title"><?php echo $formattedDate; ?></h5>
                        <p class="card-text">
                            <?php
                            // Inicializar el texto y tooltip
                            $statusText = '';
                            $tooltip = '';

                            if ($isBooked) {
                                $statusText = 'Ocupado';
                                $tooltip = 'Día reservado';
                            } elseif ($isAvailable) {
                                $statusText = 'Disponible';
                                $tooltip = 'Día disponible para reservar';
                                $cursorPointer = 'default';
                            } elseif ($CastFormattedDate < $CastToday) {
                                $statusText = 'No disponible';
                                $tooltip = 'Día no disponible para actualizar';
                                $cursorPointer = 'default';
                            } else {
                                $statusText = 'No Configurado';
                                $tooltip = 'Día disponible pero no activo';
                            }
                            ?>
                            <span class="status-text"><?php echo $statusText; ?></span>
                            <span class="tooltip-icon" data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo $tooltip; ?>">
                                <i class="bi bi-question-circle"></i>
                            </span>
                        </p>
                    </div>
                </form>
            <?php } ?>
            </div>
            <?php if($bookingID) { ?>
            <div class="modal fade" id="reservationModal<?php echo $bookingID; ?>" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="modalLabel">Detalle de la Reserva</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <!-- Modal Body -->
                    <div class="modal-body bg-light">
                        <!-- Booking Details -->
                        <div class="container mb-4">
                            <h3 class="text-center text-primary mb-4">Detalles de la Reserva</h3>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="card p-3 border-light">
                                        <p class="mb-1"><strong>Del:</strong> <?php echo $bookingDetail->getStartDate(); ?></p>
                                        <p class="mb-1"><strong>Al:</strong> <?php echo $bookingDetail->getEndDate(); ?></p>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="card p-3 border-light">
                                        <p class="mb-1"><strong>Total:</strong> $<?php echo $bookingDetail->getTotalValue(); ?></p>
                                        <p class="mb-1"><strong>Reserva:</strong> $<?php echo $bookingDetail->getAmountReservation(); ?></p>
                                    </div>
                                </div>
                            </div>
                            <hr>
                        </div>

                        <!-- Pet Details -->
                        <div class="container mb-4">
                            <h3 class="text-center text-primary mb-4"><?php echo $bookingDetail->getPetID()->getPetName(); ?></h3>
                            <div class="card p-3 border-light">
                                <?php
                                if ($image = $bookingDetail->getPetID()->getPetImage()) {
                                    $imageData = base64_encode(file_get_contents($image));
                                    echo '<img src="data:image/jpeg;base64,'.$imageData.'" class="img-fluid rounded mb-3" alt="Imagen de la mascota">';
                                } else {
                                    echo "<h5 class='text-center text-muted'>No tiene imagen</h5>";
                                }
                                ?>
                                <div class="list-group">
                                    <p class="list-group-item"><strong>Raza:</strong> <?php echo $bookingDetail->getPetID()->getPetBreedByText(); ?></p>
                                    <p class="list-group-item"><strong>Peso:</strong> <?php echo $bookingDetail->getPetID()->getPetWeight(); ?> kg</p>
                                    <p class="list-group-item"><strong>Edad:</strong> <?php echo $bookingDetail->getPetID()->getPetAge(); ?> año(s)</p>
                                    <p class="list-group-item"><strong>Tamaño:</strong> <?php echo $bookingDetail->getPetID()->getPetSize(); ?></p>
                                    <p class="list-group-item"><strong>Descripción:</strong> <?php echo $bookingDetail->getPetID()->getPetDetails(); ?></p>
                                </div>
                            </div>
                            <hr>
                        </div>

                        <!-- Vaccination Plan -->
                        <div class="d-flex flex-wrap justify-content-center w-100 mt-2">
                            <h5>Plan de Vacunación</h5>
                        </div>
                        <div class="d-flex flex-wrap justify-content-center w-100">
                            <?php 
                            if (!$bookingDetail->getPetID()->getPetVaccinationPlan()) {
                                echo "<h3>Sin plan de vacunación disponible</h3>";
                            } else {
                                $vaccinationPlanPath = $bookingDetail->getPetID()->getPetVaccinationPlan();
                                $fileType = pathinfo($vaccinationPlanPath, PATHINFO_EXTENSION);
                                if ($fileType === 'pdf') {
                                // Muestro pdf
                                    echo '<iframe src="' . FRONT_ROOT . $vaccinationPlanPath . '" class="w-100 px-3" style="height: 60vh;" frameborder="0"></iframe>';
                                } elseif (in_array(strtolower($fileType), ['jpg', 'jpeg', 'png'])) {
                                    // Muestro imagen
                                    $vaccinationPlanData = base64_encode(file_get_contents($vaccinationPlanPath));
                                    echo '<img src="data:image/' . $fileType . ';base64,' . $vaccinationPlanData . '" class="img-fluid rounded shadow-sm" style="max-height: 800px;">';
                            } else {
                                echo "<h3>Formato de archivo no compatible</h3>";
                                }
                            }
                        ?>
                        </div>

                        <!-- Pet Video -->
                        <div class="container my-4 text-center ">
                            <h5><strong>Video</strong></h5>
                            <?php 
                            if (!$bookingDetail->getPetID()->getPetVideo()) {
                                echo "<h5 class='text-muted'>Sin video</h5>";
                            } else {
                                echo '<video width="100%" height="auto" controls class="rounded">';
                                echo '<source src="' .FRONT_ROOT . $bookingDetail->getPetID()->getPetVideo() . '" type="video/mp4">';
                                echo 'Your browser does not support the video tag.';
                                echo '</video>';
                            }
                            ?>
                        </div>

                        <!-- Owner Details -->
                        <div class="container">
                            <h3 class="text-center text-primary mb-4">Datos del Dueño</h3>
                            <div class="card p-4 shadow">
                                <div class="card-body">
                                    <h5 class="card-title text-center">Información del Propietario</h5>
                                    <ul class="list-unstyled">
                                        <li class="mb-3 p-2 border rounded bg-light">
                                            <strong>Nombre:</strong> 
                                            <?php echo $bookingDetail->getPetID()->getOwnerId()->getlastName() . " " . $bookingDetail->getPetID()->getOwnerId()->getfirstName(); ?>
                                        </li>
                                        <li class="mb-3 p-2 border rounded bg-light">
                                            <strong>Celular:</strong> 
                                            <?php echo $bookingDetail->getPetID()->getOwnerId()->getCellPhone(); ?>
                                        </li>
                                        <li class="mb-3 p-2 border rounded bg-light">
                                            <strong>Cantidad de mascotas:</strong> 
                                            <?php echo $bookingDetail->getPetID()->getOwnerId()->getPetAmount(); ?>
                                        </li>
                                        <li class="mb-3 p-2 border rounded bg-light">
                                            <strong>Email:</strong> 
                                            <?php echo $bookingDetail->getPetID()->getOwnerId()->getEmail(); ?>
                                        </li>
                                        <li class="mb-3 p-2 border rounded bg-light">
                                            <strong>Fecha de nacimiento:</strong> 
                                            <?php echo $bookingDetail->getPetID()->getOwnerId()->getbirthDate(); ?>
                                        </li>
                                        <li class="mb-3 p-2 border rounded bg-light">
                                            <strong>Descripción:</strong> 
                                            <?php echo $bookingDetail->getPetID()->getOwnerId()->getDescription(); ?>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
        <?php } 
        }?>
    </div>
</div>

<script>
function saveCheckboxState() {
    const checkbox = document.getElementById('form-action');
    localStorage.setItem('formActionCheckbox', checkbox.checked);
}


function activateCheckbox() {
    const checkboxState = localStorage.getItem('formActionCheckbox');
    const checkbox = document.getElementById('form-action');
    const label = document.getElementById('checkboxLabel');
    if (checkbox) {
        checkbox.checked = (checkboxState === 'true'); // Activa el checkbox
        // Muestra el label solo si el checkbox está marcado
        if (label) {
            label.style.display = checkbox.checked ? 'block' : 'none'; // Muestra o oculta el label basado en el estado del checkbox
        }
    }
}

// Aseguro de que el DOM este listo
document.addEventListener('DOMContentLoaded', function () {
    activateCheckbox(); // Activa el checkbox y muestra el label nuevamente desde localStorage

});


// Llama a activateCheckbox cuando se carga la página
window.onload = activateCheckbox;

//Se usa para cambiar el estado del checkbox de "Activar opciones adicionales"
$(document).ready(function() {
    $('#form-action').change(function() {
        if ($(this).is(':checked')) {
            $('.options').show(); // Muestra las opciones adicionales
        } else {
            $('.options').hide(); // Oculta las opciones adicionales
        }
    });

    // Agrega evento de clic a las tarjetas 
    //(Se usa para desactivar el clic sobre cards con booking registradas) o ejecutar el ajax
    $('.card').click(function(event) {
        event.preventDefault(); // Previene el comportamiento predeterminado del clic
        var date = $(this).data('date'); // Obtiene la fecha de data-date
        var formId = '#form-' + date; // Construye el ID del formulario
        var form = $(formId); // Selecciona el formulario por su ID

        if ($(this).data('modal') === true) {//No hago nada si la card tiene un modal configurado (Con booking)
            return; 
        }

        // Verifica el estado del checkbox antes de permitir el envío
        if ($('#form-action').is(':checked')) {
            if (!form.prop('disabled')) { // Verifica que el formulario no esté deshabilitado
                var dateInput = form.find('input[name="date"]'); // Obtiene el input de fecha
                var originalDate = dateInput.val(); // Obtiene la fecha

                // Solo formatea la fecha si tiene un valor
                if (originalDate) {
                    var formattedDate = formatDate(originalDate); // Formatea la fecha
                    dateInput.val(formattedDate); // Actualiza el valor de la fecha en el formulario
                    form.find('input[name="date"]').val(formattedDate); // Actualiza el valor de la fecha en el formulario
                    
                    //Ejecución del controller
                    $.ajax({
                        type: 'POST',
                        url: '<?php echo '/FinalProject4/Keeper/updateAvailabilityDay'; ?>', // La URL de accion del formulario (controlador)
                        data: form.serialize(), // Serializa los datos de la card (Input en estado hidden)
                        success: function(response) {
                            // Alerta de exito
                            const data = JSON.parse(response);//Formateo la respuesta para mostrar
                            if (data.status === 'success') {
                                Swal.fire({
                                    title: 'Éxito',
                                    text: data.message,
                                    icon: 'success',
                                    confirmButtonText: 'Aceptar'
                                }).then(() => {
                                    refreshDays(); // Refrescar los dias
                                });
                            } else {
                                Swal.fire({
                                    title: 'Error',
                                    text: data.message,
                                    icon: 'error',
                                    confirmButtonText: 'Aceptar'
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            // Alerta de error
                            Swal.fire({
                                title: 'Error',
                                text: 'Hubo un problema al actualizar la disponibilidad.',
                                icon: 'error',
                                confirmButtonText: 'Aceptar'
                            }).then(() => {
                                refreshDays();
                            });
                        }
                    });
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: 'La fecha no está definida.',
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                }
            } else {
                Swal.fire({
                    title: 'Formulario deshabilitado',
                    text: 'El formulario no está disponible.',
                    icon: 'warning',
                    confirmButtonText: 'Aceptar'
                });
            }
        } else {//Alerta si el checkbox no esta activo.
            Swal.fire({
                title: 'Opciones adicionales no activadas!',
                text: 'Por favor, si desea cambiar el estado del dia, active las Opciones Adicionales para continuar.',
                icon: 'warning',
                confirmButtonText: 'Aceptar'
            });
        }
    });
    //Funcion para actualizar el formulario
    function refreshDays() {
    $.ajax({
        url: '<?php echo '/FinalProject4/Keeper/refreshDays'; ?>',
        type: 'GET',
        success: function(data) {
            // Actualiza la vista con los cambios realizados
                saveCheckboxState();
                setTimeout(() => {
                    location.reload();
                }, 500);
        },
        error: function(xhr, status, error) {
            console.error('Error al refrescar los días:', error);
        }
    });
}
    // Función para formatear la fecha
    function formatDate(dateString) {
        var dateParts = dateString.split('-'); // Divide la fecha en partes
        return dateParts[0] + '-' + (dateParts[1].padStart(2, '0')) + '-' + (dateParts[2].padStart(2, '0')); // Formatea a YYYY-MM-DD
    }
});

</script>
</body>