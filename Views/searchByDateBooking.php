<?php
namespace Views;
//require_once(VIEWS_PATH."ownerNav.php");
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

  <title>Booking with a keeper</title>
</head>
<body>
  <div class="container d-flex align-content-center h-100%">
    <div class="container bg-info d-flex align-items-center justify-content-center h-100%">

      <form class="row g-3 h-100 d-flex " action="<?php echo '/FinalProject4/' ?>Booking/bookingBuild" method="post">
        <div class="col-auto ">
          <label for="" class="visually-hidden">From</label>
          <input type="date" value="01/01/2022" name="value1" class="form-control-plaintext" >
        </div>
        <div class="col-auto">
          <label for="" class="visually-hidden">To</label>
          <input type="date" value ="01/01/2022" name="value2" class="form-control" >
        </div>
        <div class="col-auto">
          <button type="submit" class="btn btn-primary mb-3">Confirm new date</button>
        </div>
      </form>
    </div>
  </div>
  
</body>
</html>