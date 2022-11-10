<?php namespace Views;?>
<!-- JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
<!-- CSS only -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
<main class="py-5">
     <section id="listado" class="mb-5">
          <div class="container d-flex justify-content-center align-content-center flex-wrap">
               <h2 class="mb-4">New Pet</h2>
               <form action="<?php echo '/FinalProject4/' ?>Pet/newPet" method="post" class="bg-light-alpha p-5">
                    <div class="row">
                    <div class="col-lg-4 w-100 d-flex justify-content-center align-content-center flex-wrap">
                              <div class="form-group w-50">
                                   <label for="">Pet Name</label>
                                   <input type="text" name="petName" value="" placeholder="Name" class="form-control" Required >
                              </div>
                         </div>                         
                         <div class="col-lg-4 w-100 d-flex justify-content-center align-content-center flex-wrap">
                              <div class="form-group w-50">
                                   <label for="">Pet Image</label>
                                   <input type="text" name="petImage" value="" placeholder="Image URL" class="form-control" Required >
                              </div>
                         </div>
                         <div class="col-lg-4 w-100 d-flex justify-content-center align-content-center flex-wrap">
                              <div class="form-group w-50 m-2">
                                   <label for="">Breed</label>
                                  <select name="breedID" class="w-100 h-50" Required>
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
                                   <!--<input type="text" name="breedID" placeholder="Breed" value="" class="form-control" Required >!-->
                              </div>
                         </div>
                         <div class="col-lg-4 w-100 d-flex justify-content-center align-content-center flex-wrap">
                              <div class="form-group w-50 m-2">
                                   <label for="">Pet Size</label>
                                   <select name="petSize" class="w-100 h-50" Required>
                                        <option value="Small">Small</option>
                                        <option value="Medium">Medium</option>
                                        <option value="Big">Big</option>
                                   </select>
                              </div>
                         </div>
                         <div class="col-lg-4 w-100 d-flex justify-content-center align-content-center flex-wrap">
                              <div class="form-group w-50">
                                   <label for="">Vaccination Plan</label>
                                   <input type="text" name="petVaccinationPlan" placeholder="Vaccination Plan URL" value="" class="form-control" Required >
                              </div>
                         </div>
                         <div class="col-lg-4 w-100 d-flex justify-content-center align-content-center flex-wrap">
                              <div class="form-group w-50">
                                   <label for="">Pet Details</label>
                                   <input type="text" name="petDetails" placeholder="Details" value="" class="form-control" Required >
                              </div>
                         </div>
                         <div class="col-lg-4 w-100 d-flex justify-content-center align-content-center flex-wrap">
                              <div class="form-group w-50">
                                   <label for="">Pet Video</label>
                                   <input type="text" name="petVideo" placeholder="Video URL" value="" class="form-control" Required >
                              </div>
                         </div>
                         <div class="col-lg-4 w-100 d-flex justify-content-center align-content-center flex-wrap">
                              <div class="form-group w-50 m-2">
                                   <label for="">Pet Weight</label>
                                   <select name="petWeight" class="w-100 h-50" Required>
                                        <option value="Light">0-15 kg</option>
                                        <option value="Medium">15-30kg</option>
                                        <option value="Heavy weight">30>kg</option>
                                   </select>
                              </div>
                         </div>
                         <div class="col-lg-4 w-100 d-flex justify-content-center align-content-center flex-wrap">
                              <div class="form-group w-50">
                                   <label for="">Pet Age</label>
                                   <input type="date" name="petAge" placeholder="Age" value="" class="form-control" Required >
                              </div>
                         </div>
                    </div>
                    <div class="container d-flex justify-content-center align-content-center mt-5 w-50">
                         <button type="submit" class="btn btn-dark ml-auto d-block w-50">Save pet</button>
                    </div>
               </form>
          </div>
     </section>
</main>