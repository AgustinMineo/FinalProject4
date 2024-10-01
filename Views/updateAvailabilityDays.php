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
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <title>PET HERO</title>
  <style>
    body {
      background: white;
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
      <form id="availabilityForm" class="form-container" action="<?php echo '/FinalProject4/Keeper/updateAvailabilityDays' ?>" method="post">
        <h2 class="text-center text-uppercase mb-4">Actualizar Disponibilidad</h2>

        <div class="mb-4">
          <label for="startDate" class="form-label">Desde</label>
          <input type="date" id="startDate" name="value1" class="form-control" required>
        </div>

        <div class="mb-4">
          <label for="endDate" class="form-label">Hasta</label>
          <input type="date" id="endDate" name="value2" class="form-control" required>
        </div>

        <div class="mb-4 d-flex align-items-center justify-content-center">
          <div class="form-check">
            <input type="hidden" name="available" value="0">
            <input type="checkbox" name="available" value="1" id="availabilityCheck" class="form-check-input" checked>
            <label class="form-check-label" for="availabilityCheck">Disponible</label>
          </div>
        </div>

        <div class="text-center">
          <button type="submit" class="btn btn-primary" id="submitBtn">Confirmar nueva disponibilidad</button>
        </div>
      </form>
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const dateFields = document.querySelectorAll('input[type="date"]');
      const today = new Date().toISOString().split('T')[0];
      dateFields.forEach(field => {
        field.setAttribute('min', today);
      });
      
      const form = document.getElementById('availabilityForm');
      const submitButton = document.getElementById('submitBtn');

      // Manejo del evento de clic en el botón de envío
      submitButton.addEventListener('click', (event) => {
        event.preventDefault();
        Swal.fire({
          title: '¿Estás seguro?',
          text: "¿Deseas actualizar los días de disponibilidad?",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Sí, actualizar!',
          cancelButtonText: 'Cancelar'
        }).then((result) => {
          if (result.isConfirmed) {
            form.submit(); 
          }
        });
      });
    });
  </script>
</body>
</html>
