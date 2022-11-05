<?php 
namespace DAODB;

use \Exception as Exception;
use DAODB\Connect as Connect;
use DAO\IPetDAO as IOPetDAO;
use Models\Pet as Pet;

class PetDAO implements IPetDAO{
    private $connection;
    private $tableName = 'pet';
    
    public function AddPet($pet){

    }

    public function GetAllPet(){

    }

    private function SaveData(){

    }

    public function searchPets($email){

    }
    public function searchPetsBySize($email,$size){
    }
    
    public function selectPetByID($petID){
            }
        }
    } 
}

?>