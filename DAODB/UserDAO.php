<?php 
namespace DAODB;
use \Exception as Exception;
use DAODB\Connect as Connect;

use Helper\SessionHelper as SessionHelper;
use Models\User as User;
use DAO\IUserDAO as IUserDAO;

class UserDAO implements IUserDAO{

    private $connection;
    private $userTable = 'user';

    //Busca un usuario por correo
    public function searchUserByEmail($email){
        try {
            $query = "SELECT u.firstName, u.lastName, u.cellphone, u.birthdate, 
            u.password, u.userDescription, u.email, o.petAmount, o.userID,
            u.answerRecovery,u.questionRecovery
            FROM ".$this->userTable." u WHERE email = '$email';";
            $this->connection = Connection::GetInstance();
            $resultSet = $this->connection->Execute($query);
            if($resultSet){ 
                foreach($resultSet as $row){
                    $user = new User();
                    $user->setfirstName($row["firstName"]);
                    $user->setLastName($row["lastName"]);
                    $user->setEmail($row["email"]);
                    $user->setCellPhone($row["cellphone"]);
                    $user->setbirthDate($row["birthdate"]);
                    $user->setPassword($row["password"]);
                    $user->setDescription($row["userDescription"]);
                    $user->setPetAmount($row["petAmount"]);
                    $user->setAnswerRecovery($row["answerRecovery"]);
                    $user->setQuestionRecovery($row["questionRecovery"]);
                }
                return $user;
             }
            else{ return NULL; }
    }  catch (Exception $ex) { throw $ex; }
    }

    public function updateFirstName($newFirstName,$emailUser){
        $query = "UPDATE ".$this->userTable." SET firstName = '$newFirstName' WHERE email = '$emailUser';";
        $this->connection = Connection::GetInstance();
        $this->connection->Execute($query);
        return $this->searchUserByEmail($emailUser);
    }

    public function updateLastName($newName,$emailUser){
        $query = "UPDATE ".$this->userTable." SET lastName = '$newName' WHERE email = '$emailUser';";
        $this->connection = Connection::GetInstance();
        $this->connection->Execute($query);
        $user = $this->searchUserByEmail($emailUser);
        return $user;
    }

    public function updateCellphone($newCellphone,$emailUser){
        $query = "UPDATE ".$this->userTable." SET cellphone = '$newCellphone' WHERE email = '$emailUser';";
        $this->connection = Connection::GetInstance();
        $this->connection->Execute($query);
        return $this->searchUserByEmail($emailUser);
    }

    public function updateDescription($newDescription,$emailUser){
        $query = "UPDATE ".$this->userTable." SET userDescription = '$newDescription' WHERE email = '$emailUser';";
        $this->connection = Connection::GetInstance();
        $this->connection->Execute($query);
            return $this->searchUserByEmail($emailUser);
    }

    public function updatePassword($newPassword,$emailUser){
        $passwordNew = MD5($newPassword);
        $query = "UPDATE ".$this->userTable." SET password = '$passwordNew' WHERE email = '$emailUser';";
        $this->connection = Connection::GetInstance();
        $this->connection->Execute($query);
            return $this->searchUserByEmail($emailUser);
    }

    public function updateQuestionRecovery($newQuestionRecovery,$emailUser){
        $query = "UPDATE ".$this->userTable." SET questionRecovery = '$newQuestionRecovery' WHERE email = '$emailUser';";
        $this->connection = Connection::GetInstance();
        $resultExecute = $this->connection->Execute($query);
        if($resultExecute){
            return $this->searchUserByEmail($emailUser);
        }
    }

    public function updateAnswerRecovery($newAnswerRecovery,$emailUser){
        $query = "UPDATE ".$this->userTable." SET answerRecovery = '$newAnswerRecovery' WHERE email = '$emailUser';";
        $this->connection = Connection::GetInstance();
        $resultExecute = $this->connection->Execute($query);
        if($resultExecute){
            return $this->searchUserByEmail($emailUser);
        }
    }

    public function updateEmail($newEmail,$emailUser){
        //Buscamos si existe el correo y validamos, si existe damos error, sino modificamos
        $validateEmail = $this->searchUserByEmail($newEmail);
        if($validateEmail == NULL){
            $query = "UPDATE ".$this->userTable." SET email = '$newEmail' WHERE email = '$emailUser';";
            $this->connection = Connection::GetInstance();
            $resultExecute = $this->connection->Execute($query);
            if($resultExecute){
                return $this->searchUserByEmail($emailUser);
            }
        }else{
            return null;
        }
    }
}
?>