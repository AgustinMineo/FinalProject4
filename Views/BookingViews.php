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
    <div class="container d-flex p-5 flex-wrap flex-row w-100 bg-light">
        
        <div class="container d-flex col-md-4 w-auto p-5  flex-wrap bg-light text-center"><div class='alert alert-success'><h1>Los keeper disponibles entre las fechas seleccionadas son</h1></div></div>
       
        <?php
        if($listKeepers){

            foreach($listKeepers as $keeper)
            {
                ?>
        <form action="<?php echo '/FinalProject4/' ?>Booking/newBooking" method="get" class="w-100 d-flex flex-wrap">
        <div class="card d-flex flex-wrap m-5" style="width: 25%; ">
            <img src=" <?php echo IMG_PATH ?>keeperDog.svg" class="card-img-top" alt="KEEPER IMG">
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
            <label style=" width:100%; padding:0; margin:0;"> <input type="radio" id="" name="email" value="<?php echo $keeper->getEmail();?>"><span>Reservar con este Keeper</span></label><!--/// Cambiar el email por el keeperID-->
            </div>
        </ul>
    </div>
    <?php
    }
}
else{
    echo "<div class='container d-flex col-md-4 w-auto p-5  flex-wrap bg-light text-center'><div class='alert alert-danger'><h1>there is no keepers availables!!</h1></div></div>";
}
    ?>

<div class="container w-100 text-center">
     <div class='alert alert-success'><h1>Tus pets disponibles</h1></div>   
</div>
    <div class="container d-flex col-md-4 w-auto p-5 flex-row flex-wrap w-100">
            <?php
            if($petList){
    foreach($petList as $petSize)
    {
        ?>
    <div class="card d-flex flex-wrap m-5" style="width: 18rem;"> 
    <img src=" <?php echo IMG_PATH ?>keeperDog.svg" class="card-img-top" alt="KEEPER IMG">
    <div class="card-body">
        <h5 class="card-title">Pet</h5>
        <p class="card-text">Here you have your pets!</p>
    </div>
    <ul class="list-group list-group-flush text-center">
        <li class="list-group-item bg-light text-dark">Pet Name : <strong><?php echo $petSize->getPetName(); ?></strong></li>
        <li class="list-group-item bg-secondary text-white">Pet Size : <strong><?php echo $petSize->getPetSize(); ?></strong></li>
    </ul>
    <div class="checkboxID actionCheck">
    <label style=" width:100%; padding:0; margin:0;"> <input type="radio" name="petID" value="<?php echo $petSize->getPetID();?>"><span>Reservar con este pet</span></label>
    </div>
</div>
<?php
    }
}else {
    echo "<div class='container w-100 text-center'><div class='alert alert-success'><h1>Tus pets disponibles</h1></div></div>";
}
    ?>
    <!-- Campos ocultos para value1 y value2 -->
    <input type="hidden" name="startDate" value="<?php echo $value1; ?>">
    <input type="hidden" name="finishDate" value="<?php echo $value2; ?>">
        <button type="submit" class="btn btn-info d-flex justify-content-center align-content-center w-100">Reservation</button>  
    </div>
    </div>
    </form>
</body>
</html>