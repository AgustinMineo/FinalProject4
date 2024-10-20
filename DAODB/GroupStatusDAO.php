<?php
namespace DAODB;

use \PDO as PDO;
use \Exception as Exception;

use Models\GroupStatus as GroupStatus;

class GroupStatusDAO{
    
    private $groupStatusTable = 'group_status';

    public function getAllGroupStatus(){
        try{
            $query = "SELECT * FROM " . $this->groupStatusTable . " where is_active = 1 order by id asc";
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
    
}
?>