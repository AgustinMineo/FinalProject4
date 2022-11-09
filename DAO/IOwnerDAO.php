<?php
namespace DAO;
use Models\Owner as Owner;

interface IOwnerDAO{
    function AddOwner(Owner $owner);
    function GetAllOwner();
}
?>