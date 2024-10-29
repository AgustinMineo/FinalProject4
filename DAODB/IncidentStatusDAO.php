<?php
namespace DAODB;
use \PDO as PDO;
use \Exception as Exception;
use Models\IncidentStatus;

class IncidentStatusDAO {
    
    private $incidentStatusTable = 'incident_status';

    public function getAllIncidentStatusActive() {
        try {
            $query = "SELECT * FROM " . $this->incidentStatusTable ."  WHERE is_active=1";
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
    public function getAllIncidentStatus() {
        try {
            $query = "SELECT * FROM " . $this->incidentStatusTable ;
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
    
    public function updateIncidentStatus(IncidentStatus $updateStatus){
        try{
            $query = "UPDATE ". $this->incidentStatusTable . " 
            SET name=:name, description=:description ,is_active=:is_active WHERE id = :id";
            $parameters['id'] = $updateStatus->getId();
            $parameters['name'] = $updateStatus->getName();
            $parameters['description'] = $updateStatus->getDescription();
            $parameters['is_active'] = $updateStatus->getIsActive();
            $this->connection = Connection::GetInstance();
            $resultSet = $this->connection->Execute($query, $parameters);
            if($resultSet>0){
                return true;
            }else{
                return false;
            }
        }catch(Exception $ex){
            throw $ex;
        }
    }
    
    public function changeStatusIncident($id,$newStatus){
        try{
            $query = "UPDATE ". $this->incidentStatusTable . " 
            SET is_active=:newStatus WHERE id = :idStatus";
            $parameters['idStatus'] = $id;
            $parameters['newStatus'] = $newStatus;
            $this->connection = Connection::GetInstance();
            $resultSet = $this->connection->Execute($query, $parameters);
            if(is_array($resultSet)){
                return true;
            }else{
                return false;
            }
        }catch(Exception $ex){
            throw $ex;
        }
    }
    public function newIncidentStatus(IncidentStatus $newStatus){
        try {
            $query = "INSERT INTO " . $this->incidentStatusTable . " (name, description, is_active) 
                        VALUES (:name, :description, :is_active)";
            $parameters['name'] = $newStatus->getName();
            $parameters['description'] = $newStatus->getDescription();
            $parameters['is_active'] = $newStatus->getIsActive();
    
            $this->connection = Connection::GetInstance();
            $resultSet = $this->connection->Execute($query, $parameters);
            if(is_array($resultSet)){
                return true;
            }else{
                return false;
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    public function validateUniqueName($id, $name){
        try {
            $query = "SELECT * FROM " . $this->incidentStatusTable . " WHERE name like :name AND id not in(:id)";

            $parameters['id'] = $id;
            $parameters['name'] = $name;
            $this->connection = Connection::GetInstance();
            $resultSet = $this->connection->Execute($query, $parameters);
            if (is_array($resultSet) && count($resultSet) > 0) {
                return true;
            } else {
                return null;
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    public function validateUniqueNameByName($name){
        try {
            $query = "SELECT * FROM " . $this->incidentStatusTable . " WHERE name like :name";
            $parameters['name'] = $name;
            $this->connection = Connection::GetInstance();
            $resultSet = $this->connection->Execute($query, $parameters);
            if (is_array($resultSet) && count($resultSet) > 0) {
                return true;
            } else {
                return null;
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }
}

?>