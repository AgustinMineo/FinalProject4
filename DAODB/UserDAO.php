<?php 
namespace DAODB;
use \Exception as Exception;
use DAODB\Connect as Connect;

use Helper\SessionHelper as SessionHelper;
use Models\Owner as Owner;
use DAO\IUserDAO as IUserDAO;
use DAO\OwnerDAO as OwnerDAO;
use DAO\KeeperDAO as KeeperDAO;
class UserDAO implements IUserDAO{
    
    private $connection;
    private $userTable = 'user';
    private $keeperTable = 'keeper';
    private $ownerTable = 'owner';


    //Busca los usuarios activos (MessageController la usa)
    public function getUserByIdReduce($userID){
        try {
            $query = "SELECT u.userID,u.firstName, u.lastName, u.cellphone, u.birthdate, 
            u.userDescription, u.email,u.roleID,u.status,u.userImage
            FROM ".$this->userTable." u WHERE status = 1 and userID='$userID';";
            $this->connection = Connection::GetInstance();
            $resultSet = $this->connection->Execute($query);
            if($resultSet){ 
                foreach($resultSet as $row){
                    $user = new Owner();
                    $user->setUserID($row["userID"]);
                    $user->setfirstName($row["firstName"]);
                    $user->setLastName($row["lastName"]);
                    $user->setEmail($row["email"]);
                    $user->setCellPhone($row["cellphone"]);
                    $user->setbirthDate($row["birthdate"]);
                    $user->setDescription($row["userDescription"]);
                    $user->setRol($row["roleID"]);
                    $user->setStatus($row["status"]);
                    $user->setImage($row["userImage"]);
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
    //Busca un usuario por correo
    public function searchUserByEmail($email){
        try {
            $query = "SELECT u.userID,u.firstName, u.lastName, u.cellphone, u.birthdate, 
            u.password, u.userDescription, u.email, u.userID,
            u.answerRecovery,u.questionRecovery,u.roleID,u.status,u.userImage
            FROM ".$this->userTable." u WHERE email = '$email';";
            $this->connection = Connection::GetInstance();
            $resultSet = $this->connection->Execute($query);
            if($resultSet){ 
                foreach($resultSet as $row){
                    $user = new Owner();
                    $user->setUserID($row['userID']);
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
                    $user->setStatus($row["status"]);
                    $user->setImage($row["userImage"]);
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
            $passwordNew = md5($newPassword);
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
            $query = "SELECT u.email,u.answerRecovery,u.questionRecovery,u.status
            FROM ".$this->userTable." u WHERE email = '$email';";
            $this->connection = Connection::GetInstance();
            $resultSet = $this->connection->Execute($query);
            if($resultSet){ 
                foreach($resultSet as $row){
                $user = new Owner();
                $user->setEmail($row["email"]);
                $user->setAnswerRecovery($row["answerRecovery"]);
                $user->setQuestionRecovery($row["questionRecovery"]);
                $user->setStatus($row["status"]);
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
    public function deleteUser($emailUser,$status){//La eliminación se va a hacer de forma logica
        try{
            $query = "UPDATE ".$this->userTable." SET status = '$status' WHERE email = '$emailUser';";
            $this->connection = Connection::GetInstance();
            $this->connection->Execute($query);
            return $this->getRoleByEmail($emailUser);
            
        } catch (Exception $ex) 
        { 
            throw $ex; 
        }
    }
    public function updateImage($imagePath,$emailUser){
        try{
            $query = "UPDATE ".$this->userTable." SET userImage = '$imagePath' WHERE email = '$emailUser';";
            $this->connection = Connection::GetInstance();
            $this->connection->Execute($query);
            return $this->getRoleByEmail($emailUser);

        } catch (Exception $ex) 
        { 
            throw $ex; 
        }
    }
}
?>