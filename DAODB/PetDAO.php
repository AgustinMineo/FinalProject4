<?php 
namespace DAODB;

use \Exception as Exception;
use DAODB\Connect as Connect;
use DAO\IPetDAO as IPetDAO;
use Models\Pet as Pet;
use Helper\SessionHelper as SessionHelper;
use DAODB\OwnerDAO as OwnerDAO;
use Models\Owner;

class PetDAO implements IPetDAO{
    private $connection;
    private $petTable = 'pet';
    private $ownerTable = 'owner';
    private $breedTable = 'breed';
    private $petList = array();
    
    public function __construct() {
        $this->OwnerDAO = new OwnerDAO();
    }

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
            $this->connection->ExecuteNonQuery($query, $parameters);
            return true;
        } catch (Exception $ex) { throw $ex; } 
    }
    public function GetAllPet(){
        try {

            $query = "SELECT p.petID, p.petName, p.petImage, b.name, p.petSize, 
            p.petVaccinationPlan, p.petDetails, p.petVideo, p.petWeight, p.ownerID, p.petAge,
            p.breedID
            FROM ".$this->petTable." p  JOIN ".$this->ownerTable." o ON o.ownerID = p.ownerID
            JOIN ".$this->breedTable." b ON p.breedID = b.breedID;";
            $this->connection = Connection::GetInstance();
            $resultSet = $this->connection->Execute($query);
            if($resultSet){
                $petList = array();
                foreach($resultSet as $row){
                    $owner= new Owner();
                    $owner= $this->OwnerDAO->searchBasicInfoOwnerByID($row["ownerID"]);
                    $pet = new Pet();
                    $pet->setPetID($row["petID"]);
                    $pet->setPetName($row["petName"]);
                    $pet->setPetImage($row["petImage"]);
                   // $pet->setBreedID($row["name"]);
                    $pet->setPetSize($row["petSize"]);
                    $pet->setBreedID($row['breedID']);
                    $pet->setPetVaccinationPlan($row["petVaccinationPlan"]);
                    $pet->setPetDetails($row["petDetails"]);
                    $pet->setPetVideo($row["petVideo"]);
                    $pet->setPetWeight($row["petWeight"]);
                    $pet->setOwnerID($owner);
                    $pet->setPetAge($row["petAge"]);
                    array_push($petList, $pet);
                }
                //var_dump($petList);
            return $petList;
         }
            else { return NULL; }
        } catch (Exception $ex) { throw $ex; }
    }
    public function searchPets($ownerID){
        $query = "SELECT p.petName, p.petSize, p.petDetails, p.petImage, p.petVaccinationPlan, p.petVideo, p.petWeight, p.petAge, p.petID, p.breedID
                FROM ".$this->petTable." p JOIN ".$this->ownerTable." o ON o.ownerID = p.ownerID 
                WHERE p.ownerID = $ownerID;";
        $this->connection = Connection::GetInstance();
        $resultSet = $this->connection->Execute($query);
        $owner= new Owner();
        $owner= $this->OwnerDAO->searchBasicInfoOwnerByID($ownerID);
        if($resultSet){
            $petList = array();
            foreach($resultSet as $row){
                $pet = new Pet();
                $pet->setPetID($row['petID']);
                $pet->setOwnerID($owner);
                $pet->setPetName($row['petName']);
                $pet->setBreedID($row['breedID']);
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
        $query = "SELECT p.petName, p.petSize, p.petDetails, p.petImage, p.petVaccinationPlan, p.petVideo, p.petWeight, p.petAge, p.petID, p.breedID
        FROM ".$this->petTable." p JOIN ".$this->ownerTable." o ON o.ownerID = p.ownerID 
        WHERE p.ownerID = $ownerID AND p.petSize = '$size';";

        $this->connection = Connection::GetInstance();
        $resultSet = $this->connection->Execute($query);
        if($resultSet){
            $petList = array();
            foreach($resultSet as $row){
                $pet = new Pet();
                $pet->setPetID($row['petID']);
                $pet->setPetName($row['petName']);
                $pet->setBreedID($row['breedID']);
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
        if(SessionHelper::getCurrentUser()){
        $ownerID = SessionHelper::getCurrentOwnerID();
        $petListSearch= array();
        $petListSearch = $this->searchPets(SessionHelper::getCurrentOwnerID()); // Buscamos la lista de pets que tenga el cliente por correo. (Cambiar a objeto)
        return $petListSearch; 
        }
    }
    public function searchPet($petSearch){
        try{
            $query = "SELECT petID,petName,breedID,petDetails,petImage,petAge,ownerID,petWeight,petVideo,petVaccinationPlan,petSize 
            FROM ".$this->petTable." WHERE petID = $petSearch;";
            $this->connection = Connection::GetInstance();
            $resultSet = $this->connection->Execute($query);
            $owner = new Owner();
            if($resultSet){
                foreach($resultSet as $row){
                    $owner= $this->OwnerDAO->searchBasicInfoOwnerByID($row['ownerID']);
                    $pet = new Pet();
                    $pet->setPetID($row['petID']);
                    $pet->setPetName($row['petName']);
                    $pet->setBreedID($row['breedID']);
                    $pet->setPetDetails($row['petDetails']);
                    $pet->setPetImage($row['petImage']);
                    $pet->setPetAge($row['petAge']);
                    $pet->setOwnerID($owner);
                    $pet->setPetWeight($row['petWeight']);
                    $pet->setPetVideo($row['petVideo']);
                    $pet->setPetVaccinationPlan($row['petVaccinationPlan']);
                    $pet->setPetSize($row['petSize']);
                }
                
                return $pet;
            } else{ return null;}
        } catch (Exception $ex) {throw $ex;}
    }
    public function getBreeds(){
        try{
            $query = "SELECT * FROM ".$this->breedTable." order by breedID asc";
            $this->connection = Connection::GetInstance();
            $resultSet = $this->connection->Execute($query);
            $breedTypes=array();
            if($resultSet){
                foreach($resultSet as $row){
                    $breedTypes[] =array( 
                        'id'=> $row['breedID'],
                        'name'=> $row['name']
                    );
                }
                return $breedTypes;
            } else { return NULL; }
        } catch(Exception $ex) { throw $ex; }
    }
    public function getPetByID($petID){
        try{
            $query = "SELECT * FROM ".$this->petTable." WHERE petID = $petID;";
            $this->connection = Connection::GetInstance();
            $resultSet = $this->connection->Execute($query);
            if($resultSet){
                foreach($resultSet as $row){
                    $owner= new Owner();
                    $owner= $this->OwnerDAO->searchBasicInfoOwnerByID($row["ownerID"]);
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
                    $pet->setOwnerID($owner);
                    $pet->setPetAge($row["petAge"]);
                }
                return $pet;
            } else { return NULL; }
        } catch(Exception $ex) { throw $ex; }
    }
    public function updatePet($petID,$breedID,$petSize,$petDetails,$petWeight,
        $petAge, $petImage, $petVaccinationPlan, $petVideo) {
        try{
            $query = "UPDATE " . $this->petTable . " 
                    SET petImage = '$petImage', breedID = '$breedID', petSize = '$petSize', 
                        petVaccinationPlan = '$petVaccinationPlan', petDetails = '$petDetails', 
                        petVideo = '$petVideo', petWeight = '$petWeight', petAge = '$petAge' 
                    WHERE petID = '$petID';";

            $this->connection = Connection::GetInstance();
            return $this->connection->Execute($query);
        }catch(Exception $ex) { throw $ex; }
    }
}?>