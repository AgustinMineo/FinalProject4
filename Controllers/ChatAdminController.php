<?php
namespace Controllers;

use Helper\SessionHelper as SessionHelper;
use Helper\FileUploader as FileUploader;
use \Exception as Exception;

use DAODB\GroupStatusDAO as GroupStatusDAO;
use DAODB\GroupPrivacyDAO as GroupPrivacyDAO;
use DAODB\GroupRoleDAO as GroupRoleDAO;
use DAODB\GroupTypeDAO as GroupTypeDAO;
use DAODB\GroupDAO as GroupDAO;
use DAODB\MemberDAO as MemberDAO;


use Models\GroupPrivacy as GroupPrivacy;
use Models\GroupStatus as GroupStatus;
use Models\GroupRole as GroupRole;
use Models\GroupType as GroupType;
use Models\Group as Group;
use Models\Member as Member;

class ChatAdminController{
    public function __construct(){
        $this->GroupStatusDAO = new GroupStatusDAO();
        $this->GroupPrivacyDAO = new GroupPrivacyDAO();
        $this->GroupRoleDAO = new GroupRoleDAO();
        $this->GroupTypeDAO = new GroupTypeDAO();
        $this->GroupDAO = new GroupDAO();
        $this->MemberDAO = new MemberDAO();
        $this->fileUploader = new FileUploader(GROUPS_PATH);
    }

    public function goViewChats($groupStatusList = null,$groupPrivacyList = null,$groupRoleList = null,$groupTypeList = null,$groupList=null){
        if($groupStatusList === null && $groupPrivacyList === null && $groupRoleList ===null && $groupTypeList=== null && $groupList ===null){
            if(SessionHelper::getCurrentUser()){
                SessionHelper::redirectTo403();
            }
        }else{
            $userRole=SessionHelper::InfoSession([1]);
            require_once(VIEWS_PATH."chatAdministration.php");
        }

    }
    public function getViewChatInformation(){
        SessionHelper::InfoSession([1]);
        //$chats = $this->MessageDAO->getChatsByUserID(SessionHelper::getCurrentUser()->getUserID());
        $groupStatusList =$this->GroupStatusDAO->getAllGroupStatus();
        $groupPrivacyList =$this->GroupPrivacyDAO->getAllGroupPrivacy();
        $groupRoleList =$this->GroupRoleDAO->getAllGroupRole();
        $groupTypeList =$this->GroupTypeDAO->getAllGroupType();
        $groupList = $this->GroupDAO->getGroupsByUser(SessionHelper::getCurrentUser());//Cambiar a todos los grupos
        $this->goViewChats($groupStatusList,$groupPrivacyList,$groupRoleList,$groupTypeList,$groupList);
    }

}
?>