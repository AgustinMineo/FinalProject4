<?php
namespace DAODB;

use \PDO as PDO;
use \Exception as Exception;

use Models\GroupTypes as GroupTypes;

class GroupTypeDAO{
    
    private $groupTypeTable = 'group_type';

    public function getAllGroupType(){
        try{
            $query = "SELECT * FROM " . $this->groupTypeTable . " order by id asc";
            $this->connection = Connection::GetInstance();
            $resultSet = $this->connection->Execute($query);
            if (!empty($resultSet)) {
                $groupTypeList = array();
                foreach($resultSet as $row){
                    $groupType = new GroupTypes();
                    $groupType->setId($row['id']);
                    $groupType->setName($row['name']);
                    $groupType->setIsActive($row['is_active']);
                    $groupType->setDescription($row['description']);
                    array_push($groupTypeList,$groupType);
                }
                return $groupTypeList;
            }

        return $groupTypeList;
        }catch(Exception $ex){
            throw $ex;
        }  
    }
    public function getAllGroupTypeActive(){
        try{
            $query = "SELECT * FROM " . $this->groupTypeTable . " WHERE is_active=1 order by id asc";
            $this->connection = Connection::GetInstance();
            $resultSet = $this->connection->Execute($query);
            if (!empty($resultSet)) {
                $groupTypeList = array();
                foreach($resultSet as $row){
                    $groupType = new GroupTypes();
                    $groupType->setId($row['id']);
                    $groupType->setName($row['name']);
                    $groupType->setIsActive($row['is_active']);
                    $groupType->setDescription($row['description']);
                    array_push($groupTypeList,$groupType);
                }
                return $groupTypeList;
            }

        return $groupTypeList;
        }catch(Exception $ex){
            throw $ex;
        }  
    }
    public function getGroupTypeById($typeID) {
        try {
            $query = "SELECT * FROM " . $this->groupTypeTable . " WHERE id = :typeID LIMIT 1";
            $parameters = array();
            $parameters['typeID'] = $typeID;
    
            $this->connection = Connection::GetInstance();
            $resultSet = $this->connection->Execute($query, $parameters);
    
            if (!empty($resultSet)) {
                $row = $resultSet[0]; 
                $groupType = new GroupTypes();
                $groupType->setId($row['id']);
                $groupType->setName($row['name']);
                $groupType->setIsActive($row['is_active']);
                $groupType->setDescription($row['description']);
    
                return $groupType;
            }
            return null;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    public function validateUniqueName($id, $name){
        try {
            $query = "SELECT * FROM " . $this->groupTypeTable . " WHERE name like :name AND id not in(:id)";

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
    public function createGroupType(GroupTypes $groupType) {
        try {
            $query = "INSERT INTO " . $this->groupTypeTable . " (name, description, is_active) VALUES (:name, :description, :is_active)";
            $parameters = array();
            $parameters['name'] = $groupType->getName();
            $parameters['description'] = $groupType->getDescription();
            $parameters['is_active'] = $groupType->getIsActive();

            $this->connection = Connection::GetInstance();
            $result = $this->connection->Execute($query, $parameters);
            
            return $result;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    public function updateGroupType(GroupTypes $groupType) {
        try {
            $query = "UPDATE " . $this->groupTypeTable . " SET name = :name, description = :description, is_active = :is_active WHERE id = :id";
            $parameters = array();
            $parameters['id'] = $groupType->getId();
            $parameters['name'] = $groupType->getName();
            $parameters['description'] = $groupType->getDescription();
            $parameters['is_active'] = $groupType->getIsActive();

            $this->connection = Connection::GetInstance();
            $result = $this->connection->Execute($query, $parameters);
            
            return $result;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    public function deleteGroupType($typeID) {
        try {
            $query = "UPDATE " . $this->groupTypeTable . " SET is_active = 0 WHERE id = :typeID";
            $parameters = array();
            $parameters['typeID'] = $typeID;

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
    public function reactivateGroupType($typeID) {
        try {
            $query = "UPDATE " . $this->groupTypeTable . " SET is_active = 1 WHERE id = :typeID";
            $parameters = array();
            $parameters['typeID'] = $typeID;
    
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
}
?>