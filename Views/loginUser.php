<?php namespace Views; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <title>PET HERO</title>
</head>
<body>
  
  <section class="vh-100">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
       <span class="navbar-text">
            <strong>Pet Hero</strong>
       </span>
       <ul class="navbar-nav">
            <li class="nav-item">
                 <a class="nav-link" href="<?php echo '/FinalProject4/' ?>index.php">Home</a>
            </li>          
       </ul>
    </nav>  

    <div class="container py-5 h-100">
      <div class="row d-flex align-items-center justify-content-center h-100">
        <div class="col-md-8 col-lg-7 col-xl-6">
          <img src="https://img.freepik.com/premium-photo/tourist-with-backpack-walks-dog-field-nature-mountains-travel_163305-85442.jpg?w=1380"
            class="img-fluid" alt="Person with dog image">
        </div>
        <div class="col-md-7 col-lg-5 col-xl-5 offset-xl-1">
          <form action="<?php echo '/FinalProject4/' ?>User/loginUser" method="post">
            <div class="form-outline mb-4">
              <input type="email" name="email" class="form-control form-control-lg" required />
              <label class="form-label" for="email">Email address</label>
            </div>
            <div class="form-outline mb-4">
              <input type="password" name="password" class="form-control form-control-lg" required />
              <label class="form-label" for="password">Password</label>
            </div>

            <div class="d-flex justify-content-around align-items-center mb-4">
              <a href="<?php echo '/FinalProject4/' ?>Views/recoveryView.php">Forgot password?</a>
            </div>
  
            <div class="container d-flex flex-wrap justify-content-center align-items-center">
              <button type="submit" class="btn btn-primary btn-lg btn-block">Log in</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
</body>
</html>
