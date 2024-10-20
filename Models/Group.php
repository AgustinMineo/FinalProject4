<?php
namespace Models;

class Group {
    private $id;
    private $name;
    private $created_by;//Id del usuario que lo creo
    private $created_at;
    private $groupType;
    private $statusId;
    private $groupPrivacy; //Privacidad del grupo
    private $groupInfo;

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getCreated_by() {
        return $this->created_by;
    }

    public function setCreated_by($created_by) {
        $this->created_by = $created_by;
    }

    public function getCreated_at() {
        return $this->created_at;
    }

    public function setCreated_at($created_at) {
        $this->created_at = $created_at;
    }

    public function getGroupType() {
        return $this->groupType;
    }

    public function setGroupType($groupType) {
        $this->groupType = $groupType;
    }

    public function getStatusId() {
        return $this->statusId;
    }

    public function setStatusId($statusId) {
        $this->statusId = $statusId;
    }

    public function getGroupPrivacy() {
        return $this->groupPrivacy;
    }

    public function setGroupPrivacy($groupPrivacy) {
        $this->groupPrivacy = $groupPrivacy;
    }
    
    public function getGroupInfo(){
        return $this->groupInfo;
    }
    public function setGroupInfo($groupInfo){
        $this->groupInfo= $groupInfo;
    }
    //Para Serializar el objeto cuando lo envio al front con ajax 
    public function toArray() {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'created_by' => $this->created_by ? $this->created_by->toArray() : null,
            'created_at' => $this->created_at,
            'groupType' => $this->groupType ? $this->groupType->toArray() : null,
            'statusId' => $this->statusId ? $this->statusId->toArray() : null,
            'groupPrivacy' => $this->groupPrivacy ? $this->groupPrivacy->toArray() : null,
            'groupInfo' => $this->groupInfo ? $this->groupInfo->toArray() : null,
        ];
    }
}
?>