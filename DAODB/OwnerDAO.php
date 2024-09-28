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
            $query = "INSERT INTO ".$this->userTable."(userID, firstName, lastName, email, cellphone, 
            birthdate, password, userDescription,questionRecovery,answerRecovery,roleID)
            VALUES (:userID,:firstName, :lastName, :email, :cellphone, :birthdate, :password, 
            :userDescription, :questionRecovery, :answerRecovery,:roleID);";
                    $parameters["userID"] = NULL;
                    $parameters["firstName"] = $owner->getfirstName();
                    $parameters["lastName"] = $owner->getLastName();
                    $parameters["email"] = $owner->getEmail();
                    $parameters["cellphone"] = $owner->getCellPhone();
                    $parameters["birthdate"] = $owner->getbirthDate();
                    $parameters["password"] = MD5($owner->getPassword());
                    $parameters["userDescription"] = $owner->getDescription();
                    $parameters["questionRecovery"] = $owner->getQuestionRecovery();
                    $parameters["answerRecovery"] = $owner->getAnswerRecovery();
                    $parameters["roleID"] = $owner->getRol();
                    $this->connection = Connection::GetInstance();
                    if($this->connection->ExecuteNonQuery($query, $parameters) 
                        && intval($owner->getRol())===2){
                        //Se agrega validación de rol === 2 para poder crear los usuarios admin 
                        //Sobre la tabla de owners sin crear un registro sobre la tabla owner
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

            $query = "SELECT u.firstName, u.lastName, u.email, u.cellphone, 
            u.birthdate, u.password,u.userDescription,o.petAmount,o.ownerID,
            u.answerRecovery,u.questionRecovery,u.roleID
            FROM ".$this->userTable." u 
            INNER JOIN ".$this->ownerTable." o ON o.userID = u.userID and u.roleID=2";

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
                $owner->setAnswerRecovery($row["answerRecovery"]);
                $owner->setQuestionRecovery($row["questionRecovery"]);
                $owner->setRol($row["roleID"]);
                array_push($ownerList, $owner);
            }
            return $ownerList;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    //Busco los perfiles que tengan un roleID=1;
    public function GetAllAdminUser(){

        try {
            $ownerList = array();

            $query = "SELECT u.firstName, u.lastName, u.email, u.cellphone, 
            u.birthdate, u.password,u.userDescription,
            u.answerRecovery,u.questionRecovery,u.roleID
            FROM ".$this->userTable." u where u.roleID=1";

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
                //$owner->setPetAmount($row["petAmount"]);
               // $owner->setOwnerId($row["ownerID"]);
                $owner->setAnswerRecovery($row["answerRecovery"]);
                $owner->setQuestionRecovery($row["questionRecovery"]);
                $owner->setRol($row["roleID"]);
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
                $query = "SELECT u.firstName, u.lastName, u.cellphone, u.birthdate, 
                u.password, u.userDescription, u.email, o.petAmount, o.ownerID,
                u.answerRecovery,u.questionRecovery,u.roleID
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
                        $owner->setAnswerRecovery($row["answerRecovery"]);
                        $owner->setQuestionRecovery($row["questionRecovery"]);
                        $owner->setRol($row["roleID"]);
                    }
                    return $owner;
                 }
                else{ return NULL; }
        }  catch (Exception $ex) { throw $ex; }
    }
    public function searchAdminByEmail($email){
        try {
            $query = "SELECT u.firstName, u.lastName, u.cellphone, u.birthdate, 
            u.password, u.userDescription, u.email,
            u.answerRecovery,u.questionRecovery,u.roleID
            FROM ".$this->userTable." u WHERE u.email = '$email';";
            $this->connection = Connection::GetInstance();
            $resultSet = $this->connection->Execute($query);
            if($resultSet){ 
                foreach($resultSet as $row){
                    $admin = new Owner();
                    $admin->setfirstName($row["firstName"]);
                    $admin->setLastName($row["lastName"]);
                    $admin->setEmail($row["email"]);
                    $admin->setCellPhone($row["cellphone"]);
                    $admin->setbirthDate($row["birthdate"]);
                    $admin->setPassword($row["password"]);
                    $admin->setDescription($row["userDescription"]);
                    $admin->setAnswerRecovery($row["answerRecovery"]);
                    $admin->setQuestionRecovery($row["questionRecovery"]);
                    $admin->setRol($row["roleID"]);
                }
                return $admin;
             }
            else{ return NULL; }
    }  catch (Exception $ex) { throw $ex; }
    }
    //Funcion para buscar Owner para iniciar seseion
    public function searchOwnerToLogin($email, $password){
        try {
            $query = "SELECT o.ownerID, u.firstName, u.lastName, u.email, u.cellphone, 
            u.birthdate, u.password, u.userDescription, o.petAmount,u.answerRecovery,
            u.questionRecovery,u.roleID FROM 
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
                $owner->setAnswerRecovery($row["answerRecovery"]);
                $owner->setQuestionRecovery($row["questionRecovery"]);
                $owner->setRol($row["roleID"]);
                return $owner;
            }
        }
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    //Se va a utilizar para mostrar información en listados.
    public function searchBasicInfoOwnerByID($ownerID){
        try {
            $query = "SELECT u.firstName, u.lastName, u.cellphone, u.birthdate, 
            u.userDescription, u.email, o.petAmount, o.ownerID,u.roleID
            FROM ".$this->userTable." u 
            INNER JOIN ".$this->ownerTable." o ON u.userID = o.userID 
            WHERE o.ownerID = '$ownerID';";
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
                    $owner->setDescription($row["userDescription"]);
                    $owner->setPetAmount($row["petAmount"]);
                    $owner->setOwnerId($row["ownerID"]);
                    $owner->setRol($row["roleID"]);
                }
                return $owner;
             }
            else{ return NULL; }
    }  catch (Exception $ex) { throw $ex; }
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

    public function incrementPetAmount($ownerID){
        try{
            $queryAmount = "SELECT petAmount FROM ".$this->ownerTable." WHERE ownerID = '$ownerID';";
            $this->connection = Connection::GetInstance();
            $resultSet = $this->connection->Execute($queryAmount);
            if($resultSet){
                foreach($resultSet as $row){
                    $amount = intval($row['petAmount']);
                }
                $amount += 1;
                $query = "UPDATE ".$this->ownerTable." SET petAmount = '$amount' WHERE ownerID = '$ownerID';";
                $this->connection = Connection::GetInstance();
                $this->connection->Execute($query);
            }
            return true;
        }catch (Exception $ex) { 
            throw $ex; 
        }
    }
}?>