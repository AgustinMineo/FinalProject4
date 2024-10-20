<?php
namespace DAODB;
use Models\GroupInvitationStatus;
use DAODB\Connection;
use Exception;

class GroupInvitationStatusDAO{
    private $connection;
    private $statusTable = "invitation_status";

    public function __construct() {
        $this->connection = Connection::GetInstance();
    }

    public function getInvitationStatus() {
        try {
            $query = "SELECT * FROM " . $this->statusTable . " WHERE is_active = 1 ORDER BY id ASC";
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
}
?>

