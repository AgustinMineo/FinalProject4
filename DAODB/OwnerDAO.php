<?php 
namespace DAODB;

use \Exception as Exception;
use DAODB\Connection as Connection;
use DAO\IOwnerDAO as IOwnerDAO;
use Models\Owner as Owner;

class OwnerDAO{
   private $connection;
   private $tableName = 'user';

    public function AddOwner (Owner $owner){
        try {
           $query = "INSERT INTO ".$this->tableName."(userID,firstName, lastName, email, cellphone, birthdate, password)
        VALUES (:userID,:firstName, :lastName, :email, :cellphone, :birthdate, :password);";
                     $parameters["userID"] = NULL;
                     $parameters["firstName"] = $owner->getfirstName();
                     $parameters["lastName"] = $owner->getLastName();
                     $parameters["email"] = $owner->getEmail();
                     $parameters["cellphone"] = $owner->getCellPhone();
                     $parameters["birthdate"] = $owner->getbirthDate();
                     $parameters["password"] = $owner->getPassword();

                     $this->connection = Connection::GetInstance();
                     $this->connection->ExecuteNonQuery($query, $parameters);

        } catch (Exception $ex) {
            throw $ex;
        }   

    }

    public function GetAllOwner(){

        try {
            $ownerList = array();

            $query = "SELECT * FROM ".$this->tableName;

            $this->connection = Connection::GetInstance();

            $resultSet = $this->connection->Execute($query);

            foreach($resultSet as $row){
                $owner = new Owner();
                $owner->setfirstName($row["firstName"]);
                $owner->setLastName($row["lastName"]);
                $owner->setEmail($row["email"]);
                $owner->setCellPhone($row["cellphone"]);
                $owner->setbirthDate($row["birthdate"]);
                $owner->setPassword($row["password"]);

                array_push($ownerList, $owner);
            }
            return $ownerList;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
}
?>