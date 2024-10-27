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
}

?>