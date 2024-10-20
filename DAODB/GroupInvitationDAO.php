<?php
namespace DAODB;

use Exception;
use Helper\SessionHelper as SessionHelper;

use Models\GroupInvitation;
use Models\Group;
use DAODB\Connection;
use DAODB\GroupDAO as GroupDAO;
use DAODB\UserDAO as UserDAO;
use DAODB\GroupRoleDAO as GroupRoleDAO;
use DAODB\GroupInvitationStatusDAO as GroupInvitationStatusDAO;
class GroupInvitationDAO {

    private $connection;
    private $GroupDAO;
    private $UserDAO;
    private $groupInvitationTable = "group_invitations";

    public function __construct() {
        $this->connection = Connection::GetInstance();
        $this->GroupDAO = new GroupDAO();
        $this->UserDAO = new UserDAO();
        $this->GroupRoleDAO= new GroupRoleDAO();
        $this->GroupInvitationStatusDAO = new GroupInvitationStatusDAO();
    }
    // Enviar invitacion
    public function sendInvitation($groupId, $invitedBy, $invitedUserId, $status_id, $message,$roleInvited) {
        try {
            $query = "INSERT INTO " . $this->groupInvitationTable . " (group_id, invited_by, 
            invited_user_id, status_id, message,roleInvited) VALUES 
            (:group_id, :invited_by, :invited_user_id, :status_id, :message,:roleInvited)"; 
            
            $parameters['group_id']=$groupId;
            $parameters['invited_by']=$invitedBy;
            $parameters['invited_user_id']=$invitedUserId;
            $parameters['status_id']=$status_id;
            $parameters['message']=$message;
            $parameters['roleInvited']=$roleInvited;

            $resultSet=$this->connection->Execute($query, $parameters);
            if(is_array($resultSet)){
                return true;
            }else{
                return null;
            }
        } catch (Exception $ex) {
            error_log("Error al enviar invitación: " . $ex->getMessage());
            throw $ex;
        }
    }
    //Obtiene las invitaciones activas para el usuario enviado.
    public function getPendingInvitations($invitedUserId) {
        try {
                    $query = "SELECT gi.*
                    FROM " . $this->groupInvitationTable . " gi
                    JOIN groups g ON g.id = gi.group_id
                    JOIN user u ON u.userID = gi.invited_by
                    WHERE gi.invited_user_id = :invited_user_id AND gi.status_id = 1";

            $parameters = ["invited_user_id" => $invitedUserId];

            $resulSet=$this->connection->Execute($query, $parameters);
            if($resulSet){
                $invitationList=array();
                foreach($resulSet as $row){
                    $status = $this->GroupInvitationStatusDAO->getInvitationStatusById($row['status_id']);
                    $group = $this->GroupDAO->getGroupByID($row['group_id']);
                    $invitedBy = $this->UserDAO->getUserByIdReduce($row['invited_by']);
                    $roleInvited = $this->GroupRoleDAO->getGroupRoleById($row['roleInvited']);
                    $invitation = new GroupInvitation();
                    $invitation->setId($row['id']);
                    $invitation->setGroupId($group);
                    $invitation->setInvitedBy($invitedBy);
                    $invitation->setInvitedUser(SessionHelper::getCurrentUser());
                    $invitation->setStatus($status);
                    $invitation->setSendAt($row['sent_at']);
                    $invitation->setRespondeAt($row['responded_at']);
                    $invitation->setMessage($row['message']);
                    $invitation->setRoleInvited($roleInvited);
                    array_push($invitationList,$invitation->toArray());
                }
                return $invitationList;
            }else{
                return [];
            }
        } catch (Exception $ex) {
            error_log("Error al obtener invitaciones pendientes: " . $ex->getMessage());
            throw $ex;
        }
    }
    public function acceptInvitation($invitationId) {
        try {
            $query = "UPDATE " . $this->groupInvitationTable . "
                    SET status_id = 2, responded_at = CURRENT_TIMESTAMP
                    WHERE id = :invitation_id AND status_id = 1";

            $parameters = ["invitation_id" => $invitationId];

            return $this->connection->Execute($query, $parameters);
        } catch (Exception $ex) {
            error_log("Error al aceptar invitación: " . $ex->getMessage());
            throw $ex;
        }
    }
    public function rejectInvitation($invitationId) {
        try {
            $query = "UPDATE " . $this->groupInvitationTable . "
                    SET status_id = 3, responded_at = CURRENT_TIMESTAMP
                    WHERE id = :invitation_id AND status_id = 1"; 

            $parameters = ["invitation_id" => $invitationId];

            return $this->connection->Execute($query, $parameters);
        } catch (Exception $ex) {
            error_log("Error al rechazar invitación: " . $ex->getMessage());
            throw $ex;
        }
    }
    public function getInvitationByGroupID($groupID,$invited_user_id,$status_id){
        try {
            $query = "SELECT gi.id
                    FROM " . $this->groupInvitationTable . " gi
                    WHERE gi.group_id = :group_id AND gi.status_id = :status_id 
                    AND invited_user_id=:invited_user_id";

            $parameters['group_id'] = $groupID;
            $parameters['status_id'] = $status_id;
            $parameters['invited_user_id'] = $invited_user_id;
            
            return $this->connection->Execute($query, $parameters);
        } catch (Exception $ex) {
            error_log("Error al obtener invitaciones pendientes: " . $ex->getMessage());
            throw $ex;
        }
    }

}

?>