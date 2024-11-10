<?php
namespace DAODB;

use \PDO as PDO;
use \Exception as Exception;

use Models\GroupPrivacy as GroupPrivacy;

class GroupPrivacyDAO {
    
    private $groupPrivacyTable = 'group_privacy';

    public function getAllGroupPrivacy() {
        try {
            $query = "SELECT * FROM " . $this->groupPrivacyTable . " ORDER BY id ASC";
            $this->connection = Connection::GetInstance();
            $resultSet = $this->connection->Execute($query);

            $groupPrivacyList = array();
            if (!empty($resultSet)) {
                foreach ($resultSet as $row) {
                    $groupPrivacy = new GroupPrivacy();
                    $groupPrivacy->setId($row['id']);
                    $groupPrivacy->setName($row['name']);
                    $groupPrivacy->setIsActive($row['is_active']);
                    $groupPrivacy->setDescription($row['description']);
                    array_push($groupPrivacyList, $groupPrivacy);
                }
            }
            return $groupPrivacyList;
        } catch (Exception $ex) {
            throw $ex;
        }  
    }
    public function getAllGroupPrivacyActive() {
        try {
            $query = "SELECT * FROM " . $this->groupPrivacyTable . " WHERE is_active=1 ORDER BY id ASC";
            $this->connection = Connection::GetInstance();
            $resultSet = $this->connection->Execute($query);

            $groupPrivacyList = array();
            if (!empty($resultSet)) {
                foreach ($resultSet as $row) {
                    $groupPrivacy = new GroupPrivacy();
                    $groupPrivacy->setId($row['id']);
                    $groupPrivacy->setName($row['name']);
                    $groupPrivacy->setIsActive($row['is_active']);
                    $groupPrivacy->setDescription($row['description']);
                    array_push($groupPrivacyList, $groupPrivacy);
                }
            }
            return $groupPrivacyList;
        } catch (Exception $ex) {
            throw $ex;
        }  
    }
    public function getGroupPrivacyById($privacyID) {
        try {
            $query = "SELECT * FROM " . $this->groupPrivacyTable . " WHERE id = :privacyID LIMIT 1";
            $parameters = array();
            $parameters['privacyID'] = $privacyID;

            $this->connection = Connection::GetInstance();
            $resultSet = $this->connection->Execute($query, $parameters);

            if (!empty($resultSet)) {
                $row = $resultSet[0];
                $groupPrivacy = new GroupPrivacy();
                $groupPrivacy->setId($row['id']);
                $groupPrivacy->setName($row['name']);
                $groupPrivacy->setIsActive($row['is_active']);
                $groupPrivacy->setDescription($row['description']);

                return $groupPrivacy;
            }
            return null;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    public function validateUniqueName($id, $name){
        try {
            $query = "SELECT * FROM " . $this->groupPrivacyTable . " WHERE name like :name AND id not in(:id)";

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
    public function createGroupPrivacy(GroupPrivacy $groupPrivacy) {
        try {
            $query = "INSERT INTO " . $this->groupPrivacyTable . " (name, description, is_active) VALUES (:name, :description, :is_active)";
            $parameters = array();
            $parameters['name'] = $groupPrivacy->getName();
            $parameters['description'] = $groupPrivacy->getDescription();
            $parameters['is_active'] = $groupPrivacy->getIsActive();

            $this->connection = Connection::GetInstance();
            $result = $this->connection->Execute($query, $parameters);
            
            return $result;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    public function updateGroupPrivacy(GroupPrivacy $groupPrivacy) {
        try {
            $query = "UPDATE " . $this->groupPrivacyTable . " SET name = :name, description = :description, is_active = :is_active WHERE id = :id";
            $parameters = array();
            $parameters['id'] = $groupPrivacy->getId();
            $parameters['name'] = $groupPrivacy->getName();
            $parameters['description'] = $groupPrivacy->getDescription();
            $parameters['is_active'] = $groupPrivacy->getIsActive();

            $this->connection = Connection::GetInstance();
            $result = $this->connection->Execute($query, $parameters);
            
            return $result;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    public function deleteGroupPrivacy($privacyID) {
        try {
            $query = "UPDATE " . $this->groupPrivacyTable . " SET is_active = 0 WHERE id = :privacyID";
            $parameters = array();
            $parameters['privacyID'] = $privacyID;

            $this->connection = Connection::GetInstance();
            $result = $this->connection->Execute($query, $parameters);
            
            return $result;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    public function reactivateGroupPrivacy($privacyID){
        try {
            $query = "UPDATE " . $this->groupPrivacyTable . " SET is_active = 1 WHERE id = :privacyID";
            $parameters = array();
            $parameters['privacyID'] = $privacyID;

            $this->connection = Connection::GetInstance();
            $result = $this->connection->Execute($query, $parameters);
            
            return $result;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
}
?>
