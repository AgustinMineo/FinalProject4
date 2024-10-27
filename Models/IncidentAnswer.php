<?php
namespace Models;

class IncidentAnswer {
    private $id;
    private $incidentId;
    private $userId;
    private $answerDate;
    private $answer;

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getIncidentId() {
        return $this->incidentId;
    }

    public function setIncidentId($incidentId) {
        $this->incidentId = $incidentId;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function setUserId($userId) {
        $this->userId = $userId;
    }

    public function getAnswerDate() {
        return $this->answerDate;
    }

    public function setAnswerDate($answerDate) {
        $this->answerDate = $answerDate;
    }

    public function getAnswer() {
        return $this->answer;
    }

    public function setAnswer($answer) {
        $this->answer = $answer;
    }
    public function toArray() {
        return [
            'id' => $this->id,
            'incidentId' => $this->incidentId,
            'userId' => $this->userId ? $this->userId->toArray() : null,
            'answerDate' => $this->answerDate,
            'answer' => $this->answer,
        ];
    }
    
}

?>