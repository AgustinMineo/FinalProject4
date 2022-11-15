<?php
namespace Views;
//require_once(VIEWS_PATH."validate-session.php");
date_default_timezone_set('America/Argentina/Buenos_Aires');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

  <title>PET HERO</title>
</head>
<body>
  <div class="container w-100 bg-info d-flex justify-content-center align-items-center align-self-center h-full my-auto">
      <section class="container-fluid w-100 d-flex justify-content-center align-items-center my-auto" >
        <div class="container w-100 bg-info d-flex justify-content-center align-items-center">
        <main class="mt-5 d-flex">
            <div class="container w-100 d-flex flex-wrap text-center justify-content-center ">
              <form class="w-100 d-flex flex-wrap" action="<?php echo '/FinalProject4/' ?>Keeper/updateAvailabilityDays" method="post">
                <div class="d-flex flex-wrap w-100">
                  <label for="" class=""><h3>From</h3></label>
                  <input type="date" min="" id="fecha"  name="value1" class="form-control-plaintext" >
                </div>  <!--Agregar la fecha de hoy como min, misma funcion que para registro de owner y keeper-->
                <div class="d-flex flex-wrap w-100 h-5">
                  <label for="" class="d-flex flex-wrap"><h3>To</h3></label>
                  <input type="date" name="value2" class="form-control" >
                </div>
                <div class="d-flex flex-wrap w-100">
                  <button type="submit" class="btn btn-primary mb-3">Confirm new date</button>
                </div>
              </form>
            </div>
          </main>
          </div>
    </section>
    
  </div>
</body>
</html>