<?php namespace Views;?>
<nav class="navbar navbar-expand-lg  navbar-dark bg-dark">
     <span class="navbar-text">
          <strong>NavBar</strong>
     </span>
     <ul class="navbar-nav ml-auto">
          <li class="nav-item">
               <a class="nav-link" href="<?php echo FRONT_ROOT ?>User/addOwnerView">Agregar owner</a>
          </li>
          <li class="nav-item">
               <a class="nav-link" href="<?php echo FRONT_ROOT ?>User/addKeeperView">Agregar keeper</a>
          </li>  
          <li class="nav-item">
               <a class="nav-link" href="<?php echo FRONT_ROOT ?>Owner/loginOwner">Login keeper</a>
          </li>          
     </ul>
</nav>