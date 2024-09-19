<?php
namespace DAO;
use Models\Keeper as Keeper;

interface IKeeperDAO{
    function addKeeper(Keeper $Keeper);
    function getAllKeeper();
   // function SaveData();
}
?>