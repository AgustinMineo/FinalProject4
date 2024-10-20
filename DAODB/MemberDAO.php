<?php
namespace DAODB;
use \Exception as Exception;
use DAODB\Connect as Connect;

use Models\Member as Member;

use DAODB\UserDAO as UserDAO;
use DAODB\GroupDAO as GroupDAO;
use DAODB\GroupRoleDAO as GroupRoleDAO;

class MemberDAO{
    private $connection;
    private $groupMemberTable = 'group_members';
    private $groupTable = 'groups';

    private $UserDAO;
    private $GroupRoleDAO;
    private $GroupDAO;

    public function __construct() {
        $this->GroupRoleDAO = new GroupRoleDAO();
        $this->GroupDAO = new GroupDAO();
        $this->UserDAO = new UserDAO();
    }

    public function newMember(Member $newMember){
        try{

            $query = "INSERT INTO " .$this->groupMemberTable. " 
            (group_id,user_id,status,role) VALUES
            (:group_id,:user_id,:status,:role)
            ";
            $parameters['group_id']=$newMember->getGroupID();
            $parameters['user_id']=$newMember->getUser();
            $parameters['status']=$newMember->getStatus();
            $parameters['role']=$newMember->getRole();
            
            $this->connection = Connection::GetInstance();
            return $this->connection->Execute($query, $parameters);
    
        }catch(Exception $ex){
            throw $ex;
        }
    }
    public function getMembersByGroup($groupID){
        try{
            $query = "SELECT gm.id,gm.group_id,gm.user_id,gm.joined_at,gm.status,gm.role 
            FROM " .$this->groupMemberTable. " gm JOIN " .$this->groupTable ." g
            ON g.id = gm.group_id WHERE g.id=:groupID;
            ";
            $parameters['groupID']=$groupID;
            
            $this->connection = Connection::GetInstance();
            $resultSet=$this->connection->Execute($query, $parameters);
            if($resultSet){
                $memberArray=array();
                foreach($resultSet as $row){
                    $user = $this->UserDAO->getUserByIdReduce($row['user_id']);
                    $group= $this->GroupDAO->getGroupByID($row['group_id']);
                    $group_role=$this->GroupRoleDAO->getGroupRoleById($row['role']);
                    $member = new Member();
                    $member->setId($row['id']);
                    $member->setGroupID($group);
                    $member->setUser($user);
                    $member->setJoinedAt($row['joined_at']);
                    $member->setStatus($row['status']);
                    $member->setRole($group_role);
                    array_push($memberArray,$member);
                }
                return $memberArray;
            }else{
                return [];
            }
        }catch(Exception $ex){
            throw $ex;
        }
    }
    public function deleteMember($groupID,$userID){
        try{
            $query = "UPDATE " .$this->groupMemberTable. " SET status = 0 
            WHERE user_id=:userID AND group_id=:groupID;
            ";
            $parameters['userID']=$userID;
            $parameters['groupID']=$groupID;
            
            $this->connection = Connection::GetInstance();
            return $this->connection->Execute($query, $parameters);
        }catch(Exception $ex){
            throw $ex;
        }
    }
    public function reactivateMember($groupID,$userID){
        try{
            $query = "UPDATE " .$this->groupMemberTable. " SET status = 1 
            WHERE user_id=:userID AND group_id=:groupID;
            ";
            $parameters['userID']=$userID;
            $parameters['groupID']=$groupID;
            
            $this->connection = Connection::GetInstance();
            return $this->connection->Execute($query, $parameters);
        }catch(Exception $ex){
            throw $ex;
        }
    }
    public function updateMemberRole($userID,$role,$groupID){
        try{
            $query = "UPDATE " .$this->groupMemberTable. " SET role = :role 
            WHERE user_id = :userID AND group_id = :groupID";
            $parameters['userID']=$userID;
            $parameters['groupID']=$groupID;
            $parameters['role']=$role;
            $this->connection = Connection::GetInstance();
            return $this->connection->Execute($query, $parameters);
        }catch(Exception $ex){
            throw $ex;
        }
    }
    public function getMemberByUserAndGroupId($groupID,$user_id){
        try{
            $query = "SELECT gm.id,gm.group_id,gm.user_id,gm.joined_at,gm.status,gm.role 
            FROM " .$this->groupMemberTable. " gm JOIN " .$this->groupTable ." g
            ON g.id = gm.group_id WHERE g.id=:groupID AND gm.user_id = :user_id AND gm.status !=1;
            ";
            $parameters['groupID']=$groupID;
            $parameters['user_id']=$user_id;
            
            $this->connection = Connection::GetInstance();
            $resultSet=$this->connection->Execute($query, $parameters);
            if($resultSet){
                foreach($resultSet as $row){
                    $user = $this->UserDAO->getUserByIdReduce($row['user_id']);
                    $group= $this->GroupDAO->getGroupByID($row['group_id']);
                    $group_role=$this->GroupRoleDAO->getGroupRoleById($row['role']);
                    $member = new Member();
                    $member->setId($row['id']);
                    $member->setGroupID($group);
                    $member->setUser($user);
                    $member->setJoinedAt($row['joined_at']);
                    $member->setStatus($row['status']);
                    $member->setRole($group_role);
                }
                return $member;
            }else{
                return false;
            }
        }catch(Exception $ex){
            throw $ex;
        }
    }
    public function getUserRoleByGroup($groupID,$user_id){
        try{
            $query = "SELECT gm.role 
            FROM " .$this->groupMemberTable. " gm 
            WHERE gm.group_id=:groupID AND gm.user_id = :user_id AND gm.status =1;";
            $parameters['groupID']=$groupID;
            $parameters['user_id']=$user_id;
            
            $this->connection = Connection::GetInstance();
            $resultSet=$this->connection->Execute($query, $parameters);
            if($resultSet){
                foreach($resultSet as $row){
                    $role=$row['role']; 
                }
                return $role;
            }else{
                return false;
            }
        }catch(Exception $ex){
            throw $ex;
        }
    }
    public function getGroupOwnerByGroupId($groupID){
        try{
            $query = "SELECT gm.id,gm.group_id,gm.user_id,gm.joined_at,gm.status,gm.role 
            FROM " .$this->groupMemberTable. " gm JOIN " .$this->groupTable ." g
            ON g.id = gm.group_id WHERE g.id=:groupID and gm.role=1;
            ";
            $parameters['groupID']=$groupID;
            
            $this->connection = Connection::GetInstance();
            $resultSet=$this->connection->Execute($query, $parameters);
            if($resultSet){
                foreach($resultSet as $row){
                    $user = $this->UserDAO->getUserByIdReduce($row['user_id']);
                    $group= $this->GroupDAO->getGroupByID($row['group_id']);
                    $group_role=$this->GroupRoleDAO->getGroupRoleById($row['role']);
                    $member = new Member();
                    $member->setId($row['id']);
                    $member->setGroupID($group);
                    $member->setUser($user);
                    $member->setJoinedAt($row['joined_at']);
                    $member->setStatus($row['status']);
                    $member->setRole($group_role);
                }
                return $member;
            }else{
                return [];
            }
        }catch(Exception $ex){
            throw $ex;
        }
    }
    public function getOwnerIdByGroupId($groupID){
        try{
            $query = "SELECT gm.user_id 
            FROM " .$this->groupMemberTable. " gm WHERE gm.group_id=:groupID and gm.role=1;
            ";
            $parameters['groupID']=$groupID;
            
            $this->connection = Connection::GetInstance();
            $resultSet=$this->connection->Execute($query, $parameters);
            if($resultSet){
                foreach($resultSet as $row){
                   $userID = $row['user_id'];
                }
                return $userID;
            }else{
                return [];
            }
        }catch(Exception $ex){
            throw $ex;
        }
    }
    public function getMembersIdsByGroup($groupID){
        try{
            $query = "SELECT user_id
            FROM " .$this->groupMemberTable. " WHERE group_id=:groupID AND status!=0;";
            $parameters['groupID']=$groupID;
            
            $this->connection = Connection::GetInstance();
            $resultSet=$this->connection->Execute($query, $parameters);
            if($resultSet){
                $memberArray=array();
                foreach($resultSet as $row){
                    $userID =$row['user_id'];
                    array_push($memberArray,$userID);
                }
                return $memberArray;
            }else{
                return [];
            }
        }catch(Exception $ex){
            throw $ex;
        }
    }
}
?>