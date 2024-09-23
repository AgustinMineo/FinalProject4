<?php
namespace Views;
require_once("validate-session.php");

// Obtener mes y año seleccionados
$selectedMonth = isset($_GET['month']) ? intval($_GET['month']) : date('n');
$selectedYear = isset($_GET['year']) ? intval($_GET['year']) : date('Y');

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
    <title>Listado de Reseñas</title>
</head>
<!-- HTML para mostrar el calendario -->
<div class="container mt-4">
    <h2>Calendario de Disponibilidad</h2>

    <!-- Filtros de mes y año -->
    <form method="GET" action="">
        <div class="row mb-4">
            <div class="col-md-4">
                <label for="month">Mes:</label>
                <select id="month" name="month" class="form-control">
                    <?php
                    for ($m = 1; $m <= 12; $m++) {
                        $selected = ($m == $selectedMonth) ? 'selected' : '';
                        echo "<option value='$m' $selected>" . date('F', mktime(0, 0, 0, $m, 1)) . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-4">
                <label for="year">Año:</label>
                <select id="year" name="year" class="form-control">
                    <?php
                    $currentYear = date('Y');
                    for ($y = $currentYear; $y <= $currentYear + 5; $y++) {
                        $selected = ($y == $selectedYear) ? 'selected' : '';
                        echo "<option value='$y' $selected>$y</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-center mt-4 w-25 ">
                <label>&nbsp;</label>
                <button type="submit" class="btn btn-primary btn-block w-50">Filtrar</button>
            </div>
        </div>
    </form>

    <!-- Mostrar días del mes -->
    <div class="row">
        <?php
        foreach ($generatedDays as $day) {
            $date = new \DateTime($day['day']);
            $formattedDate = $date->format('d/m/Y');
            $isAvailable = $day['available'] === '1';
            $isBooked = isBooked($day, $filteredBookings); // Usar el bookings filtrado para el mes actual

            // Determina la clase de la card
            if ($isBooked) {
                $cardClass = 'bg-warning'; // Ocupado
                $bookingID = $isBooked->getBookingID();
                $bookingDetail=$isBooked;
            } elseif ($isAvailable) {
                $bookingID=null;
                $cardClass = 'bg-success'; // Disponible
            } else {
                $bookingID=null;
                $cardClass = 'bg-danger'; // No disponible
            }
            ?>
            <div class="col-4 col-md-2 mb-3">
            <div class="card <?php echo $cardClass; ?>" data-date="<?php echo $day['day']; ?>"data-bs-toggle="modal" type="button" data-bs-target="#reservationModal<?php echo $isBooked ? $isBooked->getBookingID() : ''; ?>">
                    <div class="card-body text-center text-white">
                        <h5 class="card-title"><?php echo $formattedDate; ?></h5>
                        <p class="card-text">
                            <?php
                            if ($isBooked) {
                                echo 'Ocupado';
                            } elseif ($isAvailable) {
                                echo 'Disponible';
                            } else {
                                echo 'No Configurado';
                            }
                            ?>
                        </p>
                    </div>
                </div>
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
                                <div class="col-md-6">
                                    <p><strong>Del:</strong> <?php echo $bookingDetail->getStartDate(); ?></p>
                                    <p><strong>Al:</strong> <?php echo $bookingDetail->getEndDate(); ?></p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Total:</strong> $<?php echo $bookingDetail->getTotalValue(); ?></p>
                                    <p><strong>Reserva:</strong> $<?php echo $bookingDetail->getAmountReservation(); ?></p>
                                </div>
                            </div>
                            <hr>
                        </div>

                        <!-- Pet Details -->
                        <div class="container mb-4">
                            <h3 class="text-center text-primary mb-4"><?php echo $bookingDetail->getPetID()->getPetName(); ?></h3>
                            <div class="card p-3">
                                <?php
                                if ($image = $bookingDetail->getPetID()->getPetImage()) {
                                    $imageData = base64_encode(file_get_contents($image));
                                    echo '<img src="data:image/jpeg;base64,'.$imageData.'" class="img-fluid rounded mb-3" alt="Imagen de la mascota">';
                                } else {
                                    echo "<h5 class='text-center text-muted'>No tiene imagen</h5>";
                                }
                                ?>

                                <p><strong>Raza:</strong> <?php echo $bookingDetail->getPetID()->getPetBreedByText(); ?></p>
                                <p><strong>Peso:</strong> <?php echo $bookingDetail->getPetID()->getPetWeight(); ?> kg</p>
                                <p><strong>Edad:</strong> <?php echo $bookingDetail->getPetID()->getPetAge(); ?> año(s)</p>
                                <p><strong>Tamaño:</strong> <?php echo $bookingDetail->getPetID()->getPetSize(); ?></p>
                                <p><strong>Descripción:</strong> <?php echo $bookingDetail->getPetID()->getPetDetails(); ?></p>
                            </div>
                            <hr>
                        </div>

                        <!-- Vaccination Plan -->
                        <div class="container mb-4 text-center">
                            <h5><strong>Plan de Vacunación</strong></h5>
                            <?php
                            if ($imagePetVaccinationPlan = $bookingDetail->getPetID()->getPetVaccinationPlan()) {
                                $imageDataimagePetVaccinationPlan = base64_encode(file_get_contents($imagePetVaccinationPlan));
                                echo '<img src="data:image/jpeg;base64,'.$imageDataimagePetVaccinationPlan.'" class="img-fluid rounded mb-3" alt="Plan de vacunación">';
                            } else {
                                echo "<h5 class='text-muted'>No tiene imagen de plan de vacunación</h5>";
                            }
                            ?>
                        </div>

                        <!-- Pet Video -->
                        <div class="container mb-4 text-center">
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
                            <div class="card p-3">
                                <p><strong>Nombre:</strong> <?php echo $bookingDetail->getPetID()->getOwnerId()->getlastName() . " " . $bookingDetail->getPetID()->getOwnerId()->getfirstName(); ?></p>
                                <p><strong>Celular:</strong> <?php echo $bookingDetail->getPetID()->getOwnerId()->getCellPhone(); ?></p>
                                <p><strong>Cantidad de mascotas:</strong> <?php echo $bookingDetail->getPetID()->getOwnerId()->getPetAmount(); ?></p>
                                <p><strong>Email:</strong> <?php echo $bookingDetail->getPetID()->getOwnerId()->getEmail(); ?></p>
                                <p><strong>Fecha de nacimiento:</strong> <?php echo $bookingDetail->getPetID()->getOwnerId()->getbirthDate(); ?></p>
                                <p><strong>Descripción:</strong> <?php echo $bookingDetail->getPetID()->getOwnerId()->getDescription(); ?></p>
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




