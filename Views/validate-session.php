<?php
namespace Views;

if(!isset($_SESSION["loggedUser"])){
    header("Location: loginUser.php");
    exit();
    //require_once(VIEWS_PATH. "loginUser.php");
}
?>