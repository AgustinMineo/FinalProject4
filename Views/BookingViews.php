<?php
namespace Views;
require_once("validate-session.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keeper Availability</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <script>
        function validateSelection() {
            const keeperSelected = document.querySelector('input[name="keeperID"]:checked');
            const petSelected = document.querySelector('input[name="petID"]:checked');
            const button = document.getElementById('reserveButton');

            if (keeperSelected && petSelected) {
                const keeperSize = keeperSelected.dataset.size;
                const petSize = petSelected.dataset.size;

                button.disabled = keeperSize !== petSize; // Habilitar solo si el tamaño coincide
            } else {
                button.disabled = true; // Deshabilitar si no hay selecciones
            }
        }

        function showAlert() {
            const keeperSelected = document.querySelector('input[name="keeperID"]:checked');
            const petSelected = document.querySelector('input[name="petID"]:checked');
            
            if (keeperSelected && petSelected) {
                const keeperName = `${keeperSelected.nextElementSibling.textContent}`;
                const petName = `${petSelected.nextElementSibling.textContent}`;
                
                Swal.fire({
                    title: 'Confirmar Reserva',
                    text: `¿Deseas reservar con ${keeperName} y ${petName}?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, reservar',
                    cancelButtonText: 'No, cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.querySelector('form').submit();
                    }
                });
            }
        }
    </script>
</head>
<body>
    <div class="container d-flex p-5 flex-wrap flex-row w-100 bg-light">
        <div class="container d-flex col-md-4 w-auto p-5 flex-wrap bg-light text-center">
            <div class='alert alert-success'>
                <h1>Keepers Disponibles</h1>
            </div>
        </div>
        
        <form action="<?php echo '/FinalProject4/' ?>Booking/newBooking" method="get" class="w-100 d-flex flex-wrap">
            <?php
            if ($listKeepers) {
                foreach ($listKeepers as $keeper) {
                    ?>
                    <div class="card d-flex flex-wrap m-3" style="width: 25%; border: none; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);">
                        <img src="<?php
                        if($keeper->getImage()){
                            echo FRONT_ROOT . $keeper->getImage();
                        }else{
                            echo FRONT_ROOT . USER_PATH .'\userDefaultImage.jpg';
                        }?>
                        " class="card-img-top" alt="KEEPER IMG" style="border-radius: 0.5rem;">
                        <div class="card-body text-center">
                            <h5 class="card-title" style="font-weight: bold; color: #007BFF;"><?php echo $keeper->getLastName(); ?> <?php echo $keeper->getfirstName(); ?></h5>
                            <p class="card-text">Experienced and loving keeper ready to take care of your pets.</p>
                            <hr>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item bg-light text-dark">Cellphone: <strong><?php echo $keeper->getCellPhone(); ?></strong></li>
                                <li class="list-group-item bg-light text-dark">Price: <strong>$<?php echo $keeper->getPrice(); ?></strong></li>
                                <li class="list-group-item bg-light text-dark">Pet Size: <strong><?php echo $keeper->getAnimalSize()?></strong></li>
                                <li class="list-group-item bg-light text-dark">Available From: <strong><?php $date = date_create($value1); echo date_format($date, "d/m/Y"); ?></strong></li>
                                <li class="list-group-item bg-light text-dark">Until: <strong><?php $date = date_create($value2); echo date_format($date, "d/m/Y"); ?></strong></li>
                                <li class="list-group-item bg-light text-dark">Ranking: <strong><?php echo intval($keeper->getPoints())?></strong></li>
                            </ul>
                            <div class="mt-3 text-center w-100">
                                <div class="checkboxID actionCheck ">
                                    <label style="width:100%; padding:0; margin:0;">
                                        <input type="radio" name="keeperID" value="<?php echo $keeper->getEmail(); ?>" data-size="<?php echo $keeper->getAnimalSize(); ?>" onclick="validateSelection()">
                                        <span class="btn btn-primary mt-2" style="width: 100%; color: white;">Reservar con <?php echo $keeper->getLastName(); ?></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo "<div class='container d-flex col-md-4 w-auto p-5 flex-wrap bg-light text-center'><div class='alert alert-danger'><h1>There are no keepers available!!</h1></div></div>";
            }
            ?>

        <div class="container w-100 text-center">
            <div class='alert alert-success'><h1>Mascotas disponibles</h1></div>   
        </div>
        <div class="container d-flex col-md-4 w-auto p-5 flex-row flex-wrap w-100">
            <?php
            if ($petList) {
                foreach ($petList as $petSize) {
                    ?>
                    <div class="card m-3 d-flex flex-wrap" style="width: 20rem; border: none; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);">
                        <div class="container" style="max-width: 100%; height: 200px; overflow: hidden; border-radius: 0.5rem;">
                            <?php 
                                if ($image = $petSize->getPetImage()) {
                                    $imageData = base64_encode(file_get_contents($image));
                                    echo '<img src="data:image/jpeg;base64,' . $imageData . '" class="c-block img-fluid" style="width: 100%; height: 100%; object-fit: cover; object-position: center;">';
                                } else {
                                    echo '<img src="' . IMG_PATH . 'keeperDog.svg" class="card-img-top" style="width: 100%; height: 100%; object-fit: cover; object-position: center;">';
                                }
                            ?>
                        </div>
                        <div class="card-body text-center">
                            <h5 class="card-title" style="font-weight: bold; color: #007BFF;"><?php echo $petSize->getPetName(); ?></h5>
                            <p class="card-text" style="font-style: italic;"><strong><?php echo $petSize->getPetBreedByText(); ?></strong></p>
                            <hr>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item bg-light text-dark">Pet Name: <strong><?php echo $petSize->getPetName(); ?></strong></li>
                                <li class="list-group-item bg-secondary text-white">Age: <strong><?php echo $petSize->getPetAge(); ?></strong></li>
                                <li class="list-group-item bg-light text-dark">Pet Size: <strong><?php echo $petSize->getPetSize(); ?></strong></li>
                            </ul>
                            <div class="checkboxID actionCheck mt-3">
                                <label style="width:100%; padding:0; margin:0;">
                                    <input type="radio" name="petID" value="<?php echo $petSize->getPetID(); ?>" data-size="<?php echo $petSize->getPetSize(); ?>" onclick="validateSelection()">
                                    <span class="btn btn-primary mt-2" style="width: 100%; color: white;">Reservar con <strong><?php echo $petSize->getPetName(); ?></strong></span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <?php
                }
            } else {
                echo "<div class='container w-100 text-center'><div class='alert alert-success'><h1>Tus pets disponibles</h1></div></div>";
            }
            ?>
            <input type="hidden" name="startDate" value="<?php echo $value1; ?>">
            <input type="hidden" name="finishDate" value="<?php echo $value2; ?>">
            <button type="button" id="reserveButton" class="btn btn-info d-flex justify-content-center align-content-center w-100" onclick="showAlert()" disabled>Reservation</button>  
        </div>
    </div>
    </form>
</body>
</html>
