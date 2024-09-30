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
    <style>
        body {
            background-color: white;
        }
        main {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .form-container {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        .navbar {
            background-color: #343a40;
        }
        .navbar-brand {
            font-weight: bold;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
            transition: background-color 0.3s;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark">
    <span class="navbar-text">
        <strong>Pet Hero</strong>
    </span>
    <ul class="navbar-nav ">
        <li class="nav-item">
            <a class="nav-link" href="<?php echo '/FinalProject4/' ?>index.php">Home</a>
        </li>
    </ul>
</nav> 
<main>
    <div class="container w-25 d-flex flex-wrap justify-content-center p-4 mb-5 form-container">
        <form class="d-flex flex-column w-100" action="<?php echo '/FinalProject4/' ?>User/forgotPassword" method="post">
            <h3 class="text-center mb-4">Recovery</h3>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" placeholder="E-mail" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="questionRecovery" class="form-label">Question</label>
                <select name="QuestionRecovery" id="questionRecovery" class="form-select" required>
                    <option value="">Select a question</option>
                    <option value="What was your first pet called?">What was your first pet called?</option>
                    <option value="What is your favorite breed of dog?">What is your favorite breed of dog?</option>
                    <option value="What is your pet's favorite place?">What is your pet's favorite place?</option>
                    <option value="What is your pet's favorite toy?">What is your pet's favorite toy?</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="answerRecovery" class="form-label">Answer</label>
                <textarea name="answerRecovery" id="answerRecovery" rows="1" maxlength="120" class="form-control" required></textarea>
            </div>
            <div class="d-flex justify-content-center my-4">
                <button type="submit" class="btn btn-primary w-100">Recovery Password!</button>
            </div>
        </form>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
</body>
</html>
