<?php
namespace Views;
require_once(VIEWS_PATH."validate-session.php");
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
/* selects all of the text within the input element and changes the color of the text */
.checkboxID label input + span{color: #000;}

.actionCheck input:checked + span{background-color: #80ff00; width:100%;}

/* This will declare how a selected input will look giving generic properties */
.checkboxID input:checked + span {
    width: 100%;
    color:black;
    text-shadow: 0 0  6px rgba(0, 0, 0, 0.8);
}
    </style>
</head>
<body>
    <div class="container d-flex col-md-4 w-auto p-5  flex-wrap bg-light"><h1>Los keeper disponibles entre las fechas seleccionadas son </h1></div>
    
    <div class="container d-flex p-5 flex-wrap flex-row w-100 bg-light">

        <?php
        if($listKeepers){

            foreach($listKeepers as $keeper)
            {
                ?>
        <form action="<?php echo '/FinalProject4/' ?>Booking/newBooking" method="get" class="w-100 d-flex flex-wrap"> <!--REVISAR PORQUE NO ME DEJA CON POST-->
        <div class="card d-flex flex-wrap m-5" style="width: 25%; ">
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
            
            <input type="checkbox" name="email" value="<?php echo $keeper->getEmail()?>"> <label for="">Reservar</label><!--/// Cambiar el email por el keeperID-->
        </ul>
    </div>
    <?php
    }
}
else{
    echo "<h1>No hay keepers<h1>";
}
    ?>
    <div class="container w-auto p-5"><h1>Tus pets disponibles</h1></div>
    
    <div class="container d-flex col-md-4 w-auto p-5 flex-row flex-wrap w-100 bg-light">
        <div class="container d-flex col-md-4 w-auto p-5 flex-row flex-wrap w-100">

            <?php
            if($petList){
    foreach($petList as $petSize)
    {
        ?>
    <div class="card d-flex flex-wrap m-5" style="width: 18rem;"> <!-- cambiar el value del width para que sea un row cada 4 pets.-->
    <!-- <img src="//<//?///php echo IMG_PATH ?><//?php if($keeper->getKeeperImg()){echo "<h1>imagen del keeper</h1>";}else{src="keeperDog.svg"}?>" class="card-img-top" alt="KEEPER IMG">-->
    <img src=" <?php echo IMG_PATH ?>keeperDog.svg" class="card-img-top" alt="KEEPER IMG">
    <div class="card-body">
        <h5 class="card-title">Pet</h5>
        <p class="card-text">Here you have your pets!</p>
    </div>
    
    <ul class="list-group list-group-flush text-center">
        <!--<li class="list-group-item bg-secondary text-white"><//?//php echo $petSize->getPetImage() ?></li>-->
        <li class="list-group-item bg-light text-dark"><?php echo $petSize->getPetName() ?></li>
        <li class="list-group-item bg-secondary text-white"><?php echo $petSize->getPetSize() ?></li>
        <li class="list-group-item bg-light text-dark"><?php echo $petSize->getOwnerID()?></li>
    </ul>
    <div class="checkboxID actionCheck">
    <label style=" width:100%; padding:0; margin:0;"> <input type="checkbox" name="petID" value="<?php echo $petSize->getPetID()?>"><span>Reservar con este pet</span></label>
    </div>
</div>
<?php
    }
}else {
    echo "<h1>No tiene pets</h1>";
}
    ?>
    <button type="submit" class="btn btn-info d-flex justify-content-center align-content-center w-100">Reservation</button>  
    </div>
    </div>
    </div>
    </form>
</body>
</html>