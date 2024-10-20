<?php
namespace Controllers;
use Helper\SessionHelper as SessionHelper;
use Helper\FileUploader as FileUploader;
use \Exception as Exception;
use DAODB\GroupInfoDAO as GroupInfoDAO;
use DAODB\GroupDAO as GroupDAO;
class GroupInfoController{
    private $GroupInfoDAO;
    private $fileUploader;
    private $GroupDAO;

    private $groupInfoTable = 'group_info';
    public function __construct(){
        $this->GroupInfoDAO = new GroupInfoDAO();
        $this->fileUploader = new FileUploader(GROUPS_PATH);
        $this->GroupDAO= new GroupDAO();
    }

    public function updateGroupInfoDescription($groupID,$newDescription){
        if($groupID && $newDescription){
            try{
                $groupInfoID = $this->GroupDAO->GetGroupInfoIDByGroupID($groupID);
                $status = $this->GroupInfoDAO->updateGroupInfoDescription($groupInfoID,$newDescription);
                if($status){
                    echo json_encode(
                        ['success' => true, 
                        'message' => 'La descripción fue cambiada exitosamente.',
                        'newDescription'=>$newDescription
                    ]);
                    return;
                } else {
                    echo json_encode(['success' => false, 'message' => 'Error al cambiar la descripción de grupo.']);
                    return; 
                }
            }catch(Exception $ex){
                throw $ex;
            }
        }else{
            echo json_encode(['success' => false, 'message' => 'Error al modificar la descripción de grupo.']);
            return; 
        }
    }
    public function updateGroupInfoRules($groupID,$newRules){
        if($groupID && $newRules){
            try{
                $groupInfoID = $this->GroupDAO->GetGroupInfoIDByGroupID($groupID);
                $status = $this->GroupInfoDAO->updateGroupInfoRules($groupInfoID,$newRules);
                if($status){
                    echo json_encode(
                        ['success' => true, 
                        'message' => 'Las reglas fueron cambiadas exitosamente.',
                        'newRules' => $newRules
                    ]);
                    return;
                } else {
                    echo json_encode(['success' => false, 'message' => 'Error al cambiar las reglas del grupo.']);
                    return; 
                }
            }catch(Exception $ex){
                throw $ex;
            }
        }else{
            echo json_encode(['success' => false, 'message' => 'Error al modificar las reglas del grupo.']);
            return; 
        }
    }
    public function updateGroupInfoDates($groupID,$newDates){
        if($groupID && $newDates){
            try{
                $groupInfoID = $this->GroupDAO->GetGroupInfoIDByGroupID($groupID);

                $dates = json_decode($newDates, true);
                $startDate = $dates['startDate'];
                $endDate = $dates['endDate'];
                $status = $this->GroupInfoDAO->updateGroupInfoDates($groupInfoID,$startDate,$endDate);
                if($status){
                    echo json_encode(
                        ['success' => true, 
                        'message' => 'Las fechas fueron cambiadas exitosamente.',
                        'startDate' =>$startDate,
                        'endDate' =>$endDate
                    ]);
                    return;
                } else {
                    echo json_encode(['success' => false, 'message' => 'Error al cambiar las fechas del grupo.']);
                    return; 
                }
            }catch(Exception $ex){
                throw $ex;
            }
        }else{
            echo json_encode(['success' => false, 'message' => 'Error al modificar las fechas del grupo.']);
            return; 
        }
    }
    public function updateGroupInfoImage($groupID, $files) {
        //error_log("Iniciando la actualización de la imagen del grupo. GroupID: " . $groupID);
        
        if ($groupID) {
            $subFolder = $groupID;
            $groupInfoID = $this->GroupDAO->GetGroupInfoIDByGroupID($groupID);
           // error_log("ID de información del grupo obtenido: " . $groupInfoID);
    
            $groupImageRoute = null;
            //error_log(json_encode($_FILES));
    
            if (isset($_FILES['newValue']) && $_FILES['newValue']['error'] === UPLOAD_ERR_OK) {
                //error_log("Archivo recibido: " . json_encode($_FILES['newValue']));
    
                $filename = $_FILES['newValue']['name']; 
                $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION)); 
                //error_log('La extensión desde la controller es: ' . $extension);
                
                $formatName = function() use ($groupID, $extension) {
                    return "{$groupID}-image." . $extension; 
                }; 
    
                $groupImageRoute = $this->fileUploader->uploadFiles($_FILES['newValue'], $subFolder, $formatName);
                //error_log("Ruta de la imagen subida: " . json_encode($groupImageRoute));
            } else {
                $groupImageRoute[0] = GROUPS_PATH . "Default/DefaultGroupImage.jpg";
                //error_log("No se recibió ningún archivo o error al cargar el archivo, se usará la imagen por defecto: " . $groupImageRoute[0]);
            }
    
            $result = $this->GroupInfoDAO->updateGroupInfoImages($groupInfoID, $groupImageRoute[0]);
            if ($result) {
               // error_log("Imagen actualizada exitosamente en la base de datos para el grupo ID: " . $groupID);
               $imageData = base64_encode(file_get_contents($groupImageRoute[0]));
                echo json_encode(
                    ['success' => true, 
                    'message' => 'La imagen fue cambiada exitosamente.',
                    'newImage' =>  'data:image/jpeg;base64,' . $imageData
                ]);
                return;
            } else {
                //error_log("Error al actualizar la imagen en la base de datos para el grupo ID: " . $groupID);
                echo json_encode(['success' => false, 'message' => 'Error al subir la imagen al grupo.']);
                return;
            }
        } else {
           //error_log("ID del grupo no válido.");
            echo json_encode(['success' => false, 'message' => 'Error subir la nueva imagen al grupo.']);
            return;
        }
    }

}
?>