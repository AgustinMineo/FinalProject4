<?php
namespace Controllers;
use Helper\SessionHelper as SessionHelper;
use \Exception as Exception;
use Models\IncidentAnswer as IncidentAnswer;
use DAODB\IncidentAnswerDAO as IncidentAnswerDAO;
class IncidentAnswerController{
    private $IncidentAnswerDAO;

    public function __construct() {
        $this->IncidentAnswerDAO = new IncidentAnswerDAO();
    }

    public function newIncidentAnswer($incidentID=null,$incidentUserId=null,$incidentAnswer=null){
        if($incidentID === null && $incidentUserId === null && $incidentAnswer ===null){
            if(SessionHelper::getCurrentUser()){
                SessionHelper::redirectTo403();
            }
        }else{
            try{
                $newIncident = new IncidentAnswer();
                $newIncident->setIncidentId($incidentID);
                $newIncident->setUserId($incidentUserId);
                $newIncident->setAnswerDate(date('Y-m-d H:i:s'));
                $newIncident->setAnswer($incidentAnswer);
                $result = $this->IncidentAnswerDAO->newIncidentAnswer($newIncident);
                if ($result) {
                    echo json_encode([
                        'success' => true,
                        'message' => 'Respuesta registrada correctamente.'
                    ]);
                } else {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Error al registrar la respuesta.',
                        'response'=>$result
                    ]);
                }
            }catch (Exception $ex) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Error: ' . $ex->getMessage()
                ]);
            }
        }
    }
}
?>