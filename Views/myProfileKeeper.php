<?php
namespace Views;
require_once(VIEWS_PATH."keeperNav.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

  <title>PET HERO</title>
</head>
<body>

    <div class="container w-100 d-flex justify-content-center align-items-center flex-column">
      <h3 class="fw-nromal my-5">Here your profile!</h3>
    <div class="col-auto  w-50 border shadow-lg p-3 mb-5 bg-body rounded">
      <label for="" class=""><h4>Last Name</h4></label>
      
      <div class="container"><h3 class="text-center"><hr><p class="text-break"><?php echo $keeper->getLastName();?></p></h3></div>
      <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#name" data-bs-whatever="@getbootstrap">Edit Last Name</button>
        <div class="modal fade" id="name" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Last Name</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="<?php echo FRONT_ROOT ?>User/updateLastNameKeeper" method="post" class="bg-light-alpha p-5">
                    <div class="mb-3">
                        <label for="recipient-name" class="col-form-label w-100">Last name right now :<hr></label>
                        <div class="container">
                            <?php echo $keeper->getLastName();?>
                        </div>
                        <hr>
                    </div>
                    <div class="mb-3">
                        <label for="message-text" class="col-form-label">New last name</label>
                        <input type="text" class="form-control" name="newName" id="newName"></input>
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
      <div class="container"><h3 class="text-center"><hr><p class="text-break"><?php echo $keeper->getfirstName();?></p></h3></div>
      <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#firstName" data-bs-whatever="@getbootstrap">Edit First Name</button>
        <div class="modal fade" id="firstName" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit First Name</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="<?php echo FRONT_ROOT ?>User/updateFirstNameKeeper" method="post" class="bg-light-alpha p-5">
                    <div class="mb-3">
                        <label for="recipient-name" class="col-form-label w-100"> First Name now :<hr></label>
                        <div class="container">
                            <?php echo $keeper->getfirstName();?>
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
      <div class="container"><h3 class="text-center"><hr><p class="text-break"><?php echo $keeper->getCellPhone();?></p></h3></div>
      <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#cellphone" data-bs-whatever="@getbootstrap">Edit cellphone</button>
        <div class="modal fade" id="cellphone" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit cellphone</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="<?php echo FRONT_ROOT ?>User/UpdateCellphoneKeeper" method="post" class="bg-light-alpha p-5"> 
                    <div class="mb-3">
                        <label for="recipient-name" class="col-form-label w-100">Cellphone now :<hr></label>
                        <div class="container">
                            <?php echo $keeper->getCellPhone();?>
                        </div>
                        <hr>
                    </div>
                    <div class="mb-3">
                        <label for="message-text" class="col-form-label">New cellphone</label>
                        <input type="number" class="form-control" name="newCellphone" id="newCellphone"></input>
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
      <div class="container"><h3 class="text-center"><hr><p class="text-break"><?php $date=date_create($keeper->getbirthDate()); echo date_format($date,"d/m/Y");?></p></h3></div>
    </div>
    <div class="col-auto  w-50 border shadow-lg p-3 mb-5 bg-body rounded">
      <label for="" class=""><h4>Email</h4></label>
      <div class="container"><h3 class="text-center"><hr><p class="text-break"><?php echo $keeper->getEmail();?></p></h3></div>
    </div>
    <div class="col-auto  w-50 border shadow-lg p-3 mb-5 bg-body rounded">
        <label for="" class=""><h4>Description</h4></label>
        <div class="container"><h3 class="text-center"><hr><p class="text-break"><?php echo $keeper->getDescription();?></p></h3></div>
            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#description" data-bs-whatever="@getbootstrap">Edit description</button>
        <div class="modal fade" id="description" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit description</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="<?php echo FRONT_ROOT ?>User/UpdateDescriptionKeeper" method="post" class="bg-light-alpha p-5">
                    <div class="mb-3">
                        <label for="recipient-name" class="col-form-label w-100">Description now :<hr></label>
                        <div class="container">
                            <p class="text-break"><?php echo $keeper->getDescription();?></p>
                        </div>
                        <hr>
                    </div>
                    <div class="mb-3">
                        <label for="message-text" class="col-form-label">New description</label>
                        <textarea class="form-control" name="newDescription" id="newDescription"></textarea>
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
        <label for="" class=""><h4>Animal Size</h4></label>
        <div class="container"><h3 class="text-center"><hr><p class="text-break"><?php echo $keeper->getAnimalSize();?></p></h3></div>
            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#animalSize" data-bs-whatever="@getbootstrap">Edit animal size</button>
        <div class="modal fade" id="animalSize" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit animal size</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="<?php echo FRONT_ROOT ?>User/UpdateAnimalSizeKeeper" method="post" class="bg-light-alpha p-5">
                    <div class="mb-3">
                        <label for="recipient-name" class="col-form-label w-100">Animal size now :<hr></label>
                        <div class="container">
                            <p class="text-break"><?php echo $keeper->getAnimalSize();?></p>
                        </div>
                        <hr>
                    </div>
                    <div class="mb-3">
                        <label for="message-text" class="col-form-label">Animal Size</label>
                        <div class="form-group h-75">
                            <label for="">Tamaño del animal</label>
                                <select name="animalSize" class="w-100 h-75" Required>
                                    <option value="Small" name="animalSize">Small</option>
                                    <option value="Medium" name="animalSize">Medium</option>
                                    <option value="Big" name="animalSize">Big</option>
                                </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Animal Size</button>
                </div>
            </div>
        </form>
            </div>
        </div>       
    </div>
    <div class="col-auto  w-50 border shadow-lg p-3 mb-5 bg-body rounded">
        <label for="" class=""><h4>Price</h4></label>
        <div class="container"><h3 class="text-center"><hr><p class="text-break"><?php echo $keeper->getPrice();?></p></h3></div>
            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#price" data-bs-whatever="@getbootstrap">Edit Price</button>
        <div class="modal fade" id="price" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Price</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="<?php echo FRONT_ROOT ?>User/UpdatePriceKeeper" method="post" class="bg-light-alpha p-5">
                    <div class="mb-3">
                        <label for="recipient-name" class="col-form-label w-100">Price now :<hr></label>
                        <div class="container">
                            <p class="text-break"><?php echo $keeper->getPrice();?></p>
                        </div>
                        <hr>
                    </div>
                    <div class="mb-3">
                        <label for="message-text" class="col-form-label">New Price</label>
                        <textarea class="form-control" name="newPrice" id="newPrice"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update price</button>
                </div>
            </div>
        </form>
            </div>
        </div>       
    </div>
    <div class="col-auto  w-50 border shadow-lg p-3 mb-5 bg-body rounded">
        <label for="" class=""><h4>CBU</h4></label>
        <div class="container"><h3 class="text-center"><hr><p class="text-break"><?php echo $keeper->getCBU();?></p></h3></div>
            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#cbu" data-bs-whatever="@getbootstrap">Edit CBU</button>
        <div class="modal fade" id="cbu" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit CBU</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="<?php echo FRONT_ROOT ?>User/UpdateCBUKeeper" method="post" class="bg-light-alpha p-5">
                    <div class="mb-3">
                        <label for="recipient-name" class="col-form-label w-100">CBU now :<hr></label>
                        <div class="container">
                            <p class="text-break"><?php echo $keeper->getCBU();?></p>
                        </div>
                        <hr>
                    </div>
                    <div class="mb-3">
                        <label for="message-text" class="col-form-label">New CBU</label>
                        <textarea class="form-control" name="newCBU" id="newCBU"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update CBU</button>
                </div>
            </div>
        </form>
            </div>
        </div>       
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
</body>
</html>