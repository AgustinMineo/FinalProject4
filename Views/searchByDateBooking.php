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
  <style>
    body {
      background: #f8f9fa;
    }
    main {
      height: 80vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .form-container {
      width: 100%;
      max-width: 600px; 
      padding: 3rem;
      background: white; 
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    .form-label {
      font-weight: bold;
    }
  </style>
</head>
<body>
  <main>
    <div class="container d-flex align-items-center justify-content-center">
      <form class="form-container" action="<?php echo '/FinalProject4/Booking/bookingBuild' ?>" method="post">
        <h2 class="text-center text-uppercase mb-4">Buscar Keepers</h2>
        
        <div class="mb-4">
          <label for="startDate" class="form-label">Desde</label>
          <input type="date" id="startDate" name="value1" class="form-control" required>
        </div>
        
        <div class="mb-4">
          <label for="endDate" class="form-label">Hasta</label>
          <input type="date" id="endDate" name="value2" class="form-control" required>
        </div>
        
        <div class="text-center">
          <button type="submit" class="btn btn-primary">¡Buscar Keepers!</button>
        </div>
      </form>
    </div>
  </main>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const dateFields = document.querySelectorAll('input[type="date"]');
      const today = new Date().toISOString().split('T')[0];
      dateFields.forEach(field => {
          field.setAttribute('min', today);
      });
    });
  </script>
  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>
