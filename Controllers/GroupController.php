<?php
namespace Controllers;
use Helper\SessionHelper as SessionHelper;
use Helper\FileUploader as FileUploader;
use \Exception as Exception;

use DAODB\GroupDAO as GroupDAO;
use DAODB\MemberDAO as MemberDAO;
use DAODB\GroupInfoDAO as GroupInfoDAO;
use DAODB\OwnerDAO as OwnerDAO;
use DAODB\UserDAO as UserDAO;
use DAODB\KeeperDAO as KeeperDAO;
use DAODB\MessageDAO as MessageDAO;
use DAODB\GroupPrivacyDAO as GroupPrivacyDAO;
use DAODB\GroupTypeDAO as GroupTypeDAO;
//Models
use Models\Group as Group;
use Models\GroupInfo as GroupInfo;
use Models\Member as Member;
class GroupController
{      
    private $GroupDAO;
    private $GroupInfoDAO;
    private $fileUploader;
    private $MemberDAO;
    private $MessageDAO;
    private $UserDAO;
    private $OwnerDAO;
    private $KeeperDAO;
    private $GroupPrivacy;
    private $GroupTypeDAO;

    private $groupTable = 'groups';


    public function __construct(){
        $this->GroupDAO = new GroupDAO();
        $this->GroupInfoDAO = new GroupInfoDAO();
        $this->GroupPrivacyDAO = new GroupPrivacyDAO();
        $this->MemberDAO = new MemberDAO();
        $this->MessageDAO = new MessageDAO();
        $this->KeeperDAO = new KeeperDAO();
        $this->UserDAO = new UserDAO();
        $this->OwnerDAO = new OwnerDAO();
        $this->fileUploader = new FileUploader(GROUPS_PATH);
        $this->GroupTypeDAO = new GroupTypeDAO();
    }

    public function goViewChats($groupID){
        if($groupID){
            echo '<div class="alert alert-success">You have successful create a group !</div>';
        }
        $chats = $this->MessageDAO->getChatsByUserID(SessionHelper::getCurrentUser()->getUserID());
        $currentUser = SessionHelper::getCurrentUser()->getUserID();
        SessionHelper::InfoSession([1,2,3]);
        require_once(VIEWS_PATH."chatView.php");
    }

    public function newGroup($currentUserID  = null, $groupName  = null, $groupType  = null ,$groupPrivacy = null,$description = null, $rules = null, $start_date=null, $end_date=null,$files = null) {
        if (!is_null($currentUserID) && !is_null($groupName) && !is_null($groupType) && !is_null($groupPrivacy) && !is_null($description) && !is_null($rules)) {
            try {
                $newGroup = new Group();
                $newGroup->setCreated_by($currentUserID);
                $newGroup->setName($groupName);
                $newGroup->setGroupType($groupType);
                $newGroup->setStatusId(1);
                $newGroup->setGroupPrivacy($groupPrivacy);

                $groupID = $this->GroupDAO->newGroup($newGroup);
                if ($groupID) {
                    $newGroupMember = new Member();
                    $newGroupMember->setGroupID($groupID);
                    $newGroupMember->setUser($currentUserID);
                    $newGroupMember->setStatus(1);
                    $newGroupMember->setRole(1);

                    $resultNewMember = $this->MemberDAO->newMember($newGroupMember);
                    $subFolder = $groupID;

                    $groupImageRoute = null; 

                    if (isset($_FILES['groupImage']) && $_FILES['groupImage']['error'][0] === UPLOAD_ERR_OK) {
                        $formatName = function($files, $key) use ($groupID) {
                            $extension = strtolower(pathinfo($_FILES['groupImage']['name'][$key], PATHINFO_EXTENSION));
                            return "{$groupID}-image." . $extension;
                        };
                        $groupImageRoute = $this->fileUploader->uploadFiles($_FILES['groupImage'], $subFolder, $formatName);
                    } else {
                        $groupImageRoute[0] = GROUPS_PATH . "Default/DefaultGroupImage.jpg";
                    }

                    $newGroupInfo = new GroupInfo();
                    $newGroupInfo->setDescription($description);
                    $newGroupInfo->setRules($rules);
                    $newGroupInfo->setImage($groupImageRoute[0]); 
                    $newGroupInfo->setStartDate($start_date ? $start_date : null);
                    $newGroupInfo->setEndDate($end_date ? $end_date : null);
                    $groupInfoStatus = $this->GroupInfoDAO->newGroupInfo($newGroupInfo);
                    //error_log(print_r($newGroupInfo, true)); 
                    if ($groupInfoStatus) {
                        $newGroup->setGroupInfo($groupInfoStatus);
                        $newGroup->setId($groupID);
                        $this->GroupDAO->updateGroup($newGroup);
                        echo json_encode(['success' => true, 'message' => 'Grupo creado exitosamente.']);
                    } else {
                        echo json_encode(['success' => false, 'message' => 'Error al asignar la información del grupo.']);
                        return; 
                    }

                    if($groupInfoStatus){
                        echo json_encode(['success' => true, 'message' => 'Grupo creado exitosamente.']);
                        return;
                    }else{
                        echo json_encode(['success' => false, 'message' => 'Error al crear la información del grupo.']);
                        return;
                    }
                }
            } catch (Exception $ex) {
                echo json_encode(['success' => false, 'message' => $ex->getMessage()]);
                return;
            }
        }
        echo json_encode(['success' => false, 'message' => 'Datos faltantes']);
        return;
    }

