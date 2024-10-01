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
         $query = "INSERT INTO ".$this->userTable."
         (userID, firstName, lastName, email, cellphone, 
         birthdate, password, userDescription,QuestionRecovery,answerRecovery,roleID)
      VALUES 
      (:userID,:firstName, :lastName, :email,
      :cellphone, :birthdate, :password, :userDescription,:QuestionRecovery,:answerRecovery,:roleID);";
                  $parameters["userID"] = NULL;
                  $parameters["firstName"] = $keeper->getfirstName();
                  $parameters["lastName"] = $keeper->getLastName();
                  $parameters["email"] = $keeper->getEmail();
                  $parameters["cellphone"] = $keeper->getCellPhone();
                  $parameters["birthdate"] = $keeper->getbirthDate();
                  $parameters["password"] = MD5($keeper->getPassword());
                  $parameters["userDescription"] = $keeper->getDescription();
                  $parameters["QuestionRecovery"] =$keeper->getQuestionRecovery();
                  $parameters["answerRecovery"] = $keeper->getAnswerRecovery();
                  $parameters["roleID"] = $keeper->getRol();
                  $this->connection = Connection::GetInstance();

                   if($this->connection->ExecuteNonQuery($query, $parameters)){

                      $id = $this->searchKeeperID($keeper->getEmail());
                      $queryKeeper = "INSERT INTO ".$this->keeperTable."(keeperID, userID, animalSize, price, cbu)
                                     VALUES (:keeperID, :userID, :animalSize, :price, :cbu);";

                      $parametersKeeper["keeperID"] = NULL;
                      $parametersKeeper["userID"] = $id;
                      $parametersKeeper["animalSize"] = $keeper->getAnimalSize();
                      $parametersKeeper["price"] = $keeper->getPrice();
                      $parametersKeeper["cbu"] = $keeper->getCBU();

                      $this->connection->ExecuteNonQuery($queryKeeper, $parametersKeeper);
                   };
      } catch (Exception $ex) { throw $ex; }   
    }
    /*public function GetAllKeeper(){
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
    }*/
    public function GetAllKeeper() {
      try {
          $query = "SELECT u.firstName, u.lastName, u.email, u.cellphone, u.birthdate, k.keeperID, k.price, k.animalSize, k.cbu, d.day, d.available
                    FROM ".$this->userTable." u
                    JOIN ".$this->keeperTable." k ON u.userID = k.userID
                    LEFT JOIN ".$this->daysTable." d ON k.keeperID = d.keeperID";
          $this->connection = Connection::GetInstance();
          $resultSet = $this->connection->Execute($query);
  
          if ($resultSet) {
              $keeperList = array();
              $keepers = array();
  
              // Agrupar los resultados por keeperID
              foreach ($resultSet as $row) {
                  if (!isset($keepers[$row['keeperID']])) {
                      $keeper = new Keeper();
                      $keeper->setKeeperId($row['keeperID']);
                      $keeper->setFirstName($row['firstName']);
                      $keeper->setLastName($row['lastName']);
                      $keeper->setEmail($row['email']);
                      $keeper->setCellPhone($row['cellphone']);
                      $keeper->setbirthDate($row['birthdate']);
                      $keeper->setAnimalSize($row['animalSize']);
                      $keeper->setPrice($row['price']);
                      $keeper->setCBU($row['cbu']);
                      $keepers[$row['keeperID']] = array('keeper' => $keeper, 'availability' => array());
                  }
                  if ($row['day']) {
                      $keepers[$row['keeperID']]['availability'][] = array('day' => $row['day'], 'available' => $row['available']);
                  }
              }
  
              // Convertir el array de keepers en una lista
              foreach ($keepers as $keeperData) {
                  $keeper = $keeperData['keeper'];
                  $availability = $keeperData['availability'];
  
                  // Establecer la lista completa de disponibilidad en el objeto Keeper
                  $keeper->setAvailability($availability);
  
                  $keeperList[] = $keeper;
              }
  
              return $keeperList;
          } else {
              return NULL;
          }
      } catch (Exception $ex) {
          throw $ex;
      }
  }
  
  


  public function getKeeperByDisponibility($date1, $date2) {
    try {
        $query = "
        SELECT k.keeperID, u.firstName, u.lastName, u.cellphone, u.email, k.price, k.animalSize, k.cbu
        FROM " . $this->userTable . " u
        JOIN " . $this->keeperTable . " k ON k.userID = u.userID
        JOIN " . $this->daysTable . " d ON d.keeperID = k.keeperID
        LEFT JOIN " . $this->bookingTable . " b ON b.keeperID = k.keeperID
        WHERE d.available = 1
          AND d.day BETWEEN :date1 AND :date2
          AND NOT EXISTS (
            SELECT 1
            FROM " . $this->bookingTable . " b2
            WHERE b2.keeperID = k.keeperID 
            AND b2.status != 2
              AND (
                (b2.startDate <= :date2 AND b2.endDate >= :date1)
              )
          )
        GROUP BY k.keeperID
        HAVING COUNT(DISTINCT d.day) = DATEDIFF(:date2, :date1) + 1;
        ";
      /* Lo que hace la query es buscar primero en la tabla de Dias que existan fechas disponibles, y a eso
      revisa que no exista el cuidador con una booking registrada en esa fecha.
      */
        $parameters['date1'] = $date1;
        $parameters['date2'] = $date2;

        $this->connection = Connection::GetInstance();
        $resultSet = $this->connection->Execute($query, $parameters);

        if ($resultSet) {
            $keeperList = array();
            foreach ($resultSet as $row) {
                $keeper = new Keeper();
                $keeper->setKeeperId($row['keeperID']);
                $keeper->setFirstName($row['firstName']);
                $keeper->setLastName($row['lastName']);
                $keeper->setEmail($row['email']);
                $keeper->setCellPhone($row['cellphone']);
                $keeper->setPrice($row['price']);
                $keeper->setCBU($row['cbu']);
                $keeper->setAnimalSize($row['animalSize']);
                array_push($keeperList, $keeper);
            }
            return $keeperList;
        } else {
            return NULL;
        }
    } catch (Exception $ex) {
        throw $ex;
    }
}


    public function searchKeeperByEmail($email){
      try {
        $query = "SELECT u.firstName, u.lastName, u.cellphone, u.email, k.keeperID, k.price, k.cbu,
                  u.userDescription,u.questionRecovery,u.answerRecovery,k.animalSize,u.roleID
                  FROM ".$this->userTable." u JOIN ".$this->keeperTable." k ON u.userID = k.userID
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
            $keeper->setAnimalSize($row['animalSize']);
            $keeper->setDescription($row['userDescription']);
            $keeper->setQuestionRecovery($row['questionRecovery']);
            $keeper->setAnswerRecovery($row['answerRecovery']);
            $keeper->setRol($row['roleID']);
            return $keeper;
          }
        } else { return NULL; } }
      catch (Exception $ex) { throw $ex; } 
    }
    public function searchKeeperToLogin($email,$password){
      if($email && $password){
      try {
      $query = "SELECT k.keeperID, k.animalSize, k.price, k.cbu, u.firstName, u.lastName, 
      u.email, u.cellphone, u.birthdate, u.password, u.userDescription, u.roleID 
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
              $keeper->setRol($row["roleID"]);
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
    public function getAvailabilityDays($keeperID) {
      try {
          $query = "SELECT day, available FROM ".$this->daysTable."
                    WHERE keeperID = :keeperID
                    ORDER BY day";
          $parameters = ['keeperID' => $keeperID];
          $this->connection = Connection::GetInstance();
          $resultSet = $this->connection->Execute($query, $parameters);
          return $resultSet ? $resultSet : [];
      } catch (Exception $ex) {
          throw $ex;
      }
  }
  

    public function changeAvailabilityDays($keeperID, $startDate, $endDate, $available) {
      // Aseguramos que $available sea un entero, 1 o 0
      $available = ($available === '1') ? 1 : 0;
  
      try {
          // Conectamos a la base de datos
          $this->connection = Connection::GetInstance();
  
          // Obtener los días existentes en el rango
          $queryExistingDays = "SELECT day FROM ".$this->daysTable."
                                WHERE keeperID = :keeperID AND day BETWEEN :startDate AND :endDate";
          $parametersExisting = [
              'keeperID' => $keeperID,
              'startDate' => $startDate,
              'endDate' => $endDate
          ];
          $existingDays = $this->connection->Execute($queryExistingDays, $parametersExisting);
  
          // Crear un array con los días existentes para una actualización rápida
          $existingDaysArray = array_column($existingDays, 'day');
          
          // Crear un array con los días del rango especificado
          $currentDate = $startDate;
          $endDateTimestamp = strtotime($endDate);
          $datesToInsert = [];
          
          while (strtotime($currentDate) <= $endDateTimestamp) {
              if (!in_array($currentDate, $existingDaysArray)) {
                  $datesToInsert[] = $currentDate;
              }
              // Actualizar los días existentes
              $queryUpdate = "UPDATE ".$this->daysTable."
                              SET available = :available
                              WHERE keeperID = :keeperID AND day = :day";
              $parametersUpdate = [
                  'keeperID' => $keeperID,
                  'day' => $currentDate,
                  'available' => $available
              ];
              $this->connection->ExecuteNonQuery($queryUpdate, $parametersUpdate);
  
              $currentDate = date('Y-m-d', strtotime($currentDate . ' +1 day'));
          }
  
          // Si no existen los dias, los agregamos
          if (!empty($datesToInsert)) {
              $queryInsert = "INSERT INTO ".$this->daysTable." (keeperID, day, available) 
                              VALUES (:keeperID, :day, :available)";
              foreach ($datesToInsert as $dateToInsert) {
                  $parametersInsert = [
                      'keeperID' => $keeperID,
                      'day' => $dateToInsert,
                      'available' => $available
                  ];
                  $this->connection->ExecuteNonQuery($queryInsert, $parametersInsert);
              }
          }
  
          return true;
  
      } catch (Exception $ex) {
          throw $ex;
      }
  }
  
  


    public function searchDays($keeperID, $value1, $value2){
      try{
        $query = "SELECT keeperDaysID FROM ".$this->daysTable."  
                  WHERE day >= '$value1' AND day <= '$value2' AND keeperID = $keeperID;";
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
        $query = "SELECT u.firstName, u.lastName, u.email, u.cellphone, u.birthdate, k.keeperID, 
        k.price,k.cbu, k.animalSize,u.rolID 
                  FROM ".$this->userTable." u 
                  INNER JOIN ".$this->keeperTable." k ON u.userID = k.userID 
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
            $keeper->setRol($row['rolID']);
           // $keeper->setFirstAvailabilityDays($row['firstDate']);
            //$keeper->setLastAvailabilityDays($row['lastDate']);
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