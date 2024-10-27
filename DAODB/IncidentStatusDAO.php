<?php
namespace DAODB;
use \PDO as PDO;
use \Exception as Exception;
use Models\IncidentStatus;

class IncidentStatusDAO {
    
    private $incidentStatusTable = 'incident_status';

    public function getAllIncidentStatus() {
        try {
            $query = "SELECT * FROM " . $this->incidentStatusTable;
            $this->connection = Connection::GetInstance();
            $resultSet = $this->connection->Execute($query);

            $statusList = array();
            foreach ($resultSet as $row) {
                $status = new IncidentStatus();
                $status->setId($row['id']);
                $status->setName($row['name']);
                $status->setDescription($row['description']);
                $status->setIsActive($row['is_active']);
                array_push($statusList, $status);
            }
            return $statusList;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function getIncidentStatusById($statusId) {
        try {
            $query = "SELECT * FROM " . $this->incidentStatusTable . " WHERE id = :statusId";
            $parameters['statusId'] = $statusId;

            $this->connection = Connection::GetInstance();
            $resultSet = $this->connection->Execute($query, $parameters);

            if (!empty($resultSet)) {
                $row = $resultSet[0];
                $status = new IncidentStatus();
                $status->setId($row['id']);
                $status->setName($row['name']);
                $status->setDescription($row['description']);
                $status->setIsActive($row['is_active']);
                
                return $status;
            }
            return null;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    public function getIncidentStatusNameById($statusId) {
        try {
            $query = "SELECT name FROM " . $this->incidentStatusTable . " WHERE id = :statusId";
            $parameters['statusId'] = $statusId;

            $this->connection = Connection::GetInstance();
            $resultSet = $this->connection->Execute($query, $parameters);
            $name;
            foreach($resultSet as $row){
                $name=$row['name'];
            }
            return $name;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
}

?>