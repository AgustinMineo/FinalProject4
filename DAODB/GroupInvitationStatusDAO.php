<?php
namespace DAODB;
use Models\GroupInvitationStatus;
use DAODB\Connection;
use Exception;

class GroupInvitationStatusDAO{
    private $statusTable = "invitation_status";

    public function getInvitationStatus() {
        try {
            $query = "SELECT * FROM " . $this->statusTable . " ORDER BY id ASC";
            $this->connection = Connection::GetInstance();
            $resultSet = $this->connection->Execute($query);

            $groupInvitationStatusList = array();
            if (!empty($resultSet)) {
                foreach ($resultSet as $row) {
                    $groupInvitationStatus = new GroupInvitationStatus();
                    $groupInvitationStatus->setId($row['id']);
                    $groupInvitationStatus->setName($row['name']);
                    $groupInvitationStatus->setIsActive($row['is_active']);
                    $groupInvitationStatus->setDescription($row['description']);
                    array_push($groupInvitationStatusList, $groupInvitationStatus);
                }
            }
            return $groupInvitationStatusList;
        } catch (Exception $ex) {
            throw $ex;
        }  
    }
    public function getInvitationStatusById($idStatus) {
        try {
            $query = "SELECT * FROM " . $this->statusTable . " WHERE is_active = 1 AND id=:id ORDER BY id ASC";
            
            $parameters['id']=$idStatus;
            $this->connection = Connection::GetInstance();
            $resultSet = $this->connection->Execute($query,$parameters);

            if (!empty($resultSet)) {
                foreach ($resultSet as $row) {
                    $groupInvitationStatus = new GroupInvitationStatus();
                    $groupInvitationStatus->setId($row['id']);
                    $groupInvitationStatus->setName($row['name']);
                    $groupInvitationStatus->setIsActive($row['is_active']);
                    $groupInvitationStatus->setDescription($row['description']);
                }
                return $groupInvitationStatus;
            }else{
                return null;
            }
        } catch (Exception $ex) {
            throw $ex;
        }  
    }
    public function validateUniqueName($id, $name){
        try {
            $query = "SELECT * FROM " . $this->statusTable . " WHERE name like :name AND id not in(:id)";

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
    public function createInvitationStatus(GroupInvitationStatus $invitationStatus) {
        try {
            $query = "INSERT INTO " . $this->statusTable . " (name, description, is_active) VALUES (:name, :description, :is_active)";
            $parameters['name'] = $invitationStatus->getName();
            $parameters['description'] = $invitationStatus->getDescription();
            $parameters['is_active'] = $invitationStatus->getIsActive();

            $this->connection = Connection::GetInstance();
            return $this->connection->Execute($query, $parameters);
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    public function updateInvitationStatus(GroupInvitationStatus $invitationStatus) {
        try {
            $query = "UPDATE " . $this->statusTable . " SET name = :name, description = :description, is_active = :is_active WHERE id = :id";
            $parameters['id'] = $invitationStatus->getId();
            $parameters['name'] = $invitationStatus->getName();
            $parameters['description'] = $invitationStatus->getDescription();
            $parameters['is_active'] = $invitationStatus->getIsActive();

            $this->connection = Connection::GetInstance();
            return $this->connection->Execute($query, $parameters);
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    public function deleteInvitationStatus($invitationStatusId) {
        try {
            $query = "UPDATE " . $this->statusTable . " SET is_active = 0 WHERE id = :id";
            $parameters['id'] = $invitationStatusId;

            $this->connection = Connection::GetInstance();
            return $this->connection->Execute($query, $parameters);
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    public function reactivateInvitationStatus($invitationStatusId){
        try {
            $query = "UPDATE " . $this->statusTable . " SET is_active = 1 WHERE id = :id";
            $parameters['id'] = $invitationStatusId;

            $this->connection = Connection::GetInstance();
            return $this->connection->Execute($query, $parameters);
        } catch (Exception $ex) {
            throw $ex;
        }
    }
}

?>

