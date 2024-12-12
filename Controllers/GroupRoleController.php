<?php
namespace Controllers;

use \Exception as Exception;
use Helper\SessionHelper as SessionHelper;
use DAODB\GroupRoleDAO as GroupRoleDAO;
use Models\GroupRole as GroupRole;

class GroupRoleController {
    private $GroupRoleDAO;

    public function __construct() {
        $this->GroupRoleDAO = new GroupRoleDAO();
    }

    public function getGroupRoleById($roleID) {
        if ($roleID) {
            try {
                $role = $this->GroupRoleDAO->getGroupRoleById($roleID);
                return $role ? $role : null;
            } catch (Exception $ex) {
                throw $ex;
            }
        }
    }

    public function getAllGroupRoles() {
        
        try {
            return $this->GroupRoleDAO->getAllGroupRoles();
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function createGroupRole($name = null, $description = null,$status=null) {
        if ($name === null && $description === null && $status === null) {
            if (SessionHelper::getCurrentUser()) {
                SessionHelper::redirectTo403();
            }
        }
        try {
            
            if (intval(SessionHelper::getCurrentUser()->getRol()) === 1) {
                $nameValid = $this->GroupRoleDAO->validateUniqueName(0, $name);
                    if (!is_null($nameValid)) {
                        echo json_encode(['success' => false, 'message' => 'Error! El nombre del rol de grupo ya existe!.']);
                        return;
                    }
                $groupRole = new GroupRole();
                $groupRole->setName($name);
                $groupRole->setDescription($description);
                $groupRole->setIsActive($status);
                $result = $this->GroupRoleDAO->createGroupRole($groupRole);
                echo json_encode(['success' => $result, 'message' => $result ? 'No se pudo crear el rol de grupo.' : 'El rol de grupo fue creado correctamente!.' ]);
                return; 
            } else {
                SessionHelper::redirectTo403();
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function updateGroupRole($id = null, $name = null, $description = null,$status= null) {
        if ($id === null && $name === null && $description === null && $status === null) {
            if (SessionHelper::getCurrentUser()) {
                SessionHelper::redirectTo403();
            }
        }
        try {
            if (intval(SessionHelper::getCurrentUser()->getRol()) === 1) {
                if (intval(SessionHelper::getCurrentUser()->getRol()) === 1) {
                    $nameValid = $this->GroupRoleDAO->validateUniqueName($id, $name);
                    if (!is_null($nameValid)) {
                        echo json_encode(['success' => false, 'message' => 'Error! El nombre del rol de grupo ya existe!.']);
                        return;
                    }
                }
                $groupRole = new GroupRole();
                $groupRole->setId($id);
                $groupRole->setName($name);
                $groupRole->setDescription($description);
                $groupRole->setIsActive($status);
                $result = $this->GroupRoleDAO->updateGroupRole($groupRole);
                echo json_encode(['success' => $result, 'message' => !$result ? 'El rol de grupo fue actualizado correctamente!.' : 'El rol de grupo no fue actualizado correctamente!.']);
                return; 
            } else {
                SessionHelper::redirectTo403();
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function deleteGroupRole($groupRole = null) {
        if ($groupRole === null) {
            if (SessionHelper::getCurrentUser()) {
                SessionHelper::redirectTo403();
            }
        }
        try {
            
            if (intval(SessionHelper::getCurrentUser()->getRol()) === 1) {
                $result = $this->GroupRoleDAO->deleteGroupRole($groupRole);
                echo json_encode(['success' => $result, 'message' => !$result ? 'El rol de grupo fue eliminado correctamente!.' : 'El rol de grupo no fue eliminado correctamente!.']);
                return; 
            } else {
                SessionHelper::redirectTo403();
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    public function reactivateGroupRole($groupRole=null){
        if ($groupRole === null) {
            if (SessionHelper::getCurrentUser()) {
                SessionHelper::redirectTo403();
            }
        }
        try {
            
            if (intval(SessionHelper::getCurrentUser()->getRol()) === 1) {
                $result = $this->GroupRoleDAO->reactivateGroupRole($groupRole);
                echo json_encode(['success' => $result, 'message' => !$result ? 'El rol de grupo fue reactivado correctamente!.' : 'El rol de grupo no fue reactivado correctamente!.']);
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
