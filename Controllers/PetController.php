<?php
namespace Controllers;

use Models\Pet as Pet;
use DAO\PetDAO as PetDAO;
//use DAODB\PetDAO as PetDAO;

class PetController{
    private $petDAO;

    public function goLandingOwner(){
        require_once(VIEWS_PATH."ownerNav.php");
    }
    public function __construct(){
        $this->PetDAO = new PetDAO();
    }
    
    public function newPet($petName,$petImage,$breedID,$petSize,$petVaccinationPlan,$petDetails,$petVideo,$petWeight,$petAge){
        if(isset($_SESSION["loggedUser"])){
            
            $this->petDAO = new PetDAO();
            $pet = new Pet();

            $pet->setPetName($petName);
            $pet->setPetImage($petImage);
            $pet->setBreedID($breedID);
            $pet->setPetSize($petSize);
            $pet->setPetVaccinationPlan($petVaccinationPlan);
            $pet->setPetDetails($petDetails);
            $pet->setPetVideo($petVideo);
            $pet->setPetWeight($petWeight);
            $pet->setOwnerID($_SESSION["loggedUser"]->getOwnerID());
            $pet->setPetAge($petAge);
            $this->petDAO->AddPet($pet);
            $this->goLandingOwner();
        }else{
            echo"Usuario no logeado";
            }
    }

    public function showPets(){
        $petList = $this->PetDAO->searchPetList();
        require_once(VIEWS_PATH . "showPet.php");
}

}
    
?>