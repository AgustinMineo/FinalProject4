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
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

  <title>PET HERO</title>
</head>
<body>
  
  <main>
    <div class="container w-100 d-flex justify-content-center align-items-center flex-column">
      <img src="https://seeklogo.com/images/B/bootstrap-logo-3C30FB2A16-seeklogo.com.png" alt="" width="75px">
      <h3 class="fw-nromal my-2">Please sign in</h3>
      <form class="row g-3" action="<?php echo '/FinalProject4/' ?>Keeper/showKeepersByAvailability" method="post">
    <div class="col-auto">
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
  </main>


  <style>
    main{
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }
  </style>



  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
</body>
</html>