<?php
namespace Views;
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keeper Availability</title>
</head>
<body>
    <div class="container d-flex col-md-4 w-auto p-5 flex-row flex-wrap "><h1>Los keeper disponibles entre las fechas seleccionadas son </h1></div>
    <div class="container d-flex col-md-4 w-auto p-5 flex-row flex-wrap">

        <?php
    foreach($listKeepers as $keeper)
    {
        ?>
        <form action="<?php echo '/FinalProject4/' ?>Booking/newBooking" method="get">
    <div class="card" style="width: 18rem;">
       <!-- <img src="//<//?///php echo IMG_PATH ?><//?php if($keeper->getKeeperImg()){echo "<h1>imagen del keeper</h1>";}else{src="keeperDog.svg"}?>" class="card-img-top" alt="KEEPER IMG">-->
        <img src=" <?php echo IMG_PATH ?>keeperDog.svg" class="card-img-top" alt="KEEPER IMG">
        <div class="card-body">
            <h5 class="card-title">Keeper</h5>
            <p class="card-text">Here you have a keeper</p>
        </div>
            <ul class="list-group list-group-flush text-center">
                
                
                <li class="list-group-item bg-secondary text-white"><?php echo $keeper->getLastName() ?> <?php echo $keeper->getfirstName() ?></li>
                <li class="list-group-item bg-light text-dark"><?php echo $keeper->getCellPhone() ?></li>
                <li class="list-group-item bg-secondary text-white">$<?php echo $keeper->getPrice() ?></li>
                
                
                <li class="list-group-item bg-light text-dark"><?php echo $keeper->getAnimalSize()?></li>
                
                
                <li class="list-group-item bg-secondary text-white">Del <?php echo $keeper->getFirstAvailabilityDays()?></li>
                
                
                <li class="list-group-item bg-light text-dark">Al <?php echo $keeper->getLastAvailabilityDays()?></li>
                
            </ul>
            <div class="card-body d-flex m-2">
                <input type="hidden" name="firstDate" value="<?php $keeper->getFirstAvailabilityDays()?>">
                <input type="hidden" name="lastDate" value="<?php $keeper->getLastAvailabilityDays()?>">
                <input type="hidden" name="keeperID" value="<?php $keeper->getKeeperId()?>" >
                <input type="hidden" name="value" value="<?php $keeper->getPrice()?>">
                <input type="hidden" name="petSize" value="<?php $keeper->getAnimalSize()?>">
                <button type="submit"  value="submit" class="btn btn-primary d-flex justify-content-center align-content-center w-100">Reservation</button>    
            </div>
        </form>
    </div>
    <?php
    }
    ?>
    </div>
</body>
</html>