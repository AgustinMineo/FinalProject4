<?php 
namespace DAODB;

use \Exception as Exception;
use DAODB\Connect as Connect;
use DAO\IPetDAO as IPetDAO;
use Models\Pet as Pet;

class PetDAO implements IPetDAO{
    private $connection;
    private $petTable = 'pet';
    
    public function AddPet(Pet $pet){
try{
    $query = "INSERT INTO ".$this->petTable."(petID, petName, petImage, breedID, petSize, petVaccinationPlan, petDetails, petVideo, petWeight, ownerID, petAge)
    VALUES (:petID, :petName, :petImage, :breedID, :petSize, :petVaccinationPlan, :petDetails, :petVideo, :petWeight, :ownerID, :petAge);";
    $parameters["petID"] = NULL;
    $parameters["petName"] = $pet->getPetName();
    $parameters["petImage"] = $pet->getPetImage();
    $parameters["breedID"] = $pet->getBreedID();
    $parameters["petSize"] = $pet->getPetSize();
    $parameters["petVaccinationPlan"] = $pet->getPetVaccinationPlan();
    $parameters["petDetails"] = $pet->getPetDetails();
    $parameters["petVideo"] = $pet->getPetVideo();
    $parameters["petWeight"] = $pet->getPetWeight();
    $parameters["ownerID"] = $_SESSION["loggedUser"]->getOwnerId();
    $parameters["petAge"] = $pet->getPetAge();
    $this->connection = Connection::GetInstance();
    $this->connection->ExecuteNonQuery($query, $parameters);
    } catch (Exception $ex) {
        throw $ex;
    }


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
?>