<?php
namespace Controllers;
use Exception;
use Helper\SessionHelper as SessionHelper;

use Models\Message as Message;

use DAODB\GroupDAO as GroupDAO;
use DAODB\MessageDAO as MessageDAO;
use DAODB\GroupStatusDAO as GroupStatusDAO;
use DAODB\GroupPrivacyDAO as GroupPrivacyDAO;
use DAODB\GroupRoleDAO as GroupRoleDAO;
use DAODB\GroupTypeDAO as GroupTypeDAO;
use DAODB\GroupInvitationStatusDAO as GroupInvitationStatusDAO;
class MessageController{
    
    private $MessageDAO;
    private $GroupDAO;
    private $GroupStatusDAO;
    private $GroupPrivacyDAO;
    private $GroupRoleDAO;
    private $GroupTypeDAO;

    public function __construct() {
        $this->MessageDAO = new MessageDAO();
        $this->GroupDAO = new GroupDAO();
        $this->GroupStatusDAO = new GroupStatusDAO();
        $this->GroupPrivacyDAO = new GroupPrivacyDAO();
        $this->GroupRoleDAO = new GroupRoleDAO();
        $this->GroupTypeDAO = new GroupTypeDAO();
        $this->GroupInvitationStatusDAO = new GroupInvitationStatusDAO();
    }

    public function goChatView($chats,$statusList,$privacyList,$roleList,$typeList,$groupList,$groupInvitation){
        $currentUser = SessionHelper::getCurrentUser()->getUserID();
        $userRole=SessionHelper::InfoSession([1,2,3]);
        require_once(VIEWS_PATH."chatView.php");
    }

    // Enviar mensaje directo entre dos usuarios o a un grupo
    public function sendDirectMessage($receiverId = null, $message, $currentID, $groupId = null) {
        try {
            $senderId = (int) $currentID;
            $receiverId = (int) $receiverId; 
            $groupId = (int) $groupId; 
            error_log("sendDirectMessage: senderId: $senderId, receiverId: $receiverId, groupId: $groupId, message: $message");
            if ($receiverId === 0 && $groupId > 0) {
                $result = $this->MessageDAO->saveMessage($senderId, $message, null, $groupId);
            } elseif ($receiverId > 0) {
                $result = $this->MessageDAO->saveMessage($senderId, $message, $receiverId, null);
            } else {
                throw new Exception('Debe proporcionar un receiverId válido o un groupId.');
            }
    
            if ($result) {
                echo json_encode(['success' => true]);
            } else {
                throw new Exception('No se pudo enviar el mensaje.');
            }
        } catch (Exception $ex) {
            error_log("Error al enviar el mensaje: " . $ex->getMessage());
            echo json_encode(["status" => "error", "message" => $ex->getMessage()]);
            return;
        }
    }
    

    // Obtener mensajes de un chat directo
    public function getDirectMessages($receiverId) {
        if(SessionHelper::getCurrentUser()->getRol()!='3'){
            $senderId = SessionHelper::getCurrentOwnerID();
        }else{
            $senderId = SessionHelper::getCurrentKeeperID();
        }
        $messages = $this->MessageDAO->getMessages($senderId, $receiverId, null);
        return $messages;
    }

    // Obtener mensajes de un grupo
    public function getGroupMessages($groupId) {
        $messages = $this->MessageDAO->getMessagesByGroup($groupId);
        if ($messages === false) { 
            http_response_code(500);
            echo json_encode(['error' => 'Error al cargar los mensajes']);
            return;
        } else {
        echo json_encode($messages); 
        return;
        }
    }
    //Cargo datos para la vista del currentUser
    public function getChats(){
        $chats = $this->MessageDAO->getChatsByUserID(SessionHelper::getCurrentUser()->getUserID());
        $statusList=$this->GroupStatusDAO->getAllGroupStatus();
        $privacyList=$this->GroupPrivacyDAO->getAllGroupPrivacy();
        $roleList=$this->GroupRoleDAO->getAllGroupRole();
        $typeList=$this->GroupTypeDAO->getAllGroupType();
        $groupList = $this->GroupDAO->getGroupsByUser(SessionHelper::getCurrentUser());
        $groupInvitation = $this->GroupInvitationStatusDAO->getInvitationStatus();
        $this->goChatView($chats,$statusList,$privacyList,$roleList,$typeList,$groupList,$groupInvitation);
    }
    //Funcion preparada para enviar en formato al ajax
    public function getChatMessages($chatUserID) {
        $currentUserID = SessionHelper::getCurrentUser()->getUserID();
    
        $messages = $this->MessageDAO->getMessagesByUsers($currentUserID, $chatUserID);
        
        if ($messages ===false) {
            http_response_code(500);
        echo json_encode(['error' => 'Error al cargar los mensajes']);
        return;
        } else {
        echo json_encode($messages); // Devuelve los mensajes en formato JSON para AJAX
        return;
        }
    }
    public function getUsersWithoutChat(){
        try {
        $currentUserId = SessionHelper::getCurrentUser()->getUserID();
        $usersWithoutChat = $this->MessageDAO->getUsersWithoutChat($currentUserId);
        
        echo json_encode($usersWithoutChat);
        
        } catch (Exception $ex) {
            echo json_encode(array("error" => $ex->getMessage()));
        }
    }
    public function getUnreadMessages(){
        if(SessionHelper::getCurrentUser()->getUserID()){
            try{
                $totalMessages = $this->MessageDAO->getUnreadMessages(SessionHelper::getCurrentUser()->getUserID());
                $resultValue =array();
                foreach($totalMessages as $message){
                    $resultValue[] = $message;
                }
                
                if($totalMessages){
                    $_SESSION['messageCount']=$totalMessages;
                    echo json_encode($resultValue);
                    exit();
                }else{
                    $_SESSION['messageCount']=[];
                    echo json_encode([]);
                }
                exit();
            }catch(Exeption $e){
                echo json_encode(['error'=>'Error en la controller','message'=>$e->getMessage()]);
            }
        }
    }
    public function getUnreadMessagesGroup() {
        // Asegurarse de que el usuario esté autenticado
        if(SessionHelper::getCurrentUser()->getUserID()) {
            try {
                // Llamar al DAO para obtener los mensajes no leídos
                $totalMessages = $this->MessageDAO->getUnreadMessagesGroup(SessionHelper::getCurrentUser()->getUserID());
                $resultValue = [];
    
                // Verificar que el resultado sea un array válido
                if (is_array($totalMessages) && count($totalMessages) > 0) {
                    foreach($totalMessages as $message) {
                        $resultValue[] = $message;
                    }
    
                    // Actualizar la sesión con los mensajes no leídos del grupo
                    $_SESSION['messageCountGroup'] = $totalMessages;
                } else {
                    // Si no hay mensajes, vaciar la sesión
                    $_SESSION['messageCountGroup'] = [];
                }
    
                // Enviar la respuesta en formato JSON
                echo json_encode($resultValue);
                exit();
                
            } catch (Exception $e) {
                // Manejo de excepciones, asegurando una salida clara en caso de error
                echo json_encode([
                    'error' => 'Error en la controller',
                    'message' => $e->getMessage() // Mensaje detallado del error
                ]);
                exit();
            }
        } else {
            // Si el usuario no está autenticado, enviar un error
            echo json_encode([
                'error' => 'Usuario no autenticado'
            ]);
            exit();
        }
    }
    
    

}


?>