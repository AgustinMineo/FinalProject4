<?php
namespace Controllers;

use \Exception as Exception;
use DAODB\GroupStatusDAO as GroupStatusDAO;
use Helper\SessionHelper as SessionHelper;
use Models\GroupStatus as GroupStatus;

class GroupStatusController {
    private $GroupStatusDAO;

    public function __construct() {
        $this->GroupStatusDAO = new GroupStatusDAO();
    }

    public function getGroupStatusById($statusID) {
        if ($statusID) {
            try {
                $status = $this->GroupStatusDAO->getGroupStatusById($statusID);
                return $status ? $status : null;
            } catch (Exception $ex) {
                throw $ex;
            }
        }
    }

    public function deleteGroupStatus($groupStatus = null) {
        if ($groupStatus === null) {
            if (SessionHelper::getCurrentUser()) {
                SessionHelper::redirectTo403();
            }
        }
        try {
            if (intval(SessionHelper::getCurrentUser()->getRol()) === 1) {
                $result = $this->GroupStatusDAO->deleteGroupStatus($groupStatus);
                echo json_encode(['success' => $result, 'message' => !$result ? 'El estado del grupo fue eliminado correctamente!.' : 'El estado del grupo no fue eliminado correctamente!.']);
                return; 
            } else {
                SessionHelper::redirectTo403();
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function getAllGroupStatus() {
        try {
            $statusList=$this->GroupStatusDAO->getAllGroupStatus();
            if($statusList){
                return $statusList;
            }else{
                return [];
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function createGroupStatus($name, $description,$status) {
        if($name ===null && $description === null && $status === null){
            if (SessionHelper::getCurrentUser()) {
                SessionHelper::redirectTo403();
            }
        }else{
            try {
                $nameValid = $this->GroupStatusDAO->validateUniqueName(0,$name);
                if(!is_null($nameValid)){
                    echo json_encode(['success' => false, 'message' => 'Error! El nombre del estado de grupo fue ya existe!.' ]);
                    return;
                }
                $newGroupStatus = new GroupStatus();
                $newGroupStatus->setName($name);
                $newGroupStatus->setDescription($description);
                $newGroupStatus->setIsActive($status);
                $result = $this->GroupStatusDAO->createGroupStatus($newGroupStatus);
                echo json_encode(['success' => $result, 'message' => $result ? 'El estado del grupo fue creado correctamente!.' : 'No se pudo crear el estado del grupo.']);
                return; 
            } catch (Exception $ex) {
                throw $ex;
            }
        }
    }

    public function updateGroupStatus($id = null ,$name= null ,$description= null ,$status= null ) {
        if ($id === null && $name === null && $description === null && $status === null) {
            if (SessionHelper::getCurrentUser()) {
                SessionHelper::redirectTo403();
            }
        }
        try {
            if (intval(SessionHelper::getCurrentUser()->getRol()) === 1) {
                $nameValid = $this->GroupStatusDAO->validateUniqueName($id,$name);
                if(!is_null($nameValid)){
                    echo json_encode(['success' => false, 'message' => 'Error! El nombre del estado de grupo fue ya existe!.' ]);
                    return;
                }
                $newGroupStatus = new GroupStatus();
                $newGroupStatus->setId($id);
                $newGroupStatus->setName($name);
                $newGroupStatus->setDescription($description);
                $newGroupStatus->setIsActive($status);

                $result = $this->GroupStatusDAO->updateGroupStatus($newGroupStatus);
                if(is_array($result)){
                    echo json_encode(['success' => $result, 'message' => 'El estado del grupo fue actualizado correctamente!.' ]);
                }else{
                    echo json_encode(['success' => $result, 'message' => 'El estado del grupo no fue actualizado correctamente!.']);
                }
                return; 
            } else {
                SessionHelper::redirectTo403();
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    public function reactiveGroupStatus($groupStatusId){
        if ($groupStatusId === null) {
            if (SessionHelper::getCurrentUser()) {
                SessionHelper::redirectTo403();
            }
        }
        try {
            if (intval(SessionHelper::getCurrentUser()->getRol()) === 1) {
                $result = $this->GroupStatusDAO->reactiveGroupStatus($groupStatusId);
                echo json_encode(['success' => $result, 'message' => $result ? 'El estado no fue actualizado correctamente!.' : 'El estado fue actualizado correctamente!.' ]);
                return; 
            } else {
                SessionHelper::redirectTo403();
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }
}
?>
