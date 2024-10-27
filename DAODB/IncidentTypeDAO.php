<?php
namespace DAODB;
use \PDO as PDO;
use \Exception as Exception;
use Models\IncidentType;

class IncidentTypeDAO {
    
    private $incidentTypeTable = 'incident_type';

    public function getAllIncidentTypes() {
        try {
            $query = "SELECT * FROM " . $this->incidentTypeTable;
            $this->connection = Connection::GetInstance();
            $resultSet = $this->connection->Execute($query);

            $typeList = array();
            foreach ($resultSet as $row) {
                $type = new IncidentType();
                $type->setId($row['id']);
                $type->setName($row['name']);
                $type->setDescription($row['description']);
                $type->setIsActive($row['is_active']);
                array_push($typeList, $type);
            }
            return $typeList;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function getIncidentTypeById($typeId) {
        try {
            $query = "SELECT * FROM " . $this->incidentTypeTable . " WHERE id = :typeId";
            $parameters['typeId'] = $typeId;

            $this->connection = Connection::GetInstance();
            $resultSet = $this->connection->Execute($query, $parameters);

            if (!empty($resultSet)) {
                $row = $resultSet[0];
                $type = new IncidentType();
                $type->setId($row['id']);
                $type->setName($row['name']);
                $type->setDescription($row['description']);
                $type->setIsActive($row['is_active']);
                
                return $type;
            }
            return null;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
}

?>