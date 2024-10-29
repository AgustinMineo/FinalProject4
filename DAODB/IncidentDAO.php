<?php
namespace DAODB;

use \PDO as PDO;
use \Exception as Exception;

use Models\Incident as Incident;
use Models\IncidentType as IncidentType;
use Models\IncidentStatus as IncidentStatus;
use Models\User as User;

use DAODB\UserDAO as UserDAO;
use DAODB\IncidentTypeDAO as IncidentTypeDAO;
use DAODB\IncidentStatusDAO as IncidentStatusDAO;
use DAODB\IncidentAnswerDAO as IncidentAnswerDAO;
use DAODB\IncidentFileDAO as IncidentFileDAO;

class IncidentDAO {
    
    private $incidentTable = 'incidents';
    private $IncidentTypeDAO;
    private $IncidentStatusDAO;
    private $IncidentAnswerDAO;
    private $userDAO;
    private $IncidentFileDAO;

    public function __construct() {
        $this->IncidentTypeDAO = new IncidentTypeDAO();
        $this->IncidentStatusDAO = new IncidentStatusDAO();
        $this->IncidentAnswerDAO = new IncidentAnswerDAO();
        $this->IncidentFileDAO = new IncidentFileDAO();
        $this->UserDAO = new UserDAO();
    }

    public function newIncident(Incident $incident) {
        try {
            $query = "INSERT INTO " . $this->incidentTable . " (idUsuario, incidentTypeId, incidentStatus, incidentDate, description) 
                      VALUES (:idUser, :incidentTypeId, :incidentStatus, :incidentDate, :description)";
            $parameters = array(
                "idUser" => $incident->getUserId(),
                "incidentTypeId" => $incident->getIncidentTypeId(),
                "incidentStatus" => $incident->getStatusId(),
                "incidentDate" => $incident->getIncidentDate(),
                "description" => $incident->getDescription()
            );

            $this->connection = Connection::GetInstance();
            $result= $this->connection->Execute($query, $parameters);
            if(is_array($result)){
                return $this->connection->lastInsertId(); 
            }else{
                return false;
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function answerIncident($incidentAnswer) {
        try {
            $query = "INSERT INTO incident_answer (idIncident, idUser, answerTime, answer) 
                      VALUES (:idIncident, :idUser, :answerTime, :answer)";
            $parameters = array(
                "idIncident" => $incidentAnswer->getIncidentId(),
                "idUser" => $incidentAnswer->getUserId(),
                "answerTime" => $incidentAnswer->getAnswerTime(),
                "answer" => $incidentAnswer->getAnswer()
            );

            $this->connection = Connection::GetInstance();
            $this->connection->Execute($query, $parameters);
            
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function getAllIncidents() {
        try {
            $query = "SELECT * FROM " . $this->incidentTable . " ORDER BY incidentDate DESC";
            $this->connection = Connection::GetInstance();
            $resultSet = $this->connection->Execute($query);

            $incidentList = array();

            foreach ($resultSet as $row) {
                $incident = $this->buildIncident($row);
                array_push($incidentList, $incident);
            }

            return $incidentList;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function getIncidentById($incidentID) {
        try {
            $query = "SELECT * FROM " . $this->incidentTable . " WHERE id = :id LIMIT 1";
            $parameters["id"] = $incidentID;

            $this->connection = Connection::GetInstance();
            $resultSet = $this->connection->Execute($query, $parameters);

            if (!empty($resultSet)) {
                foreach ($resultSet as $row) {
                    $incident = $this->buildIncident($row);
                    return $incident;
                }
                return $incident;
            }
            return [];
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    public function getIncidentByUserId($userId) {
        try {
            $query = "SELECT * FROM " . $this->incidentTable . " WHERE idUsuario = :userId ORDER BY incidentDate DESC";
            $parameters ["userId"] = $userId;

            $this->connection = Connection::GetInstance();
            $resultSet = $this->connection->Execute($query, $parameters);

            $incidentList = array();

            foreach ($resultSet as $row) {
                $incident = $this->buildIncident($row);
                array_push($incidentList, $incident);
            }

            return $incidentList;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    public function changeStatusIncident($incidentID,$statusID){
        try {
            $query = "UPDATE " . $this->incidentTable . " 
            SET incidentStatus=:statusID 
            WHERE id = :incidentID";
            $parameters['statusID'] = $statusID;
            $parameters['incidentID'] = $incidentID;

            $this->connection = Connection::GetInstance();
            return $this->connection->Execute($query, $parameters);
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    private function buildIncident($row) {
        $incident = new Incident();
        $incident->setId($row['id']);
        $incident->setDescription($row['description']);
        $incident->setIncidentDate($row['incidentDate']);
        
        $user = $this->UserDAO->getUserByIdReduce($row['idUsuario']);
        $incident->setUserId($user);
        
        $incidentType = $this->IncidentTypeDAO->getIncidentTypeById($row['incidentTypeId']);
        $incident->setIncidentTypeId($incidentType);
        
        $incidentStatus = $this->IncidentStatusDAO->getIncidentStatusById($row['incidentStatus']);
        $incident->setStatusId($incidentStatus);
        $incidentAnswers = $this->IncidentAnswerDAO->getAnswersByIncidentId($row['id']);
        $incident->setAnswers($incidentAnswers);
        $incidentFiles = $this->IncidentFileDAO->getFilesByIncidentId($row['id']);
        $incident->setFiles($incidentFiles);
        return $incident;
    }
}

?>