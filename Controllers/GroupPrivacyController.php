<?php
namespace Controllers;

use \Exception as Exception;
use Helper\SessionHelper as SessionHelper;
use DAODB\GroupPrivacyDAO as GroupPrivacyDAO;
use Models\GroupPrivacy as GroupPrivacy;

class GroupPrivacyController {
    private $GroupPrivacyDAO;

    public function __construct() {
        $this->GroupPrivacyDAO = new GroupPrivacyDAO();
    }

    public function getGroupPrivacyById($privacyID) {
        if ($privacyID) {
            try {
                $privacy = $this->GroupPrivacyDAO->getGroupPrivacyById($privacyID);
                return $privacy ? $privacy : null;
            } catch (Exception $ex) {
                throw $ex;
            }
        }
    }

    public function getAllGroupPrivacies() {
        
        try {
            return $this->GroupPrivacyDAO->getAllGroupPrivacies();
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function createGroupPrivacy($name = null, $description = null,$status=null) {
        if ($name === null && $description === null && $status === null) {
            if (SessionHelper::getCurrentUser()) {
                SessionHelper::redirectTo403();
            }
        }
        try {
            if (intval(SessionHelper::getCurrentUser()->getRol()) === 1) {
                $groupPrivacy = new GroupPrivacy();
                $groupPrivacy->setName($name);
                $groupPrivacy->setDescription($description);
                $groupPrivacy->setIsActive($status);
                $result = $this->GroupPrivacyDAO->createGroupPrivacy($groupPrivacy);
                echo json_encode(['success' => $result, 'message' => $result ? 'La configuración de privacidad del grupo fue creada correctamente!.' : 'No se pudo crear la configuración de privacidad del grupo.']);
                return; 
            } else {
                SessionHelper::redirectTo403();
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function updateGroupPrivacy($id = null, $name = null, $description = null,$status=null) {
        if ($id === null && $name === null && $description === null && $status === null) {
            if (SessionHelper::getCurrentUser()) {
                SessionHelper::redirectTo403();
            }
        }
        try { 
            if (intval(SessionHelper::getCurrentUser()->getRol()) === 1) {
                $nameValid = $this->GroupPrivacyDAO->validateUniqueName($id, $name);
                if (!is_null($nameValid)) {
                    echo json_encode(['success' => false, 'message' => 'Error! El nombre de la privacidad de grupo ya existe!.']);
                    return;
                }
                $groupPrivacy = new GroupPrivacy();
                $groupPrivacy->setId($id);
                $groupPrivacy->setName($name);
                $groupPrivacy->setDescription($description);
                $groupPrivacy->setIsActive($status);
                $result = $this->GroupPrivacyDAO->updateGroupPrivacy($groupPrivacy);
                echo json_encode(['success' => $result, 'message' => !$result ? 'La configuración de privacidad del grupo fue actualizada correctamente!.' : 'La configuración de privacidad del grupo no fue actualizada correctamente!.']);
                return; 
            } else {
                SessionHelper::redirectTo403();
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function deleteGroupPrivacy($groupPrivacy = null) {
        if ($groupPrivacy === null) {
            if (SessionHelper::getCurrentUser()) {
                SessionHelper::redirectTo403();
            }
        }
        try {
            
            if (intval(SessionHelper::getCurrentUser()->getRol()) === 1) {
                $result = $this->GroupPrivacyDAO->deleteGroupPrivacy($groupPrivacy);
                echo json_encode(['success' => $result, 'message' => !$result ? 'La configuración de privacidad del grupo fue eliminada correctamente!.' : 'La configuración de privacidad del grupo no fue eliminada correctamente!.']);
                return; 
            } else {
                SessionHelper::redirectTo403();
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    public function reactivateGroupPrivacy($groupPrivacy = null) {
        if ($groupPrivacy === null) {
            if (SessionHelper::getCurrentUser()) {
                SessionHelper::redirectTo403();
            }
        }
        try {
            
            if (intval(SessionHelper::getCurrentUser()->getRol()) === 1) {
                $result = $this->GroupPrivacyDAO->reactivateGroupPrivacy($groupPrivacy);
                echo json_encode(['success' => $result, 'message' => !$result ? 'La configuración de privacidad del grupo fue reactivada correctamente!.' : 'La configuración de privacidad del grupo no fue reactivada correctamente!.']);
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
