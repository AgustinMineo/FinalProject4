<?php
namespace DAODB;

use \PDO as PDO;
use \Exception as Exception;
use Models\Message as Message;
use DAODB\QueryType as QueryType;
use DAODB\MemberDAO as MemberDAO;
use DAODB\UserDAO as UserDAO;
use Helper\SessionHelper as SessionHelper;
class MessageDAO {
    
    private $connection;
    private $tableMessages = "messages";
    private $userTable = 'user';
    private $groupTable = 'groups';
    private $groupMembers='group_members';
    private $tableMessageGroup= 'group_message_reads';
    private $MemberDAO;

    public function __construct() {
        $this->UserDAO = new UserDAO();
        $this->MemberDAO = new MemberDAO();
    }
    // Guardar mensaje nuevos
    public function saveMessage($senderId, $message, $receiverId = null, $groupId = null) {
        try {
            if (empty($message)) {
                throw new Exception("El mensaje no puede estar vacío.");
            }
            if ($groupId) {
                $query = "INSERT INTO " . $this->tableMessages . " (sender_id, group_id, message) 
                        VALUES (:senderId, :groupId, :message)";
                
                $parameters["senderId"] = $senderId;
                $parameters["groupId"] = $groupId;
                $parameters["message"] = $message;
    
            } else {
                $query = "INSERT INTO " . $this->tableMessages . " (sender_id, receiver_id, message) 
                        VALUES (:senderId, :receiverId, :message)";

                $parameters["senderId"] = $senderId;
                $parameters["receiverId"] = $receiverId;
                $parameters["message"] = $message;
            }
    
            $this->connection = Connection::GetInstance();
            error_log("Ejecutando consulta: $query con parámetros: " . json_encode($parameters));
            $result = $this->connection->Execute($query, $parameters);
            if($groupId){
                $this->newMessageGroup($this->connection->lastInsertId(),$senderId,$groupId);
            }
            error_log("Mensaje guardado correctamente.");
            return true;
    
        } catch (Exception $ex) {
            error_log("Error en saveMessage: " . $ex->getMessage());
            throw $ex; 
        }
    }
    //Obtiene los mensajes de un usuario particular.
    public function getMessagesByUsers($currentUser, $chatUser) {
        try {
            $query = "SELECT message, sender_id, receiver_id, sent_at,is_read
                        FROM " . $this->tableMessages . "
                        WHERE (sender_id = :currentUser AND receiver_id = :chatUser)
                        OR (sender_id = :chatUser AND receiver_id = :currentUser)
                        ORDER BY sent_at ASC";
    
            $parameters["currentUser"] = $currentUser;
            $parameters["chatUser"] = $chatUser;
    
            $this->connection = Connection::GetInstance();
            $resultSet = $this->connection->Execute($query, $parameters);
            if($resultSet){//Si existen, los actualizo.
                $this->updateUnreadMessages($currentUser,$chatUser);
            }
            return $resultSet; 
    
        } catch (Exception $e) {
            throw $e;
        }
    }
    
