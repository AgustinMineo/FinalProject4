<?php
namespace Views;

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
<body style="background:#E0dfff;">
<nav class="navbar navBar404 navbar-expand-lg d-flex navbar-dark bg-dark" id="navbar">
        <span class="navbar-text">
            <strong>Pet Hero</strong>
        </span>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="<?php echo '/FinalProject4/' ?>index.php">Home</a>
            </li>          
        </ul>
    </nav> 
 <main >
     <div style="background:#E0dfff;" class="container w-25 h-50 d-flex flex-wrap justify-content-center shadow-lg p-3 mb-5 bg-body rounded">
        <form class="d-flex flex-column w-100" action="<?php echo '/FinalProject4/' ?>User/forgotPassword" method="post">
      <h3 class="text-center w-100 mt-5">Recovery</h3>
    <div class="container w-75">
        <div class="form-group w-100 ">
        <div class="mb-5">
            <div class="form-group">
                <label for="">Email</label>
                <input type="email" name="email" id="email" placeholder="E-mail" value="" class="form-control"  Required>
            </div>
        </div>

            <label for=""><h3>Question</h3></label>
            <div class="container d-flex align-items-center justify-content-center">

                <select name="QuestionRecovery" class="w-auto border border-1  text-center" Required>
                    <option value="What was your first pet called?" name="questionRecovery">What was your first pet called?</option>
                    <option value="What is your favorite breed of dog?" name="questionRecovery">What is your favorite breed of dog?</option>
                    <option value="What is your pet's favorite place?" name="questionRecovery">What is your pet's favorite place?</option>
                    <option value="What is your pet's favorite toy?" name="questionRecovery">What is your pet's favorite toy?</option>
                </select>
            </div>
        </div>
            <div class="form-group">
                <label for="">Answer</label>
                <textarea type="textarea" name="answerRecovery" rows = "1" cols ="15" maxlength="120" class="form-control" Required></textarea>
            </div>
        
            <div class="d-flex justify-content-center my-5">
                <button type="submit" class="btn btn-primary mb-3 w-100">Recovery Password!</button>
            </div>
    </div>
  </form>

    </div>
  </main>


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