<?php
require_once(VIEWS_PATH."header.php");
echo "<h1>ESTOY EN LA LANDING</h1>";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <span>Bienvenido <?php echo var_dump( $_SESSION["loggedUser"]) ?> </span>
</body>
</html>