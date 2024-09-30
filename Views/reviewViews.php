<?php
namespace Views;
require_once("validate-session.php");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/review.css">
    <title>Listado de Reseñas</title>
</head>
<body>
    <main class="py-2">
        <div class="container d-flex flex-wrap bg-light">
            <section id="listado" class="mb-5 justify-content-center w-100">
                <div class="container d-flex flex-wrap justify-content-center w-100">
                    <h2 class="mb-4 mt-5">Listado de Reseñas</h2>
                    <div class="container d-flex flex-wrap justify-content-center">
                        <?php
                        if (!$reviewList) {
                            echo "<div class='d-flex flex-wrap justify-content-center w-100'><h1>No hay reseñas disponibles!</h1></div>";
                        } else {
                            
                            foreach ($reviewList as $review) {
                                ?>
                                    <div class="card m-3" style="width: 20rem;">
                                        <div class="card-body ">
                                        <h5 class="card-title text-primary">Reseña ID: <?php echo $review->getReviewID(); ?></h5>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item">
                                            <strong>Reseña: <br></strong> <?php echo $review->getDescription(); ?>
                                        </li>
                                        <li class="list-group-item">
                                            <strong>Puntuación:</strong> <?php echo $review->getPoints(); ?>
                                        </li>
                                        <li class="list-group-item">
                                            <strong>Reserva ID:</strong> <?php echo $review->getBooking()->getBookingID(); ?>
                                        </li>
                                        <li class="list-group-item">
                                            <strong>Estado de la Reserva:</strong> 
                                            <?php echo $review->getBooking()->getStatus(); ?>
                                        </li>
                                        <li class="list-group-item">
                                            <strong>Fecha de Inicio:</strong> <?php echo $review->getBooking()->getStartDate(); ?>
                                        </li>
                                        <li class="list-group-item">
                                            <strong>Fecha de Fin:</strong> <?php echo $review->getBooking()->getEndDate(); ?>
                                        </li>
                                    </ul>
                                    <div class="d-flex justify-content-center mt-3">
                                        <button type="button" class="btn btn-outline-info me-2" data-bs-toggle="modal" data-bs-target="#infoModal<?php echo $review->getReviewID(); ?>">Ver Detalle</button>
                                        <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#bookingModal<?php echo $review->getBooking()->getBookingID(); ?>">Ver Booking</button>
                                    </div>
                                        
                                        <!-- Modal Review -->
                                        <div class="modal fade" id="infoModal<?php echo $review->getReviewID(); ?>" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modalLabel">Detalle Completo de la Reseña</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body bg-light">
                                                    <h3 class="text-center">Detalles de la Reseña</h3>
                                                    <hr>
                                                    <ul class="list-group list-group-flush">
                                                        <li class="list-group-item">
                                                            <strong>Reseña:</strong> <?php echo $review->getDescription(); ?>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <strong>Puntuación:</strong>
                                                            <span class="stars">
                                                                <?php 
                                                                    $points = $review->getPoints();
                                                                    for ($i = 0; $i < 5; $i++) {
                                                                        echo ($i < $points) ? '⭐' : '☆'; 
                                                                    }
                                                                ?>
                                                            </span>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <strong>Reserva ID:</strong> <?php echo $review->getBooking()->getBookingID(); ?>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <strong>Estado de la Reserva:</strong>
                                                            <?php 
                                                                echo $review->getBooking()->getStatus();
                                                            ?>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <strong>Fecha de Inicio:</strong> <?php echo date("d/m/Y", strtotime($review->getBooking()->getStartDate())); ?>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <strong>Fecha de Fin:</strong> <?php echo date("d/m/Y", strtotime($review->getBooking()->getEndDate())); ?>
                                                        </li>
                                                    </ul>
                                                    <hr>
                                                </div>

                                        <!--Modal keeper-->
                                                    <?php if ($userRole === 2 || $userRole=== 1 ): ?> 
                                                        <div class="modal-body bg-light p-4 rounded">
                                                            <h3 class="text-center text-primary">Detalles del Cuidador</h3>
                                                            <ul class="list-group list-group-flush">
                                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                    <strong>Nombre:</strong>
                                                                    <span><?php echo $review->getBooking()->getKeeperID()->getLastName() ?> <?php echo $review->getBooking()->getKeeperID()->getFirstName() ?></span>
                                                                </li>
                                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                    <strong>Correo:</strong>
                                                                    <span><?php echo $review->getBooking()->getKeeperID()->getEmail() ?></span>
                                                                </li>
                                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                    <strong>Descripción:</strong>
                                                                    <span><?php echo $review->getBooking()->getKeeperID()->getDescription() ?></span>
                                                                </li>
                                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                    <strong>Fecha de Nacimiento:</strong>
                                                                    <span><?php echo date("d/m/Y", strtotime($review->getBooking()->getKeeperID()->getBirthDate())) ?></span>
                                                                </li>
                                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                    <strong>Tamaño de Mascota:</strong>
                                                                    <span><?php echo $review->getBooking()->getKeeperID()->getAnimalSize() ?></span>
                                                                </li>
                                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                    <strong>Precio por Día:</strong>
                                                                    <span>$<?php echo number_format($review->getBooking()->getKeeperID()->getPrice(), 2) ?></span>
                                                                </li>
                                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                    <strong>CBU:</strong>
                                                                    <span><?php echo $review->getBooking()->getKeeperID()->getCBU() ?></span>
                                                                </li>
                                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                    <strong>Puntos:</strong>
                                                                    <span><?php echo $review->getBooking()->getKeeperID()->getPoints() ?></span>
                                                                </li>
                                                            </ul>
                                                            <hr>
                                                            
                                                        </div>


                                                    <?php endif; ?>
                                                        <!--Modal owner--> 
                                                        <?php if ($userRole == 3 || $userRole===1): ?> 
                                                            <div class="modal-body bg-light">
                                                                <h3 class="text-center">Dueño</h3>
                                                                <hr>
                                                                <div class="owner-info">
                                                                <ul class="list-group list-group-flush">
                                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                        <strong>Nombre:</strong>
                                                                        <span><?php echo $review->getBooking()->getPetID()->getOwnerId()->getLastName(); ?> <?php echo $review->getBooking()->getPetID()->getOwnerId()->getFirstName(); ?></span>
                                                                    </li>
                                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                        <strong>Correo:</strong>
                                                                        <span><?php echo $review->getBooking()->getPetID()->getOwnerId()->getEmail(); ?></span>
                                                                    </li>
                                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                        <strong>Descripción:</strong>
                                                                        <span><?php echo $review->getBooking()->getPetID()->getOwnerId()->getDescription(); ?></span>
                                                                    </li>
                                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                        <strong>Fecha de nacimiento:</strong>
                                                                        <span><?php echo date("d/m/Y", strtotime($review->getBooking()->getPetID()->getOwnerId()->getBirthDate())); ?></span>
                                                                    </li>
                                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                        <strong>Cantidad de mascotas:</strong>
                                                                        <span><?php echo $review->getBooking()->getPetID()->getOwnerId()->getPetAmount(); ?></span>
                                                                    </li>
                                                                </div>
                                                                <hr>
                                                            </div>
                                                    <?php endif; ?>
                                                        <!-- Modal owner-->
                                                        <!--Modal Pet-->
                                                        <div class="container">
                                                            <div class="modal-dialog modal-lg">
                                                                <h5 class="modal-title text-center" id="modalLabel">Detalles de <?php echo $review->getBooking()->getPetID()->getPetName(); ?> </h5>
                                                                </div>
                                                                <div class="modal-body bg-light d-flex flex-nowrap align-items-center justify-content-center">
                                                                    <div class="card m-3" style="width: 75%;">
                                                                        <?php 
                                                                        if ($image = $review->getBooking()->getPetID()->getPetImage()) {
                                                                            $imageData = base64_encode(file_get_contents($image));
                                                                            echo '<img src="data:image/jpeg;base64,'.$imageData.'" class="card-img-top" alt="Imagen de '. $review->getBooking()->getPetID()->getPetName() .'">';
                                                                        } else {
                                                                            echo '<img src="'.IMG_PATH.'default-pet.jpg" class="card-img-top" alt="Imagen por defecto">';
                                                                        }
                                                                        ?>
                                                                        <div class="card-body">
                                                                            <h5 class="card-title text-center"><?php echo $review->getBooking()->getPetID()->getPetName(); ?></h5>
                                                                            <hr>
                                                                            <div class="pet-details">
                                                                                <ul class="list-group list-group-flush">
                                                                                    <li class="list-group-item"><strong>Raza:</strong> <?php echo $review->getBooking()->getPetID()->getPetBreedByText(); ?></li>
                                                                                    <li class="list-group-item"><strong>Peso:</strong> <?php echo $review->getBooking()->getPetID()->getPetWeight(); ?></li>
                                                                                    <li class="list-group-item"><strong>Tamaño:</strong> <?php echo $review->getBooking()->getPetID()->getPetSize(); ?></li>
                                                                                    <li class="list-group-item"><strong>Edad:</strong> <?php echo $review->getBooking()->getPetID()->getPetAge(); ?> años</li>
                                                                                    <li class="list-group-item"><strong>Detalles:</strong> <?php echo $review->getBooking()->getPetID()->getPetDetails(); ?></li>
                                                                                </ul>
                                                                            </div>
                                                                            <hr>
                                                                            <div class="d-flex flex-wrap justify-content-center w-100 mt-2">
                                                                                <h5>Plan de Vacunación</h5>
                                                                            </div>
                                                                            <div class="d-flex flex-wrap justify-content-center w-100">
                                                                                <?php 
                                                                                if (!$review->getBooking()->getPetID()->getPetVaccinationPlan()) {
                                                                                    echo "<h3>Sin plan de vacunación disponible</h3>";
                                                                                } else {
                                                                                    $vaccinationPlanPath = $review->getBooking()->getPetID()->getPetVaccinationPlan();
                                                                                    $fileType = pathinfo($vaccinationPlanPath, PATHINFO_EXTENSION);

                                                                                    if ($fileType === 'pdf') {
                                                                                        // Mostrar PDF con iframe
                                                                                        echo '<iframe src="' . FRONT_ROOT . $vaccinationPlanPath . '" class="w-100 px-3" style="height: 60vh;" frameborder="0"></iframe>';
                                                                                    } elseif (in_array(strtolower($fileType), ['jpg', 'jpeg', 'png'])) {
                                                                                        // Mostrar imagen
                                                                                        $vaccinationPlanData = base64_encode(file_get_contents($vaccinationPlanPath));
                                                                                        echo '<img src="data:image/' . $fileType . ';base64,' . $vaccinationPlanData . '" class="img-fluid rounded shadow-sm" style="max-height: 800px;">';
                                                                                    } else {
                                                                                        echo "<h3>Formato de archivo no compatible</h3>";
                                                                                    }
                                                                                }
                                                                                ?>
                                                                            </div>

                                                                            <hr>
                                                                            <div class="text-center">
                                                                                <h6>Video de <?php echo $review->getBooking()->getPetID()->getPetName(); ?></h6>
                                                                                <p class="text-muted">Aquí puedes ver un video de <?php echo $review->getBooking()->getPetID()->getPetName(); ?> disfrutando de su tiempo.</p>
                                                                                <?php 
                                                                                if (!$review->getBooking()->getPetID()->getPetVideo()) {
                                                                                    echo "<p>No disponible</p>";
                                                                                } else {
                                                                                    echo '<div class="video-container" style="animation: fadeIn 0.5s ease;">'; // Añadimos una animación de entrada
                                                                                    echo '<video width="100%" height="240" controls>';
                                                                                    echo '<source src="' . FRONT_ROOT . $review->getBooking()->getPetID()->getPetVideo() . '" type="video/mp4">';
                                                                                    echo 'Tu navegador no soporta la etiqueta de video.';
                                                                                    echo '</video>';
                                                                                    echo '</div>'; // Cerramos el contenedor del video
                                                                                }
                                                                                ?>
                                                                            </div>

                                                                        </div>   
                                                                    </div>
                                                                </div>
                                                    </div>
                                                    
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Modal Review -->
                                        <!--Modal Owner-->
                                        
                                        <!-- Modal Booking -->
                                        <div class="modal fade" id="bookingModal<?php echo $review->getBooking()->getBookingID(); ?>" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modalLabel">Detalles de la Reserva</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body bg-light">
                                                    <h3 class="text-center">Información de la Reserva</h3>
                                                    <hr>
                                                    <div class="reservation-details" style="animation: fadeIn 0.5s ease;">
                                                        <div class="mb-3 border rounded p-3 hover-effect">
                                                            <p><strong>Reserva ID:</strong> <?php echo $review->getBooking()->getBookingID(); ?></p>
                                                        </div>
                                                        <div class="mb-3 border rounded p-3 hover-effect">
                                                            <p><strong>Estado de la Reserva:</strong> 
                                                                <?php 
                                                                    echo $review->getBooking()->getStatus(); 
                                                                ?>
                                                            </p>
                                                        </div>
                                                        <div class="mb-3 border rounded p-3 hover-effect">
                                                            <p><strong>Fecha de Inicio:</strong> <?php echo date("d/m/Y", strtotime($review->getBooking()->getStartDate())); ?></p>
                                                        </div>
                                                        <div class="mb-3 border rounded p-3 hover-effect">
                                                            <p><strong>Fecha de Fin:</strong> <?php echo date("d/m/Y", strtotime($review->getBooking()->getEndDate())); ?></p>
                                                        </div>
                                                        <div class="mb-3 border rounded p-3 hover-effect">
                                                            <p><strong>Valor Total:</strong> $<?php echo number_format($review->getBooking()->getTotalValue(), 2); ?></p>
                                                        </div>
                                                        <div class="mb-3 border rounded p-3 hover-effect">
                                                            <p><strong>Mascota:</strong> <?php echo $review->getBooking()->getPetID()->getPetName(); ?></p>
                                                        </div>
                                                        <div class="mb-3 border rounded p-3 hover-effect">
                                                            <p><strong>Cuidador:</strong> <?php echo $review->getBooking()->getKeeperID()->getFirstName(); ?> <?php echo $review->getBooking()->getKeeperID()->getLastName(); ?></p>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                         <!-- Modal Review -->
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
