<?php
namespace DAODB;

use Helper\SessionHelper as SessionHelper;
use \PDO as PDO;
use \Exception as Exception;
//Models
use Models\Group as Group;
use Models\Owner as Owner;
use Models\Keeper as Keeper;

//Daos
use DAODB\QueryType as QueryType;
use DAODB\UserDAO as UserDAO;
/*use DAODB\OwnerDAO as OwnerDAO;
use DAODB\KeeperDAO as KeeperDAO;*/
use DAODB\GroupStatusDAO as GroupStatusDAO;
use DAODB\GroupPrivacyDAO as GroupPrivacyDAO;
use DAODB\GroupRoleDAO as GroupRoleDAO;
use DAODB\GroupTypeDAO as GroupTypeDAO;
use DAODB\GroupInfoDAO as GroupInfoDAO;

class GroupDAO{
    private $GroupStatusDAO;
    private $GroupPrivacyDAO;
    private $GroupRoleDAO;
    private $GroupTypeDAO;
    private $GroupInfoDAO;
    private $groupTable = 'groups';
    private $messageTable='messages';
    private $groupMemberTable = 'group_members';

    public function __construct() {
        $this->GroupTypeDAO = new GroupTypeDAO();
        $this->GroupStatusDAO = new GroupStatusDAO();
        $this->GroupPrivacyDAO = new GroupPrivacyDAO();
        $this->GroupRoleDAO = new GroupRoleDAO();
        $this->GroupInfoDAO = new GroupInfoDAO();
        $this->UserDAO = new UserDAO();
    }
    
