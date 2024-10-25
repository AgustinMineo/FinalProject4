<?php
namespace Controllers;

use Exception;
use DAODB\UserDAO as UserDAO;
use DAODB\MemberDAO as MemberDAO;
use Models\Member as Member;
use DAODB\GroupInvitationDAO as GroupInvitationDAO;

class GroupInvitationController{

    private $GroupInvitationDAO;
    private $UserDAO;
    private $MemberDAO;


    public function __construct() {
        $this->GroupInvitationDAO = new GroupInvitationDAO();
        $this->UserDAO = new UserDAO();
        $this->MemberDAO = new MemberDAO();
    }

    public function sendInvitation($groupID, $invitedBy, $invitedUser, $message = null, $roleInvited) {
        if($groupID && $invitedBy && $invitedUser && $roleInvited){
            try {
                    $user = $this->UserDAO->searchUserByEmail($invitedUser);
                    if($user){
                        $invitationSend = $this->GroupInvitationDAO->getInvitationByGroupID($groupID,$user->getUserID(),1);//Valido que no exista una invitación
                        if(!$invitationSend){
                            $alreadyExists= $this->MemberDAO->getMemberByUserAndGroupId($groupID,$user->getUserID());
                            if($alreadyExists){
                                echo json_encode(["success" => false, "message" => "Error, el usuario ya esta en el grupo."]);
                            }else{
                                $statusId = 1;
                                $statusInvitation=$this->GroupInvitationDAO->sendInvitation($groupID, $invitedBy, $user->getUserID(), $statusId, $message,$roleInvited);
                                if(is_null($statusInvitation)){
                                    echo json_encode(["success" => false, "message" => "Error al enviar la Invitación."]);
                                }else{
                                    echo json_encode(["success" => true, "message" => "Invitación enviada."]);
                                }
                            }
                        }else{
                            echo json_encode(["success" => false, "message" => "El usuario ya tiene una invitación existente"]);
                            return; 
                        }
                    }else{
                        echo json_encode(["success" => false, "message" => "El usuario no existe"]);
                        return;
                    }
                } catch (Exception $ex) {
                    echo json_encode(["success" => false, "message" => $ex->getMessage()]);
                }
        }else{
            echo json_encode(["success" => false, "message" => "Error al enviar la Invitación"]);
            return;
        }
    }

    public function acceptInvitation($invitationId,$groupID,$userID,$roleInvited) {
        try {
            $value = $this->GroupInvitationDAO->acceptInvitation($invitationId);
            $newMember = new Member();
            $newMember->setGroupID($groupID);
            $newMember->setUser($userID);
            $newMember->setStatus(1);
            $newMember->setRole($roleInvited);
            $result = $this->MemberDAO->newMember($newMember);
            if($result){
                echo json_encode(["success" => true, "message" => "Invitación aceptada."]);
            }else{
                echo json_encode(["success" => false, "message" => "Error al asignar el usuario al grupo."]);
            }
        } catch (Exception $e) {
            echo json_encode(["success" => false, "message" => $e->getMessage()]);
        }
    }
    public function rejectInvitation($invitationId) {
        try {
            $this->GroupInvitationDAO->rejectInvitation($invitationId);
            echo json_encode(["success" => true, "message" => "Invitación rechazada."]);
        } catch (Exception $e) {
            echo json_encode(["success" => false, "message" => $e->getMessage()]);
        }
    }

    // Obtener todas las invitaciones del usuario actual
    public function getUserInvitations($userId) {
        try {
            $invitations = $this->GroupInvitationDAO->getPendingInvitations($userId);
            if($invitations){
                echo json_encode($invitations);
                return;
            }else{
                echo json_encode([]); 
            }
        } catch (Exception $e) {
            echo json_encode(["success" => false, "message" => $e->getMessage()]);
        }
    }
    public function getInvitationsByGroup($groupID) {
        if(!$groupID){
            echo json_encode(['success' => false, 'message' => 'El campo groupID es obligatorio.']);
            return;
        }
        $invitations = $this->GroupInvitationDAO->getPendingInvitationsByGroup($groupID);
        if ($invitations) {
            echo json_encode(['success' => true, 'invitations' => $invitations]);
        } else {
            echo json_encode(['success' => false, 'message' => 'No se encontraron invitaciones.']);
        }
    }
    
}
?>