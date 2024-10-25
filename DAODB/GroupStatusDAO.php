<?php
namespace DAODB;

use \PDO as PDO;
use \Exception as Exception;

use Models\GroupStatus as GroupStatus;

class GroupStatusDAO{
    
    private $groupStatusTable = 'group_status';

    public function getAllGroupStatus(){
        try{
            $query = "SELECT * FROM " . $this->groupStatusTable . " order by id asc";
            $this->connection = Connection::GetInstance();
            $resultSet = $this->connection->Execute($query);
            if (!empty($resultSet)) {
                $groupStatusList = array();
                foreach($resultSet as $row){
                    $groupStatus = new GroupStatus();
                    $groupStatus->setId($row['id']);
                    $groupStatus->setName($row['name']);
                    $groupStatus->setIsActive($row['is_active']);
                    $groupStatus->setDescription($row['description']);
                    array_push($groupStatusList,$groupStatus);
                }
                return $groupStatusList;
            }
            
        return $groupStatusList;
        }catch(Exception $ex){
            throw $ex;
        }  
    }
    public function getGroupStatusById($statusID) {
        try {
            $query = "SELECT * FROM " . $this->groupStatusTable . " WHERE id = :statusID LIMIT 1";
            $parameters = array();
            $parameters['statusID'] = $statusID;
    
            $this->connection = Connection::GetInstance();
            $resultSet = $this->connection->Execute($query, $parameters);
    
            if (!empty($resultSet)) {
                $row = $resultSet[0]; 
                $groupStatus = new GroupStatus();
                $groupStatus->setId($row['id']);
                $groupStatus->setName($row['name']);
                $groupStatus->setIsActive($row['is_active']);
                $groupStatus->setDescription($row['description']);
    
                return $groupStatus;
            }
            return null;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    public function validateUniqueName($id,$name){
        try {
            $query = "SELECT * FROM " . $this->groupStatusTable . " WHERE name like :name AND id not in(:id)";

            $parameters['id'] = $id;
            $parameters['name'] = $name;
            $this->connection = Connection::GetInstance();
            $resultSet = $this->connection->Execute($query, $parameters);
            if (is_array($resultSet) && count($resultSet) > 0) {
                return true;
            }else{
                return null;
            }
        } catch (Exception $ex) {
            throw $ex;
        } 
    }
    public function createGroupStatus(GroupStatus $groupStatus) {
        try {
            $query = "INSERT INTO " . $this->groupStatusTable . " (name, description, is_active) VALUES (:name, :description, :is_active)";
            $parameters = array();
            $parameters['name'] = $groupStatus->getName();
            $parameters['description'] = $groupStatus->getDescription();
            $parameters['is_active'] = $groupStatus->getIsActive();

            $this->connection = Connection::GetInstance();
            $result = $this->connection->Execute($query, $parameters);
            if(is_array($result)){
                return true;
            }else{
                return false;
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    public function updateGroupStatus(GroupStatus $groupStatus) {
        try {
            $query = "UPDATE " . $this->groupStatusTable . " SET name = :name, description = :description, is_active = :is_active WHERE id = :id";
            $parameters = array();
            $parameters['id'] = $groupStatus->getId();
            $parameters['name'] = $groupStatus->getName();
            $parameters['description'] = $groupStatus->getDescription();
            $parameters['is_active'] = $groupStatus->getIsActive();

            $this->connection = Connection::GetInstance();
            $result = $this->connection->Execute($query, $parameters);
            
            return $result;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    public function deleteGroupStatus($statusID) {
        try {
            $query = "UPDATE " . $this->groupStatusTable . " SET is_active = 0 WHERE id = :statusID";
            $parameters = array();
            $parameters['statusID'] = $statusID;

            $this->connection = Connection::GetInstance();
            $result = $this->connection->Execute($query, $parameters);
            
            return $result; 
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    public function reactiveGroupStatus($statusID){
        try {
            $query = "UPDATE " . $this->groupStatusTable . " SET is_active = 1 WHERE id = :statusID";
            $parameters = array();
            $parameters['statusID'] = $statusID;

            $this->connection = Connection::GetInstance();
            $result = $this->connection->Execute($query, $parameters);
            
            return $result; 
        } catch (Exception $ex) {
            throw $ex;
        }
    }
}
?>