<?php namespace Views;?>
<!-- JavaScript Bundle with Popper -->
<!DOCTYPE html>
<html lang="en">
<head>
     <meta charset="UTF-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
     <!-- CSS only -->
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
     <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
     <title>Login Owner</title>
</head>
<body>
     
     <nav class="navbar navbar-expand-lg  navbar-dark bg-dark">
          <span class="navbar-text">
               <strong>Pet Hero</strong>
          </span>
          <ul class="navbar-nav ml-auto">
               <li class="nav-item">
                    <a class="nav-link" href="<?php echo '/FinalProject4/' ?>index.php">Home</a>
               </li>          
          </ul>
       </nav>
     <main class="py-5">
          <section id="listado" class="mb-5">
               <div class="container">
                    <h2 class="mb-4">New Owner</h2>
                    <form action="<?php echo FRONT_ROOT ?>Owner/newOwner" method="post" class="bg-light-alpha p-5">
                         <div class="row">                         
                              <div class="col-lg-4">
                                   <div class="form-group">
                                        <label for="">Last Name</label>
                                        <input type="text" name="lastName" id="lastName" placeholder="Last name" value="" class="form-control"  Required>
                                   </div>
                              </div>
                              <div class="col-lg-4">
                                   <div class="form-group">
                                        <label for="">First Name</label>
                                        <input type="text" name="firstName" id="firstName" placeholder="First Name" value="" class="form-control"  Required>
                                   </div>
                              </div>
                              <div class="col-lg-4">
                                   <div class="form-group">
                                        <label for="">CellPhone</label>
                                        <input type="number" name="cellPhone" id="cellPhone" placeholder="Cellphone" value="" class="form-control" Required >
                                   </div>
                              </div>
                              <div class="col-lg-4">
                                   <div class="form-group">
                                        <label for="">BirthDate</label>
                                        <input type="date" name="birthDate" id="birthDate"  min="01-01-1925" max="2004-01-01" value ="01/01/2022"  class="form-control"  Required> 
                                   </div>
                              </div>
                              <div class="col-lg-4">
                                   <div class="form-group">
                                        <label for="">Email</label>
                                        <input type="email" name="email" id="email" placeholder="E-mail" value="" class="form-control"  Required>
                                   </div>
                              </div>
                              <div class="col-lg-4">
                                   <div class="form-group">
                                        <label for="">Password</label>
                                        <input type="password" name="password" id="password" placeholder="Password" value="" class="form-control"  Required>
                                   </div>
                              </div>

                         <div class="col-lg-4">
                              <div class="form-group">
                                   <label for=""> Repeat password</label><!--Se tiene que validar con el password de arriba que sean iguales.-->
                                   <input type="password" name="confirmPassword" placeholder="Repeat password" value="" class="form-control" Required >
                              </div>
                         </div>
                         <div class="col-lg-4">
                              <div class="form-group">
                                   <label for="">Description</label>
                                   <textarea type="textarea" name="userDescription" rows = "1" cols ="10" class="form-control"></textarea>
                              </div>
                         </div>
                         </div>
                         <div class ="container d-flex justify-content-center mt-5">
                              <button id="btnRegister" type="submit" class="btn btn-dark ml-auto d-block w-25">Sign Up</button>
                              <script src='<?php echo JS_PATH ?>owner.js'></script>
                         </div>
                    </form>
               </div>
          </section>
     </main>
</body>
</html>