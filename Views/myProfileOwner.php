<?php
namespace Views;
include ("ownerNav.php");
//require_once(VIEWS_PATH."validate-session.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"  crossorigin="anonymous">

  <title>PET HERO</title>
</head>
<body>
    <div class="container w-100 d-flex justify-content-center align-items-center flex-column">
      <h3 class="fw-nromal my-5">Here your profile!</h3>
    <div class="col-auto  w-50 border shadow-lg p-3 mb-5 bg-body rounded">
      <label for="" class=""><h4>Last Name</h4></label>
      
      <div class="container"><h3 class="text-center"><hr><p class="text-break"><?php echo $owner->getLastName();?></p></h3></div>
      <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#name" data-bs-whatever="@getbootstrap">Edit Last Name</button>
        <div class="modal fade" id="name" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Last Name</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="<?php echo FRONT_ROOT ?>User/updateLastName" method="post" class="bg-light-alpha p-5">
                    <div class="mb-3">
                        <label for="recipient-name" class="col-form-label w-100">Last name right now :<hr></label>
                        <div class="container">
                            <?php echo $owner->getLastName();?>
                        </div>
                        <hr>
                    </div>
                    <div class="mb-3">
                        <label for="message-text" class="col-form-label">New last name</label>
                        <input type="text" class="form-control" name="newName" id="newName" minlength="3" value='<?php echo $owner->getLastName();?>'></input>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update last name</button>
                </div>
            </form>
                </div>
            </div>
        </div> 
    </div>
    <div class="col-auto  w-50 border shadow-lg p-3 mb-5 bg-body rounded">
      <label for="" class=""><h4>First Name</h4></label>
      <div class="container"><h3 class="text-center"><hr><p class="text-break"><?php echo $owner->getfirstName();?></p></h3></div>
      <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#firstName" data-bs-whatever="@getbootstrap">Edit First Name</button>
        <div class="modal fade" id="firstName" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit First Name</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="<?php echo FRONT_ROOT ?>User/updateFirstName" method="post" class="bg-light-alpha p-5">
                    <div class="mb-3">
                        <label for="recipient-name" class="col-form-label w-100"> First Name now :<hr></label>
                        <div class="container">
                            <?php echo $owner->getfirstName();?>
                        </div>
                        <hr>
                    </div>
                    <div class="mb-3">
                        <label for="message-text" class="col-form-label">New first Name</label>
                        <input type="text" class="form-control" name="newFirstName" id="newFirstName"></input>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update first Name</button>
                </div>
            </div>
        </form>
            </div>
        </div> 
    </div>
    <div class="col-auto  w-50 border shadow-lg p-3 mb-5 bg-body rounded">
      <label for="" class=""><h4>Cellphone</h4></label>
      <div class="container"><h3 class="text-center"><hr><p class="text-break"><?php echo $owner->getCellPhone();?></p></h3></div>
      
      <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#cellphone" data-bs-whatever="@getbootstrap">Edit cellphone</button>
        <div class="modal fade" id="cellphone" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit cellphone</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="<?php echo FRONT_ROOT ?>User/UpdateUserCellphone" method="post" class="bg-light-alpha p-5"> 
                    <div class="mb-3">
                        <label for="recipient-name" class="col-form-label w-100">Cellphone now :<hr></label>
                        <div class="container">
                            <?php echo $owner->getCellPhone();?>
                        </div>
                        <hr>
                    </div>
                    <div class="mb-3">
                        <label for="message-text" class="col-form-label">New cellphone</label>
                        <input type="number" class="form-control" name="newCellphone" id="newCellphone" value='<?php echo $owner->getCellPhone();?>'></input>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update cellphone</button>
                </div>
            </div>
        </form>
            </div>
        </div> 
    </div>
    <div class="col-auto  w-50 border shadow-lg p-3 mb-5 bg-body rounded">
      <label for="" class=""><h4>BirthDate</h4></label>
      <div class="container"><h3 class="text-center"><hr><p class="text-break"><?php $date=date_create($owner->getbirthDate()); echo date_format($date,"d/m/Y");?></p></h3></div>
    </div>
    <div class="col-auto  w-50 border shadow-lg p-3 mb-5 bg-body rounded">
      <label for="" class=""><h4>Email</h4></label>
      <div class="container"><h3 class="text-center"><hr><p class="text-break"><?php echo $owner->getEmail();?></p></h3></div>
    </div>
    <div class="col-auto  w-50 border shadow-lg p-3 mb-5 bg-body rounded">
        <label for="" class=""><h4>Description</h4></label>
        <div class="container"><h3 class="text-center"><hr><p class="text-break"><?php echo $owner->getDescription();?></p></h3></div>
            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#description" data-bs-whatever="@getbootstrap">Edit description</button>
        <div class="modal fade" id="description" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit description</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="<?php echo FRONT_ROOT ?>User/updateDescription" method="post" class="bg-light-alpha p-5">
                    <div class="mb-3">
                        <label for="recipient-name" class="col-form-label w-100">Description now :<hr></label>
                        <div class="container">
                            <p class="text-break"><?php echo $owner->getDescription();?></p>
                        </div>
                        <hr>
                    </div>
                    <div class="mb-3">
                        <label for="message-text" class="col-form-label">New description</label>
                        <textarea class="form-control" name="newDescription" id="newDescription" value='<?php echo $owner->getDescription();?>'></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update description</button>
                </div>
            </div>
        </form>
            </div>
        </div>       
    </div>

    <div class="col-auto  w-50 border shadow-lg p-3 mb-5 bg-body rounded">
        <label for="" class=""><h4>Password</h4></label>
        <div class="container"><h3 class="text-center"><hr><p class="text-break">***********</p></h3></div>
            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#password" data-bs-whatever="@getbootstrap">Edit password</button>
        <div class="modal fade" id="password" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="<?php echo FRONT_ROOT ?>User/updatePassword" method="post" class="bg-light-alpha p-5">
                    <div class="mb-3">
                        <label for="recipient-name" class="col-form-label w-100">Password now :<hr></label>
                        <div class="container">
                            <p class="text-break">**********</p>
                        </div>
                        <hr>
                    </div>
                    <div class="mb-3">
                        <label for="message-text" class="col-form-label">New Password</label>
                        <input class="form-control" type="password" minlength="6" required  name="password" id="password" ></input>
                    </div>
                    <div class="mb-3">
                        <label for="message-text" class="col-form-label">Repeat Password</label>
                        <input class="form-control" type="password" minlength="6" required  name="password1" id="password1" ></input>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary passwordButton" >Update Password</button>
                </div>
            </div>
        </form>
            </div>
        </div>       
    </div>

    <div class="col-auto  w-50 border shadow-lg p-3 mb-5 bg-body rounded">
      <label for="" class=""><h4>Pet Amount</h4></label>
      <div class="container"><h3 class="text-center"><hr><p class="text-break"><?php echo $owner->getPetAmount();?></p></h3></div>
    </div>
    </div>
 
  <style>
    main{
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }
  </style>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
<script src="./js/main.js"></script>
</body>
</html>