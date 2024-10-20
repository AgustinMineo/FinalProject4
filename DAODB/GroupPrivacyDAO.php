<?php
namespace DAODB;

use \PDO as PDO;
use \Exception as Exception;

use Models\GroupPrivacy as GroupPrivacy;

class GroupPrivacyDAO {
    
    private $groupPrivacyTable = 'group_privacy';

    public function getAllGroupPrivacy() {
        try {
            $query = "SELECT * FROM " . $this->groupPrivacyTable . " WHERE is_active = 1 ORDER BY id ASC";
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
}
?>
