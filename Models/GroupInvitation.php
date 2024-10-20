<?php
namespace Models;

class GroupInvitation {
    private $id;
    private $groupId;
    private $invited_by;
    private $invited_user;
    private $status;
    private $send_at;
    private $responded_at;
    private $message;
    private $roleInvited; //Role que va a tener si acepta

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getGroupId() {
        return $this->groupId;
    }

    public function setGroupId($groupId) {
        $this->groupId = $groupId;
    }

    public function getInvitedBy() {
        return $this->invited_by;
    }

    public function setInvitedBy($invited_by) {
        $this->invited_by = $invited_by;
    }

    public function getInvitedUser() {
        return $this->invited_user;
    }

    public function setInvitedUser($invited_user) {
        $this->invited_user = $invited_user;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public function getSendAt() {
        return $this->send_at;
    }

    public function setSendAt($send_at) {
        $this->send_at = $send_at;
    }

    public function getRespondeAt() {
        return $this->responded_at;
    }

    public function setRespondeAt($responded_at) {
        $this->responded_at = $responded_at;
    }

    public function getMessage() {
        return $this->message;
    }

    public function setMessage($message) {
        $this->message = $message;
    }

    public function getRoleInvited() {
        return $this->roleInvited;
    }

    public function setRoleInvited($roleInvited) {
        $this->roleInvited = $roleInvited;
    }

    public function toArray() {
        return [
            'id' => $this->id,
            'groupId' => $this->groupId ? $this->groupId->toArray() : null,
            'invited_by' => $this->invited_by ? $this->invited_by->toArray() : null,
            'invited_user' => $this->invited_user ? $this->invited_user->toArray() : null,
            'status' => $this->status ? $this->status->toArray() : null,
            'send_at' => $this->send_at,
            'responded_at' => $this->responded_at,
            'message' => $this->message,
            'roleInvited' => $this->roleInvited ? $this->roleInvited->toArray() : null,
        ];
    }
}
?>