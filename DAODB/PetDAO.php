<?php 
namespace DAODB;

use \Exception as Exception;
use DAODB\Connect as Connect;
use DAO\IPetDAO as IPetDAO;
use Models\Pet as Pet;

class PetDAO implements IPetDAO{
    private $connection;
    private $petTable = 'pet';
    private $ownerTable = 'owner';
    private $petList = array();
    
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
        try {
            $petList = array();

            $query = "SELECT * FROM ".$this->petTable." p LEFT JOIN ".$this->ownerTable." o ON o.ownerID = p.ownerID";

            $this->connection = Connection::GetInstance();

            $resultSet = $this->connection->Execute($query);

            foreach($resultSet as $row){
                $pet = new Pet();

                $pet->setPetID($row["petID"]);
                $pet->setPetName($row["petName"]);
                $pet->setPetImage($row["petImage"]);
                $pet->setBreedID($row["breedID"]);
                $pet->setPetSize($row["petSize"]);
                $pet->setPetVaccinationPlan($row["petVaccinationPlan"]);
                $pet->setPetDetails($row["petDetails"]);
                $pet->setPetVideo($row["petVideo"]);
                $pet->setPetWeight($row["petWeight"]);
                $pet->setOwnerID($row["ownerID"]);
                $pet->setPetAge($row["petAge"]);
                array_push($petList, $pet);
            }
            return $petList;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    private function SaveData(){

    }

    public function searchPets($ownerID){
            $petList = $this->GetAllPet();
            $petListSearch = array();
            foreach($petList as $ownerPet){
                if($ownerPet->getOwnerID() == $ownerID){
                    array_push($petListSearch,$ownerPet);
                }
            }
            return $petListSearch;
        }
        
        public function searchPetsBySize($email,$size){
        }
        
        public function selectPetByID($petID){
            
        }
    }
?>