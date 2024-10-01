<?php
namespace Views;
require_once("validate-session.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
  <title>PET HERO</title>
  <style>
        /* Animación de entrada deslizante desde arriba */
        @keyframes slideInDown {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* Aplica la animación solo al abrir */
        .modal.show .modal-dialog {
            animation: slideInDown 0.5s ease-out;
        }

        /* Estilo del modal (bordes redondeados y sombra) */
        .modal-content {
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        /* Fondo del backdrop más oscuro */
        .modal-backdrop {
            background-color: rgba(0, 0, 0, 0.8);
        }
    </style>
</head>
<body>

  <div class="container my-5">

    <h3 class="fw-normal text-center mb-4"><?php if($flag === 1 && $userRole===1){  echo 'Modificando a '. "<strong>". $user->getLastName(). " " . $user->getFirstName() . "</strong>"; }else{ echo 'Your Profile';} ?> </h3>
  <!-- Last Name Section -->
  <div class="row mb-4">
  <!-- Last Name Section -->
  <div class="col-md-6 my-2">
    <div class="card shadow">
      <div class="card-body text-center">
        <h4 class="card-title"><i class="fas fa-user"></i> Last Name</h4>
        <p class="card-text"><?php echo $user->getLastName(); ?></p>
        <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#LastnameModal">
          <span>Edit Last Name</span>
        </button>
      </div>
    </div>
  </div>

  <!-- First Name Section -->
  <div class="col-md-6 my-2">
    <div class="card shadow">
      <div class="card-body text-center">
          <h4 class="card-title"><i class="fas fa-user"></i> First Name</h4>
          <p class="card-text"><?php echo $user->getFirstName(); ?></p>
          <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#firstNameModal">
              <span>Edit First Name</span>
          </button>
      </div>
    </div>
  </div>


<!-- Last Name Modal -->
<div class="modal fade" id="LastnameModal" tabindex="-1" aria-labelledby="editLastNameLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editLastNameLabel">Edit Last Name</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="<?php echo FRONT_ROOT ?>User/updateLastName" method="post">
          <div class="mb-3">
            <label for="newName" class="form-label">New Last Name</label>
            <input type="text" class="form-control" id="newName" name="newName" value="<?php echo $user->getLastName(); ?>" required>
          </div>
          <div class="modal-footer">
            <input type="hidden" name="userEmail" value="<?php echo $user->getEmail(); ?>">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- First Name Modal -->
<div class="modal fade" id="firstNameModal" tabindex="-1" aria-labelledby="editFirstNameLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editFirstNameLabel">Edit First Name</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="<?php echo FRONT_ROOT ?>User/updateFirstName" method="post">
          <div class="mb-3">
            <label for="newFirstName" class="form-label">New First Name</label>
            <input type="text" class="form-control" id="newFirstName" name="newFirstName" value="<?php echo $user->getFirstName(); ?>" required>
          </div>
          <div class="modal-footer">
            <input type="hidden" name="userEmail" value="<?php echo $user->getEmail(); ?>">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
    <!-- First Name Section -->

    <!-- Cellphone Section -->

  <div class="col-md-6 my-2">
    <div class="card shadow">
      <div class="card-body text-center">
        <h4 class="card-title"><i class="fas fa-mobile-alt"></i> Cellphone</h4>
        <p class="card-text"><?php echo $user->getCellPhone(); ?></p>
        <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#cellphoneModal">
          <span>Edit Cellphone</span>
        </button>
      </div>
    </div>
  </div>

<div class="modal fade" id="cellphoneModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Cellphone</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form action="<?php echo FRONT_ROOT ?>User/UpdateUserCellphone" method="post">
          <div class="mb-3">
            <label for="newCellphone" class="form-label">New Cellphone</label>
            <input type="text" class="form-control" id="newCellphone" name="newCellphone" value="<?php echo $user->getCellphone(); ?>" required>
          </div>
          <div class="modal-footer">
            <input type="hidden" name="userEmail" value="<?php echo $user->getEmail(); ?>">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- Cellphone Section -->

<!-- BirthDate Section -->
  <div class="col-md-6 my-2">
        <div class="card shadow">
            <div class="card-body text-center">
                <h4 class="card-title"><i class="fas fa-birthday-cake"></i> Birth Date</h4>
                <p class="card-text"><?php echo date_format(date_create($user->getBirthDate()), 'd/m/Y'); ?></p>
                <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#BirthDateModal">
                    <span>Edit Birth Date</span>
                </button>
            </div>
        </div>
  </div>

<div class="modal fade" id="BirthDateModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Birth Date</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form action="<?php echo FRONT_ROOT ?>User/updateBirthdate" method="post">
          <div class="mb-3">
            <label for="newBirthdate" class="form-label">New Birth Date</label>
            <input type="date" class="form-control" id="newBirthdate" name="newBirthdate" value="<?php echo $user->getbirthDate(); ?>" required>
          </div>
          <div class="modal-footer">
            <input type="hidden" name="userEmail" value="<?php echo $user->getEmail(); ?>">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- BirthDate Section -->

<!-- Email Section -->
<div class="col-md-6 my-2">
        <div class="card shadow ">
            <div class="card-body text-center">
                <h4 class="card-title"><i class="fas fa-envelope"></i> Email</h4>
                <p class="card-text"><?php echo $user->getEmail(); ?></p>
                <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#EmailModal">
                    <span>Edit Email</span>
                </button>
            </div>
        </div>
  </div>


<div class="modal fade" id="EmailModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Email</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form action="<?php echo FRONT_ROOT ?>User/updateEmail" method="post">
          <div class="mb-3">
            <label for="newEmail" class="form-label">Email</label>
            <input type="text" class="form-control" id="newEmail" name="newEmail" value="<?php echo $user->getEmail(); ?>" required>
          </div>
          <div class="modal-footer">
            <input type="hidden" name="userEmail" value="<?php echo $user->getEmail(); ?>">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- Email Section -->

<!-- Password Section -->

  <div class="col-md-6 my-2">
        <div class="card shadow ">
            <div class="card-body text-center">
                <h4 class="card-title"><i class="fas fa-lock"></i> Password</h4>
                <p class="card-text">*************</p>
                <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#passwordModal">
                    <span>Edit Password</span>
                </button>
            </div>
        </div>
  </div>

<div class="modal fade" id="passwordModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Password</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form action="<?php echo FRONT_ROOT ?>User/updatePassword" method="post">
          <div class="mb-3">
            <label for="newPassword" class="form-label">Password</label>
            <input type="password" class="form-control" id="newPassword" name="newPassword" value="" required>
            <label for="Repeat">Repeat password</label>
            <input type="password" class="form-control" id="newPassword1" name="newPassword1" value="" required>
          </div>
          <div class="modal-footer">
            <input type="hidden" name="userEmail" value="<?php echo $user->getEmail(); ?>">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- Password Section -->

<!-- User Description Section -->
<div class="col-md-6 my-2">
        <div class="card shadow">
            <div class="card-body text-center">
                <h4 class="card-title"><i class="fas fa-user"></i>  Description</h4>
                <p class="card-text"><?php echo $user->getDescription(); ?></p>
                <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#descriptionModal">
                    <span>Edit Description</span>
                </button>
            </div>
        </div>
  </div>
<div class="modal fade" id="descriptionModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Description</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form action="<?php echo FRONT_ROOT ?>User/UpdateDescription" method="post">
          <div class="mb-3">
            <label for="newDescription" class="form-label">Description</label>
            <textarea type="text" class="form-control" id="newDescription" name="newDescription" required><?php echo $user->getDescription();?></textarea>
          </div>
          <div class="modal-footer">
            <input type="hidden" name="userEmail" value="<?php echo $user->getEmail(); ?>">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- User Description Section -->
    
    <!-- User Recovery Section -->
    <div class="col-md-6 my-2">
        <div class="card shadow ">
            <div class="card-body text-center">
                <h4 class="card-title"><i class="fas fa-redo-alt"></i> Recovery Password</h4>
                <p class="card-text"><?php echo $user->getQuestionRecovery(); ?></p>
                <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#recoveryModal">
                    <span>Edit Recovery</span>
                </button>
            </div>
        </div>
    </div>
    <div class="modal fade" id="recoveryModal" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Edit Recovery</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <form action="<?php echo FRONT_ROOT ?>User/updateRecovery" method="post">
              <div class="mb-3">
                <label for="newRecovery" class="form-label"><strong>Recovery Question</strong></label>
                <select name="QuestionRecovery" class="w-100 border border-1 mb-5 h-5" Required>
                  <option value="What was your first pet called?" name="QuestionRecovery">What was your first pet called?</option>
                  <option value="What is your favorite breed of dog?" name="QuestionRecovery">What is your favorite breed of dog?</option>
                  <option value="What is your pet's favorite place?" name="QuestionRecovery">What is your pet's favorite place?</option>
                  <option value="What is your pet's favorite toy?" name="QuestionRecovery">What is your pet's favorite toy?</option>
                </select>
                <textarea type="text" class="form-control" id="newName" name="answerRecovery" value="" required></textarea>
              </div>
              <div class="modal-footer">
                <input type="hidden" name="userEmail" value="<?php echo $user->getEmail(); ?>">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <!-- User Recovery Section -->

    <!-- Pet Amount Section (Only Owner) -->
    <?php if($userRole=== 2 || ($userRole !==3 && $flag===1 && $user->getRol()!='3' && $user->getRol()!='1' )  ): ?>
    <div class="col-md-6 my-2">
        <div class="card shadow">
            <div class="card-body text-center">
                <h4 class="card-title"><i class="fas fa-paw"></i> Pet Amount</h4>
                <p class="card-text"><?php if($user->getPetAmount()===0){ echo 0;}else{echo $user->getPetAmount();} ?></p>
                <a class="btn btn-outline-primary" href="<?php echo FRONT_ROOT ?>Pet/showPets" style="text-decoration: none;">
                    <i class="fas fa-dog"></i> Mis Mascotas
                </a>
            </div>
        </div>
    </div>
    <?php endif;?>
    <!-- Pet Amount Section (Only Owner) -->

    <!-- Keeper section (Only Keeper) -->
    <!--Se evalua de la siguiente forma, si es keeper se muestra,
     sino si es admin y flag=1 (Editando un usuario, se muestra)-->
    <?php if($userRole===3 || ($userRole===1 && $flag===1 && $user->getRol()==='3')): ?>
    <!-- Keeper Animal Size Section -->
    <div class="col-md-6 my-2">
        <div class="card shadow ">
            <div class="card-body text-center">
                <h4 class="card-title"><i class="fas fa-ruler"></i> Animal Size</h4>
                <p class="card-text"><?php echo $user->getAnimalSize(); ?></p>
                <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#AnimalSizeModal">
                    <span>Edit Animal Size</span>
                </button>
            </div>
        </div>
    </div>
    <div class="modal fade" id="AnimalSizeModal" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Edit Animal Size</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <form action="<?php echo FRONT_ROOT ?>User/UpdateAnimalSize" method="post">
              <div class="mb-3">
                <label for="newAnimalSize" class="form-label">Current Animal Size</label>
                <input type="text" class="form-control mb-3" value= "<?php echo $user->getAnimalSize(); ?>" disabled>
                <label for="newAnimalSize" class="form-label">New Animal Size</label>  
                <select name="animalSize" class="w-100 h-75" Required>
                  <option value="Small">Small</option>
                  <option value="Medium">Medium</option>
                  <option value="Big">Big</option>
                </select>
              </div>
              <div class="modal-footer">
              <input type="hidden" name="userEmail" value="<?php echo $user->getEmail(); ?>">
                <input type="hidden" name="keeperID" value="<?php echo $user->getKeeperId(); ?>">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <!-- Keeper Animal Size Section -->

    <!-- Keeper Price Section -->
    <div class="col-md-6 my-2">
        <div class="card shadow ">
            <div class="card-body text-center">
                <h4 class="card-title"><i class="fas fa-tag"></i> Price</h4>
                <p class="card-text">$<?php echo $user->getPrice(); ?></p>
                <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#PriceModal">
                    <span>Edit Price</span>
                </button>
            </div>
        </div>
    </div>
    <div class="modal fade" id="PriceModal" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Edit Price</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <form action="<?php echo FRONT_ROOT ?>User/UpdatePrice" method="post">
              <div class="mb-3">
                <label for="newPrice" class="form-label">New Price</label><br>
                <input type="text" class="form-control" id="newPrice" name="newPrice" value="<?php echo $user->getPrice(); ?>" required>
              </div>
              <div class="modal-footer">
                <input type="hidden" name="userEmail" value="<?php echo $user->getEmail(); ?>">
                <input type="hidden" name="keeperID" value="<?php echo $user->getKeeperId(); ?>">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <!-- Keeper Animal Size Section -->

    <!-- Keeper CBU Section -->
    <div class="col-md-6 my-2">
        <div class="card shadow ">
            <div class="card-body text-center">
                <h4 class="card-title"><i class="fas fa-university"></i> CBU</h4>
                <p class="card-text"><?php echo $user->getCBU(); ?></p>
                <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#CbuModal">
                    <span>Edit CBU</span>
                </button>
            </div>
        </div>
    </div>
    <div class="modal fade" id="CbuModal" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Edit CBU</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <form action="<?php echo FRONT_ROOT ?>User/UpdateCBU" method="post">
              <div class="mb-3">
                <label for="newCBU" class="form-label">CBU</label>
                <input type="text" class="form-control" id="newCBU" name="newCBU" value="<?php echo $user->getCbu(); ?>" required>
              </div>
              <div class="modal-footer">
                <input type="hidden" name="userEmail" value="<?php echo $user->getEmail(); ?>">
                <input type="hidden" name="keeperID" value="<?php echo $user->getKeeperId(); ?>">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <!-- Keeper Animal Size Section -->
    <!-- Keeper Points Section -->
    <div class="col-md-6 my-2">
        <div class="card shadow">
            <div class="card-body text-center">
                <h4 class="card-title"><i class="fas fa-trophy"></i> Ranking</h4>
                <p class="card-text"><?php echo $user->getPoints(); ?></p>
                <a class="btn btn-outline-primary" href="<?php echo FRONT_ROOT ?>Review/getAllReviews" style="text-decoration: none;">
                    <i class="fas fa-star"></i> Mis Reviews
                </a>
            </div>
        </div>
    </div>
    <!-- Keeper Points Section -->
    <?php endif;?>
    <!-- Keeper section (Only Keeper) -->
     <!-- Delete user section-->
  <div class=" my-2 <?php if($user->getRol()=== '2'){echo 'col-md-6';}else{echo 'col-md-12';}?>">
        <div class="card shadow ">
            <div class="card-body text-center">
                <h4 class="card-title"><i class="fas fa-user-slash text-danger"></i> Delete Account</h4>
                <p class="card-text">Are you sure you want to delete your account? This action cannot be undone.</p>
                <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                    <span>Delete Account</span>
                </button>
            </div>
        </div>
  </div>

  <div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Delete Account</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <p>Are you sure you want to delete your account? This action cannot be undone.</p>
        </div>
        <div class="modal-footer">
          <form action="<?php echo FRONT_ROOT ?>User/deleteUser" method="post">
            <input type="hidden" name="userEmail" value="<?php echo $user->getEmail(); ?>">
            <input type="hidden" name="status" value="<?php echo intval($user->getStatus()); ?>">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-danger">Delete Account</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- Delete User Section -->
  </div>
  </div><!--End row-->

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
