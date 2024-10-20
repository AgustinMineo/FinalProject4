<?php
namespace DAODB;

use \PDO as PDO;
use \Exception as Exception;

use Models\GroupTypes as GroupTypes;

class GroupTypeDAO{
    
    private $groupTypeTable = 'group_type';

    public function getAllGroupType(){
        try{
            $query = "SELECT * FROM " . $this->groupTypeTable . " where is_active = 1 order by id asc";
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
    
}
?>