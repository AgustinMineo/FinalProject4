<?php
namespace DAODB;
use \Exception as Exception;
use DAODB\Connect as Connect;
use DAO\IKeeperDAO as IKeeperDAO;
use Models\Keeper as Keeper;
class KeeperDAO implements IKeeperDAO{
    private $connection;
    private $userTable = 'user';
    private $keeperTable = 'keeper';
    private $keeperDaysTable = 'keeperdays';


    public function AddKeeper (Keeper $keeper){
      try {
         $query = "INSERT INTO ".$this->userTable."(userID, firstName, lastName, email, cellphone, birthdate, password, userImage, userDescription)
      VALUES (:userID,:firstName, :lastName, :email, :cellphone, :birthdate, :password, :userImage, :userDescription);";
                  $parameters["userID"] = NULL;
                  $parameters["firstName"] = $keeper->getfirstName();
                  $parameters["lastName"] = $keeper->getLastName();
                  $parameters["email"] = $keeper->getEmail();
                  $parameters["cellphone"] = $keeper->getCellPhone();
                  $parameters["birthdate"] = $keeper->getbirthDate();
                  $parameters["password"] = MD5($keeper->getPassword());
                  $parameters["userImage"] = $keeper->getImage();
                  $parameters["userDescription"] = $keeper->getDescription();

                  $this->connection = Connection::GetInstance();

                   if($this->connection->ExecuteNonQuery($query, $parameters)){
                      $id = $this->searchKeeperByEmail($keeper->getEmail());
                      $queryKeeper = "INSERT INTO ".$this->keeperTable."(keeperID, userID, animalSize, price)
                                     VALUES (:keeperID, :userID, :animalSize, :price);
                      ";

                      $parametersKeeper["keeperID"] = NULL;
                      $parametersKeeper["userID"] = $id;
                      $parametersKeeper["animalSize"] = $keeper->getAnimalSize();
                      $parametersKeeper["price"] = $keeper->getPrice();

                      $this->connection->ExecuteNonQuery($queryKeeper, $parametersKeeper);
                   };
      } catch (Exception $ex) { throw $ex; }   
    }
    public function GetAllKeeper(){
      try{
        $query = "SELECT  u.firstName, u.lastName, u.email, u.cellphone, u.birthdate, k.keeperID, k.price, k.animalSize, d.firstDate, d.lastDate FROM "
                .$this->userTable." u JOIN ".$this->keeperTable." k ON u.userID = k.userID JOIN ".$this->keeperDaysTable." d ON
                k.keeperID = d.keeperID;";
        $this->connection = Connection::GetInstance();
        $resultSet = $this->connection->Execute($query);
        if($resultSet){
          $keeperList = array();
          foreach($resultSet as $row){
            $keeper = new Keeper();
            $keeper->setKeeperId($row['keeperID']);
            $keeper->setFirstName($row['firstName']);
            $keeper->setLastName($row['lastName']);
            $keeper->setEmail($row['email']);
            $keeper->setCellPhone($row['cellphone']);
            $keeper->setbirthDate($row['birthdate']);
            $keeper->setFirstAvailabilityDays($row['firstDate']);
            $keeper->setLastAvailabilityDays($row['lastDate']);
            $keeper->setAnimalSize($row['animalSize']);
            $keeper->setPrice($row['price']);
            array_push($keeperList,$keeper);
          }
          return $keeperList;
        } else {return NULL;}
      } catch(Exception $ex){throw $ex;}
    }
    public function getKeeperByDisponibility($date1,$date2){
      try{
        $query =  "SELECT u.email, kd.firstDate, kd.lastDate from user u join keeper k on u.userID = k.userID 
				           left join keeperdays kd on kd.keeperID = k.keeperID
                   left join booking b on b.keeperDaysID = kd.keeperDaysID
                   where b.keeperDaysID is null and firstDate >= '$date1' and lastDate <= '$date2';";
        $this->connection = Connection::GetInstance();
        $resultSet = $this->connection->Execute($query);
        if($resultSet){
          foreach($resultSet as $row){
            $keeperList = array();
            $keeper = new Keeper();
            $keeper->setKeeperId($row['keeperID']);
            $keeper->setFirstName($row['firstName']);
            $keeper->setLastName($row['lastName']);
            $keeper->setEmail($row['email']);
            $keeper->setCellPhone($row['cellphone']);
            $keeper->setPrice($row['price']);
            $keeper->setAnimalSize($row['animalSize']);
            $keeper->setFirstAvailabilityDays($row['firstDate']);
            $keeper->setLastAvailabilityDays($row['lastDate']);
            array_push($keeperList, $keeper);
            }
          return $keeperList;
        } else { return NULL; } 
      } catch(Exception $ex){ throw $ex; } 
    }
    public function searchKeeperByEmail($email){
      try {
        $query = "SELECT u.firstName, u.lastName, u.cellphone, u.email, k.keeperID, k.price, d.firstDate, d.lastDate
        FROM ".$this->userTable." u JOIN ".$this->keeperTable." k ON u.userID = k.userID
        JOIN ".$this->keeperDaysTable." d ON d.keeperID = k.keeperID  
        WHERE email = '$email';";
        $this->connection = Connection::GetInstance();
        $resultSet = $this->connection->Execute($query);
        if($resultSet){
          foreach($resultSet as $row){
            $keeper = new Keeper();
            $keeper->setKeeperId($row['keeperID']);
            $keeper->setFirstName($row['firstName']);
            $keeper->setLastName($row['lastName']);
            $keeper->setCellPhone($row['cellphone']);
            $keeper->setEmail($row['email']);
            $keeper->setPrice($row['price']);
            $keeper->setFirstAvailabilityDays($row['firstDate']);
            $keeper->setLastAvailabilityDays($row['lastDate']);
            return $keeper;
          }
        } else { return NULL; } }
      catch (Exception $ex) { throw $ex; } 
    }
    public function searchKeeperToLogin($email,$password){
    if($email && $password){
    try {
      $query = "SELECT k.keeperID, k.animalSize, k.price, u.firstName, u.lastName, u.email, u.cellphone, u.birthdate, u.password, u.userImage, u.userDescription FROM ".$this->userTable." u RIGHT JOIN ".$this->keeperTable." k ON u.userID = k.userID WHERE email = '$email' AND password = md5($password);";
      $this->connection = Connection::GetInstance();

            $resultSet = $this->connection->Execute($query);

            
            if($resultSet)
            {
                foreach($resultSet as $row){

                $keeper = new Keeper();
                $keeper->setKeeperID($row["keeperID"]);
                $keeper->setAnimalSize($row["animalSize"]);
                $keeper->setPrice($row["price"]);
                $keeper->setfirstName($row["firstName"]);
                $keeper->setLastName($row["lastName"]);
                $keeper->setEmail($row["email"]);
                $keeper->setCellPhone($row["cellphone"]);
                $keeper->setbirthDate($row["birthdate"]);
                $keeper->setPassword($row["password"]);
                $keeper->setImage($row["userImage"]);
                $keeper->setDescription($row["userDescription"]);
                return $keeper;
            }
        }
        else{
            echo '<div class="alert alert-danger">The user doesn´t exits . Please create an account!</div>';
            }
        } catch (Exception $ex) {
            throw $ex;
        }
      }else if($password){
        echo '<div class="alert alert-danger">Incorrect Email . Please try again!</div>';
      } else if($email){
        echo '<div class="alert alert-danger">Incorrect password . Please try again!</div>';
      }else{
        echo '<div class="alert alert-danger">Incorrect Email or password . Please try again!</div>';
      }
    }
    public function changeAvailabilityDays($keeperID, $value1, $value2){
      $exist = $this->searchDays($keeperID, $value1, $value2);
      if($exist == NULL){
        try {  
          $query = "INSERT INTO ".$this->keeperDaysTable." (keeperDaysID, keeperID, firstDate, lastDate) 
          VALUES (:keeperDaysID, :keeperID, :firstDate, :lastDate);";
          $parameters['keeperDaysID'] = NULL;
          $parameters['keeperID'] = $keeperID;
          $parameters['firstDate'] = $value1;
          $parameters['lastDate'] = $value2;
          
          $this->connection = Connection::GetInstance();
          if($this->connection->ExecuteNonQuery($query, $parameters)){ return true; }
          else{ return false; } 
          
        } catch (Exception $ex) { throw $ex; }
      }
    }
    public function searchDays($keeperID, $value1, $value2){
      try{
        $query = "SELECT keeperDaysID FROM ".$this->keeperDaysTable."  
                  WHERE firstDate >= '$value1' AND lastDate <= '$value2' AND keeperID = $keeperID;";
        $this->connection = Connection::GetInstance();
        $resultSet = $this->connection->Execute($query);
        if($resultSet){ 
          foreach($resultSet as $row){
            $id = $row['keeperDaysID'];
          }
          return $id; 
        } else { return NULL; } }
      catch (Exception $ex) { throw $ex; } 
    }
    public function searchKeeperByID($keeperID){
      try {
        $query = "SELECT u.firstName, u.lastName, u.email, u.cellphone, u.birthdate, k.keeperID, k.price, k.animalSize, d.firstDate, d.lastDate 
                  FROM user u INNER JOIN keeper k ON u.userID = k.userID
                  LEFT JOIN keeperDays kd ON kd.keeperID = k.keeperID 
                  WHERE k.keeperID = $keeperID;";
        $this->connection = Connection::GetInstance();
        $resultSet = $this->connection->Execute($query);
        if($resultSet){
          foreach($resultSet as $row){
            $keeper = new Keeper();
            $keeper-setKeeperID($row['keeperID']);
            $keeper->setFirstName($row['firstName']);
            $keeper->setLastName($row['lastName']);
            $keeper->setEmail($row['email']);
            $keeper->setCellPhone($row['cellphone']);
            $keeper->setBirthdate($row['birthdate']);
            $keeper->setAnimalSize($row['animalSize']);
            $keeper->setPrice($row['price']);
            $keeper->setFirstAvailabilityDays($row['firstDate']);
            $keeper->setLastAvailabilityDays($row['lastDate']);
          }
          return $keeper;
        }
      } catch (Exception $th) {
        throw $th;
      }
    }
}?>