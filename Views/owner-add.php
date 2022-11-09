<?php namespace Views;?>
<!-- JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
<!-- CSS only -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
<main class="py-5">
     <section id="listado" class="mb-5">
          <div class="container">
               <h2 class="mb-4">New Owner</h2>
               <form action="<?php echo FRONT_ROOT ?>Owner/newOwner" method="post" class="bg-light-alpha p-5">
                    <div class="row">                         
                         <div class="col-lg-4">
                              <div class="form-group">
                                   <label for="">Last Name</label>
                                   <input type="text" name="lastName" placeholder="Last name" value="" class="form-control" Required >
                              </div>
                         </div>
                         <div class="col-lg-4">
                              <div class="form-group">
                                   <label for="">First Name</label>
                                   <input type="text" name="firstName"  placeholder="First Name" value="" class="form-control" Required >
                              </div>
                         </div>
                         <div class="col-lg-4">
                              <div class="form-group">
                                   <label for="">CellPhone</label>
                                   <input type="text" name="cellPhone" placeholder="Cellphone" value="" class="form-control" Required >
                              </div>
                         </div>
                         <div class="col-lg-4">
                              <div class="form-group">
                                   <label for="">BirthDate</label>
                                   <input type="date" name="birthDate" value="" class="form-control" Required >
                              </div>
                         </div>
                         <div class="col-lg-4">
                              <div class="form-group">
                                   <label for="">Email</label>
                                   <input type="email" name="email" placeholder="E-mail" value="" class="form-control" Required >
                              </div>
                         </div>
                         <div class="col-lg-4">
                              <div class="form-group">
                                   <label for="">Password</label>
                                   <input type="password" name="password" placeholder="Password" value="" class="form-control" Required >
                              </div>
                         </div>
                         <div class="col-lg-4">
                              <div class="form-group">
                                   <label for="">Image</label>
                                   <input type="file" name="userImage" value="" class="form-control">
                              </div>
                         </div>
                         <div class="col-lg-4">
                              <div class="form-group">
                                   <label for="">Description</label>
                                   <textarea type="textarea" name="userDescription" rows = "1" cols ="10" class="form-control"></textarea>
                              </div>
                         </div>
                    </div>
                    <div class = "container d-flex justify-content-center mt-5">
                         <button type="submit" class="btn btn-dark ml-auto d-block w-25">Sign Up</button>
                    </div>
               </form>
          </div>
     </section>
</main>