    //Crea un grupo nuevo y asigna al usuario que lo creo.
    public function newGroup(Group $newGroup) {
        if ($newGroup) {
            try {
                $query = "INSERT INTO " . $this->groupTable . " (created_by, name, group_type, 
                status_id,group_privacy,groupInfo_id) 
                VALUES (:created_by, :name, :group_type, :status_id,:group_privacy,:groupInfo_id)";
                $parameters['created_by'] = $newGroup->getCreated_by();
                $parameters['name'] = $newGroup->getName();
                $parameters['group_type'] = $newGroup->getGroupType();
                $parameters['status_id'] = 1; // 1 = activo
                $parameters['group_privacy']=$newGroup->getGroupPrivacy();
                $parameters['groupInfo_id']=$newGroup->getGroupInfo();
                $this->connection = Connection::GetInstance();
                
                $this->connection->Execute($query, $parameters);
                return $this->connection->lastInsertId(); 
            } catch (Exception $ex) {
                error_log("Error en newGroup: " . $ex->getMessage());
                throw $ex; 
            }
        }
        return false;
    }
    
    //Trae los grupos en base al usuario actual
    public function getGroupsByUser($currentUser){
        $currentUserId = $currentUser->getUserId();
        if($currentUserId){
            try{ 
                $query = "SELECT DISTINCT g.id, g.name, g.created_by, g.created_at, g.group_type, g.status_id, g.group_privacy, g.groupInfo_id, m.sent_at
                        FROM " . $this->groupTable . " g
                        LEFT JOIN " . $this->groupMemberTable . " gm ON gm.group_id = g.id
                        LEFT JOIN (
                            SELECT m1.group_id, MAX(m1.sent_at) as sent_at
                            FROM " . $this->messageTable . " m1
                            GROUP BY m1.group_id
                        ) m ON m.group_id = g.id
                        WHERE gm.user_id = :currentUser 
                        AND g.status_id IN (1, 4) 
                        AND gm.status = 1
                            
                        UNION
                            
                        SELECT DISTINCT g.id, g.name, g.created_by, g.created_at, g.group_type, g.status_id, g.group_privacy, g.groupInfo_id, NULL as sent_at
                        FROM " . $this->groupTable . " g
                        RIGHT JOIN (
                            SELECT m1.group_id, MAX(m1.sent_at) as sent_at
                            FROM " . $this->messageTable . " m1
                            GROUP BY m1.group_id
                        ) m ON m.group_id = g.id
                        LEFT JOIN " . $this->groupMemberTable . " gm ON gm.group_id = g.id
                        WHERE gm.user_id = :currentUser 
                        AND g.status_id IN (1, 4) 
                        AND gm.status = 1
                        AND g.id NOT IN (
                            SELECT g1.id
                            FROM " . $this->groupTable . " g1
                            LEFT JOIN " . $this->groupMemberTable . " gm1 ON gm1.group_id = g1.id
                            WHERE gm1.user_id = :currentUser 
                            AND g1.status_id IN (1, 4) 
                            AND gm1.status = 1
                        )
                        ORDER BY sent_at DESC";

                $parameters['currentUser']=$currentUserId;
                $this->connection = Connection::GetInstance();
                $resultSet = $this->connection->Execute($query,$parameters);
                $groupList = array();
                
                foreach ($resultSet as $row) {
                    $ownerGroup = $this->UserDAO->getUserByIdReduce($row['created_by']);
                    $group=$this->buildGroupByRow($row,$ownerGroup);
                    array_push($groupList, $group);
                }
                return $groupList;
            }catch(Exception $ex){
                throw $ex;
            }
        }
    }
    //Se agrego validacion por estado
    public function getGroupByID($groupID){
        if($groupID){
            try{ 
                $query = "SELECT DISTINCT id,name,created_by,
                created_at,group_type,status_id,group_privacy,groupInfo_id FROM " 
                . $this->groupTable . " WHERE id = $groupID AND status_id in (1,4)";
                
                $this->connection = Connection::GetInstance();
                $resultSet = $this->connection->Execute($query);
                if($resultSet){
                    foreach ($resultSet as $row) {
                        $currentUser = $this->UserDAO->getUserByIdReduce($row['created_by']);
                        $group=$this->buildGroupByRow($row,$currentUser);
                    }

                }
                
                return $group;
            }catch(Exception $ex){
                throw $ex;
            }
        }
    }
    public function getAllGroups(){
        try{
            $query = "SELECT * FROM " 
            . $this->groupTable . "
                order by created_at desc";

                $this->connection = Connection::GetInstance();
                $resultSet = $this->connection->Execute($query, $parameters);

                $groupUser = array();

                foreach ($resultSet as $row) {
                    $user = $this->UserDAO->getUserByIdReduce($row["chat_user_id"]);
                    if ($user) {
                        array_push($groupUser, $user);
                    }
                }

        return $chatUsers;
        }catch(Exception $ex){
            throw $ex;
        }
    }
    public function updateGroup(Group $modifyGroup){
        if ($modifyGroup) {
            try {
                $query = "UPDATE " . $this->groupTable . " 
                        SET name = :name, group_type = :group_type, group_privacy = :group_privacy,
                        groupInfo_id = :groupInfo_id WHERE id = :id";
    
                $parameters['id'] = $modifyGroup->getId();
                $parameters['name'] = $modifyGroup->getName();
                $parameters['group_type'] = $modifyGroup->getGroupType();
                $parameters['group_privacy'] = $modifyGroup->getGroupPrivacy();
                $parameters['groupInfo_id'] = $modifyGroup->getGroupInfo(); 
                $this->connection = Connection::GetInstance();
                $this->connection->Execute($query, $parameters);
    
                return true; 
            } catch (Exception $ex) {
                throw $ex; 
            }
        }
        return false; 
    }
    private function buildGroupByRow($row,$user){
        $groupType = $this->GroupTypeDAO->getGroupTypeById($row["group_type"]);
        $groupPrivacy = $this->GroupPrivacyDAO->getGroupPrivacyById($row["group_privacy"]);
        $groupStatus = $this->GroupStatusDAO->getGroupStatusById($row["status_id"]);
        $groupInfo = $this->GroupInfoDAO->getGroupInfoById($row["groupInfo_id"]);
    
        $group = new Group();
        $group->setId($row["id"]);
        $group->setName($row["name"]);
        $group->setCreated_at($row["created_at"]);
        $group->setCreated_by($user);
        $group->setGroupType($groupType);
        $group->setGroupPrivacy($groupPrivacy);
        $group->setStatusId($groupStatus);
        $group->setGroupInfo($groupInfo);
        return $group;
    }
    public function deleteGroup($groupID,$userID){
        try{
            $query = "UPDATE " . $this->groupTable . " 
                        SET status_id = 3 WHERE id = :groupID AND created_by = :userID AND status_id = 1";

            $parameters['groupID'] = $groupID;
            $parameters['userID'] = $userID;
            $this->connection = Connection::GetInstance();
            return $this->connection->Execute($query, $parameters);
        }catch(Exepction $ex){
            throw $ex;
        }
    }
    public function getPublicGroups($userID){
        try{
            $query = "SELECT g.* FROM " 
            . $this->groupTable . " g WHERE g.id NOT IN 
            (SELECT group_id FROM " . $this->groupMemberTable . " WHERE user_id = :userID) 
            AND g.group_privacy in (1,3,5) AND g.status_id=1 order by g.created_at desc";

                $parameters['userID']=$userID;
                $this->connection = Connection::GetInstance();
                $resultSet = $this->connection->Execute($query,$parameters);
                if($resultSet){
                    $groups = array();
                        foreach ($resultSet as $row) {
                            $currentUser = $this->UserDAO->getUserByIdReduce($row['created_by']);
                            $group=$this->buildGroupByRow($row,$currentUser);
                            array_push($groups,$group->toArray());
                        }
                    return $groups;
                }else{
                    return [];
                }

        }catch(Exepction $ex){
            throw $ex;
        }
    }
    public function updateGroupPrivacy($groupID,$newGroupPrivacy){
        try {
            $query = "UPDATE " . $this->groupTable . " 
                    SET group_privacy = :newGroupPrivacy WHERE id = :groupID";

            $parameters['groupID'] = $groupID;
            $parameters['newGroupPrivacy'] = $newGroupPrivacy;
            $this->connection = Connection::GetInstance();
            $this->connection->Execute($query, $parameters);

            return true; 
        } catch (Exception $ex) {
            throw $ex; 
        }
    }
    public function updateGroupType($groupID,$newGroupType){
        try {
            $query = "UPDATE " . $this->groupTable . " 
                    SET group_type = :newGroupType WHERE id = :groupID";

            $parameters['groupID'] = $groupID;
            $parameters['newGroupType'] = $newGroupType;
            $this->connection = Connection::GetInstance();
            $this->connection->Execute($query, $parameters);

            return true; 
        } catch (Exception $ex) {
            throw $ex; 
        }
    }
    public function updateGroupName($groupID,$newName){
        try {
            $query = "UPDATE " . $this->groupTable . " 
                    SET name = :newName WHERE id = :groupID";

            $parameters['groupID'] = $groupID;
            $parameters['newName'] = $newName;
            $this->connection = Connection::GetInstance();
            $this->connection->Execute($query, $parameters);

            return true; 
        } catch (Exception $ex) {
            throw $ex; 
        }
    }
    public function GetGroupInfoIDByGroupID($groupID){
        if($groupID){
            try{ 
                $query = "SELECT groupInfo_id FROM " 
                . $this->groupTable . " WHERE id = $groupID";
                
                $this->connection = Connection::GetInstance();
                $resultSet = $this->connection->Execute($query);
                if($resultSet){
                    foreach ($resultSet as $row) {
                        $groupInfoID =$row['groupInfo_id'];
                    }
                    return $groupInfoID;
                }
                return null;
            }catch(Exception $ex){
                throw $ex;
            }
        }
    }

}
?>