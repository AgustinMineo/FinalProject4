<?php
namespace Controllers;
use Helper\SessionHelper as SessionHelper;
use \Exception as Exception;

use DAODB\IncidentStatusDAO as IncidentStatusDAO;
use Models\IncidentStatus as IncidentStatus;
class IncidentStatusController {

    public function __construct() {
        $this->IncidentStatusDAO = new IncidentStatusDAO();
    }

    public function changeStatusIncident($incidentID=null,$incidentStatus=null){
        if($incidentID === null && $incidentStatus == null){
            if(SessionHelper::getCurrentUser()){
                SessionHelper::redirectTo403();
            }    
        }else{
            try{
                $result = $this->IncidentStatusDAO->changeStatusIncident($incidentID,$incidentStatus);
                if($result){
                    echo json_encode(['success' => true, 'message' => '¡Se cambio el estado correctamente!']);
                    return;
                }else{
                    echo json_encode(['success' => false, 'message' => '¡No se pudo cambiar el estado!']);
                    return;
                }
            } catch (Exception $ex) {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Error al cambiar de estado la incidencia: ' . $ex->getMessage()]);
            }   
        }
    }
    public function updateIncidentStatus($idStatus=null, $name=null,$description=null,$is_active=null){
        if($idStatus===null && $name===null && $description===null && $is_active ===null){
            if(SessionHelper::getCurrentUser()){
                SessionHelper::redirectTo403();
            }
        }else{
            try{
                $newName = $this->IncidentStatusDAO->validateUniqueName($idStatus,$name);
                if($newName){
                    echo json_encode(['success' => false, 'message' => '¡El nombre ya existe!']);
                    return;
                }
                $updateStatus = new IncidentStatus();
                $updateStatus->setId($idStatus);
                $updateStatus->setName($name);
                $updateStatus->setDescription($description);
                $updateStatus->setIsActive($is_active);

                $result = $this->IncidentStatusDAO->updateIncidentStatus($updateStatus);

                if($result){
                    echo json_encode(['success' => true, 'message' => '¡Se actualizo el estado correctamente!']);
                    return;
                }else{
                    echo json_encode(['success' => false, 'message' => '¡No se pudo actualizar el estado!']);
                    return;
                }
            } catch (Exception $ex) {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Error al actualizar el estado: ' . $ex->getMessage()]);
            }  
        }
    }
    public function newIncidentStatus($idStatus=null, $name=null,$description=null,$is_active=null){
        if($idStatus===null && $name===null && $description===null && $is_active ===null){
            if(SessionHelper::getCurrentUser()){
                SessionHelper::redirectTo403();
            }
        }else{
            try{
                $newName = $this->IncidentStatusDAO->validateUniqueNameByName($name);
                if($newName){
                    echo json_encode(['success' => false, 'message' => '¡El nombre ya existe!']);
                    return;
                }
                $updateStatus = new IncidentStatus();
                $updateStatus->setId($idStatus);
                $updateStatus->setName($name);
                $updateStatus->setDescription($description);
                $updateStatus->setIsActive($is_active);

                $result = $this->IncidentStatusDAO->newIncidentStatus($updateStatus);

                if($result){
                    echo json_encode(['success' => true, 'message' => '¡Se creo el estado correctamente!']);
                    return;
                }else{
                    echo json_encode(['success' => false, 'message' => '¡No se pudo creo el estado!']);
                    return;
                }
            } catch (Exception $ex) {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Error al creo el estado: ' . $ex->getMessage()]);
            }  
        }
    }
}

?>