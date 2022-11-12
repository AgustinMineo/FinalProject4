<?php 
namespace DAODB;

use \Exception as Exception;
use DAODB\Connect as Connect;
use DAO\IPetDAO as IPetDAO;
use Models\Pet as Pet;

class PetDAO implements IPetDAO{
    private $connection;
    private $petTable = 'Pet';
    private $ownerTable = 'owner';
    private $breedTable = 'breed';
    private $petList = array();
    
    public function AddPet(Pet $pet){
try{
    $query = "INSERT INTO ".$this->petTable."(petID, petName, petImage, breedID, petSize, petVacunationPlan, petDetails, petVideo, petWeight, ownerID, petAge)
    VALUES (:petID, :petName, :petImage, :breedID, :petSize, :petVacunationPlan, :petDetails, :petVideo, :petWeight, :ownerID, :petAge);";
    $parameters["petID"] = NULL;
    $parameters["petName"] = $pet->getPetName();
    $parameters["petImage"] = $pet->getPetImage();
    $parameters["breedID"] = $pet->getBreedID();
    $parameters["petSize"] = $pet->getPetSize();
    $parameters["petVacunationPlan"] = $pet->getPetVaccinationPlan();
    $parameters["petDetails"] = $pet->getPetDetails();
    $parameters["petVideo"] = $pet->getPetVideo();
    $parameters["petWeight"] = $pet->getPetWeight();
    $parameters["ownerID"] = $_SESSION["loggedUser"]->getOwnerId();
    $parameters["petAge"] = $pet->getPetAge();
    $this->connection = Connection::GetInstance();
    //$this->connection->ExecuteNonQuery($query, $parameters)

        //Funcion para hacer el Update de PETAMOUNT en la Tabla de Owners. 
         if($this->connection->ExecuteNonQuery($query, $parameters)){
                $queryAmount = "update " .$this->ownerTable." o set petAmount = ".$_SESSION["loggedUser"]->getPetAmount()." + 1 WHERE o.ownerID = ".$_SESSION["loggedUser"]->getOwnerId();
                $this->connection->ExecuteNonQuery($queryAmount,array());
         }
    } catch (Exception $ex) {
        throw $ex;
    }

    }

    public function GetAllPet(){
        try {
            $petList = array();

            $query = "SELECT p.petID, p.petName, p.petImage, b.name, p.petSize, p.petVacunationPlan, p.petDetails, p.petVideo, p.petWeight, p.ownerID, p.petAge
            FROM ".$this->petTable." p  JOIN ".$this->ownerTable." o ON o.ownerID = p.ownerID
            JOIN ".$this->breedTable." b ON p.breedID = b.breedID;";

            $this->connection = Connection::GetInstance();

            $resultSet = $this->connection->Execute($query);

            foreach($resultSet as $row){
                $pet = new Pet();

                $pet->setPetID($row["petID"]);
                $pet->setPetName($row["petName"]);
                $pet->setPetImage($row["petImage"]);
                $pet->setBreedID($row["name"]);
                $pet->setPetSize($row["petSize"]);
                $pet->setPetVaccinationPlan($row["petVacunationPlan"]);
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
            $petList = array();
            $petList = $this->searchPets($email);
            var_dump($petList);
                if($petList){
                    $petListFilter =array();
                    foreach($petList as $pet){
                        if(strcmp($pet->getPetSize(),$size) ==0){
                            array_push($petListFilter,$petSize);
                        }
                }
                return $petListFilter;
            }else{
                //echo "<h1>El owner no tiene pets</h1>";
                return array();
            }
            //query = "SELECT * FROM ".$this->petTable. "p WHERE p.petSize = $size AND p.ownerID = "
        }
        
        public function selectPetByID($petID){
            
        }
    }
?>