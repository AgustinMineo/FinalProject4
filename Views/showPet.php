<?php
namespace Views;
require_once("validate-session.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
     <meta charset="UTF-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"  crossorigin="anonymous">
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"  crossorigin="anonymous"></script>
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
          <style>
               .table >td{
                    height:5px; 
                    width:5px;
                    background-color:gray;
               }
               </style>
     <title>My Pets</title>
</head>
<body>
     <main class="py-2">
          <div class="container d-flex flex-wrap bg-light">
               <section id="listado" class="mb-5 justify-content-center w-100">
                    <div class="container d-flex flex-wrap justify-content-center w-100">
                         <h2 class="mb-4 mt-5">Listado de Pets</h2>
                              <div class="mb-4 w-100">
                                   <input type="text" id="filterPetName" placeholder="Buscar por nombre" class="form-control filter-input" oninput="filterPets()">
                                   <select id="filterPetSize" class="form-select filter-input" onchange="filterPets()">
                                        <option value="">Seleccionar Tamaño</option>
                                        <option value="Small">Small</option>
                                        <option value="Medium">Medium</option>
                                        <option value="Big">Big</option>
                                   </select>
                              </div>
                         <div class="container d-flex flex-wrap m-5">
                              <div class="container">
                                   <div id="noPetsMessage" class="alert alert-danger text-center d-none">No se encontraron mascotas.</div>
                              </div>
                              <?php
                              if(!$petList){
                                   echo "<div class='d-flex flex-wrap justify-content-center w-100'> <h1>No tiene pets cargados!</h1> </div>";
                              } else {
                                   foreach($petList as $pets) {
                                        ?>

                                        <div class="card m-3 pet-item " style="width: 20rem;"
                                        data-name="<?php echo $pets->getPetName(); ?>"
                                        data-size="<?php echo $pets->getPetSize(); ?>">
                                        <div class="container d-flex" style="max-width: 100%; height: 200px; overflow: hidden;">
                                             <?php 
                                                  if ($image = $pets->getPetImage()) {
                                                       $imageData = base64_encode(file_get_contents($image));
                                                       echo '<img src="data:image/jpeg;base64,'.$imageData.'" class="d-block img-fluid img-thumbnail" style="width: 100%; height: 100%; object-fit: cover;">';
                                                  } else {
                                                       echo '<img src="'.IMG_PATH.'default-pet.jpg" class="card-img-top" style="width: 100%; height: 100%; object-fit: cover;">';
                                                  }
                                             ?>
                                        </div>
                                        <div class="card-body d-flex flex-wrap justify-content-center align-items-center w-100">
                                             <h5 class="card-title d-flex flex-wrap justify-content-center w-100"><?php echo $pets->getPetName() ?></h5>
                                             <span class="w-100"><hr></span>
                                             <p class="card-text d-flex flex-wrap justify-content-center w-100"><?php echo $pets->getPetBreedByText() ?></p>
                                             <div class="d-grid gap-2 d-sm-block">
                                                  <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal<?php echo $pets->getPetID()?>">Ver Detalles</button>
                                                  <button type="button" class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#modifyModal<?php echo $pets->getPetID()?>">Modificar</button>
                                             </div>
                                                  <!-- Modal pet -->
                                                  <div class="modal fade" id="exampleModal<?php echo $pets->getPetID() ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                  <div class="modal-dialog modal-fullscreen modal-dialog-centered modal-dialog-scrollable">
                                                       <div class="modal-content shadow-lg border-0 rounded-3" style="animation: fadeIn 0.5s;">
                                                            
                                                            <div class="modal-header bg-primary text-white rounded-top-3">
                                                                 <h5 class="modal-title" id="exampleModalLabel"><?php echo $pets->getPetName() ?></h5>
                                                                 <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>

                                                            <!-- Modal Body -->
                                                            <div class="modal-body bg-light">
                                                                 <!-- Tabla con información de la mascota -->
                                                                 <table class="table table-bordered text-center">
                                                                      <thead class="table-primary">
                                                                      <tr>
                                                                           <th scope="col">Nombre</th>
                                                                           <th scope="col">Raza</th>
                                                                           <th scope="col">Peso</th>
                                                                           <th scope="col">Tamaño</th>
                                                                           <th scope="col">Edad (Años)</th>
                                                                      </tr>
                                                                      </thead>
                                                                      <tbody>
                                                                      <tr>
                                                                           <td><?php echo $pets->getPetName() ?></td>
                                                                           <td><?php echo $pets->getPetBreedByText() ?></td>
                                                                           <td><?php echo $pets->getPetWeight() ?></td>
                                                                           <td id="filterPetSize"><?php echo $pets->getPetSize() ?></td>
                                                                           <td><?php echo $pets->getPetAge() ?></td>
                                                                      </tr>
                                                                      </tbody>
                                                                 </table>

                                                                 <!-- Imagen de la mascota -->
                                                                 <div class="d-flex flex-wrap justify-content-center w-100 mt-2">
                                                                      <h5>Imagen de la Mascota</h5>
                                                                 </div>
                                                                 <div class="d-flex flex-wrap justify-content-center w-100 card-img">
                                                                      <?php
                                                                      if ($image = $pets->getPetImage()) {
                                                                      $imageData = base64_encode(file_get_contents($image));
                                                                      echo '<img src="data:image/jpeg;base64,' . $imageData . '" class="img-fluid rounded shadow-sm" width="300" height="auto">';
                                                                      } else {
                                                                      echo "<h3>No tiene imagen</h3>";
                                                                      }
                                                                      ?>
                                                                 </div>
                                                                 <hr>

                                                                 <!-- Plan de vacunación -->
                                                                 <div class="d-flex flex-wrap justify-content-center w-100 mt-2">
                                                                      <h5>Plan de Vacunación</h5>
                                                                      </div>
                                                                      <div class="d-flex flex-wrap justify-content-center w-100">
                                                                      <?php 
                                                                      if (!$pets->getPetVaccinationPlan()) {
                                                                           echo "<h3>Sin plan de vacunación disponible</h3>";
                                                                      } else {
                                                                           $vaccinationPlanPath = $pets->getPetVaccinationPlan();
                                                                           $fileType = pathinfo($vaccinationPlanPath, PATHINFO_EXTENSION);

                                                                           if ($fileType === 'pdf') {
                                                                                // Muestro pdf
                                                                                echo '<iframe src="' . FRONT_ROOT . $vaccinationPlanPath . '" class="w-100 px-3" style="height: 60vh;" frameborder="0"></iframe>';
                                                                           } elseif (in_array(strtolower($fileType), ['jpg', 'jpeg', 'png'])) {
                                                                                // Muestro imagen
                                                                                $vaccinationPlanData = base64_encode(file_get_contents($vaccinationPlanPath));
                                                                                echo '<img src="data:image/' . $fileType . ';base64,' . $vaccinationPlanData . '" class="img-fluid rounded shadow-sm" style="max-height: 800px;">';
                                                                           } else {
                                                                                echo "<h3>Formato de archivo no compatible</h3>";
                                                                           }
                                                                      }
                                                                      ?>
                                                                 </div>

                                                                 <hr>

                                                                 <!-- Detalles adicionales -->
                                                                 <div class="d-flex flex-wrap justify-content-center w-100 mt-2">
                                                                      <h5>Detalles</h5>
                                                                 </div>
                                                                 <div class="d-flex flex-wrap justify-content-center w-100">
                                                                      <p class="text-break "><?php echo $pets->getPetDetails() ?></p>
                                                                 </div>
                                                                 <hr>

                                                                 <!-- Video de la mascota -->
                                                                 <h5 class="text-center">Video</h5>
                                                                 <div class="d-flex flex-wrap justify-content-center w-100">
                                                                      <?php 
                                                                      if (!$pets->getPetVideo()) {
                                                                      echo "<h3>Sin video</h3>";
                                                                      } else {
                                                                      echo '<video width="320" height="240" controls class="rounded shadow-sm">';
                                                                      echo '<source src="' .FRONT_ROOT . $pets->getPetVideo() . '" type="video/mp4">';
                                                                      echo 'Your browser does not support the video tag.';
                                                                      echo '</video>';
                                                                      }
                                                                      ?>
                                                                 </div>
                                                                 <?php if($userRole===1):?>
                                                                 <div class="container mt-5">
                                                                      <div class="card-header text-center">
                                                                           <h2>Dueño de <?php echo $pets->getPetName() ?></h2>
                                                                      </div>
                                                                      <div class="card">
                                                                           
                                                                           <div class="list-group">
                                                                                <div class="list-group-item list-group-item-action list-group-item-success">
                                                                                     <strong>Nombre:</strong> <span class="text-muted"><?php echo $pets->getOwnerID()->getFirstName(); ?></span>
                                                                                </div>
                                                                                <div class="list-group-item list-group-item-action list-group-item-success">
                                                                                     <strong>Apellido:</strong> <span class="text-muted"><?php echo $pets->getOwnerID()->getLastName(); ?></span>
                                                                                </div>
                                                                                <div class="list-group-item list-group-item-action list-group-item-success">
                                                                                     <strong>Email:</strong> <span class="text-muted"><?php echo $pets->getOwnerID()->getEmail(); ?></span>
                                                                                </div>
                                                                                <div class="list-group-item list-group-item-action list-group-item-success">
                                                                                     <strong>Teléfono:</strong> <span class="text-muted"><?php echo $pets->getOwnerID()->getCellPhone(); ?></span>
                                                                                </div>
                                                                                <div class="list-group-item list-group-item-action list-group-item-success">
                                                                                     <strong>Fecha de Nacimiento:</strong> <span class="text-muted"><?php echo $pets->getOwnerID()->getBirthDate(); ?></span>
                                                                                </div>
                                                                                <div class="list-group-item list-group-item-action list-group-item-success">
                                                                                     <strong>Descripción:</strong> <span class="text-muted"><?php echo $pets->getOwnerID()->getDescription(); ?></span>
                                                                                </div>
                                                                                <div class="list-group-item list-group-item-action list-group-item-success">
                                                                                     <strong>Cantidad de Mascotas:</strong> <span class="text-muted"><?php echo $pets->getOwnerID()->getPetAmount(); ?></span>
                                                                                </div>
                                                                           </div>
                                                                      </div>
                                                                 </div>
                                                                 
                                                                 <?php endif;?>
                                                            </div>
                                                            <div class="modal-footer d-flex flex-wrap justify-content-center bg-secondary rounded-bottom-3">
                                                                 <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                            </div>
                                                       </div>
                                                  </div>
                                                  </div>
                                                                 <!--Modal update-->
                                             <div class="modal fade" id="modifyModal<?php echo $pets->getPetID() ?>" tabindex="-1" aria-labelledby="modifyModalLabel" aria-hidden="true">
                                                  <div class="modal-dialog modal-fullscreen">
                                                       <div class="modal-content">
                                                            <div class="modal-header">
                                                                 <h5 class="modal-title" id="modifyModalLabel">Modificar Mascota</h5>
                                                                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                 <form action="<?php echo '/FinalProject4/' ?>Pet/updatePet" method="post" enctype="multipart/form-data" class="bg-light p-5 rounded shadow-sm w-100">
                                                                      <input type="hidden" id="petID" name="petID" value="<?php echo $pets->getPetID();?>">
                                                                      <div class="row gy-3">
                                                                      <!-- Pet Name -->
                                                                      <div class="col-lg-6">
                                                                           <div class="form-group">
                                                                                <label for="petName" class="form-label">Nombre de la Mascota</label>
                                                                                <input type="text" name="petName" value="<?php echo $pets->getPetName(); ?>" class="form-control" disabled>
                                                                           </div>
                                                                      </div>

                                                                      <!-- Pet Image -->
                                                                      <div class="col-lg-6">
                                                                           <div class="form-group">
                                                                                <label for="petImage" class="form-label">Imagen de la Mascota (PNG/JPEG)</label>
                                                                                <input type="file" name="petImage" class="form-control" accept=".png, .jpg, .jpeg" onchange="previewImage(event)">
                                                                                <img id="imagePreview" src="<?php 
                                                                                     if ($image = $pets->getPetImage()) {
                                                                                          $imageData = base64_encode(file_get_contents($image));
                                                                                          echo 'data:image/jpeg;base64,'.$imageData;
                                                                                     } else {
                                                                                          echo '<img src="'.IMG_PATH.'default-pet.jpg" class="card-img-top">';
                                                                                     } 
                                                                                ?>" alt="Vista previa de la imagen" class="img-fluid mt-2" style="max-height: 200px;">
                                                                           </div>
                                                                      </div>

                                                                      <!-- Pet Breed -->
                                                                      <div class="col-lg-6">
                                                                           <div class="form-group">
                                                                                <label for="breedID" class="form-label">Breed</label>
                                                                                <select name="breedID" class="form-select" required>
                                                                                     <option value="" disabled>Select breed</option>
                                                                                     <?php foreach ($breedList as $breed) { ?>
                                                                                          <option value="<?php echo $breed['id']; ?>" 
                                                                                               <?php echo ($breed['id'] == $pets->getBreedID()) ? 'selected' : ''; ?>>
                                                                                               <?php echo $breed['name']; ?>
                                                                                          </option>
                                                                                     <?php } ?>
                                                                                </select>
                                                                           </div>
                                                                      </div>



                                                                      <!-- Pet Size -->
                                                                      <div class="col-lg-6">
                                                                           <div class="form-group">
                                                                                <label for="petSize" class="form-label">Tamaño de la Mascota</label>
                                                                                <select name="petSize" class="form-select" required>
                                                                                     <option value="" disabled selected>Select size</option>
                                                                                     <option value="Small" <?php echo ($pets->getPetSize() == 'Small') ? 'selected' : ''; ?>>Pequeño</option>
                                                                                     <option value="Medium" <?php echo ($pets->getPetSize() == 'Medium') ? 'selected' : ''; ?>>Mediano</option>
                                                                                     <option value="Big" <?php echo ($pets->getPetSize() == 'Big') ? 'selected' : ''; ?>>Grande</option>
                                                                                </select>
                                                                           </div>
                                                                      </div>

                                                                      <!-- Vaccination Plan -->
                                                                      <div class="col-lg-6">
                                                                           <div class="form-group">
                                                                                <label for="petVaccinationPlan" class="form-label">Plan de Vacunación (PDF/Imagen)</label>
                                                                                <input type="file" name="petVaccinationPlan" class="form-control" accept=".pdf, .png, .jpg, .jpeg" onchange="previewVaccinationPlan(event)">

                                                                                <div id="vaccinationPreview" class="mt-2" style="height: 80vh">
                                                                                     <?php if ($pets->getPetVaccinationPlan()) : ?>
                                                                                          <input type="hidden" id="existingVaccinationPlan" value="<?php echo FRONT_ROOT . $pets->getPetVaccinationPlan(); ?>">
                                                                                     <?php endif; ?>
                                                                                </div>
                                                                           </div>
                                                                      </div>


                                                                      <!-- Pet Video -->
                                                                      <div class="col-lg-6">
                                                                      <div class="form-group">
                                                                           <label for="petVideo" class="form-label">Video de la Mascota (MP4)</label>
                                                                           <input type="file" name="petVideo" class="form-control" accept="video/mp4" onchange="previewVideo(event)">

                                                                           <div class="d-flex flex-wrap justify-content-center w-100">
                                                                                <video id="videoPreview" width="920" height="720" controls class="rounded shadow-sm" style="display: none;">
                                                                                     <source id="videoSource" src="<?php echo FRONT_ROOT . $pets->getPetVideo(); ?>" type="video/mp4">
                                                                                     Tu navegador no soporta el elemento de video.
                                                                                </video>

                                                                                <?php 
                                                                                     if (!$pets->getPetVideo()) {
                                                                                     echo "<h3>Sin video</h3>";
                                                                                     } else {
                                                                                     echo '<video  width="820" height="720" controls class="rounded shadow-sm" id="current-video">';
                                                                                     echo '<source src="' .FRONT_ROOT . $pets->getPetVideo() . '" type="video/mp4">';
                                                                                     echo 'Your browser does not support the video tag.';
                                                                                     echo '</video>';
                                                                                     }
                                                                                ?>
                                                                           </div>
                                                                      </div>
                                                                      </div>
                                                                      <!-- Pet Details -->
                                                                      <div class="col-lg-6">
                                                                           <div class="form-group">
                                                                                <label for="petDetails" class="form-label">Detalles de la Mascota</label>
                                                                                <input type="text" name="petDetails" value="<?php echo $pets->getPetDetails(); ?>" class="form-control" maxlength="254" required>
                                                                           </div>
                                                                      </div>
                                                                      <!-- Pet Weight -->
                                                                      <div class="col-lg-6">
                                                                           <div class="form-group">
                                                                                <label for="petWeight" class="form-label">Peso de la Mascota</label>
                                                                                <select name="petWeight" class="form-select" required>
                                                                                     <option value="" disabled selected>Select weight category</option>
                                                                                     <option value="Light" <?php echo ($pets->getPetWeight() == 'Light') ? 'selected' : ''; ?>>0-15 kg</option>
                                                                                     <option value="Medium" <?php echo ($pets->getPetWeight() == 'Medium') ? 'selected' : ''; ?>>15-30 kg</option>
                                                                                     <option value="Heavy" <?php echo ($pets->getPetWeight() == 'Heavy') ? 'selected' : ''; ?>>30 kg y más</option>
                                                                                </select>
                                                                           </div>
                                                                      </div>

                                                                      <!-- Pet Age -->
                                                                      <div class="col-lg-6">
                                                                           <div class="form-group">
                                                                                <label for="petAge" class="form-label">Edad de la Mascota (Años)</label>
                                                                                <input type="number" name="petAge" value="<?php echo $pets->getPetAge(); ?>" class="form-control" min="0" required>
                                                                           </div>
                                                                      </div>
                                                                      </div>
                                                                      <!-- Submit Button -->
                                                                      <div class="d-flex justify-content-center align-content-center mt-4">
                                                                      <button type="submit" class="btn btn-outline-success w-50">Guardar Mascota</button>
                                                                      </div>
                                                                 </form>
                                                            </div>
                                                       </div>
                                                  </div>
                                             </div>
                                                                 <!--Modal update Finish-->
                                             </div>
                                        </div>
                                        <?php
                                   }
                              }
                              ?>
                         </div>
                    </div>
                    <nav aria-label="Page navigation">
                         <ul id="pagination" class="pagination justify-content-center">
                         
                         </ul>
                    </nav>
               </section>
          </div>
     </main>
</body>
<script>
document.addEventListener('DOMContentLoaded', filterPets);
function previewImage(event) {
    const imagePreview = document.getElementById('imagePreview');
    const file = event.target.files[0];
    const reader = new FileReader();
    
    reader.onload = function() {
        imagePreview.src = reader.result;
        imagePreview.style.display = 'block';
    }
    
    if (file) {
        reader.readAsDataURL(file);
    }
}

function previewVideo(event) {
    const videoPreview = document.getElementById('videoPreview');
    const videoSource = document.getElementById('videoSource');
    const currentVideo= document.getElementById('current-video');
    const file = event.target.files[0];
    const reader = new FileReader();

    if (file) {
        reader.onload = function() {
            videoSource.src = reader.result;
            videoPreview.style.display = 'block'; // Mostrar el video
            currentVideo.style.display='none';
            videoPreview.load(); // Cargar el nuevo video
        }
        reader.readAsDataURL(file);
    }
}

document.addEventListener('DOMContentLoaded', function() {
     const existingPlanUrl = document.getElementById('existingVaccinationPlan');
     filterPets();
     if (existingPlanUrl && existingPlanUrl.value) {
          previewExistingVaccinationPlan(existingPlanUrl.value);
     }
});

function previewVaccinationPlan(event) {
     const vaccinationPreview = document.getElementById('vaccinationPreview');
     const file = event.target.files[0];
     const reader = new FileReader();

     vaccinationPreview.innerHTML = ''; // Limpiar la vista previa

     reader.onload = function() {
          const fileType = file.type;

          if (fileType === 'application/pdf') {
               const iframe = document.createElement('iframe');
               iframe.src = reader.result; 
               iframe.type = 'application/pdf';
               iframe.classList.add('w-100', 'px-3');
               iframe.style.height = '60vh';
               vaccinationPreview.appendChild(iframe);
          } else if (fileType.startsWith('image/')) {
               const img = document.createElement('img');
               img.src = reader.result;
               img.alt = 'Vista previa del plan de vacunación';
               img.classList.add('img-fluid');
               img.style.maxHeight = '1200px';
               vaccinationPreview.appendChild(img);
          } else {
               vaccinationPreview.innerHTML = '<h3>Archivo no compatible</h3>';
          }
     }

     if (file) {
          reader.readAsDataURL(file);
     }
}

function previewExistingVaccinationPlan(url) {
     const vaccinationPreview = document.getElementById('vaccinationPreview');
     vaccinationPreview.innerHTML = ''; // Limpiar la vista previa

     // Detectar si es PDF o imagen
     const fileType = url.split('.').pop().toLowerCase();

     if (fileType === 'pdf') {
          const iframe = document.createElement('iframe');
          iframe.src = url;
          iframe.type = 'application/pdf';
          iframe.classList.add('w-100', 'px-3');
          iframe.style.height = '60vh';
          vaccinationPreview.appendChild(iframe);
     } else if (['png', 'jpg', 'jpeg'].includes(fileType)) {
          const img = document.createElement('img');
          img.src = url;
          img.alt = 'Vista previa del plan de vacunación';
          img.classList.add('img-fluid');
          img.style.maxHeight = '80vh';
          vaccinationPreview.appendChild(img);
     } else {
          vaccinationPreview.innerHTML = '<h3>Archivo no compatible</h3>';
     }
}


const petsPerPage = 6; 
let currentPage = 1; 
let filteredPets = []; 

function filterPets() {
     const noPetsMessage = document.getElementById('noPetsMessage');
     const nameFilter = document.getElementById('filterPetName').value.toLowerCase();
     const sizeFilter = document.getElementById('filterPetSize').value.toLowerCase();
     
     const pets = document.querySelectorAll('.pet-item');
     filteredPets = [];
     let foundPet = false; 

     pets.forEach(pet => {
          const name = pet.getAttribute('data-name').toLowerCase();
          const size = pet.getAttribute('data-size').toLowerCase();

          const matchesName = name.includes(nameFilter);
          const matchesSize = !sizeFilter || size.includes(sizeFilter);

          if (matchesName && matchesSize) {
               pet.style.display = ''; 
               filteredPets.push(pet); // Agregar a la lista de mascotas filtradas
               foundPet = true; 
          } else {
               pet.style.display = 'none'; 
          }
     });
     
     if (foundPet) {
          noPetsMessage.classList.add('d-none'); 
          paginatePets(); // Llamar a la función de paginación
     } else {
          noPetsMessage.classList.remove('d-none'); 
          document.getElementById('pagination').innerHTML = ''; // Limpiar paginación
     }
}

function paginatePets() {
     const pagination = document.getElementById('pagination');
     pagination.innerHTML = ''; // Limpiar elementos de paginación

    const totalPages = Math.ceil(filteredPets.length / petsPerPage); // Total de páginas

    // Crear botones de paginación
     for (let i = 1; i <= totalPages; i++) {
          const li = document.createElement('li');
          li.className = 'page-item';
          li.innerHTML = `<a class="page-link" href="#" onclick="changePage(${i})">${i}</a>`;
          pagination.appendChild(li);
     }

     displayPets();
}

function changePage(page) {
     currentPage = page; 
     displayPets(); 
}

function displayPets() {
     const pets = document.querySelectorAll('.pet-item');
     const start = (currentPage - 1) * petsPerPage;
     const end = start + petsPerPage;

     pets.forEach((pet, index) => {
          if (filteredPets.includes(pet)) {
               pet.style.display = (index >= start && index < end) ? '' : 'none';
          }
     });
}

</script>
</html>