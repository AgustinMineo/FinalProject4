<?php
namespace Controllers;

use Models\Pet as Pet;
use DAO\PetDAO as PetDAO;
//use DAODB\PetDAO as PetDAO;
use DAO\OwnerDAO as OwnerDAO;
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
            $this->PetDAO->AddPet($pet);
            $this->goLandingOwner();
        }else{
            echo"Usuario no logeado";
            }
    }
    public function searchPetList(){
        $petListSearch= array();
        if(SessionHelper::getCurrentUser()){
            // Buscamos la lista de pets que tenga el cliente por correo. (Cambiar a objeto)
            $petListSearch = $this->PetDAO->searchPets(SessionHelper::getCurrentOwnerID()); 
            return $petListSearch; 
        }
    }
    public function showPets(){
        $petList = $this->PetDAO->searchPetList();
        require_once(VIEWS_PATH . "showPet.php");
}

}
    
?>