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
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
     <title>PET HERO</title>
</head>
<body>
     <main class="py-5">
     <section id="listado" class="mb-5">
     <div class="container d-flex justify-content-center align-content-center flex-wrap">
          <h2 class="mb-4">Register New Pet</h2>
          <form action="<?php echo '/FinalProject4/' ?>Pet/newPet" method="post" enctype="multipart/form-data" class="bg-light p-5 rounded shadow-sm w-100">
               <div class="row gy-3">
                    <!-- Pet Name -->
                    <div class="col-lg-6">
                         <div class="form-group">
                              <label for="petName" class="form-label">Pet Name</label>
                              <input type="text" name="petName" placeholder="Enter pet's name" class="form-control" required>
                         </div>
                    </div>
                    
                    <!-- Pet Image -->
                    <div class="col-lg-6">
                         <div class="form-group">
                              <label for="petImage" class="form-label">Pet Image (PNG/JPEG)</label>
                              <input type="file" name="petImage" class="form-control" accept=".png, .jpg, .jpeg" required>
                         </div>
                    </div>
                    
                    <!-- Pet Breed -->
                    <div class="col-lg-6">
                         <div class="form-group">
                              <label for="breedID" class="form-label">Breed</label>
                              <select name="breedID" class="form-select" required>
                                   <option value="" disabled selected>Select breed</option>
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
                                   <option value="16">Poodle</option>
                                   <option value="17">Rottweiler</option>
                                   <option value="18">Labrador Retriever</option>
                                   <option value="19">Pug</option>
                                   <option value="20">Siberian Husky</option>
                                   <option value="21">Boxer</option>
                                   <option value="22">Dalmatian</option>
                                   <option value="23">Maltese</option>
                                   <option value="24">Saint Bernard</option>
                                   <option value="25">Cavalier King Charles Spaniel</option>
                                   <option value="26">French Bulldog</option>
                                   <option value="27">Great Dane</option>
                                   <option value="28">Basenji</option>
                                   <option value="29">Akita</option>
                                   <option value="30">Alaskan Malamute</option>
                                   <option value="31">Samoyed</option>
                                   <option value="32">Basset Hound</option>
                                   <option value="33">Australian Shepherd</option>
                                   <option value="34">Pembroke Welsh Corgi</option>
                                   <option value="35">Bichon Frise</option>
                                   <option value="36">Papillon</option>
                                   <option value="37">Jack Russell Terrier</option>
                                   <option value="38">Weimaraner</option>
                                   <option value="39">Bull Terrier</option>
                                   <option value="40">Pekingese</option>
                                   <option value="41">Staffordshire Bull Terrier</option>
                                   <option value="42">Airedale Terrier</option>
                                   <option value="43">Cane Corso</option>
                                   <option value="44">English Setter</option>
                                   <option value="45">Saluki</option>
                                   <option value="46">Italian Greyhound</option>
                                   <option value="47">Portuguese Water Dog</option>
                                   <option value="48">Tibetan Mastiff</option>
                                   <option value="49">Chow Chow</option>
                                   <option value="50">Irish Wolfhound</option>
                                   <option value="51">Pitbull</option>
                              </select>
                         </div>
                    </div>
                    <!-- Pet Size -->
                    <div class="col-lg-6">
                         <div class="form-group">
                              <label for="petSize" class="form-label">Pet Size</label>
                              <select name="petSize" class="form-select" required>
                                   <option value="" disabled selected>Select size</option>
                                   <option value="Small">Small</option>
                                   <option value="Medium">Medium</option>
                                   <option value="Big">Big</option>
                              </select>
                         </div>
                    </div>
                    
                    <!-- Vaccination Plan -->
                    <div class="col-lg-6">
                         <div class="form-group">
                              <label for="petVaccinationPlan" class="form-label">Vaccination Plan (PDF/Image)</label>
                              <input type="file" name="petVaccinationPlan" class="form-control" accept=".pdf, .png, .jpg, .jpeg" required>
                         </div>
                    </div>
                    
                    <!-- Pet Details -->
                    <div class="col-lg-6">
                         <div class="form-group">
                              <label for="petDetails" class="form-label">Pet Details</label>
                              <input type="text" name="petDetails" placeholder="Enter pet's details" class="form-control" required>
                         </div>
                    </div>
                    
                    <!-- Pet Video -->
                    <div class="col-lg-6">
                         <div class="form-group">
                              <label for="petVideo" class="form-label">Pet Video (MP4)</label>
                              <input type="file" name="petVideo" class="form-control" accept="video/mp4" required>
                         </div>
                    </div>
                    
                    <!-- Pet Weight -->
                    <div class="col-lg-6">
                         <div class="form-group">
                              <label for="petWeight" class="form-label">Pet Weight</label>
                              <select name="petWeight" class="form-select" required>
                                   <option value="" disabled selected>Select weight category</option>
                                   <option value="Light">0-15 kg</option>
                                   <option value="Medium">15-30 kg</option>
                                   <option value="Heavy">30 kg and above</option>
                              </select>
                         </div>
                    </div>
                    
                    <!-- Pet Age -->
                    <div class="col-lg-6">
                         <div class="form-group">
                              <label for="petAge" class="form-label">Pet Age (Years)</label>
                              <input type="number" name="petAge" placeholder="Enter pet's age" class="form-control" min="0" required>
                         </div>
                    </div>
               </div>
               <!-- Submit Button -->
               <div class="d-flex justify-content-center align-content-center mt-4">
                    <button type="submit" class="btn btn-success w-50">Save Pet</button>
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
