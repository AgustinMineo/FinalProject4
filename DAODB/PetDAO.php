<?php 
namespace DAODB;

use \Exception as Exception;
use DAODB\Connect as Connect;
use DAO\IPetDAO as IPetDAO;
use Models\Pet as Pet;
use Helper\SessionHelper as SessionHelper;

class PetDAO implements IPetDAO{
    private $connection;
    private $petTable = 'Pet';
    private $ownerTable = 'owner';
    private $breedTable = 'breed';
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
            $parameters["ownerID"] = SessionHelper::getCurrentOwnerID();
            $parameters["petAge"] = $pet->getPetAge();
            $this->connection = Connection::GetInstance();
            //Funcion para hacer el Update de PETAMOUNT en la Tabla de Owners. 
            if($this->connection->ExecuteNonQuery($query, $parameters)){
                $queryAmount = "update " .$this->ownerTable." o set petAmount = ".SessionHelper::getCurrentPetAmount()." + 1 WHERE o.ownerID = ".SessionHelper::getCurrentOwnerID();
                $this->connection->ExecuteNonQuery($queryAmount,array());
            }
        } catch (Exception $ex) { throw $ex; } 
    }
    public function GetAllPet(){
        try {

            $query = "SELECT p.petID, p.petName, p.petImage, b.name, p.petSize, p.petVaccinationPlan, p.petDetails, p.petVideo, p.petWeight, p.ownerID, p.petAge
            FROM ".$this->petTable." p  JOIN ".$this->ownerTable." o ON o.ownerID = p.ownerID
            JOIN ".$this->breedTable." b ON p.breedID = b.breedID;";
            $this->connection = Connection::GetInstance();
            $resultSet = $this->connection->Execute($query);
            if($resultSet){
                $petList = array();
                foreach($resultSet as $row){
                    $pet = new Pet();
                    $pet->setPetID($row["petID"]);
                    $pet->setPetName($row["petName"]);
                    $pet->setPetImage($row["petImage"]);
                    $pet->setBreedID($row["name"]);
                    $pet->setPetSize($row["petSize"]);
                    $pet->setPetVaccinationPlan($row["petVaccinationPlan"]);
                    $pet->setPetDetails($row["petDetails"]);
                    $pet->setPetVideo($row["petVideo"]);
                    $pet->setPetWeight($row["petWeight"]);
                    $pet->setOwnerID($row["ownerID"]);
                    $pet->setPetAge($row["petAge"]);
                    array_push($petList, $pet);
                }
            return $petList; }
            else { return NULL; }
        } catch (Exception $ex) { throw $ex; }
    }
    public function searchPets($ownerID){
        $query = "SELECT p.petName, p.petSize, p.petDetails, p.petImage, p.petVaccinationPlan, p.petVideo, p.petWeight, p.petAge, p.petID, b.name
                  FROM ".$this->petTable." p JOIN ".$this->ownerTable." o ON o.ownerID = p.ownerID 
                  JOIN ".$this->breedTable." b ON p.breedID = b.breedID
                  WHERE p.ownerID = $ownerID;";
        $this->connection = Connection::GetInstance();
        $resultSet = $this->connection->Execute($query);
        if($resultSet){
            $petList = array();
            foreach($resultSet as $row){
                $pet = new Pet();
                $pet->setPetID($row['petID']);
                $pet->setPetName($row['petName']);
                $pet->setBreedID($row['name']);
                $pet->setPetSize($row['petSize']);
                $pet->setPetWeight($row['petWeight']);
                $pet->setPetAge($row['petAge']);
                $pet->setPetImage($row['petImage']);
                $pet->setPetVaccinationPlan($row['petVaccinationPlan']);
                $pet->setPetDetails($row['petDetails']);
                $pet->setPetVideo($row['petVideo']);
                array_push($petList, $pet);
            }
            return $petList;
        } else {return NULL;}
    }
    public function searchPetsBySize($ownerID,$size){
        $query = "SELECT p.petName, p.petSize, p.petDetails, p.petImage, p.petVaccinationPlan, p.petVideo, p.petWeight, p.petAge, p.petID, b.name
        FROM ".$this->petTable." p JOIN ".$this->ownerTable." o ON o.ownerID = p.ownerID 
        JOIN ".$this->breedTable." b ON p.breedID = b.breedID
        WHERE p.ownerID = $ownerID AND p.petSize = '$size';";

        $this->connection = Connection::GetInstance();
        $resultSet = $this->connection->Execute($query);
        if($resultSet){
            $petList = array();
            foreach($resultSet as $row){
                $pet = new Pet();
                $pet->setPetID($row['petID']);
                $pet->setPetName($row['petName']);
                $pet->setBreedID($row['name']);
                $pet->setPetSize($row['petSize']);
                $pet->setPetWeight($row['petWeight']);
                $pet->setPetAge($row['petAge']);
                $pet->setPetImage($row['petImage']);
                $pet->setPetVaccinationPlan($row['petVaccinationPlan']);
                $pet->setPetDetails($row['petDetails']);
                $pet->setPetVideo($row['petVideo']);
                array_push($petList, $pet);
            }
            return $petList;
        }
        else { return NULL; }
    }
    public function getSizePet($petID){
        try{
            $query = "SELECT petSize FROM ".$this->petTable." WHERE petID = $petID;";
            $this->connection = Connection::GetInstance();
            $resultSet = $this->connection->Execute($query);
            if($resultSet){
                foreach($resultSet as $row){
                    $size = $row['petSize'];
                }
                return $size;
            } else { return NULL; }
        } catch(Exception $ex) { throw $ex; }
    }
    public function searchPetList(){
        if(isset($_SESSION["loggedUser"])){
        $ownerID = $_SESSION["loggedUser"]->getOwnerId();
        $petListSearch= array();
        $petListSearch = $this->searchPets($_SESSION["loggedUser"]->getOwnerId()); // Buscamos la lista de pets que tenga el cliente por correo. (Cambiar a objeto)
        return $petListSearch; 
        }
    }
}?>