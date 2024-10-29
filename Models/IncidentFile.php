<?php
namespace Models;
class IncidentFile {
    private $id;
    private $incidentId;
    private $filePath;
    private $createdAt;

    public function getId() {
        return $this->id;
    }
    public function setId($id) {
        $this->id=$id;
    }

    public function getIncidentId() {
        return $this->incidentId;
    }
    public function setIncidentId($incidentId) {
        $this->incidentId=$incidentId;
    }

    public function getFilePath() {
        return $this->filePath;
    }
    public function setFilePath($filePath) {
        $this->filePath=$filePath;
    }

    public function getCreatedAt() {
        return $this->createdAt;
    }
    public function setCreatedAt($createdAt) {
        $this->createdAt=$createdAt;
    }

    public function toArray() {
        return [
            'id' => $this->id,
            'incident_id' => $this->incidentId,
            'file_path' => $this->filePath,
            'created_at' => $this->createdAt,
        ];
    }
}

?>