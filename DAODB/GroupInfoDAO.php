<?php
namespace DAODB;

use Helper\SessionHelper as SessionHelper;
use \PDO as PDO;
use \Exception as Exception;

use Models\GroupInfo as GroupInfo;

class GroupInfoDAO{
    private $groupInfoTable = 'group_info';

    public function newGroupInfo(GroupInfo $GroupInfo) {
        try {
            if ($GroupInfo->getStartDate() && $GroupInfo->getEndDate()) {
                $query = "INSERT INTO " . $this->groupInfoTable . " 
                        (description, rules, image, start_date, end_date) 
                        VALUES (
                            '" . $GroupInfo->getDescription() . "', 
                            '" . $GroupInfo->getRules() . "', 
                            '" . ($GroupInfo->getImage() ?? GROUPS_PATH . "/Default/DefaultGroupImage.jpg") . "', 
                            '" . $GroupInfo->getStartDate() . "', 
                            '" . $GroupInfo->getEndDate() . "'
                        )";
            } else {
                $query = "INSERT INTO " . $this->groupInfoTable . " 
                        (description, rules, image) 
                        VALUES (
                            '" . $GroupInfo->getDescription() . "', 
                            '" . $GroupInfo->getRules() . "', 
                            '" . ($GroupInfo->getImage() ?? GROUPS_PATH . "/Default/DefaultGroupImage.jpg") . "'
                        )";
            }

            $this->connection = Connection::GetInstance();
            $result = $this->connection->Execute($query);

            return $this->connection->lastInsertId();
    
        } catch (Exception $ex) {
            error_log("Error en newGroupInfo: " . $ex->getMessage());
            throw $ex;
        }
    }
    //Busca la info del grupo por id
    public function getGroupInfoById($id){
        if($id){
            try{
                $query = "SELECT * FROM " .$this->groupInfoTable. " WHERE id = $id";
                $this->connection = Connection::GetInstance();
                $resultSet=$this->connection->Execute($query);
                if($resultSet){
                    foreach($resultSet as $row){
                        $groupInfo = new GroupInfo();
                        $groupInfo->setId($row['id']);
                        $groupInfo->setDescription($row['description']);
                        $groupInfo->setRules($row['rules']);
                        $groupInfo->setImage($row['image']);
                        $groupInfo->setCreatedAt($row['created_at']);
                        $groupInfo->setUpdatedAt($row['updated_at']);
                        $groupInfo->setStartDate($row['start_date']);
                        $groupInfo->setEndDate($row['end_date']);
                    }
                    return $groupInfo;
                }else{
                    return false;
                }
            }catch(Exeption $ex){
                throw $ex;
            }
        }else{
            return false;
        }
    }
    //Metodo para admin (Evaluar el alcance de este caso)
    public function getAllGroupInfo($userRole){
        if($userRole==='1'){
                try{
                    $query = "SELECT * FROM " .$this->groupInfoTable. "order by id asc";
                    $parameters['groupID']=$groupID;
                    $this->connection = Connection::GetInstance();
                    $resultSet=$this->connection->Execute($query, $parameters);
                    if($resultSet){
                        $groupInfoArray=array();
                        foreach($resultSet as $row){
                            $groupInfo = new GroupInfo();
                            $groupInfo->setId($row['id']);
                            $groupInfo->setGroupId($row['group_id']);
                            $groupInfo->setDescription($row['description']);
                            $groupInfo->setRules($row['rules']);
                            $groupInfo->setImage($row['image']);
                            $groupInfo->setCreatedAt($row['created_at']);
                            $groupInfo->setUpdatedAt($row['updated_at']);
                            $groupInfo->setStartDate($row['start_date']);
                            $groupInfo->setEndDate($row['end_date']);
                            array_push($groupInfoArray,$groupInfo);
                        }
                        return $groupInfoArray;
                    }
                }catch(Exeption $ex){
                    throw $ex;
                }
        }else{
            return;
        }
    }
    public function getCurrentGroupImage($id){
        if($id){
            try{
                $query = "SELECT image FROM " .$this->groupInfoTable. " WHERE id = :id";
                $parameters['id']= $id;
                $this->connection = Connection::GetInstance();
                $resultSet=$this->connection->Execute($query,$parameters);
                if($resultSet){
                    foreach($resultSet as $row){
                        $groupInfoImage = $row['image'];
                    }
                    return $groupInfoImage;
                }else{
                    return '';
                }
            }catch(Exeption $ex){
                throw $ex;
            }
        }else{
            return false;
        }
    }
    public function updateGroupInfo(GroupInfo $groupInfo, $groupInfoId) {
        try {
            $query = "UPDATE " .$this->groupInfoTable . " SET 
                        description = :description, 
                        rules = :rules, 
                        image = :image, 
                        start_date = :start_date, 
                        end_date = :end_date,
                        updated_at = CURRENT_TIMESTAMP
                    WHERE id = :groupInfoId";
    
            $parameters = array();
            $parameters["description"] = $groupInfo->getDescription();
            $parameters["rules"] = $groupInfo->getRules();
            $parameters["image"] = $groupInfo->getImage();
            $parameters["start_date"] = $groupInfo->getStartDate();
            $parameters["end_date"] = $groupInfo->getEndDate();
            $parameters["groupInfoId"] = $groupInfoId;
    
            $this->connection = Connection::GetInstance();
            $result =$this->connection->Execute($query, $parameters);
            if($result>0){
                return true;
            }else{
                return false;
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    public function updateGroupInfoDescription($groupInfoId,$newDescription){
        try {
            $query = "UPDATE " .$this->groupInfoTable . " SET description = :description WHERE id = :groupInfoId";
    
            $parameters["description"] = $newDescription;
            $parameters["groupInfoId"] = $groupInfoId;
    
            $this->connection = Connection::GetInstance();
            $resultSet=$this->connection->Execute($query, $parameters);
            if(is_array($resultSet)){
                return true;
            }else{
                return false;
            }
    
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    public function updateGroupInfoRules($groupInfoId,$newRules){
        try {
            $query = "UPDATE " .$this->groupInfoTable . " SET rules = :newRules WHERE id = :groupInfoId";
    
            //$parameters = array();
            $parameters["newRules"] = $newRules;
            $parameters["groupInfoId"] = $groupInfoId;
    
            $this->connection = Connection::GetInstance();
            $resultSet=$this->connection->Execute($query, $parameters);
            if(is_array($resultSet)){
                return true;
            }else{
                return false;
            }
    
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    public function updateGroupInfoDates($groupInfoId,$startDate,$endDate){
        try {
            $query = "UPDATE " .$this->groupInfoTable . " SET start_date = :startDate, end_date = :endDate
            WHERE id = :groupInfoId";
            $parameters["startDate"] = $startDate;
            $parameters["endDate"] = $endDate;
            $parameters["groupInfoId"] = $groupInfoId;
    
            $this->connection = Connection::GetInstance();
            $resultSet= $this->connection->Execute($query, $parameters);
            if(is_array($resultSet)){
                return true;
            }else{
                return false;
            }
    
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    public function updateGroupInfoImages($groupInfoId,$newPatchImage){
        try {
            $query = "UPDATE " .$this->groupInfoTable . " SET image = :newPatchImage WHERE id = :groupInfoId";
            $parameters["newPatchImage"] = $newPatchImage;
            $parameters["groupInfoId"] = $groupInfoId;
        
            $this->connection = Connection::GetInstance();
            $resultSet= $this->connection->Execute($query, $parameters);
            if(is_array($resultSet)){
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