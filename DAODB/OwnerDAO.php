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

    public function AddOwner (Owner $owner){
        try {
           $query = "INSERT INTO ".$this->userTable."(userID, firstName, lastName, email, cellphone, birthdate, password, userImage, userDescription)
        VALUES (:userID,:firstName, :lastName, :email, :cellphone, :birthdate, :password, :userImage, :userDescription);";
                    $parameters["userID"] = NULL;
                    $parameters["firstName"] = $owner->getfirstName();
                    $parameters["lastName"] = $owner->getLastName();
                    $parameters["email"] = $owner->getEmail();
                    $parameters["cellphone"] = $owner->getCellPhone();
                    $parameters["birthdate"] = $owner->getbirthDate();
                    $parameters["password"] = $owner->getPassword();
                    $parameters["userImage"] = $owner->getImage();
                    $parameters["userDescription"] = $owner->getDescription();
                    $this->connection = Connection::GetInstance();
                     if($this->connection->ExecuteNonQuery($query, $parameters)){
                        $id = $this->bringUserID($owner->getEmail());
                        $queryOwner = "INSERT INTO ".$this->ownerTable."(ownerId, userID, petAmount)
                                       VALUES (:ownerId, :userID, :petAmount);
                        ";

                        $parametersOwner["ownerId"] = NULL;
                        $parametersOwner["userID"] = $id;
                        $parametersOwner["petAmount"] = '0';

                        $this->connection->ExecuteNonQuery($queryOwner, $parametersOwner);
                     };
            

        } catch (Exception $ex) {
            throw $ex;
        }   

    }

    public function GetAllOwner(){

        try {
            $ownerList = array();

            $query = "SELECT * FROM ".$this->userTable;

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
                $owner->setImage($row["userImage"]);
                $owner->setDescription($row["userDescription"]);

                array_push($ownerList, $owner);
            }
            return $ownerList;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function searchOwner($email, $password){

        try {
            $query = "SELECT firstName, lastName, email, cellphone, birthdate, password, userImage, userDescription FROM ".$this->userTable." WHERE email = '$email' AND password = $password;";
            
            $this->connection = Connection::GetInstance();

            $resultSet = $this->connection->Execute($query);

            
            if($resultSet)
            {
                foreach($resultSet as $row){

                $owner = new Owner();
                $owner->setfirstName($row["firstName"]);
                $owner->setLastName($row["lastName"]);
                $owner->setEmail($row["email"]);
                $owner->setCellPhone($row["cellphone"]);
                $owner->setbirthDate($row["birthdate"]);
                $owner->setPassword($row["password"]);
                $owner->setImage($row["userImage"]);
                $owner->setDescription($row["userDescription"]);
                return $owner;
            }
        }
        else{
                echo "El email ingresado no existe";
            }
        } catch (Exception $ex) {
            throw $ex;
        }

    }
    public function bringUserID($email){
        try {
            $query = "SELECT userID FROM ".$this->userTable." WHERE email = '$email';";
            
            $this->connection = Connection::GetInstance();

            $resultSet = $this->connection->Execute($query);

            var_dump($resultSet);
            var_dump($resultSet);

            foreach($resultSet as $row){
                $id = $row['userID'];
            }
            return $id;
    }
    catch (Exception $ex) {
        throw $ex;
    }
 }
}

?>