    public function getMessagesByGroup($groupID) {
        try {
            $query = "
                SELECT m.message, m.sender_id, m.receiver_id, m.sent_at,
                    concat(u.firstName, ' ', u.lastName) as NombreUsuario,
                    CASE 
                        WHEN COUNT(mg.is_read = 0 OR NULL) = 0 THEN 1
                        ELSE 0
                    END as all_read
                FROM " . $this->tableMessages . " m 
                JOIN user u on u.userId = m.sender_id
                LEFT JOIN " . $this->tableMessageGroup . " mg ON mg.message_id = m.id
                WHERE m.group_id = :groupID
                GROUP BY m.id, m.sender_id, m.receiver_id, m.sent_at, u.firstName, u.lastName
                ORDER BY m.sent_at ASC";
    
            $parameters['groupID'] = $groupID;
    
            $this->connection = Connection::GetInstance();
            $resultSet = $this->connection->Execute($query, $parameters);
    
            if ($resultSet) {
                $this->updateUnreadMessagesGroup(SessionHelper::getCurrentUser()->getUserID(), $groupID);
            }
    
            return $resultSet; 
    
        } catch (Exception $e) {
            throw $e;
        }
    }
    //Obtiene los usuarios a mostrar en la vista de chats
    public function getChatsByUserID($userID) {
        try {
            $query="SELECT 
                        CASE 
                            WHEN sender_id = :userID THEN receiver_id ELSE sender_id END AS chat_user_id, 
                        MAX(sent_at) AS last_message_time FROM " . $this->tableMessages . "
                        WHERE :userID IN (sender_id, receiver_id)
                        GROUP BY chat_user_id
                        ORDER BY last_message_time DESC";
        
            $parameters["userID"] = $userID;
    
            $this->connection = Connection::GetInstance();
            $resultSet = $this->connection->Execute($query, $parameters);
    
            // Devuelve lista de usuarios (user ids)
            $chatUsers = array();
            foreach ($resultSet as $row) {
                $user = $this->UserDAO->getUserByIdReduce($row["chat_user_id"]);
                if ($user) {
                    array_push($chatUsers, $user);
                }
            }
    
            return $chatUsers;
    
        } catch (Exception $e) {
            throw $e;
        }
    }
    //Obtengo los usuarios que no tienen mensajes con el usuario logeado
    public function getUsersWithoutChat($currentUserId){
        try {
            $query = "SELECT u.userID, CONCAT(u.firstName, ' ', u.lastName) AS name, u.email, u.cellphone,
                    u.birthdate, u.userDescription, u.roleID, u.status,u.userImage
                    FROM " . $this->userTable . " u
                    WHERE u.userID != '$currentUserId'
                    AND u.status != 0
                    AND NOT EXISTS (
                        SELECT 1
                        FROM " . $this->tableMessages . " m
                        WHERE (m.sender_id = u.userID AND m.receiver_id = '$currentUserId')
                        OR (m.receiver_id = u.userID AND m.sender_id = '$currentUserId')
                    )";


            $this->connection = Connection::GetInstance();
            $resultSet = $this->connection->Execute($query);

            return $resultSet;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    //Trae el id de usuario y la cantidad de mensajes no leidos que tiene el usuario logeado
    public function getUnreadMessages($userID){
        try {
            $query = "SELECT count(id) as 'cantidad',sender_id as 'idUsuario' FROM " . $this->tableMessages . "
                    WHERE receiver_id = :userID AND is_read=0 group by sender_id";
            
            $parameters["userID"] = $userID;
            $unreadMessages=array();
            $this->connection = Connection::GetInstance();
            $resultSet = $this->connection->Execute($query, $parameters);
            foreach ($resultSet as $row) {
                $unreadMessages[]=[
                    'cantidad'=> $row["cantidad"],
                    'idUsuario'=> $row["idUsuario"]
                ];
            }

            return $unreadMessages;

        } catch (Exception $e) {
            error_log("Error en getUnreadMessages: " . $e->getMessage());
            throw $e;
        }
    }
    //Actualiza los mensajes a leidos.
    public function updateUnreadMessages($userID,$senderID){
        try{
            $query = "UPDATE ".$this->tableMessages." SET is_read = 1 WHERE sender_id = '$senderID' 
            AND receiver_id = '$userID' and is_read=0;";
            $this->connection = Connection::GetInstance();
            $this->connection->Execute($query);
            return true;
        } catch (Exception $ex) 
        { 
            throw $ex; 
        }
    }
    public function updateUnreadMessagesGroup($user_id,$group_id){
        try{
            $query = "UPDATE ".$this->tableMessageGroup." SET is_read = 1 
            WHERE user_id = :user_id and group_id = :group_id and is_read=0;";
            $parameters['user_id']=$user_id;
            $parameters['group_id']=$group_id;
            $this->connection = Connection::GetInstance();
            $this->connection->Execute($query,$parameters);
            return true;
        } 
        catch (Exception $ex){ 
            throw $ex; 
        }
    }
    //Se usa para insertar el registro en la tabla de group_message_reads para poder tener el visto 
    public function newMessageGroup($message_id,$user_id,$group_id){
        try{
            $members = $this->MemberDAO->getMembersIdsByGroup($group_id);
            foreach($members as $member){
                $query = "INSERT INTO ".$this->tableMessageGroup." (message_id, user_id, group_id, is_read, read_at) 
                VALUES (:message_id, :user_id, :group_id, 0, NULL)";
                $parameters["message_id"] = $message_id;
                $parameters["user_id"] = $member;
                $parameters["group_id"]= $group_id;

                $this->connection->Execute($query, $parameters); 
            }
            error_log("Registros de mensajes no leídos insertados correctamente para los miembros del grupo, incluido el remitente.");
            return true;
        } catch (Exception $ex) {
            error_log("Error en newMessageGroup: " . $ex->getMessage());
            throw $ex;
        }
    }
}
?>
