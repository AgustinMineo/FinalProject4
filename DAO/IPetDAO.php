<?php
namespace DAO;
Use Models\Pet as Pet;

interface IPetDAO{
    function AddPet(Pet $pet);
    function GetAllPet();
    //function EditPet();
}
?>