<?php 
namespace DAODB;

use \Exception as Exception;
use DAODB\Connection as Connection;
use DAO\IOwnerDAO as IOwnerDAO;
use Models\Owner as Owner;

class OwnerDAO{
   private $connection;
   private $userTable = 'user';
   private $ownerTable = 'owner';

    //Funcion para agregar un Owner a la DB
    public function AddOwner (Owner $owner){
        try {
           $query = "INSERT INTO ".$this->userTable."(userID, firstName, lastName, email, cellphone, birthdate, password, userDescription)
           VALUES (:userID,:firstName, :lastName, :email, :cellphone, :birthdate, :password, :userDescription);";
                    $parameters["userID"] = NULL;
                    $parameters["firstName"] = $owner->getfirstName();
                    $parameters["lastName"] = $owner->getLastName();
                    $parameters["email"] = $owner->getEmail();
                    $parameters["cellphone"] = $owner->getCellPhone();
                    $parameters["birthdate"] = $owner->getbirthDate();
                    $parameters["password"] = MD5($owner->getPassword());
                    $parameters["userDescription"] = $owner->getDescription();
                    $this->connection = Connection::GetInstance();
                     if($this->connection->ExecuteNonQuery($query, $parameters)){
                        $queryOwner = "INSERT INTO ".$this->ownerTable."(ownerId, userID, petAmount)
                                       VALUES (:ownerId, :userID, :petAmount);
                        ";
                        $parametersOwner["ownerId"] = NULL;
                        //Traemos el USER ID de la base y pasamos como parametro.
                        $parametersOwner["userID"] = $this->bringUserID($owner->getEmail());
                        $parametersOwner["petAmount"] = $owner->getPetAmount();

                        $this->connection->ExecuteNonQuery($queryOwner, $parametersOwner);
                     };
        } catch (Exception $ex) {
            throw $ex;
        }   

    }
    //Funcion para obtener todos los Owner de la DB
    public function GetAllOwner(){

        try {
            $ownerList = array();

            $query = "SELECT u.firstName, u.lastName, u.email, u.cellphone, u.birthdate, u.password,u.userDescription,o.petAmount,o.ownerID
            FROM ".$this->userTable." u 
            INNER JOIN ".$this->ownerTable." o ON o.userID = u.userID";

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
                $owner->setDescription($row["userDescription"]);
                $owner->setPetAmount($row["petAmount"]);
                $owner->setOwnerId($row["ownerID"]);
                array_push($ownerList, $owner);
            }
            return $ownerList;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    //Funcion para validar que no exista el mail
    public function searchOwnerByEmail($email){
        try {
                $query = "SELECT u.firstName, u.lastName, u.cellphone, u.birthdate, u.password, u.userDescription, u.email, o.petAmount, o.ownerID  
                FROM ".$this->userTable." u 
                INNER JOIN ".$this->ownerTable." o ON u.userID = o.userID 
                WHERE email = '$email';";
                $this->connection = Connection::GetInstance();
                $resultSet = $this->connection->Execute($query);
                if($resultSet){ 
                    foreach($resultSet as $row){
                        $owner = new Owner();
                
                        $owner->setfirstName($row["firstName"]);
                        $owner->setLastName($row["lastName"]);
                        $owner->setEmail($row["email"]);
                        $owner->setCellPhone($row["cellphone"]);
                        $owner->setbirthDate($row["birthdate"]);
                        $owner->setPassword($row["password"]);
                        $owner->setDescription($row["userDescription"]);
                        $owner->setPetAmount($row["petAmount"]);
                        $owner->setOwnerId($row["ownerID"]);
                    }
                    return $owner;
                 }
                else{ return NULL; }
        }  catch (Exception $ex) { throw $ex; }
    }
    //Funcion para buscar Owner para iniciar seseion
    public function searchOwnerToLogin($email, $password){
        try {
            $query = "SELECT o.ownerID, u.firstName, u.lastName, u.email, u.cellphone, u.birthdate, u.password, u.userDescription, o.petAmount FROM 
            ".$this->userTable." u 
            RIGHT JOIN ".$this->ownerTable." o ON u.userID = o.userID 
            WHERE email = '$email' AND password = md5($password);";

            $this->connection = Connection::GetInstance();

            $resultSet = $this->connection->Execute($query);

            
            if($resultSet)
            {
                foreach($resultSet as $row){

                $owner = new Owner();
                $owner->setOwnerId($row["ownerID"]);
                $owner->setfirstName($row["firstName"]);
                $owner->setLastName($row["lastName"]);
                $owner->setEmail($row["email"]);
                $owner->setCellPhone($row["cellphone"]);
                $owner->setbirthDate($row["birthdate"]);
                $owner->setPassword($row["password"]);
                $owner->setDescription($row["userDescription"]);
                $owner->setPetAmount($row["petAmount"]);
                return $owner;
            }
        }
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    //Buscamos el USER ID del usuario con el mail.
    public function bringUserID($email){
        try {
            $query = "SELECT userID FROM ".$this->userTable." WHERE email = '$email';";
            
            $this->connection = Connection::GetInstance();

            $resultSet = $this->connection->Execute($query);

            foreach($resultSet as $row){
                $id = $row['userID'];
            }
            return $id;
        }
        catch (Exception $ex) {
        throw $ex;
        }
    }
    public function updateName($newName,$emailUser){
        $query = "UPDATE ".$this->userTable." SET lastName = '$newName' WHERE email = '$emailUser';";
        $this->connection = Connection::GetInstance();
        $this->connection->Execute($query);
        $owner = $this->searchOwnerByEmail($emailUser);
        return $owner;
    }
    public function updateFirstName($newFirstName,$emailUser){
        $query = "UPDATE ".$this->userTable." SET firstName = '$newFirstName' WHERE email = '$emailUser';";
        $this->connection = Connection::GetInstance();
        $this->connection->Execute($query);
        return $this->searchOwnerByEmail($emailUser);
    }
    public function updateCellphone($newCellphone,$emailUser){
        $query = "UPDATE ".$this->userTable." SET cellphone = '$newCellphone' WHERE email = '$emailUser';";
        $this->connection = Connection::GetInstance();
        $this->connection->Execute($query);
        return $this->searchOwnerByEmail($emailUser);
    }
    public function updateDescription($newDescription,$emailUser){
        $query = "UPDATE ".$this->userTable." SET userDescription = '$newDescription' WHERE email = '$emailUser';";
        $this->connection = Connection::GetInstance();
        $this->connection->Execute($query);
            return $this->searchOwnerByEmail($emailUser);
    }
}?>