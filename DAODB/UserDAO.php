<?php 
namespace DAODB;
use \Exception as Exception;
use DAODB\Connect as Connect;

use Helper\SessionHelper as SessionHelper;
use Models\Owner as Owner;
use DAO\IUserDAO as IUserDAO;

class UserDAO implements IUserDAO{

    private $connection;
    private $userTable = 'user';


    //Busca un usuario por correo
    public function searchUserByEmail($email){
        try {
            $query = "SELECT u.firstName, u.lastName, u.cellphone, u.birthdate, 
            u.password, u.userDescription, u.email, u.userID,
            u.answerRecovery,u.questionRecovery,u.roleID
            FROM ".$this->userTable." u WHERE email = '$email';";
            $this->connection = Connection::GetInstance();
            $resultSet = $this->connection->Execute($query);
            if($resultSet){ 
                foreach($resultSet as $row){
                    $user = new Owner();
                    $user->setfirstName($row["firstName"]);
                    $user->setLastName($row["lastName"]);
                    $user->setEmail($row["email"]);
                    $user->setCellPhone($row["cellphone"]);
                    $user->setbirthDate($row["birthdate"]);
                    $user->setPassword($row["password"]);
                    $user->setDescription($row["userDescription"]);
                    $user->setAnswerRecovery($row["answerRecovery"]);
                    $user->setQuestionRecovery($row["questionRecovery"]);
                    $user->setRol($row["roleID"]);
                }
                return $user;
            }
            else{
                return NULL; 
            }
        } catch (Exception $ex) 
        { 
            throw $ex; 
        }
    }
    public function getRoleByEmail($email){
        try {
            $query = "SELECT u.roleID
            FROM ".$this->userTable." u WHERE email = '$email';";
            $this->connection = Connection::GetInstance();
            $resultSet = $this->connection->Execute($query);
            if($resultSet){ 
                foreach($resultSet as $row){
                    $user=intval($row["roleID"]);
                }
                return $user;
            }
            else{
                return NULL; 
            }
        } catch (Exception $ex) 
        { 
            throw $ex; 
        }
    }
    public function updateFirstName($newFirstName,$emailUser){
        try{

            $query = "UPDATE ".$this->userTable." SET firstName = '$newFirstName' WHERE email = '$emailUser';";
            $this->connection = Connection::GetInstance();
            $this->connection->Execute($query);
            return $this->getRoleByEmail($emailUser);
        }catch (Exception $ex) 
        { 
            throw $ex; 
        }
    }

    public function updateLastName($newName,$emailUser){
        try{
            $query = "UPDATE ".$this->userTable." SET lastName = '$newName' WHERE email = '$emailUser';";
            $this->connection = Connection::GetInstance();
            $this->connection->Execute($query);
            return $this->getRoleByEmail($emailUser);
        }catch (Exception $ex) 
        { 
            throw $ex; 
        }
    }

    public function updateCellphone($newCellphone,$emailUser){
        try{

            $query = "UPDATE ".$this->userTable." SET cellphone = '$newCellphone' WHERE email = '$emailUser';";
            $this->connection = Connection::GetInstance();
            $this->connection->Execute($query);
            return $this->getRoleByEmail($emailUser);
        }catch (Exception $ex) 
        { 
            throw $ex; 
        }
    }

    public function updateDescription($newDescription,$emailUser){
        try{

            $query = "UPDATE ".$this->userTable." SET userDescription = '$newDescription' WHERE email = '$emailUser';";
            $this->connection = Connection::GetInstance();
            $this->connection->Execute($query);
            return $this->getRoleByEmail($emailUser);
        }catch (Exception $ex) 
        { 
            throw $ex; 
        }
    }

    public function updatePassword($newPassword,$emailUser){
        try{

            $passwordNew = MD5($newPassword);
            $query = "UPDATE ".$this->userTable." SET password = '$passwordNew' WHERE email = '$emailUser';";
            $this->connection = Connection::GetInstance();
            $this->connection->Execute($query);
            return $this->getRoleByEmail($emailUser);
        }catch (Exception $ex) 
        { 
            throw $ex; 
        }
    }

    public function updateQuestionRecovery($newQuestionRecovery,$emailUser){
        try{
            $query = "UPDATE ".$this->userTable." SET questionRecovery = '$newQuestionRecovery' WHERE email = '$emailUser';";
            $this->connection = Connection::GetInstance();
            $this->connection->Execute($query);
            return $this->getRoleByEmail($emailUser);
        }catch (Exception $ex) 
        { 
            throw $ex; 
        }
    }

    public function updateAnswerRecovery($newAnswerRecovery,$emailUser){
        try{
            $query = "UPDATE ".$this->userTable." SET answerRecovery = '$newAnswerRecovery' WHERE email = '$emailUser';";
            $this->connection = Connection::GetInstance();
            $this->connection->Execute($query);
            return $this->getRoleByEmail($emailUser);
        }catch (Exception $ex) 
        { 
            throw $ex; 
        }
    }

    public function updateEmail($newEmail,$emailUser){
        try{
            $query = "UPDATE ".$this->userTable." SET email = '$newEmail' WHERE email = '$emailUser';";
            $this->connection = Connection::GetInstance();
            $this->connection->Execute($query);
            return $this->getRoleByEmail($newEmail);
            
        } catch (Exception $ex) 
        { 
            throw $ex; 
        }
    }
    public function updateBirthdate($newBirthdate,$emailUser){
        try{
            $query = "UPDATE ".$this->userTable." SET birthdate = '$newBirthdate' WHERE email = '$emailUser';";
            $this->connection = Connection::GetInstance();
            $this->connection->Execute($query);
            return $this->getRoleByEmail($emailUser);

        } catch (Exception $ex) 
        { 
            throw $ex; 
        }
    }
    //Traemos los datos necesarios unicamente
    public function searchUserToRecovery($email){
        try {
            $query = "SELECT u.email,u.answerRecovery,u.questionRecovery
            FROM ".$this->userTable." u WHERE email = '$email';";
            $this->connection = Connection::GetInstance();
            $resultSet = $this->connection->Execute($query);
            if($resultSet){ 
                foreach($resultSet as $row){
                $user = new Owner();
                $user->setEmail($row["email"]);
                $user->setAnswerRecovery($row["answerRecovery"]);
                $user->setQuestionRecovery($row["questionRecovery"]);
                }
                return $user;
            }else{
                return NULL; 
            }
        } catch (Exception $ex) 
        { 
            throw $ex; 
        }
    }
}
?>