<?php
namespace DAODB;

use \PDO as PDO;
use \Exception as Exception;
use Models\IncidentAnswer as IncidentAnswer;
use DAODB\UserDAO as UserDAO;
class IncidentAnswerDAO {
    
    private $incidentAnswerTable = 'incident_answer';
    public function __construct() {
        $this->UserDAO = new UserDAO();
    }
    public function getAnswersByIncidentId($incidentId) {
        try {
            $query = "SELECT * FROM " . $this->incidentAnswerTable . " WHERE idIncident = :incidentId order by answerDate asc";
            $parameters["incidentId"] = $incidentId;
    
            $this->connection = Connection::GetInstance();
            $resultSet = $this->connection->Execute($query, $parameters);
            $answersList = [];
            
            if (is_array($resultSet) && count($resultSet) > 0) {
                foreach ($resultSet as $row) {
                    $answer = new IncidentAnswer();
                    $answer->setId($row['id']);
                    $answer->setIncidentId($row['idIncident']);
                    $answer->setUserId($this->UserDAO->getUserByIdReduce($row['idUser']));
                    $answer->setAnswerDate($row['answerDate']);
                    $answer->setAnswer($row['answer']);
                    array_push($answersList, $answer);
                }
            }
    
            return $answersList;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    

    public function newIncidentAnswer(IncidentAnswer $incidentAnswer) {
        try {
            $query = "INSERT INTO " . $this->incidentAnswerTable . " (idIncident, idUser, answerDate, answer) VALUES (:incidentId, :userId, :answerDate, :answer)";
            $parameters['incidentId'] = $incidentAnswer->getIncidentId();
            $parameters['userId'] = $incidentAnswer->getUserId();
            $parameters['answerDate'] = $incidentAnswer->getAnswerDate();
            $parameters['answer'] = $incidentAnswer->getAnswer();

            $this->connection = Connection::GetInstance();
            $this->connection->Execute($query, $parameters);
            return true;
            
        } catch (Exception $ex) {
            throw $ex;
        }
    }
}
?>
