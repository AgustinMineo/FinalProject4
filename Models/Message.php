<?php
namespace Models;

class Message {
    private $id;
    private $senderId;
    private $receiverId;
    private $groupId;
    private $message;
    private $sentAt;
    private $is_read;

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getSenderId() {
        return $this->senderId;
    }

    public function setSenderId($senderId) {
        $this->senderId = $senderId;
    }

    public function getReceiverId() {
        return $this->receiverId;
    }

    public function setReceiverId($receiverId) {
        $this->receiverId = $receiverId;
    }

    public function getGroupId() {
        return $this->groupId;
    }

    public function setGroupId($groupId) {
        $this->groupId = $groupId;
    }

    public function getMessage() {
        return $this->message;
    }

    public function setMessage($message) {
        $this->message = $message;
    }

    public function getSentAt() {
        return $this->sentAt;
    }

    public function setSentAt($sentAt) {
        $this->sentAt = $sentAt;
    }
    public function getIsRead() {
        return $this->is_read;
    }

    public function setIsRead($is_read) {
        $this->is_read = $is_read;
    }
}
?>