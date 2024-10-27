<?php
namespace Models;

class Incident {
    private $id;
    private $userId;
    private $incidentTypeId;
    private $statusId;
    private $incidentDate;
    private $description;
    private $answers = [];

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function setUserId($userId) {
        $this->userId = $userId;
    }

    public function getIncidentTypeId() {
        return $this->incidentTypeId;
    }

    public function setIncidentTypeId($incidentTypeId) {
        $this->incidentTypeId = $incidentTypeId;
    }

    public function getStatusId() {
        return $this->statusId;
    }

    public function setStatusId($statusId) {
        $this->statusId = $statusId;
    }

    public function getIncidentDate() {
        return $this->incidentDate;
    }

    public function setIncidentDate($incidentDate) {
        $this->incidentDate = $incidentDate;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description) {
        $this->description = $description;
    }
    public function setAnswers(array $answers) {
        $this->answers = $answers;
    }

    public function getAnswers() {
        return $this->answers;
    }
    public function toArray() {
        return [
            'id' => $this->id,
            'userId' => $this->userId ? $this->userId->toArray() : null,
            'incidentTypeId' => $this->incidentTypeId ? $this->incidentTypeId->toArray() : null,
            'statusId' => $this->statusId ? $this->statusId->toArray() : null, 
            'incidentDate' => $this->incidentDate,
            'description' => $this->description,
            'answers' => array_map(function($answer) {
                return $answer->toArray();
            }, $this->answers), 
        ];
    }
}

?>