<?php
namespace Controllers;

use Models\Pet as Pet;
use DAO\PetDAO as PetDAO;
Use Controllers\UserController as UserController;
session_start();
class PetController{
    private $petDAO;
    public function newPet(/*$petId,*/$petName,$petImage,$breed,$petSize,$vaccinationPlan,$petDetails,$petVideo,$petWeight){
        if(isset($_SESSION["loggedUser"])){
            
            $this->petDAO = new PetDAO();
            $pet = new Pet();

            //$pet->setPetId($petId);
            $pet->setPetName($petName);
            $pet->setPetImage($petImage);
            $pet->setBreed($breed);
            $pet->setPetSize($petSize);
            $pet->setVaccinationPlan($vaccinationPlan);
            $pet->setPetDetails($petDetails);
            $pet->setPetVideo($petVideo);
            $pet->setPetWeight($petWeight);
            $pet->setPetOwner($_SESSION["loggedUser"]->getEmail());
            $this->petDAO->AddPet($pet);
        }else{
            echo"Usuario no logeado";
            }
    }

    public function searchPetList(){
        $petListSearch= array();
        $this->petDAO = new PetDAO();
        if(isset($_SESSION["loggedUser"])){
        $petListSearch = $this->petDAO->searchPets($_SESSION["loggedUser"]->getEmail()); // Buscamos la lista de pets que tenga el cliente por correo. (Cambiar a objeto)
        return $petListSearch; 
    }
    }

    public function showPets(){
            $petList = $this->searchPetList();
            require_once(VIEWS_PATH . "showPet.php");
    }
}

?>