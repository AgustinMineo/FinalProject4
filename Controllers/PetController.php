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
    
    public function newPet($petName,$petImage,$breedID,$petSize,$petVaccinationPlan,$petDetails,$petVideo,$petWeight,$petAge){
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
        $petList = $this->PetDAO->searchPetList();
        require_once(VIEWS_PATH . "showPet.php");
    }
}?>