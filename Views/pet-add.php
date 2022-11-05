<?php namespace Views;?>
<!-- JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
<!-- CSS only -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
<main class="py-5">
     <section id="listado" class="mb-5">
          <div class="container">
               <h2 class="mb-4">New Pet</h2>
               <form action="<?php echo '/FinalProject4/' ?>Pet/newPet" method="post" class="bg-light-alpha p-5">
                    <div class="row">
                    <div class="col-lg-4">
                              <div class="form-group">
                                   <label for="">Pet Name</label>
                                   <input type="text" name="petName" value="" placeholder="Name" class="form-control" Required >
                              </div>
                         </div>                         
                         <div class="col-lg-4">
                              <div class="form-group">
                                   <label for="">Pet Image</label>
                                   <input type="text" name="petImage" value="" placeholder="Image URL" class="form-control" Required >
                              </div>
                         </div>
                         <div class="col-lg-4">
                              <div class="form-group">
                                   <label for="">Breed</label>
                                   <input type="text" name="breed"  placeholder="Breed" value="" class="form-control" Required >
                              </div>
                         </div>
                         <div class="col-lg-4">
                              <div class="form-group">
                                   <label for="">Pet Size</label>
                                   <select name="petSize" class="w-100 h-50" Required>
                                        <option value="Small">Small</option>
                                        <option value="Medium">Medium</option>
                                        <option value="Big">Big</option>
                                   </select>
                              </div>
                         </div>
                         <div class="col-lg-4">
                              <div class="form-group">
                                   <label for="">Vaccination Plan</label>
                                   <input type="text" name="vaccinationPlan" placeholder="Vaccination Plan URL" value="" class="form-control" Required >
                              </div>
                         </div>
                         <div class="col-lg-4">
                              <div class="form-group">
                                   <label for="">Pet Details</label>
                                   <input type="text" name="petDetails" placeholder="Details" value="" class="form-control" Required >
                              </div>
                         </div>
                         <div class="col-lg-4">
                              <div class="form-group">
                                   <label for="">Pet Video</label>
                                   <input type="text" name="petVideo" placeholder="Video URL" value="" class="form-control" Required >
                              </div>
                         </div>
                         <div class="col-lg-4">
                              <div class="form-group">
                                   <label for="">Pet Weight</label>
                                   <select name="petWeight" class="w-100 h-50" Required>
                                        <option value="Light">0-15 kg</option>
                                        <option value="Medium">15-30kg</option>
                                        <option value="Heavy weight">30>kg</option>
                                   </select>
                              </div>
                         </div>
                         
                    </div>
                    <button type="submit" class="btn btn-dark ml-auto d-block">Save pet</button>
               </form>
          </div>
     </section>
</main>