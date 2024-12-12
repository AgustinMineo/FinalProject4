<?php
namespace Controllers;

use \Exception as Exception;
use Helper\SessionHelper as SessionHelper;
use Models\GroupInvitationStatus as GroupInvitationStatus;
use DAODB\GroupInvitationStatusDAO as GroupInvitationStatusDAO;

class InvitationStatusController {
    private $GroupInvitationStatusDAO;

    public function __construct() {
        $this->GroupInvitationStatusDAO = new GroupInvitationStatusDAO();
    }

    public function getInvitationStatusById($statusID) {
        if ($statusID) {
            try {
                $status = $this->GroupInvitationStatusDAO->getInvitationStatusById($statusID);
                return $status ? $status : null;
            } catch (Exception $ex) {
                throw $ex;
            }
        }
    }

    public function getInvitationStatus() {
        try {
            return $this->GroupInvitationStatusDAO->getInvitationStatus();
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function createInvitationStatus($name = null, $description = null,$status=null) {
        if ($name === null && $description === null && $status === null) {
            if (SessionHelper::getCurrentUser()) {
                SessionHelper::redirectTo403();
            }
        }
        try {
            
            if (intval(SessionHelper::getCurrentUser()->getRol()) === 1) {
                $nameValid = $this->GroupInvitationStatusDAO->validateUniqueName(0, $name);
                if (!is_null($nameValid)) {
                    echo json_encode(['success' => false, 'message' => 'Error! El nombre del estado de invitación ya existe!.']);
                    return;
                }
                $invitationStatus = new GroupInvitationStatus();
                $invitationStatus->setName($name);
                $invitationStatus->setDescription($description);
                $invitationStatus->setIsActive($status);
                $result = $this->GroupInvitationStatusDAO->createInvitationStatus($invitationStatus);
                echo json_encode(['success' => $result, 'message' => $result ? 'No se pudo crear el estado de la invitación.' : 'El estado de la invitación fue creado correctamente!.' ]);
                return; 
            } else {
                SessionHelper::redirectTo403();
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function updateInvitationStatus($id = null, $name = null, $description = null,$status=null) {
        if ($id === null && $name === null && $description === null && $status === null) {
            if (SessionHelper::getCurrentUser()) {
                SessionHelper::redirectTo403();
            }
        }
        try {
            
            if (intval(SessionHelper::getCurrentUser()->getRol()) === 1) {
                $nameValid = $this->GroupInvitationStatusDAO->validateUniqueName($id, $name);
                if (!is_null($nameValid)) {
                    echo json_encode(['success' => false, 'message' => 'Error! El nombre del estado de invitación ya existe!.']);
                    return;
                }
                $invitationStatus = new GroupInvitationStatus();
                $invitationStatus->setId($id);
                $invitationStatus->setName($name);
                $invitationStatus->setDescription($description);
                $result = $this->GroupInvitationStatusDAO->updateInvitationStatus($invitationStatus);
                echo json_encode(['success' => $result, 'message' => !$result ? 'El estado de la invitación fue actualizado correctamente!.' : 'El estado de la invitación no fue actualizado correctamente!.']);
                return; 
            } else {
                SessionHelper::redirectTo403();
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function deleteInvitationStatus($invitationStatus = null) {
        if ($invitationStatus === null) {
            if (SessionHelper::getCurrentUser()) {
                SessionHelper::redirectTo403();
            }
        }
        try {
            if (intval(SessionHelper::getCurrentUser()->getRol()) === 1) {
                $result = $this->GroupInvitationStatusDAO->deleteInvitationStatus($invitationStatus);
                echo json_encode(['success' => $result, 'message' => $result ? 'El estado de la invitación no fue eliminado correctamente!.' : 'El estado de la invitación fue eliminado correctamente!.']);
                return; 
            } else {
                SessionHelper::redirectTo403();
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    public function reactivateInvitationStatus($invitationStatus = null){
        if ($invitationStatus === null) {
            if (SessionHelper::getCurrentUser()) {
                SessionHelper::redirectTo403();
            }
        }
        try {
            if (intval(SessionHelper::getCurrentUser()->getRol()) === 1) {
                $result = $this->GroupInvitationStatusDAO->reactivateInvitationStatus($invitationStatus);
                echo json_encode(['success' => $result, 'message' => $result ? 'El estado de la invitación no fue reactivado correctamente!.' : 'El estado de la invitación fue reactivado correctamente!.']);
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
