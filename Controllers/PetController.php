<?php
namespace Controllers;

use Models\Pet as Pet;
//use DAO\PetDAO as PetDAO;
//use DAO\OwnerDAO as OwnerDAO;

use DAODB\PetDAO as PetDAO;
use DAODB\OwnerDAO as OwnerDAO;
use Helper\SessionHelper as SessionHelper;
use Models\Owner as Owner;
class PetController{

    private $OwnerDAO;
    private $PetDAO;

    public function __construct(){
        $this->PetDAO = new PetDAO();
        $this->OwnerDAO = new OwnerDAO();       
    }

    //Views
    public function goLanding(){
        $userRole=SessionHelper::InfoSession([1,2]);
    }
    public function goNewPet(){
        $breedList= $this->PetDAO->getBreeds();
        $userRole=SessionHelper::InfoSession([2]);
        require_once(VIEWS_PATH . "pet-add.php");
    }
    public function showPetsList($petList=null,$breedList=null){
        if($petList === null && $breedList === null){
            if(SessionHelper::getCurrentUser()){
                SessionHelper::redirectTo403();
            }
        }
        $userRole=SessionHelper::InfoSession([1,2]);

        require_once(VIEWS_PATH . "showPet.php");
    }
    
    public function newPet($petName, $breedID, $petSize,$petDetails, $petWeight, $petAge,$petImage,$petVaccinationPlan,$petVideo) {
        if (SessionHelper::getCurrentUser()) {
            $owner = new Owner();
            $pet = new Pet();

            $pet->setPetName($petName);
            $pet->setBreedID($breedID);
            $pet->setPetSize($petSize);
            $pet->setPetWeight($petWeight);
            $pet->setPetDetails($petDetails);
            $pet->setPetAge($petAge);
            $owner = $this->OwnerDAO->searchBasicInfoOwnerByID(SessionHelper::getCurrentOwnerID());
            $pet->setOwnerID($owner);
    
            $uploadResult = $this->uploadFile($pet);
            //var_dump($uploadResult);
    
            if ($uploadResult['success']) {
                $pet->setPetImage($uploadResult['petImage']);
                $pet->setPetVaccinationPlan($uploadResult['petVaccinationPlan']);
                $pet->setPetVideo($uploadResult['petVideo']);
                
                $this->OwnerDAO->incrementPetAmount(SessionHelper::getCurrentOwnerID());
                $result = $this->PetDAO->AddPet($pet);
    
                if ($result) {
                    echo "<div class='alert alert-success'>$petName was created successfully!</div>";
                    $this->goLanding();
                } else {
                    echo "<div class='alert alert-danger'>Ups! Error registering the pet!</div>";
                    $this->goLanding();
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
    //Muestra la lista de pets
    public function showPets(){
        if(SessionHelper::getCurrentRole()===1){
            //Listado para admin
            $petList = $this->PetDAO->GetAllPet();
        }else{
            $petList = $this->PetDAO->searchPetList();
        }
        $breedList= $this->PetDAO->getBreeds();
        if($petList){
            $this->showPetsList($petList,$breedList);
        }else{
            echo '<div class="alert alert-danger">You dont have any pet´s  right now!</div>';
            $this->goLanding();
        }
    }
    //Controla de subir los archivos (Se llama en newPet)
        
    private function uploadFile(Pet $pet, $existingFiles = []) {
        $uploadDir = PETS_PATH . "{$pet->getOwnerID()->getOwnerID()}-{$pet->getBreedID()}-{$pet->getPetName()}/";
    
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        //Inicializo los resultados (petImage, petVaccinationPlan y petVideo son las rutas de las imagens y videos)
        $result = [
            'success' => true,
            'petImage' => '',
            'petVaccinationPlan' => '',
            'petVideo' => '',
            'message' => ''
        ];
    
        // Manejo de archivos
        $handleFileUpload = function($fileKey, $existingFilePath) use ($pet, $uploadDir, &$result) {
            if (isset($_FILES[$fileKey]) && $_FILES[$fileKey]['error'] === UPLOAD_ERR_OK) {
                // Verifico si existe la ruta
                if ($existingFilePath) {
                    unlink($existingFilePath); // Elimino el archivo si ya existen (Update)
                }
    
                // Manejar carga según tipo de archivo
                $fileExtension = strtolower(pathinfo($_FILES[$fileKey]['name'], PATHINFO_EXTENSION));
                $newFileName = "{$pet->getPetName()}-" . ucfirst($fileKey) . "." . $fileExtension;
    
                // Validaciones específicas (Formato y seteo de errores si es necesario)
                if ($fileKey === 'petVaccinationPlan') {
                    $allowedFormats = ['jpg', 'jpeg', 'png', 'pdf'];
                    if (!in_array($fileExtension, $allowedFormats)) {
                        $result['success'] = false;
                        $result['message'] .= "Vaccination plan must be an image (jpg, jpeg, png or pdf). ";
                        return;
                    }
                } elseif ($fileKey === 'petVideo') {
                    $fileType = mime_content_type($_FILES[$fileKey]['tmp_name']);
                    if ($fileType !== 'video/mp4') {
                        $result['success'] = false;
                        $result['message'] .= "Invalid video format. Only MP4 files are allowed. ";
                        return;
                    }
                }
    
                // Intentar mover el archivo subido
                if (move_uploaded_file($_FILES[$fileKey]['tmp_name'], $uploadDir . $newFileName)) {
                    $result[$fileKey] = $uploadDir . $newFileName; // Guardo la ruta
                } else {
                    $result['success'] = false;
                    $result['message'] .= "Error uploading {$fileKey}. ";
                }
            } else {
                // Si no se subio y existe el file, no se actualiza la ruta.
                $result[$fileKey] = $existingFilePath;
            }
        };
    
        // Manejo los archivos en base a la respuesta con la ruta.
        $handleFileUpload('petImage', $existingFiles['petImage'] ?? null);
        $handleFileUpload('petVaccinationPlan', $existingFiles['petVaccinationPlan'] ?? null);
        $handleFileUpload('petVideo', $existingFiles['petVideo'] ?? null);
    
        return $result;
    }
    
    public function updatePet($petID, $breedID, $petSize, $petDetails, $petWeight, $petAge, $petImage, $petVaccinationPlan, $petVideo) {
        // Traigo el pet por si no se actualizo alguna data.
        $currentPetData = $this->PetDAO->getPetByID($petID);

        
        $petFolder = PETS_PATH . $petID . "-" . $currentPetData->getPetName() . "/";
    
        // Si no existe,la creo
        if (!is_dir($petFolder)) {
            mkdir($petFolder, 0777, true);
        }
    
        // Imagen
        if (!empty($petImage['name'])) {
            // Si se actualizo la imagen, lo subimos al directorio
            $imagePath = $petFolder . basename($petImage['name']);
            move_uploaded_file($petImage['tmp_name'], $imagePath);
        } else {
            // Si no se actualizo, dejamos el mismo
            $imagePath = $currentPetData->getPetImage(); 
        }
    
        // Plan de vacunacion
        if (!empty($petVaccinationPlan['name'])) {
            $vaccinationPath = $petFolder . basename($petVaccinationPlan['name']);
            move_uploaded_file($petVaccinationPlan['tmp_name'], $vaccinationPath);
        } else {
            $vaccinationPath = $currentPetData->getPetVaccinationPlan();
        }
    
        // Video
        if (!empty($petVideo['name'])) {
            $videoPath = $petFolder . basename($petVideo['name']);
            move_uploaded_file($petVideo['tmp_name'], $videoPath);
        } else {
            $videoPath = $currentPetData->getPetVideo();
        }
    
        $this->PetDAO->updatePet($petID, $breedID, $petSize, $petDetails, $petWeight, $petAge, $imagePath, $vaccinationPath, $videoPath);

        echo '<div class="alert alert-success">The pet was updated!</div>';
        $this->showPets();
    } 
}?>