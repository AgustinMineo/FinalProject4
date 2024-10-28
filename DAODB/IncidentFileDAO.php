<?php
namespace DAODB;

use \PDO as PDO;
use \Exception as Exception;
use Models\IncidentFile as IncidentFile;

class IncidentFileDAO
{
    private $incidentFileTable = 'incident_files';

    // Método para agregar un nuevo archivo asociado a una incidencia
    public function saveIncidentFile($incidentId, $filePath){
        try {
            $query = "INSERT INTO " . $this->incidentFileTable . " (incident_id, file_path) 
                      VALUES (:incident_id, :file_path)";
            
            $parameters['incident_id'] = $incidentId;
            $parameters['file_path'] = $filePath;
            $this->connection = Connection::GetInstance();
            $this->connection->Execute($query, $parameters);
            return true;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    // Método para obtener todos los archivos de una incidencia
    public function getFilesByIncidentId($incidentId){
        try {
            $query = "SELECT * FROM " . $this->incidentFileTable . " WHERE incident_id = :incidentId";
            $parameters['incidentId'] = $incidentId;
            $this->connection = Connection::GetInstance();
            $resultSet = $this->connection->Execute($query, $parameters);
            if(is_array($resultSet) && count($resultSet)>0){
                $files = array();
                foreach ($resultSet as $row) {
                    $file = new IncidentFile();
                    $file->setId($row['id']);
                    $file->setIncidentId($row['incident_id']);
                    $file->setFilePath($row['file_path']);
                    $file->setCreatedAt($row['created_at']);
                    array_push($files,$file->toArray());
                }
                return $files; 
            }else{
                return [];
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    // Método para obtener el nombre del archivo por su ID
    public function getFilePathById($fileId){
        try {
            $query = "SELECT file_path FROM " . $this->incidentFileTable . " WHERE id = :file_id";
            $parameters = ['file_id' => $fileId];

            $resultSet = $this->connection->Execute($query, $parameters);
            $filePath = null;
            foreach ($resultSet as $row) {
                $filePath = $row['file_path'];
            }
            return $filePath;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
}
