<?php
namespace Controllers;

use Models\Pet as Pet;
//use DAO\PetDAO as PetDAO;
//use DAO\OwnerDAO as OwnerDAO;

use DAODB\PetDAO as PetDAO;
use DAODB\OwnerDAO as OwnerDAO;
use Helper\SessionHelper as SessionHelper;

class PetController{

    private $OwnerDAO;
    private $PetDAO;

    public function goLandingOwner(){
        require_once(VIEWS_PATH."ownerNav.php");
    }
    public function __construct(){
        $this->PetDAO = new PetDAO();
        $this->OwnerDAO = new OwnerDAO();       
    }

    public function goNewPet(){
        SessionHelper::validateUserRole([2]);
        require_once(VIEWS_PATH."ownerNav.php");
        require_once(VIEWS_PATH . "pet-add.php");
    }
    
    public function newPet($petName,$petImage,$breedID,$petSize,$petVaccinationPlan,$petDetails,$petVideo,$petWeight,$petAge){
        SessionHelper::validateUserRole([2]);
        if(SessionHelper::getCurrentUser()){
            
            //$this->petDAO = new PetDAO();
            $pet = new Pet();
                
            $pet->setPetName($petName);
            $pet->setPetImage($petImage);
            $pet->setBreedID($breedID);
            $pet->setPetSize($petSize);
            $pet->setPetVaccinationPlan($petVaccinationPlan);
            $pet->setPetDetails($petDetails);
            $pet->setPetVideo($petVideo);
            $pet->setPetWeight($petWeight);
            $pet->setOwnerID(SessionHelper::getCurrentOwnerID());
            $pet->setPetAge($petAge);
            $this->OwnerDAO->incrementPetAmount(SessionHelper::getCurrentOwnerID());
            $result=$this->PetDAO->AddPet($pet);
            if($result){
                echo "<div class='alert alert-success'>$petName was create successfully!</div>";
                $this->goLandingOwner();
            }else{
                echo "<div class='alert alert-danger'>Ups!Error registering the pet!</div>";
                $this->goLandingOwner();
            }
        }else{
            echo "<div class='alert alert-danger'>Ups!You are not register</div>";
            }
    }
    public function searchPetList(){
        SessionHelper::validateUserRole([2]);
        $petListSearch= array();
        if(SessionHelper::getCurrentUser()){
           // Buscamos la lista de pets que tenga el cliente por correo. (Cambiar a objeto)
           // $petListSearch = $this->PetDAO->searchPets($_SESSION["loggedUser"]->getOwnerId());
           // if($petListSearch){
           //     return $petListSearch; 
           // } else { echo "<div class='alert alert-danger'>You have no pets!!</div>";
           //         $this->goIndex();}
           $petListSearch = $this->PetDAO->searchPets(SessionHelper::getCurrentOwnerID()); 
            return $petListSearch; 
        }
    }
    public function showPets(){
        SessionHelper::validateUserRole([2]);
        $petList = $this->PetDAO->searchPetList();
        require_once(VIEWS_PATH . "showPet.php");
    }
     private function UploadFiles(Owner $owner,Pet $pet){ 
            
            $message = "";

            if (isset($_FILES['picture'])){
                if($_FILES['picture']['error']==0){
                    $dir = IMG_PATH;
                    $filename = $owner->getUserName() . $pet->getName() . ".jpg"; // debería ser por Id de pet, no nombre

                    $fileToAdd = $dir . $filename;

                    if(move_uploaded_file($_FILES['picture']['tmp_name'], $fileToAdd)){
                        $message = $message . $_FILES['picture']['name'] . ' was uploaded and saved as '. $filename . '</br>';
                        $pet->setPicture($filename);
                    }else{$message = $message . 'ERROR: Could not move Picture file. ';}
                }else{$message = $message .  'ERROR: Could not upload Picture file. ';}
            }else{$message = $message .  'ERROR: Could not find Picture file. ';}

            if (isset($_FILES['vacPlan'])){
                if($_FILES['vacPlan']['error']==0){
                    $dir = IMG_PATH;
                    $filename = $owner->getUserName() . $pet->getName() . "-VAC.jpg"; // debería ser por Id de pet, no nombre

                    $fileToAdd = $dir . $filename;

                    if(move_uploaded_file($_FILES['vacPlan']['tmp_name'], $fileToAdd)){
                        $message = $message .  $_FILES['vacPlan']['name'] . ' was uploaded and saved as '. $filename . '</br>';
                        $pet->setVacPlan($filename);
                    }else{$message = $message .  'ERROR: Could not move VacPlan file. ';}
                }else{$message = $message .  'ERROR: Could not upload VacPlan file. ';}
            }else{$message = $message .  'ERROR: Could not find VacPlan file. ';}

            if (isset($_FILES['video'])){
                if($_FILES['video']['error']==0){
                    $dir = IMG_PATH;
                    $filename = $owner->getUserName() . $pet->getName() . "-VID.gif"; // debería ser por Id de pet, no nombre

                    $fileToAdd = $dir . $filename;

                    if(move_uploaded_file($_FILES['video']['tmp_name'], $fileToAdd)){
                        $message = $message .  $_FILES['video']['name'] . ' was uploaded and saved as '. $filename . '</br>';
                        $pet->setVideo($filename);
                    }else{$message = $message .  'ERROR: Could not move video file. ';}
                }else{$message = $message .  'ERROR: Could not upload video file. ';}
            }else{$message = $message .  'ERROR: Could not find video file. ';}
            
            return $message;
        }
}?>