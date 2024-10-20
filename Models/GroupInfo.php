<?php
namespace Models;


class GroupInfo {
    private $id;
    private $description;
    private $rules;
    private $image;
    private $createdAt;
    private $updatedAt;
    private $start_date;
    private $end_date;

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function getRules() {
        return $this->rules;
    }

    public function setRules($rules) {
        $this->rules = $rules;
    }

    public function getImage() {
        return $this->image;
    }

    public function setImage($image) {
        $this->image = $image;
    }

    public function getCreatedAt() {
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt) {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt() {
        return $this->updatedAt;
    }

    public function setUpdatedAt($updatedAt) {
        $this->updatedAt = $updatedAt;
    }

    public function getStartDate() {
        return $this->start_date;
    }

    public function setStartDate($start_date) {
        $this->start_date = $start_date;
    }

    public function getEndDate() {
        return $this->end_date;
    }
    public function setEndDate($end_date) {
        $this->end_date = $end_date;
    }
    public function toArray() {
        return [
            'id' => $this->id,
            'description' => $this->description,
            'rules' => $this->rules,
            'image' => $this->image,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
        ];
    }
}

?>