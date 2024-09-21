<?php
namespace Views;
require_once("validate-session.php");
require_once("ownerNav.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
     <meta charset="UTF-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
     <title>PET HERO</title>
</head>
<body>
     <main class="py-5">
          <section id="listado" class="mb-5">
               <div class="container d-flex justify-content-center align-content-center flex-wrap">
                    <h2 class="mb-4">New Pet</h2>
                    <form action="<?php echo '/FinalProject4/' ?>Pet/newPet" method="post" enctype="multipart/form-data" class="bg-light-alpha p-5">
                         <div class="row">
                              <div class="col-lg-4 w-100 d-flex justify-content-center align-content-center flex-wrap">
                                   <div class="form-group w-50">
                                        <label for="">Pet Name</label>
                                        <input type="text" name="petName" value="" placeholder="Name" class="form-control" required>
                                   </div>
                              </div>
                              <div class="col-lg-4 w-100 d-flex justify-content-center align-content-center flex-wrap">
                                   <div class="form-group w-50">
                                        <label for="">Pet Image (PNG/JPEG)</label>
                                        <input type="file" name="petImage" class="form-control" accept=".png, .jpg, .jpeg" required>
                                   </div>
                              </div>
                              <div class="col-lg-4 w-100 d-flex justify-content-center align-content-center flex-wrap">
                                   <div class="form-group w-50 m-1">
                                        <label for="">Breed</label>
                                        <select name="breedID" class="w-100 h-50" required>
                                             <option value="1">Beagle</option>
                                             <option value="2">Chihuahua</option>
                                             <option value="3">Bulldog</option>
                                             <option value="4">German Shepherd</option>
                                             <option value="5">Shih-tzu</option>
                                             <option value="6">Dogo</option>
                                             <option value="7">Golden Retriever</option>
                                             <option value="8">Fox Terrier</option>
                                             <option value="9">Whippet</option>
                                             <option value="10">Pinscher</option>
                                             <option value="11">Cocker</option>
                                             <option value="12">Shiba Inu</option>
                                             <option value="13">Doberman</option>
                                             <option value="14">Border Collie</option>
                                             <option value="15">Yorkshire</option>
                                        </select>
                                   </div>
                              </div>
                              <div class="col-lg-4 w-100 d-flex justify-content-center align-content-center flex-wrap">
                                   <div class="form-group w-50 m-1">
                                        <label for="">Pet Size</label>
                                        <select name="petSize" class="w-100 h-50" required>
                                             <option value="Small">Small</option>
                                             <option value="Medium">Medium</option>
                                             <option value="Big">Big</option>
                                        </select>
                                   </div>
                              </div>
                              <div class="col-lg-4 w-100 d-flex justify-content-center align-content-center flex-wrap">
                                   <div class="form-group w-50">
                                        <label for="">Vaccination Plan (PDF/Image)</label>
                                        <input type="file" name="petVaccinationPlan" class="form-control" accept=".pdf, .png, .jpg, .jpeg" required>
                                   </div>
                              </div>
                              <div class="col-lg-4 w-100 d-flex justify-content-center align-content-center flex-wrap">
                                   <div class="form-group w-50">
                                        <label for="">Pet Details</label>
                                        <input type="text" name="petDetails" placeholder="Details" value="" class="form-control" required>
                                   </div>
                              </div>
                              <div class="col-lg-4 w-100 d-flex justify-content-center align-content-center flex-wrap">
                              <div class="form-group w-50">
                                   <label for="">Pet Video</label>
                                   <input type="file" name="petVideo" placeholder="Select Video" class="form-control" accept="video/mp4" required>
                              </div>
                              </div>
                              <div class="col-lg-4 w-100 d-flex justify-content-center align-content-center flex-wrap">
                                   <div class="form-group w-50 m-2">
                                        <label for="">Pet Weight</label>
                                        <select name="petWeight" class="w-100 h-50" required>
                                             <option value="Light">0-15 kg</option>
                                             <option value="Medium">15-30 kg</option>
                                             <option value="Heavy">30> kg</option>
                                        </select>
                                   </div>
                              </div>
                              <div class="col-lg-4 w-100 d-flex justify-content-center align-content-center flex-wrap">
                                   <div class="form-group w-50">
                                        <label for="">Pet Age</label>
                                        <input type="number" name="petAge" placeholder="Age" value="" class="form-control" required>
                                   </div>
                              </div>
                         </div>
                         <div class="container d-flex justify-content-center align-content-center mt-5 w-50">
                              <button type="submit" class="btn bg-success ml-auto d-block w-50 mr-3">Guardar</button>
                         </div>
                    </form>
               </div>
          </section>
     </main>

     <script>
document.addEventListener("DOMContentLoaded", function() {
    const form = document.querySelector("form");
    const petImageInput = document.querySelector("input[name='petImage']");
    const vaccinationPlanInput = document.querySelector("input[name='petVaccinationPlan']");
    const petVideoInput = document.querySelector("input[name='petVideo']");

    form.addEventListener("submit", function(event) {
        let valid = true;
        let message = '';

        // Validar imagen de la mascota
        if (petImageInput.files.length > 0) {
            const imageType = petImageInput.files[0].type;
            const allowedImageTypes = ['image/jpeg', 'image/png'];
            if (!allowedImageTypes.includes(imageType)) {
                valid = false;
                message += "Pet image must be a JPG or PNG.\n";
            }
        }

        // Validar plan de vacunación (solo imágenes)
        if (vaccinationPlanInput.files.length > 0) {
            const vacPlanType = vaccinationPlanInput.files[0].type;
            const allowedVacPlanTypes = ['image/jpeg', 'image/png'];
            if (!allowedVacPlanTypes.includes(vacPlanType)) {
                valid = false;
                message += "Vaccination plan must be an image (JPG or PNG).\n";
            }
        }

        // Validar video (cualquier tipo permitido)
        if (petVideoInput.files.length > 0) {
            const videoType = petVideoInput.files[0].type;
            // Cualquier tipo de video es permitido, pero puedes agregar más validaciones aquí
        }

        if (!valid) {
            event.preventDefault(); // Detener el envío del formulario
            alert(message); // Mostrar mensaje de error
        }
    });
});
</script>
</body>
</html>
