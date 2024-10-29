<?php
namespace Controllers;
use Helper\SessionHelper as SessionHelper;
use \Exception as Exception;

use DAODB\IncidentTypeDAO as IncidentTypeDAO;
use Models\IncidentType as IncidentType;
class IncidentTypeController{
    private $IncidentTypeDAO;

    public function __construct() {
        $this->IncidentTypeDAO = new IncidentTypeDAO();
    }
    public function changeStatusIncidentType($id=null,$newStatus=null){
        if($id===null && $newStatus===null){
            if(SessionHelper::getCurrentUser()){
                SessionHelper::redirectTo403();
            } 
        }else{
            try{
                $result = $this->IncidentTypeDAO->changeStatusIncidentType($id,$newStatus);
                if($result){
                    echo json_encode(['success' => true, 'message' => '¡Se cambio el estado correctamente!']);
                    return;
                }else{
                    echo json_encode(['success' => false, 'message' => '¡No se pudo cambiar el estado del tipo de incidencia!']);
                    return;
                }
            } catch (Exception $ex) {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Error al cambiar de estado del typo de incidencia: ' . $ex->getMessage()]);
            } 
        }
    }
    public function newStatusIncidentType($name=null,$description=null,$is_active=null){
        if($name === null && $description===null && $is_active===null){
            if(SessionHelper::getCurrentUser()){
                SessionHelper::redirectTo403();
            } 
        }else{
            try{
                $newName = $this->IncidentTypeDAO->validateUniqueNameByName($name);
                if($newName){
                    echo json_encode(['success' => false, 'message' => '¡El nombre ya existe!']);
                    return;
                }
                $newType = new IncidentType();
                $newType->setName($name);
                $newType->setDescription($description);
                $newType->setIsActive($is_active);

                $result = $this->IncidentTypeDAO->newStatusIncidentType($newType);
                if($result){
                    echo json_encode(['success' => true, 'message' => '¡Se creo el estado correctamente!']);
                    return;
                }else{
                    echo json_encode(['success' => false, 'message' => '¡No se pudo crear el estado del tipo de incidencia!']);
                    return;
                }
            } catch (Exception $ex) {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Error al crear el estado del typo de incidencia: ' . $ex->getMessage()]);
            } 
        }
    }
    public function updateStatusIncidentType($id=null,$name=null,$description=null,$is_active=null){
        if($id=== null && $name === null && $description===null && $is_active===null){
            if(SessionHelper::getCurrentUser()){
                SessionHelper::redirectTo403();
            } 
        }else{
            try{
                $newName = $this->IncidentTypeDAO->validateUniqueName($id,$name);
                if(!$newName){
                    $newType = new IncidentType();
                    $newType->setId($id);
                    $newType->setName($name);
                    $newType->setDescription($description);
                    $newType->setIsActive($is_active);
                    
                    $result = $this->IncidentTypeDAO->updateStatusIncidentType($newType);
                    if($result){
                        echo json_encode(['success' => true, 'message' => '¡Se cambio el estado correctamente!']);
                        return;
                    }else{
                        echo json_encode(['success' => false, 'message' => '¡No se pudo cambiar el estado del tipo de incidencia!']);
                        return;
                    }
                }else{
                    echo json_encode(['success' => false, 'message' => '¡El nombre ya existe!']);
                    return;
                }
            } catch (Exception $ex) {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Error al cambiar de estado del typo de incidencia: ' . $ex->getMessage()]);
            } 
        }
    }

}
?>