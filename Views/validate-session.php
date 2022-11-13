<?php
namespace Views;

if(!isset($_SESSION["loggedUser"])){
    require_once(VIEWS_PATH. "loginUser.php");
}
?>