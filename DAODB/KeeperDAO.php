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
          

      } catch (Exception $ex) {
          throw $ex;
      }   

  }
  public function GetAllKeeper(){

    try {
        $keeperList = array();

        $query = "SELECT * FROM ".$this->userTable;

        $this->connection = Connection::GetInstance();

        $resultSet = $this->connection->Execute($query);

        foreach($resultSet as $row){
            $keeper = new Keeper();
            $keeper->setfirstName($row["firstName"]);
            $keeper->setLastName($row["lastName"]);
            $keeper->setEmail($row["email"]);
            $keeper->setCellPhone($row["cellphone"]);
            $keeper->setbirthDate($row["birthdate"]);
            $keeper->setPassword($row["password"]);
            $keeper->setImage($row["userImage"]);
            $keeper->setDescription($row["userDescription"]);

            array_push($keeperList, $keeper);
        }
        return $keeperList;
    } catch (Exception $ex) {
        throw $ex;
    }
}


  public function searchKeeperByEmail($email){
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

  public function getKeeperByDisponibility($date1,$date2){
    $keeperList = array();
    $keeperList = $this->getAllKeeper();
    if($keeperList){
    $keeperListDisponibility= array();
    foreach($keeperList as $value){
        //if($value->getFirstAvailabilityDays()<=$date1 && $value->getLastAvailabilityDays()>=$date1){
          //  if($value->getFirstAvailabilityDays()<=$date2 && $value->getLastAvailabilityDays()>=$date2){
                array_push($keeperListDisponibility,$value);
            //}
       // }
    }
}else{
    echo "<h1>No existen keepers </h1>";
    return array();
}
    return $keeperListDisponibility;
}
}
?>