<?php
namespace Controllers;

use Models\Pet as Pet;

//          Json
//use DAO\PetDAO as PetDAO;
//use DAO\OwnerDAO as OwnerDAO;

//          DB
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
    
    public function newPet($petName, $breedID, $petSize,$petDetails, $petWeight, $petAge,$petImage,$petVaccinationPlan,$petVideo) {
        SessionHelper::validateUserRole([2]);
        if (SessionHelper::getCurrentUser()) {
            
            $pet = new Pet();

            $pet->setPetName($petName);
            $pet->setBreedID($breedID);
            $pet->setPetSize($petSize);
            $pet->setPetWeight($petWeight);
            $pet->setPetDetails($petDetails);
            $pet->setPetAge($petAge);
            $pet->setOwnerID(SessionHelper::getCurrentOwnerID());
    
            $uploadResult = $this->uploadFile($pet);
    
            if ($uploadResult['success']) {
                $pet->setPetImage($uploadResult['petImage']);
                $pet->setPetVaccinationPlan($uploadResult['vaccinationPlan']);
                $pet->setPetVideo($uploadResult['video']);
                
                $this->OwnerDAO->incrementPetAmount(SessionHelper::getCurrentOwnerID());
                $result = $this->PetDAO->AddPet($pet);
    
                if ($result) {
                    echo "<div class='alert alert-success'>$petName was created successfully!</div>";
                    $this->goLandingOwner();
                } else {
                    echo "<div class='alert alert-danger'>Ups! Error registering the pet!</div>";
                    $this->goLandingOwner();
                }
            } else {
                // Manejar errores de carga de archivos
                echo "<div class='alert alert-danger'>Error uploading files: {$uploadResult['message']}</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>Ups! You are not registered</div>";
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
    
    private function uploadFile(Pet $pet) {
        $uploadDir = UPLOADS_PATH . "{$pet->getOwnerID()}-{$pet->getBreedID()}-{$pet->getPetName()}/";
        
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
    
        $result = [
            'success' => true,
            'petImage' => '',
            'vaccinationPlan' => '',
            'video' => '',
            'message' => ''
        ];
    
        if (isset($_FILES['petImage']) && $_FILES['petImage']['error'] === UPLOAD_ERR_OK) {
            $imageName = "{$pet->getPetID()}-Image." . pathinfo($_FILES['petImage']['name'], PATHINFO_EXTENSION);
            if (move_uploaded_file($_FILES['petImage']['tmp_name'], $uploadDir . $imageName)) {
                $result['petImage'] = $uploadDir . $imageName;
            } else {
                $result['success'] = false;
                $result['message'] .= "Error uploading pet image. ";
            }
        }
    
        if (isset($_FILES['petVaccinationPlan']) && $_FILES['petVaccinationPlan']['error'] === UPLOAD_ERR_OK) {
            $allowedFormats = ['jpg', 'jpeg', 'png'];
            $vacPlanExtension = strtolower(pathinfo($_FILES['petVaccinationPlan']['name'], PATHINFO_EXTENSION));
            
            if (in_array($vacPlanExtension, $allowedFormats)) {
                $vacPlanName = "{$pet->getPetID()}-VaccinationPlan." . $vacPlanExtension;
                if (move_uploaded_file($_FILES['petVaccinationPlan']['tmp_name'], $uploadDir . $vacPlanName)) {
                    $result['vaccinationPlan'] = $uploadDir . $vacPlanName;
                } else {
                    $result['success'] = false;
                    $result['message'] .= "Error uploading vaccination plan. ";
                }
            } else {
                $result['success'] = false;
                $result['message'] .= "Vaccination plan must be an image (jpg, jpeg, png). ";
            }
        }
    
        
        if (isset($_FILES['petVideo']) && $_FILES['petVideo']['error'] === UPLOAD_ERR_OK) {
            // Verificar que el archivo es un MP4
            $fileType = mime_content_type($_FILES['petVideo']['tmp_name']);
            if ($fileType === 'video/mp4') {
                $videoName = "{$pet->getPetID()}-Video." . pathinfo($_FILES['petVideo']['name'], PATHINFO_EXTENSION);
                if (move_uploaded_file($_FILES['petVideo']['tmp_name'], $uploadDir . $videoName)) {
                    $result['video'] = $uploadDir . $videoName;
                    
                } else {
                    $result['success'] = false;
                    $result['message'] .= "Error uploading pet video. ";
                }
            } else {
                $result['success'] = false;
                
                $result['message'] .= "Invalid video format. Only MP4 files are allowed. ";
            }
        }
    
        return $result;
    }
    
    
    
    
    
}?>