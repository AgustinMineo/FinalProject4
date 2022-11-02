<?php namespace Views;?>
<div class="container">
    <header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
      <a href="/" class="d-flex align-items-center col-md-3 mb-2 mb-md-0 text-dark text-decoration-none">
        <svg class="bi me-2" width="40" height="32" role="img" aria-label="Bootstrap"><use xlink:href="#bootstrap"/></svg>
      </a>
      <div class="col-md-3 text-end">
        
        <button type="button" class="btn">
            <a href="<?php echo FRONT_ROOT?>User/addOwnerView">Sign-up owner</a>
        </button>
        <button type="button" class="btn ">
            <a href="<?php echo FRONT_ROOT?>User/addKeeperView">Sign-up Keeper</a>
        </button>
        <button type="button" class="btn ">
            <a href="<?php echo FRONT_ROOT?>Views/loginKeeper.php">Login as keeper</a>
        </button>
        <button type="button" class="btn ">
            <a href="<?php echo FRONT_ROOT?>Views/loginOwner.php">Login as Owner</a>
        </button>
      </div>
    </header>
  </div>