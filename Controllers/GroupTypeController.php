<?php
namespace Controllers;

use \Exception as Exception;
use DAODB\GroupTypeDAO as GroupTypeDAO;
use Models\GroupTypes as GroupTypes;
use Helper\SessionHelper as SessionHelper;

class GroupTypeController {
    private $GroupTypeDAO;

    public function __construct() {
        $this->GroupTypeDAO = new GroupTypeDAO();
    }

    public function getGroupTypeById($typeID) {
        if ($typeID) {
            try {
                $type = $this->GroupTypeDAO->getGroupTypeById($typeID);
                return $type ? $type : null;
            } catch (Exception $ex) {
                throw $ex;
            }
        }
    }

    public function getAllGroupTypes() {
        
        try {
            return $this->GroupTypeDAO->getAllGroupTypes();
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function createGroupType($name=null,$description=null,$status=null) {
        if($name===null && $description === null && $status === null){
            if (SessionHelper::getCurrentUser()) {
                SessionHelper::redirectTo403();
            }
        }
        try {
            if (intval(SessionHelper::getCurrentUser()->getRol()) === 1) {
                $groupType = new GroupTypes();
                $groupType->setName($name);
                $groupType->setDescription($description);
                $groupType->setIsActive($status);
                $result = $this->GroupTypeDAO->createGroupType($groupType);
                echo json_encode(['success' => $result, 'message' => $result ? 'No se pudo crear el tipo de grupo.' : 'El tipo de grupo fue creado correctamente!.']);
                return; 
            } else {
                SessionHelper::redirectTo403();
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function updateGroupType($id=null,$name=null,$description=null,$is_active=null) {
        if($id === null && $name===null && $description==null && $is_active===null){
            if (SessionHelper::getCurrentUser()) {
                SessionHelper::redirectTo403();
            }
        }
        try {
            if (intval(SessionHelper::getCurrentUser()->getRol()) === 1) {
                $nameValid = $this->GroupTypeDAO->validateUniqueName($id, $name);
                if (!is_null($nameValid)) {
                    echo json_encode(['success' => false, 'message' => 'Error! El nombre del tipo de grupo ya existe!.']);
                    return;
                }
                $groupType = new GroupTypes();
                $groupType->setId($id);
                $groupType->setName($name);
                $groupType->setDescription($description);
                $groupType->setIsActive($is_active);
                $result = $this->GroupTypeDAO->updateGroupType($groupType);
                echo json_encode(['success' => $result, 'message' => !$result ? 'El tipo de grupo fue actualizado correctamente!.' : 'El tipo de grupo no fue actualizado correctamente!.']);
                return; 
            } else {
                SessionHelper::redirectTo403();
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function deleteGroupType($groupType = null) {
        if ($groupType === null) {
            if (SessionHelper::getCurrentUser()) {
                SessionHelper::redirectTo403();
            }
        }
        try {
            if (intval(SessionHelper::getCurrentUser()->getRol()) === 1) {
                $result = $this->GroupTypeDAO->deleteGroupType($groupType);
                if($result){
                    echo json_encode(
                        ['success' => true, 
                        'message' => 'El tipo de grupo fue eliminado correctamente!.'
                    ]);
                    return; 
                }else{
                    echo json_encode(['success' => false, 'message' => 'El tipo de grupo fue eliminado correctamente!.']);
                    return;
                }
            } else {
                SessionHelper::redirectTo403();
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function reactivateGroupType($typeID) {
        try {
            $groupTypeDAO = new GroupTypeDAO();
            $result = $groupTypeDAO->reactivateGroupType($typeID);
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Group type fue reactivado correctamente.']);
                return;
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al reactivar el group type.']);
                return;
            }
        } catch (Exception $ex) {
            echo json_encode(['success' => false, 'message' => $ex->getMessage()]);
            return;
        }
    }
    
}
?>
