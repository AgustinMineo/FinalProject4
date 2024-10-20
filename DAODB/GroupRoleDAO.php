<?php
namespace DAODB;

use \PDO as PDO;
use \Exception as Exception;

use Models\GroupRole as GroupRole;

class GroupRoleDAO {
    
    private $groupRoleTable = 'group_role';

    public function getAllGroupRole() {
        try {
            $query = "SELECT * FROM " . $this->groupRoleTable . " WHERE is_active = 1 ORDER BY id ASC";
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
}
?>
