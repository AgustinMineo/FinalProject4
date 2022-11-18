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
    private $daysTable = 'keeperdays';
    private $bookingTable = 'booking';



    public function AddKeeper (Keeper $keeper){
      try {
         $query = "INSERT INTO ".$this->userTable."(userID, firstName, lastName, email, cellphone, birthdate, password, userDescription)
      VALUES (:userID,:firstName, :lastName, :email, :cellphone, :birthdate, :password, :userDescription);";
                  $parameters["userID"] = NULL;
                  $parameters["firstName"] = $keeper->getfirstName();
                  $parameters["lastName"] = $keeper->getLastName();
                  $parameters["email"] = $keeper->getEmail();
                  $parameters["cellphone"] = $keeper->getCellPhone();
                  $parameters["birthdate"] = $keeper->getbirthDate();
                  $parameters["password"] = MD5($keeper->getPassword());
                  $parameters["userDescription"] = $keeper->getDescription();

                  $this->connection = Connection::GetInstance();

                   if($this->connection->ExecuteNonQuery($query, $parameters)){
                      $id = $this->searchKeeperID($keeper->getEmail());
                      $queryKeeper = "INSERT INTO ".$this->keeperTable."(keeperID, userID, animalSize, price, cbu)
                                     VALUES (:keeperID, :userID, :animalSize, :price, :cbu);
                      ";

                      $parametersKeeper["keeperID"] = NULL;
                      $parametersKeeper["userID"] = $id;
                      $parametersKeeper["animalSize"] = $keeper->getAnimalSize();
                      $parametersKeeper["price"] = $keeper->getPrice();
                      $parametersKeeper["cbu"] = $keeper->getCBU();

                      $this->connection->ExecuteNonQuery($queryKeeper, $parametersKeeper);
                   };
      } catch (Exception $ex) { throw $ex; }   
    }
    public function GetAllKeeper(){
      try{
        $query = "SELECT  u.firstName, u.lastName, u.email, u.cellphone, u.birthdate, k.keeperID, k.price, k.animalSize, k.cbu, d.firstDate, d.lastDate 
                FROM ".$this->userTable." u 
                JOIN ".$this->keeperTable." k ON u.userID = k.userID 
                JOIN ".$this->daysTable." d ON k.keeperID = d.keeperID;";
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
            $keeper->setCBU($row['cbu']);
            array_push($keeperList,$keeper);
          }
          return $keeperList;
        } else {return NULL;}
      } catch(Exception $ex){throw $ex;}
    }
    public function getKeeperByDisponibility($date1,$date2){
      try{
        $query = "SELECT k.keeperID, u.firstName, u.lastName, u.cellphone, u.email, k.price, k.animalSize, d.firstDate, d.lastDate  
                  FROM ".$this->userTable." u JOIN ".$this->keeperTable." k ON k.userID = u.userID 
                  JOIN ".$this->daysTable." d ON d.keeperID = k.keeperID
                  LEFT join ".$this->bookingTable." b on b.keeperDaysID = d.keeperDaysID
                  WHERE b.keeperDaysID is null and firstDate >= '$date1' AND lastDate <= '$date2';";
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
            $keeper->setPrice($row['price']);
            $keeper->setCBU($row['cbu']);
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
        $query = "SELECT u.firstName, u.lastName, u.cellphone, u.email, k.keeperID, k.price, k.cbu, d.firstDate, d.lastDate
                  FROM ".$this->userTable." u JOIN ".$this->keeperTable." k ON u.userID = k.userID
                  JOIN ".$this->daysTable." d ON d.keeperID = k.keeperID  
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
            $keeper->setCBU($row['cbu']);
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
      $query = "SELECT k.keeperID, k.animalSize, k.price, k.cbu, u.firstName, u.lastName, u.email, u.cellphone, u.birthdate, u.password, u.userDescription 
                FROM ".$this->userTable." u 
                RIGHT JOIN ".$this->keeperTable." k ON u.userID = k.userID 
                WHERE email = '$email' AND password = md5($password);";
      $this->connection = Connection::GetInstance();

            $resultSet = $this->connection->Execute($query);

            
            if($resultSet)
            {
              foreach($resultSet as $row){

              $keeper = new Keeper();
              $keeper->setKeeperID($row["keeperID"]);
              $keeper->setAnimalSize($row["animalSize"]);
              $keeper->setPrice($row["price"]);
              $keeper->setCBU($row["cbu"]);
              $keeper->setfirstName($row["firstName"]);
              $keeper->setLastName($row["lastName"]);
              $keeper->setEmail($row["email"]);
              $keeper->setCellPhone($row["cellphone"]);
              $keeper->setbirthDate($row["birthdate"]);
              $keeper->setPassword($row["password"]);
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
          $query = "INSERT INTO ".$this->daysTable." (keeperDaysID, keeperID, firstDate, lastDate) 
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
        $query = "SELECT keeperDaysID FROM ".$this->daysTable."  
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
        $query = "SELECT u.firstName, u.lastName, u.email, u.cellphone, u.birthdate, k.keeperID, k.price,k.cbu, k.animalSize, kd.firstDate, kd.lastDate 
                  FROM ".$this->userTable." u 
                  INNER JOIN ".$this->keeperTable." k ON u.userID = k.userID
                  INNER JOIN ".$this->daysTable." kd ON kd.keeperID = k.keeperID 
                  WHERE k.keeperID = $keeperID;";
        $this->connection = Connection::GetInstance();
        $resultSet = $this->connection->Execute($query);
        if($resultSet){
          foreach($resultSet as $row){
            $keeper = new Keeper();
            $keeper->setKeeperId($row['keeperID']);
            $keeper->setFirstName($row['firstName']);
            $keeper->setLastName($row['lastName']);
            $keeper->setEmail($row['email']);
            $keeper->setCellPhone($row['cellphone']);
            $keeper->setBirthdate($row['birthdate']);
            $keeper->setAnimalSize($row['animalSize']);
            $keeper->setPrice($row['price']);
            $keeper->setCBU($row['cbu']);
            $keeper->setFirstAvailabilityDays($row['firstDate']);
            $keeper->setLastAvailabilityDays($row['lastDate']);
          }
          return $keeper;
        }
      } catch (Exception $th) {
        throw $th;
      }
    }
    public function searchKeeperID($email){
      $query = "SELECT userID FROM ".$this->userTable." Where email = '$email';";

      $this->connection = Connection::GetInstance();
      $resultSet = $this->connection->Execute($query);
      if($resultSet){
        foreach($resultSet as $row){
          $id = $row['userID'];
        }
        return $id;
      } else { return 1; }
    }
}?>