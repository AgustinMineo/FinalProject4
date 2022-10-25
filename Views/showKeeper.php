<?php
namespace Views;
    require_once('nav.php');
?>
<main class="py-5">
     <section id="listado" class="mb-5">
          <div class="container">
               <h2 class="mb-4">Listado de Keepers</h2>
               <table class="table bg-light-alpha">
                    <thead>
                         <th>Last Name</th>
                         <th>First Name</th>
                         <th>Cellphone</th>
                    </thead>
                    <tbody>
                         <?php
                              foreach($keeperList as $keeper)
                              {
                                   ?>
                                        <tr>
                                             <td><?php echo $keeper->getLastName() ?></td>
                                             <td><?php echo $keeper->getfirstName() ?></td>
                                             <td><?php echo $keeper->getCellPhone() ?></td>
                                        </tr>
                                   <?php
                              }
                         ?>
                         </tr>
                    </tbody>
               </table>
          </div>
     </section>
</main>