<?php
namespace DAODB;
use \PDO as PDO;
use \Exception as Exception;
use Models\IncidentType;

class IncidentTypeDAO {
    
    private $incidentTypeTable = 'incident_type';

    public function getAllIncidentTypesActives() {
        try {
            $query = "SELECT * FROM " . $this->incidentTypeTable ."  WHERE is_active=1";
            $this->connection = Connection::GetInstance();
            $resultSet = $this->connection->Execute($query);

            $typeList = array();
            foreach ($resultSet as $row) {
                $type = new IncidentType();
                $type->setId($row['id']);
                $type->setName($row['name']);
                $type->setDescription($row['description']);
                $type->setIsActive($row['is_active']);
                array_push($typeList, $type);
            }
            return $typeList;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    public function getAllIncidentTypes() {
        try {
            $query = "SELECT * FROM " . $this->incidentTypeTable ;
            $this->connection = Connection::GetInstance();
            $resultSet = $this->connection->Execute($query);

            $typeList = array();
            foreach ($resultSet as $row) {
                $type = new IncidentType();
                $type->setId($row['id']);
                $type->setName($row['name']);
                $type->setDescription($row['description']);
                $type->setIsActive($row['is_active']);
                array_push($typeList, $type);
            }
            return $typeList;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function getIncidentTypeById($typeId) {
        try {
            $query = "SELECT * FROM " . $this->incidentTypeTable . " WHERE id = :typeId";
            $parameters['typeId'] = $typeId;

            $this->connection = Connection::GetInstance();
            $resultSet = $this->connection->Execute($query, $parameters);

            if (!empty($resultSet)) {
                $row = $resultSet[0];
                $type = new IncidentType();
                $type->setId($row['id']);
                $type->setName($row['name']);
                $type->setDescription($row['description']);
                $type->setIsActive($row['is_active']);
                
                return $type;
            }
            return null;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    public function changeStatusIncidentType($id,$newStatus){
        try{
            $query = "UPDATE ". $this->incidentTypeTable . " 
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
    public function updateStatusIncidentType(IncidentType $updateType){
        try{
            $query = "UPDATE ". $this->incidentTypeTable . " 
            SET name=:name, description=:description ,is_active=:is_active WHERE id = :id";
            $parameters['id'] = $updateType->getId();
            $parameters['name'] = $updateType->getName();
            $parameters['description'] = $updateType->getDescription();
            $parameters['is_active'] = $updateType->getIsActive();

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
    public function newStatusIncidentType(IncidentType $newType){
        try {
            $query = "INSERT INTO " . $this->incidentTypeTable . " (name, description, is_active) 
                        VALUES (:name, :description, :is_active)";
            $parameters['name'] = $newType->getName();
            $parameters['description'] = $newType->getDescription();
            $parameters['is_active'] = $newType->getIsActive();
    
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
            $query = "SELECT * FROM " . $this->incidentTypeTable . " WHERE name like :name AND id not in(:id)";

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
            $query = "SELECT * FROM " . $this->incidentTypeTable . " WHERE name like :name";
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