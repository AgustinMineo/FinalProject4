<?php
namespace DAO;
use Models\Keeper as Keeper;
use DAODB\Connect as Connect;

interface IKeeperDAO{
    function addKeeper(Keeper $Keeper);
    function getAllKeeper();
    //function editKeeper();
}
?>