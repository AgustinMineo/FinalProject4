<?php
namespace Models;

class GroupRole {
    private $id;
    private $name;
    private $is_active;
    private $description;

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

    public function getIsActive() {
        return $this->is_active;
    }

    public function setIsActive($is_active) {
        $this->is_active = $is_active;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description) {
        $this->description = $description;
    }
    public function toArray() {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'is_active' => $this->is_active,
            'description' => $this->description,
        ];
    }
}