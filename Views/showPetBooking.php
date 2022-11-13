<?php
namespace Views;
include ("ownerNav.php");
require_once(VIEWS_PATH."validate-session.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
<!-- CSS only -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">

    <title>Pets Availability</title>
</head>
<body>
    <div class="container d-flex col-md-4 w-auto p-5 flex-row flex-wrap">
    <?php
    foreach($petList as $petSize)
    {
        ?>
    <div class="card" style="width: 18rem;">
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
            <li class="list-group-item bg-light text-dark"><?php echo $petSize->getOwnerId()?></li>
        </ul>
        <div class="card-body d-flex m-2">
            <a href="<?php //searchPetByID($petSize->getPetId());?>" class="btn btn-primary d-flex justify-content-center align-content-center w-100">Reservation</a>
            
        </div>
    </div>
    <?php
    }
    ?>
    </div>
</body>
</html>