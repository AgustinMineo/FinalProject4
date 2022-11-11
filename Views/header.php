<?php namespace Views;?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PetHeros</title>
  </head>
<body>
  <div class="container">
    <header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
      <a href="#" class="d-flex align-items-center col-md-3 mb-2 mb-md-0 text-dark text-decoration-none w-25 p-3">
        <div style="width:50%;">
          <img src="<?php echo IMG_PATH?>logoOwner.svg" alt="Logo Pet">
        </div>
      </a>
      <div class="col-lg-6 text-end d-flex justify-content-left w-75 p-5 ">
        <button type="button" class="btn border border-1 p-4">
          <a href="<?php echo FRONT_ROOT?>User/addOwnerView" class="text-decoration-none">Sign-up owner</a>
        </button>
        <button type="button" class="btn border border-1 p-4">
          <a href="<?php echo FRONT_ROOT?>User/addKeeperView" class="text-decoration-none">Sign-up Keeper</a>
          </button>
          <button type="button" class="btn border border-1 p-4">
            <a href="<?php echo FRONT_ROOT?>Views/loginKeeper.php" class="text-decoration-none">Login as keeper</a>
          </button>
          <button type="button" class="btn border border-1 p-4">
            <a href="<?php echo FRONT_ROOT?>Views/loginOwner.php" class="text-decoration-none">Login as Owner</a>
          </button>
        </div>
      </header>
    </div>
    <script type='text/javascript'>document.addEventListener('DOMContentLoaded', function () {window.setTimeout(document.querySelector('svg').classList.add('animated'),1000);})</script>
  </body>
</html>