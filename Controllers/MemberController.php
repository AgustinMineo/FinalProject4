<?php
namespace Controllers;

use Models\Member as Member;
use Helper\SessionHelper as SessionHelper;

use DAODB\MemberDAO as MemberDAO;
use DAODB\GroupDAO as GroupDAO;
use DAODB\UserDAO as UserDAO;
use DAODB\OwnerDAO as OwnerDAO;
use DAODB\KeeperDAO as KeeperDAO;
class MemberController{

    private $GroupDAO;
    private $MemberDAO;
    private $UserDAO;
    private $OwnerDAO;
    private $KeeperDAO;

    private $groupTable = 'group_members';
    public function __construct(){
        $this->GroupDAO = new GroupDAO();
        $this->MemberDAO = new MemberDAO();
        $this->KeeperDAO = new KeeperDAO();
        $this->UserDAO = new UserDAO();
        $this->OwnerDAO = new OwnerDAO();
    }

    public function getAllMembersByGroup($groupID) {
        if ($groupID) {
            $groupMembers = $this->MemberDAO->getMembersByGroup($groupID);

            $membersArray = [];
            foreach ($groupMembers as $member) {
                $membersArray[] = $member->toArray();//Los convierto a arreglos
            }
            echo json_encode($membersArray); 
            return;
        } else {
            echo json_encode([]);
            return;
        }
    }

    public function getUserRoleByGroup($groupID,$userID){
        if($groupID && $userID){
            $role= $this->MemberDAO->getUserRoleByGroup($groupID,$userID);
            if($role){
                echo json_encode(["success" => true, "role" => $role]); 
                return;
            }else{
                echo json_encode(["success" => false, "message" => "Rol no encontrado"]);
                return;
            }
        }
    }
    
    public function modifyMemberRole($memberId,$role,$groupID){
        if($memberId && $role){
            if($role!=1){//Si el role es distinto a dueño, actualizo el usuario solo
                $result = $this->MemberDAO->updateMemberRole($memberId,$role,$groupID);
                if($result){
                    echo json_encode(["success" => true, "Se actualizo el role del usuario"]); 
                    return;
                }else{
                    echo json_encode(["success" => false, "Error al actualizar el role del usuario"]); 
                    return;
                }
            }else{
                //Tomo el antiguo dueño, actualizo el nuevo dueño y actualizo el antiguo dueño a administrador.
                $oldOwner = $this->MemberDAO->getOwnerIdByGroupId($groupID);
                $result = $this->MemberDAO->updateMemberRole($memberId,$role,$groupID);
                $changeOwner = $this->MemberDAO->updateMemberRole($oldOwner,2,$groupID);
                $newOwner = $this->GroupDAO->updateGroupOwner($groupID,$memberId);
                if($result && $changeOwner && $newOwner){
                    echo json_encode(["success" => true, "Se actualizo el nuevo dueño del grupo"]); 
                    return;
                }else{
                    echo json_encode(["success" => false, "Error al actualizar el nuevo dueño del grupo"]); 
                    return;
                }
            }
        }else{
            echo json_encode(["success" => false, "Error, faltan campos"]); 
            return;
        }
    }

    public function joinPublicGroup($groupID,$userID){
        if($groupID && $userID){
            $newMember = new Member();
            $newMember->setGroupID($groupID);
            $newMember->setUser($userID);
            $newMember->setStatus(1);
            $newMember->setRole(3);
            $result = $this->MemberDAO->newMember($newMember);
            if (!is_null($result)) {
                echo json_encode(["success" => true, "message" => "¡Bienvenido al grupo!"]);
            } else {
                echo json_encode(["success" => false, "message" => "No se pudo unir al grupo, por favor intente más tarde."]);
            }
        }
    }
    public function deleteMember($groupID,$userID){
        if($groupID && $userID){
            $result = $this->MemberDAO->deleteMember($groupID,$userID);
            if (!is_null($result)) {
                echo json_encode(["success" => true, "message" => "¡El usuario fue eliminado correctamente!"]);
            } else {
                echo json_encode(["success" => false, "message" => "No se pudo eliminar el usuario, por favor intente más tarde."]);
            }
        }
    }
    public function reactivateMember($groupID,$userID){
        if($groupID && $userID){
            $result = $this->MemberDAO->reactivateMember($groupID,$userID);
            if (!is_null($result)) {
                echo json_encode(["success" => true, "message" => "¡El usuario fue reactivado correctamente!"]);
            } else {
                echo json_encode(["success" => false, "message" => "No se pudo reactivar el usuario, por favor intente más tarde."]);
            }
        }
    }
}
?>