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
    <style>
        .checkboxID{
            background-color: white;
            border-radius: 4px;
            border: 1px solid #fff;
            overflow: hidden;
            float: left;
            display: flex;
            justify-content: center;
            align-items:center;
        }
        
        .checkboxID label {
            float: left; line-height: 3.0em;
            width: 8.0em; height: 3.0em;
        }
        .checkboxID label span {
            text-align: center;
            display: block;
        }
        
        .checkboxID label input {
        position: absolute;
         display: none;
        }
.checkboxID label input + span{color: #000;}
.actionCheck input:checked + span{background-color: #80ff00; width:100%;}

.checkboxID input:checked + span {
    width: 100%;
    color:black;
    text-shadow: 0 0  6px rgba(0, 0, 0, 0.8);
}
    </style>

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
    </script>
</head>
<body>
    <div class="container d-flex p-5 flex-wrap flex-row w-100 bg-light">
        <div class="container d-flex col-md-4 w-auto p-5 flex-wrap bg-light text-center">
            <div class='alert alert-success'>
                <h1>Los keeper disponibles entre las fechas seleccionadas son</h1>
            </div>
        </div>
        
        <form action="<?php echo '/FinalProject4/' ?>Booking/newBooking" method="get" class="w-100 d-flex flex-wrap">
            <?php
            if ($listKeepers) {
                foreach ($listKeepers as $keeper) {
                    ?>
                    <div class="card d-flex flex-wrap m-5" style="width: 25%;">
                        <img src="<?php echo IMG_PATH ?>keeperDog.svg" class="card-img-top" alt="KEEPER IMG">
                        <div class="card-body">
                            <h5 class="card-title">Keeper</h5>
                            <p class="card-text">Here you have a keeper</p>
                        </div>
                        <ul class="list-group list-group-flush text-center">
                            <li class="list-group-item bg-secondary text-white"><?php echo $keeper->getLastName(); ?> <?php echo $keeper->getfirstName(); ?></li>
                            <li class="list-group-item bg-light text-dark">Cellphone : <?php echo $keeper->getCellPhone(); ?></li>
                            <li class="list-group-item bg-secondary text-white">Price : $<?php echo $keeper->getPrice(); ?></li>
                            <li class="list-group-item bg-light text-dark">Pet Size : <?php echo $keeper->getAnimalSize()?></li>
                            <li class="list-group-item bg-secondary text-white">Del <?php $date=date_create($value1); echo date_format($date,"d/m/Y");?></li>
                            <li class="list-group-item bg-light text-dark">Al <?php $date=date_create($value2); echo date_format($date,"d/m/Y");?></li>
                            <div class="checkboxID actionCheck">
                                <label style=" width:100%; padding:0; margin:0;">
                                    <input type="radio" name="keeperID" value="<?php echo $keeper->getEmail(); ?>" data-size="<?php echo $keeper->getAnimalSize(); ?>" onclick="validateSelection()">
                                    <span>Reservar con <?php echo $keeper->getLastName(); ?> <?php echo $keeper->getfirstName(); ?></span>
                                </label>
                            </div>
                        </ul>
                    </div>
                    <?php
                }
            } else {
                echo "<div class='container d-flex col-md-4 w-auto p-5 flex-wrap bg-light text-center'><div class='alert alert-danger'><h1>There are no keepers available!!</h1></div></div>";
            }
            ?>

        <div class="container w-100 text-center">
            <div class='alert alert-success'><h1>Tus pets disponibles</h1></div>   
        </div>
        <div class="container d-flex col-md-4 w-auto p-5 flex-row flex-wrap w-100">
            <?php
            if ($petList) {
                foreach ($petList as $petSize) {
                    ?>
                    <div class="card d-flex flex-wrap m-5" style="width: 18rem;"> 
                    <?php 
                        if ($image = $petSize->getPetImage()) {
                            $imageData = base64_encode(file_get_contents($image));
                            echo '<img src="data:image/jpeg;base64,' . $imageData . '" class="card-img-top">';
                        } else {
                            echo '<img src="' . IMG_PATH . 'keeperDog.svg" class="card-img-top">';
                        }
                    ?>
                        <div class="card-body">
                            <h5 class="card-title text-center"><strong><?php echo $petSize->getPetName(); ?></strong></h5>
                            <hr>
                            <p class="card-text text-center"><strong><?php echo $petSize->getPetBreedByText(); ?></strong></p>
                        </div>
                        <ul class="list-group list-group-flush text-center">
                            <li class="list-group-item bg-light text-dark">Pet Name : <strong><?php echo $petSize->getPetName(); ?></strong></li>
                            <li class="list-group-item bg-secondary text-white">Pet Size : <strong><?php echo $petSize->getPetSize(); ?></strong></li>
                        </ul>
                        <div class="checkboxID actionCheck">
                            <label style=" width:100%; padding:0; margin:0;">
                                <input type="radio" name="petID" value="<?php echo $petSize->getPetID(); ?>" data-size="<?php echo $petSize->getPetSize(); ?>" onclick="validateSelection()">
                                <span>Reservar con <strong><?php echo $petSize->getPetName(); ?></strong></span>
                            </label>
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
            <button type="submit" id="reserveButton" class="btn btn-info d-flex justify-content-center align-content-center w-100" disabled>Reservation</button>  
        </div>
    </div>
    </form>
</body>
</html>