    public function getUserGroups($currentUserID,$userRole) {
        if (!$currentUserID && !$userRole) {
            return json_encode([]);
        } try {
            if(intval($userRole) === 1){
                $currentUser = $this->UserDAO->getUserByIdReduce($currentUserID);
            }elseif(intval($userRole) === 2){
                $currentUser = $this->OwnerDAO->searchBasicInfoOwnerByUserID($currentUserID);
            }else{
                $currentUser = $this->KeeperDAO->searchKeeperByUserIDReduce($currentUserID);
            }
            if($currentUser){
                $groups = $this->GroupDAO->getGroupsByUser($currentUser);
                echo json_encode($groups);
            }
        } catch (Exception $ex) {
            
            return json_encode(['error' => $ex->getMessage()]);
        }
    }
    public function deleteGroup($groupID,$userId){
        if($groupID && $userId){
            try{
                $result = $this->GroupDAO->deleteGroup($groupID,$userId);
                if($result){
                    echo json_encode(['success' => true, 'message' => 'El Grupo fue eliminado exitosamente.']);
                    return;
                } else {
                    echo json_encode(['success' => false, 'message' => 'Error al eliminar el grupo.']);
                    return; 
                }
            }catch(Exception $ex){
                throw $ex;
            }
        }else{
            echo json_encode(['success' => false, 'message' => 'Error al eliminar el grupo.']);
            return; 
        }
    }
    public function getPublicGroups($userId){
        $groups = $this->GroupDAO->getPublicGroups($userId);
        if($groups){
            echo json_encode($groups);
        }else{
            echo json_encode([]);
        }
    }
    public function updateGroupPrivacy($groupID,$newPrivacy){
        if($groupID && $newPrivacy){
            try{
                $status = $this->GroupDAO->updateGroupPrivacy($groupID,$newPrivacy);
                $newPrivacyResponse = $this->GroupPrivacyDAO->getGroupPrivacyById($newPrivacy);
                if($status){
                    echo json_encode(
                        ['success' => true, 
                        'message' => 'La privacidad fue cambiada exitosamente.',
                        'newPrivacyName' => $newPrivacyResponse->getName(),
                        'newPrivacyDescription' => $newPrivacyResponse->getDescription(),
                    ]);
                    return;
                } else {
                    echo json_encode(['success' => false, 'message' => 'Error al cambiar la privacidad del grupo.']);
                    return; 
                }
            }catch(Exception $ex){
                throw $ex;
            }
        }else{
            echo json_encode(['success' => false, 'message' => 'Error al modificar la privacidad del grupo.']);
            return; 
        }
    }
    public function updateGroupType($groupID,$newType){
        if($groupID && $newType){
            try{
                $status = $this->GroupDAO->updateGroupType($groupID,$newType);
                if($status){
                    $newType = $this->GroupTypeDAO->getGroupTypeById($newType);

                    echo json_encode(
                        ['success' => true, 
                        'message' => 'El tipo fue cambiada exitosamente.',
                        'newTypeName'=>$newType->getName(),
                        'newTypeDescription' => $newType->getDescription()
                    ]);
                    return;
                } else {
                    echo json_encode(['success' => false, 'message' => 'Error al cambiar el tipo de grupo.']);
                    return; 
                }
            }catch(Exception $ex){
                throw $ex;
            }
        }else{
            echo json_encode(['success' => false, 'message' => 'Error al modificar el tipo de grupo.']);
            return; 
        }
    }
    public function updateGroupName($groupID,$newName){
        if($groupID && $newName){
            try{
                $status = $this->GroupDAO->updateGroupName($groupID,$newName);
                if($status){
                    echo json_encode(
                        ['success' => true, 
                        'message' => 'El nombre fue cambiada exitosamente.',
                        "newName" => $newName
                    ]);
                    return;
                } else {
                    echo json_encode(['success' => false, 'message' => 'Error al cambiar el nombre de grupo.']);
                    return; 
                }
            }catch(Exception $ex){
                throw $ex;
            }
        }else{
            echo json_encode(['success' => false, 'message' => 'Error al modificar el nombre de grupo.']);
            return; 
        }
    }
    public function getGroupByID($groupID) {
        if ($groupID) {
            try {
                $group = $this->GroupDAO->getGroupByID($groupID);
                if ($group) {
                    $groupBuild = $group->toArray(); 
                    $response = [
                        'success' => true,
                        'data' => $groupBuild 
                    ];
    
                    echo json_encode($response);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Grupo no encontrado.']);
                }
            } catch (Exception $ex) {
                echo json_encode(['success' => false, 'error' => $ex->getMessage()]);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'ID de grupo no proporcionado.']);
        }
    }
    
    //Admin
    public function changeStatusGroup($groupID,$status){
        if ($groupID === null && $status === null) {
            if (SessionHelper::getCurrentUser()) {
                SessionHelper::redirectTo403();
            }
        }else{
            try{
                $result = $this->GroupDAO->changeStatusGroup($groupID,$status);
                if(is_array($result)){
                    echo json_encode(
                        ['success' => true, 
                        'message' => 'Se cambio de estado el grupo.'
                        ]);
                    return;
                }else{
                    echo json_encode(
                        ['success' => false, 
                        'message' => 'Error al cambiar de estado el grupo.'
                    ]);
                    return;
                }
            }catch(Exception $ex){
                throw $ex;
            }
            echo json_encode(['success' => false, 'message' => 'Error al cambiar de estado el grupo.']);
            return; 
        }
    }
    public function updateGroup($groupID = null, $groupName = null, $groupType = null, $groupPrivacy = null, $description = null, $rules = null, $start_date = null, $end_date = null, $files = null) {
        if (empty($groupID) || empty($groupName) || empty($groupType) || empty($groupPrivacy) || empty($description) || empty($rules)) {
            echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios.']);
            return;
        }
    
        try {
            $groupInfoID = $this->GroupDAO->GetGroupInfoIDByGroupID($groupID);
    
            if (!$groupInfoID) {
                echo json_encode(['success' => false, 'message' => 'Información del grupo no encontrada.']);
                return;
            }
    
            $groupImageRoute = null;
            if (isset($_FILES['groupImage']) && $_FILES['groupImage']['error'][0] === UPLOAD_ERR_OK) {
                $subFolder = $groupID;
                $formatName = function($files, $key) use ($groupID) {
                    $extension = strtolower(pathinfo($_FILES['groupImage']['name'][$key], PATHINFO_EXTENSION));
                    return "{$groupID}-image." . $extension;
                };
                $groupImageRoute = $this->fileUploader->uploadFiles($_FILES['groupImage'], $subFolder, $formatName);
            } else {
                $groupImageRoute = [$this->GroupInfoDAO->getCurrentGroupImage($groupInfoID)];
            }
    
            $groupInfo = new GroupInfo();
            $groupInfo->setId($groupInfoID);
            $groupInfo->setDescription($description);
            $groupInfo->setRules($rules);
            $groupInfo->setImage($groupImageRoute[0]);
            $groupInfo->setStartDate($start_date ? $start_date : null);
            $groupInfo->setEndDate($end_date ? $end_date : null);
            $groupInfoStatus = $this->GroupInfoDAO->updateGroupInfo($groupInfo,$groupInfoID);
    
            if ($groupInfoStatus) {
                $group = new Group();
                $group->setId($groupID);
                $group->setName($groupName);
                $group->setGroupType($groupType);
                $group->setGroupPrivacy($groupPrivacy);
                $group->setGroupInfo($groupInfoID);
                $updateGroupStatus = $this->GroupDAO->updateGroup($group);
    
                if ($updateGroupStatus) {
                    echo json_encode(['success' => true, 'message' => 'Grupo actualizado exitosamente.']);
                    return;
                } else {
                    echo json_encode(['success' => false, 'message' => 'Error al actualizar el grupo.']);
                    return;
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al actualizar la información del grupo.']);
                return;
            }
    
        } catch (Exception $ex) {
            echo json_encode(['success' => false, 'message' => $ex->getMessage()]);
            return;
        }
    }
    
}
?>