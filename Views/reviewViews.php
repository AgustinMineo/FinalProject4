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
                                        <h5 class="card-title">Reseña ID: <?php echo $review->getReviewID(); ?></h5>
                                        <p class="card-text"><strong>Descripción:</strong> <?php echo $review->getDescription(); ?></p>
                                        <p class="card-text"><strong>Puntuación:</strong> <?php echo $review->getPoints(); ?></p>
                                        <p class="card-text"><strong>Reserva ID:</strong> <?php echo $review->getBooking()->getBookingID(); ?></p>
                                        <p class="card-text"><strong>Estado de la Reserva:</strong> <?php if($review->getBooking()->getStatus() == '7'){echo "Completed";}?></p>
                                        <p class="card-text"><strong>Fecha de Inicio:</strong> <?php echo $review->getBooking()->getStartDate(); ?></p>
                                        <p class="card-text"><strong>Fecha de Fin:</strong> <?php echo $review->getBooking()->getEndDate(); ?></p>
                                        <div class="container d-flex justify-content-center">
                                        
                                            <div class="d-flex flex-wrap mx-auto">
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#infoModal<?php echo $review->getReviewID(); ?>">Ver Detalle</button>
                                            </div>
                                        
                                            <div class="d-flex justify-content-center mx-auto">
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#bookingModal<?php echo $review->getBooking()->getBookingID(); ?>">Ver Booking</button>
                                            </div>
                                            
                                        </div>
                                        
                                        <!-- Modal Review -->
                                        <div class="modal fade" id="infoModal<?php echo $review->getReviewID(); ?>" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalLabel">Detalle completo</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body bg-light">
                                                        <h3 class="text-center">Reseña</h3>
                                                        <p><strong>Descripción:</strong> <?php echo $review->getDescription(); ?></p>
                                                        <p><strong>Puntuación:</strong> <?php echo $review->getPoints(); ?></p>
                                                        <p><strong>Reserva ID:</strong> <?php echo $review->getBooking()->getBookingID(); ?></p>
                                                        <p><strong>Estado de la Reserva:</strong> <?php if($review->getBooking()->getStatus() == '7'){echo "Completed";} ?></p>
                                                        <p><strong>Fecha de Inicio:</strong> <?php echo $review->getBooking()->getStartDate(); ?></p>
                                                        <p><strong>Fecha de Fin:</strong> <?php echo $review->getBooking()->getEndDate(); ?></p>
                                                        
                                                    </div>
                                        <!--Modal keeper-->
                                                    <?php if ($userRole == 2): ?> 
                                                    <div class="modal-body bg-light">
                                                        <hr>
                                                        <h3 class="text-center">Cuidador</h3>
                                                        <p><strong>Nombre:</strong> <?php echo $review->getBooking()->getKeeperID()->getLastName() ?> <?php echo $review->getBooking()->getKeeperID()->getfirstName() ?></p>
                                                        <p><strong>Correo:</strong> <?php echo $review->getBooking()->getKeeperID()->getEmail()  ?></p>
                                                        <p><strong>Descripción:</strong> <?php echo $review->getBooking()->getKeeperID()->getDescription()  ?></p>
                                                        <p><strong>Fecha de nacimiento:</strong> <?php echo $review->getBooking()->getKeeperID()->getbirthDate()  ?></p>
                                                        <p><strong>Tamaño de mascota:</strong> <?php echo $review->getBooking()->getKeeperID()->getAnimalSize()  ?></p>
                                                        <p><strong>Precio por dia:</strong> $<?php echo $review->getBooking()->getKeeperID()->getPrice()  ?></p>
                                                        <p><strong>CBU:</strong> <?php echo $review->getBooking()->getKeeperID()->getCBU()  ?></p>
                                                        <p><strong>Puntos:</strong> <?php echo $review->getBooking()->getKeeperID()->getPoints()  ?></p>
                                                        <hr>
                                                    </div>
                                                    <?php endif; ?>
                                                        <!--Modal owner--> 
                                                        <?php if ($userRole == 3): ?> 
                                                    <div class="modal-body bg-light">
                                                        <hr>
                                                        <h3 class="text-center">Dueño</h3>
                                                        <p><strong>Nombre y apellido:</strong> <?php echo $review->getBooking()->getPetID()->getOwnerId()->getLastName() ?> <?php echo $review->getBooking()->getKeeperID()->getfirstName() ?></p>
                                                        <p><strong>Correo:</strong> <?php echo $review->getBooking()->getPetID()->getOwnerId()->getEmail()  ?></p>
                                                        <p><strong>Descripción:</strong> <?php echo $review->getBooking()->getPetID()->getOwnerId()->getDescription()  ?></p>
                                                        <p><strong>Fecha de nacimiento:</strong> <?php echo $review->getBooking()->getPetID()->getOwnerId()->getbirthDate()  ?></p>
                                                        <p><strong>Cantidad de mascotas:</strong> <?php echo $review->getBooking()->getPetID()->getOwnerId()->getPetAmount()  ?></p>
                                            
                                                        <hr>
                                                    </div>
                                                    <?php endif; ?>
                                                        <!-- Modal owner-->
                                                        <div class="container">
                                                        <div class="modal-dialog modal-lg">
                                                            <h5 class="modal-title text-center" id="modalLabel">Detalles de <?php echo $review->getBooking()->getPetID()->getPetName(); ?> </h5>
                                                            </div>
                                                            <div class="modal-body bg-light d-flex flex-nowarp align-items-center justify-content-center">
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
                                                                <ul class="list-group list-group-flush">
                                                                        
                                                                        <li class="list-group-item"><strong>Breed:</strong> <?php echo $review->getBooking()->getPetID()->getPetBreedByText(); ?></li>
                                                                        
                                                                        <li class="list-group-item"><strong>Weight:</strong> <?php echo $review->getBooking()->getPetID()->getPetWeight(); ?> kg</li>
                                                                        
                                                                        <li class="list-group-item"><strong>Size:</strong> <?php echo $review->getBooking()->getPetID()->getPetSize(); ?></li>
                                                                        
                                                                        <li class="list-group-item"><strong>Age:</strong> <?php echo $review->getBooking()->getPetID()->getPetAge(); ?> years</li>

                                                                        <li class="list-group-item"><strong>Detalles:</strong> <?php echo $review->getBooking()->getPetID()->getPetDetails(); ?></li>
                                                                </ul>
                                                                <hr>
                                                                <div class="text-center">
                                                                        
                                                                        <h6>Vaccination Plan</h6>
                                                                        <?php 
                                                                        if (!$review->getBooking()->getPetID()->getPetVaccinationPlan()) {
                                                                            echo "<p>Not available</p>";
                                                                        } else {
                                                                            $VaccinationPlan = $review->getBooking()->getPetID()->getPetVaccinationPlan();
                                                                            $VaccinationPlanData = base64_encode(file_get_contents($VaccinationPlan));
                                                                            echo '<img src="data:image/jpeg;base64,'.$VaccinationPlanData.'" class="img-fluid" alt="Plan de vacunación">';
                                                                        }
                                                                        ?>
                                                                </div>
                                                                <hr>
                                                                <div class="text-center">
                                                                        
                                                                        <h6>Video</h6>
                                                                        <?php 
                                                                        if (!$review->getBooking()->getPetID()->getPetVideo()) {
                                                                            echo "<p>Not available</p>";
                                                                        } else {
                                                                            echo '<video width="100%" height="240" controls>';
                                                                            echo '<source src="' . FRONT_ROOT . $review->getBooking()->getPetID()->getPetVideo() . '" type="video/mp4">';
                                                                            echo 'Your browser does not support the video tag.';
                                                                            echo '</video>';
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
                                                        <p><strong>Reserva ID:</strong> <?php echo $review->getBooking()->getBookingID(); ?></p>
                                                        <p><strong>Estado de la Reserva:</strong> <?php if($review->getBooking()->getStatus() == '7'){echo "Completed";} ?></p>
                                                        <p><strong>Fecha de Inicio:</strong> <?php echo $review->getBooking()->getStartDate(); ?></p>
                                                        <p><strong>Fecha de Fin:</strong> <?php echo $review->getBooking()->getEndDate(); ?></p>
                                                        <p><strong>Valor Total:</strong> <?php echo $review->getBooking()->getTotalValue(); ?></p>
                                                        <p><strong>Mascota</strong> <?php echo $review->getBooking()->getPetID()->getPetName(); ?></p>
                                                        <p><strong>Cuidador:</strong> <?php echo $review->getBooking()->getKeeperID()->getfirstName(); ?> <?php echo $review->getBooking()->getKeeperID()->getlastName(); ?></p>
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
