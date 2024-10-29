<?php
namespace Controllers;
use Helper\SessionHelper as SessionHelper;
use Helper\FileUploader as FileUploader;

use DAODB\IncidentTypeDAO as IncidentTypeDAO;
use DAODB\IncidentStatusDAO as IncidentStatusDAO;
use DAODB\IncidentDAO as IncidentDAO;
use DAODB\IncidentAnswerDAO as IncidentAnswerDAO;
use DAODB\IncidentFileDAO as IncidentFileDAO;
use Models\Incident as Incident;
use Models\IncidentAnswer as IncidentAnswer;
class IncidentController {

    private $IncidentDAO;
    private $IncidentStatusDAO;
    private $IncidentTypeDAO;

    public function __construct() {
        $this->IncidentDAO = new IncidentDAO();
        $this->IncidentTypeDAO = new IncidentTypeDAO();
        $this->IncidentStatusDAO = new IncidentStatusDAO();
        $this->IncidentAnswerDAO = new IncidentAnswerDAO();
        $this->IncidentFileDAO = new IncidentFileDAO();
        $this->fileUploader = new FileUploader(INCIDENT_PATH);
    }
    public function goIncidentView($incidentTypeList=null,$incidentStatusList=null,$incidentsList=null){
        if($incidentTypeList === null && $incidentStatusList == null && $incidentsList === null){
            if(SessionHelper::getCurrentUser()){
                SessionHelper::redirectTo403();
            }    
        }
        $userRole=SessionHelper::InfoSession([1,2,3]);
        $userID = intval(SessionHelper::getCurrentUser()->getUserId());
        require_once(VIEWS_PATH. "incidentView.php");
    }
    public function loadViewIncidents(){
        $incidentTypeList =array();
        $incidentStatusList=array();
        $incidentsList=array();
        $incidentStatusList = $this->IncidentStatusDAO->getAllIncidentStatusActive();
        $incidentTypeList = $this->IncidentTypeDAO->getAllIncidentTypesActives();
        if(SessionHelper::getCurrentRole() === 1){
            $incidentsList = $this->IncidentDAO->getAllIncidents();
        }else{
            $incidentsList=$this->IncidentDAO->getIncidentByUserId(SessionHelper::getCurrentUser()->getUserId());
        }
        $this->goIncidentView($incidentTypeList,$incidentStatusList,$incidentsList);
    }
    public function goIncidentAdministrationView($incidentStatusList=null,$incidentTypeList=null){
        if($incidentTypeList === null && $incidentStatusList == null){
            if(SessionHelper::getCurrentUser()->getRole()!=='1'){
                SessionHelper::redirectTo403();
            }    
        }
        $userRole=SessionHelper::InfoSession([1]);
        $userID = intval(SessionHelper::getCurrentUser()->getUserId());
        require_once(VIEWS_PATH. "IncidentAdministration.php");
    }
    public function loadAdministrationIncidentView(){
        $incidentTypeList =array();
        $incidentStatusList=array();
        $incidentStatusList = $this->IncidentStatusDAO->getAllIncidentStatus();
        $incidentTypeList = $this->IncidentTypeDAO->getAllIncidentTypes();
        $this->goIncidentAdministrationView($incidentStatusList,$incidentTypeList);
    }
    /*
    public function newIncident($userId, $incidentTypeId, $description) {
        try {
            if ($userId === null && $incidentTypeId === null && $description === null) {
                if (SessionHelper::getCurrentUser()) {
                    SessionHelper::redirectTo403();
                }
            }
            $statusId = 1;
            $incident = new Incident();
            $incident->setUserId($userId);
            $incident->setIncidentTypeId($incidentTypeId);
            $incident->setStatusId($statusId);
            $incident->setDescription($description);
            $incident->setIncidentDate(date('Y-m-d H:i:s'));

            $result = $this->IncidentDAO->newIncident($incident);
            if($result){
                echo json_encode(['success' => true, 'message' => '¡Incidente creado exitosamente!']);
            }else{
                echo json_encode(['success' => false, 'message' => '¡La incidencia no pudo ser creada!']);
            }
        } catch (Exception $ex) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error al crear el incidente: ' . $ex->getMessage()]);
        }
    }*/
    public function newIncident($userId, $incidentTypeId, $description) {
        try {
            if ($userId === null || $incidentTypeId === null || $description === null) {
                if (SessionHelper::getCurrentUser()) {
                    SessionHelper::redirectTo403();
                }
            }
    
            $statusId = 1;
            $incident = new Incident();
            $incident->setUserId($userId);
            $incident->setIncidentTypeId($incidentTypeId);
            $incident->setStatusId($statusId);
            $incident->setDescription($description);
            $incident->setIncidentDate(date('Y-m-d H:i:s'));
    
            $result = $this->IncidentDAO->newIncident($incident);
    
            if ($result) {
                $incidentId = $result;
            
                $subFolder = $incidentId; 
                $incidentImageRoute = [];
    
                if (isset($_FILES['media'])) {
                    $formatName = function($files, $key) use ($incidentId) {
                        $extension = strtolower(pathinfo($files['name'][$key], PATHINFO_EXTENSION));
                        return "{$incidentId}-image-{$key}." . $extension; // Nombre de archivo único por índice
                    };
                    $incidentImageRoute = $this->fileUploader->uploadFiles($_FILES['media'], $subFolder, $formatName);

                    //Guardo en base los patch
                    foreach ($incidentImageRoute as $file) {
                        $this->IncidentFileDAO->saveIncidentFile($incidentId, $file);
                    }
                }
                if($result){
                    echo json_encode(['success' => true, 'message' => '¡Incidente creado exitosamente!']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => '¡La incidencia no pudo ser creada!']);
            }
        } catch (Exception $ex) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error al crear el incidente: ' . $ex->getMessage()]);
        }
    }

    public function answerIncident($incidentId, $userId, $answer) {
        try {
            if ($incidentId === null || $userId === null || $answer === null) {
                if (SessionHelper::getCurrentUser()) {
                    SessionHelper::redirectTo403();
                }
            }

            if (strlen($answer) < 5 || strlen($answer) > 500) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'La respuesta debe tener entre 5 y 500 caracteres.']);
                return;
            }

            $incidentAnswer = new IncidentAnswer();
            $incidentAnswer->setIncidentId($incidentId);
            $incidentAnswer->setUserId($userId);
            $incidentAnswer->setAnswerTime(date('Y-m-d H:i:s'));
            $incidentAnswer->setAnswer($answer);
    
            $this->incidentDAO->addIncidentAnswer($incidentAnswer);
            echo json_encode(['success' => true, 'message' => '¡Respuesta agregada exitosamente!']);
        } catch (Exception $ex) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error al responder el incidente: ' . $ex->getMessage()]);
        }
    }

    public function getAllIncidents() {
        try {
            return $this->incidentDAO->getAllIncidents();
        } catch (Exception $ex) {
            echo "<div class='alert alert-danger'>Error al obtener los incidentes: {$ex->getMessage()}</div>";
        }
    }

    public function getIncidentById($incidentId) {
        try {
            if ($incidentId === null) {
                throw new Exception("ID de incidente no puede ser nulo.");
                echo json_encode(['success' => false, 'message' => 'El id no puede ser nulo.']);
                return;
            }
            $incident = $this->IncidentDAO->getIncidentById($incidentId);
            
            if (!is_null($incident)) {
                echo json_encode(
                    ['success' => true, 
                    'message' => 'Incidencia obtenia correctamente',
                    'incident' =>$incident->toArray()
                ]);
                return;
            } else {
                echo json_encode(['success' => false, 'message' => 'No se pudo obtener la información requerida']);
                return;
            }
        } catch (Exception $ex) {
            echo json_encode(['error' => 'Error al obtener el incidente: ' . $ex->getMessage()]);
        }
    }
    public function getIncidentsByUserId($userId) {
        try {
            if ($userId === null) {
                throw new Exception("ID de usuario no puede ser nulo.");
            }
            return $this->incidentDAO->getIncidentsByUserId($userId);
        } catch (Exception $ex) {
            echo "<div class='alert alert-danger'>Error al obtener los incidentes del usuario: {$ex->getMessage()}</div>";
        }
    }
    public function changeStatusIncident($incidentID=null,$incidentStatus=null){
        if($incidentID === null && $incidentStatus == null){
            if(SessionHelper::getCurrentUser()){
                SessionHelper::redirectTo403();
            }    
        }else{
            try{
                $result = $this->IncidentDAO->changeStatusIncident($incidentID,$incidentStatus);
                if(is_array($result)){
                    $incidentStatusName = $this->IncidentStatusDAO->getIncidentStatusNameById($incidentStatus);
                    $IncidentAnswer = new IncidentAnswer();
                    $IncidentAnswer->setIncidentId($incidentID);
                    $IncidentAnswer->setUserId(SessionHelper::getCurrentUser()->getUserID());
                    $IncidentAnswer->setAnswer('Se cambio el estado de la incidencia a ' . $incidentStatusName);
                    $IncidentAnswer->setAnswerDate(date('Y-m-d H:i:s'));
                    $resulstMessage=$this->IncidentAnswerDAO->newIncidentAnswer($IncidentAnswer);
                    if($resulstMessage){
                        echo json_encode(['success' => true, 'message' => '¡Se cambio el estado correctamente!']);
                        return;
                    }
                }else{
                    echo json_encode(['success' => false, 
                    'message' => '¡No se pudo cambiar el estado!',
                    'error'=>$result]);
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