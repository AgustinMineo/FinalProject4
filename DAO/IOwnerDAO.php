<?php
namespace DAO;
use Models\Owner as Owner;
use DAODB\Connect as Connect;

interface IOwnerDAO{
    function AddOwner(Owner $owner);
    function GetAllOwner();
}
?>