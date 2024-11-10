<?php
namespace DAODB;

use \PDO as PDO;
use \Exception as Exception;

use Models\GroupRole as GroupRole;

class GroupRoleDAO {
    
    private $groupRoleTable = 'group_role';

    public function getAllGroupRole() {
        try {
            $query = "SELECT * FROM " . $this->groupRoleTable . " ORDER BY id ASC";
            $this->connection = Connection::GetInstance();
            $resultSet = $this->connection->Execute($query);

            $groupRoleList = array();
            if (!empty($resultSet)) {
                foreach ($resultSet as $row) {
                    $groupRole = new GroupRole();
                    $groupRole->setId($row['id']);
                    $groupRole->setName($row['name']);
                    $groupRole->setIsActive($row['is_active']);
                    $groupRole->setDescription($row['description']);
                    array_push($groupRoleList, $groupRole);
                }
            }
            return $groupRoleList;
        } catch (Exception $ex) {
            throw $ex;
        }  
    }
    public function getAllGroupRoleActive() {
        try {
            $query = "SELECT * FROM " . $this->groupRoleTable . " WHERE is_active=1 ORDER BY id ASC";
            $this->connection = Connection::GetInstance();
            $resultSet = $this->connection->Execute($query);

            $groupRoleList = array();
            if (!empty($resultSet)) {
                foreach ($resultSet as $row) {
                    $groupRole = new GroupRole();
                    $groupRole->setId($row['id']);
                    $groupRole->setName($row['name']);
                    $groupRole->setIsActive($row['is_active']);
                    $groupRole->setDescription($row['description']);
                    array_push($groupRoleList, $groupRole);
                }
            }
            return $groupRoleList;
        } catch (Exception $ex) {
            throw $ex;
        }  
    }
    public function getGroupRoleById($roleID) {
        try {
            $query = "SELECT * FROM " . $this->groupRoleTable . " WHERE id = :roleID LIMIT 1";
            $parameters = array();
            $parameters['roleID'] = $roleID;

            $this->connection = Connection::GetInstance();
            $resultSet = $this->connection->Execute($query, $parameters);

            if (!empty($resultSet)) {
                $row = $resultSet[0];
                $groupRole = new GroupRole();
                $groupRole->setId($row['id']);
                $groupRole->setName($row['name']);
                $groupRole->setIsActive($row['is_active']);
                $groupRole->setDescription($row['description']);

                return $groupRole;
            }
            return null;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    public function validateUniqueName($id, $name){
        try {
            $query = "SELECT * FROM " . $this->groupRoleTable . " WHERE name like :name AND id not in(:id)";

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
    public function deleteGroupRole($roleID) {
        try {
            $query = "UPDATE " . $this->groupRoleTable . " SET is_active = 0 WHERE id = :roleID";
            $parameters = array();
            $parameters['roleID'] = $roleID;

            $this->connection = Connection::GetInstance();
            $result = $this->connection->Execute($query, $parameters);
            
            return $result; 
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    public function createGroupRole(GroupRole $groupRole) {
        try {
            $query = "INSERT INTO " . $this->groupRoleTable . " (name, description, is_active) VALUES (:name, :description, :is_active)";
            $parameters = array();
            $parameters['name'] = $groupRole->getName();
            $parameters['description'] = $groupRole->getDescription();
            $parameters['is_active'] = $groupRole->getIsActive();

            $this->connection = Connection::GetInstance();
            $result = $this->connection->Execute($query, $parameters);
            
            return $result; 
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    public function updateGroupRole(GroupRole $groupRole) {
        try {
            $query = "UPDATE " . $this->groupRoleTable . " SET name = :name, description = :description, is_active = :is_active WHERE id = :id";
            $parameters = array();
            $parameters['id'] = $groupRole->getId();
            $parameters['name'] = $groupRole->getName();
            $parameters['description'] = $groupRole->getDescription();
            $parameters['is_active'] = $groupRole->getIsActive();

            $this->connection = Connection::GetInstance();
            $result = $this->connection->Execute($query, $parameters);
            
            return $result;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    public function reactivateGroupRole($roleID){
        try {
            $query = "UPDATE " . $this->groupRoleTable . " SET is_active = 1 WHERE id = :roleID";
            $parameters = array();
            $parameters['roleID'] = $roleID;

            $this->connection = Connection::GetInstance();
            $result = $this->connection->Execute($query, $parameters);
            
            return $result; 
        } catch (Exception $ex) {
            throw $ex;
        }
    }
}
?>
