<?php
namespace Views;
require_once("validate-session.php");
date_default_timezone_set('America/Argentina/Buenos_Aires');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
  <title>PET HERO</title>
</head>
<body>
  <main style="background:#E0dfff;">
    <div class="container w-100 d-flex text-center ">
      <div style="margin-left:190px" class="w-50">
        <form class="row g-3 h-100 d-flex w-auto" action="<?php echo '/FinalProject4/' ?>Keeper/updateAvailabilityDays" method="post">
          <div style="margin:125px;" class="container w-100 shadow-lg p-3  bg-body rounded">
            
            <label for="" class="text-uppercase mt-3 fs-2">From</label>
            <div class="col-auto border ">
              <input type="date" name="value1" class="form-control" >
            </div>
            <label for="" class="text-uppercase mt-3 fs-2">To</label>
            <div class="col-auto d-flex">
              <input type="date" name="value2" class="form-control" >
            </div>
            
            <div class="col-auto mt-3">
              <div class="form-check">
              <input type="hidden" name="available" value="0">
              <input type="checkbox" name="available" value="1" checked>
              <label class="form-check-label" for="availabilityCheck">
                Disponible
              </label>
              </div>
            </div>
            
            <div class="col-auto d-flex flex-wrap justify-content-center align-items-end pt-5">
              <button type="submit" class="btn btn-primary mb-3">Confirm your new availability</button>
            </div>
          </div>
        </form>
      </div>
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
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"  crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"  crossorigin="anonymous"></script>
  <script>
      document.addEventListener('DOMContentLoaded', () => {
      // Selecciona todos los campos de entrada de tipo 'date'
      const dateFields = document.querySelectorAll('input[type="date"]');
      const dateFormat = 'dd/mm/yyyy';
      
      // Obtén la fecha actual en formato YYYY-MM-DD
      const today = new Date().toISOString().split('T')[0];
      
      // Aplica la restricción de fecha mínima a todos los campos de fecha
      dateFields.forEach(field => {
          field.setAttribute('min', today )
          
      });
  });
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"  crossorigin="anonymous"></script>
</body>
</html>
