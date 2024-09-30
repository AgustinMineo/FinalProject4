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
    private $petTable = 'pet';
    private $reviewTable = 'review';


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
                      $queryKeeper = "INSERT INTO ".$this->keeperTable."(keeperID, userID, animalSize, price, cbu,rank)
                      VALUES (:keeperID, :userID, :animalSize, :price, :cbu,:rank);";

                      $parametersKeeper["keeperID"] = NULL;
                      $parametersKeeper["userID"] = $id;
                      $parametersKeeper["animalSize"] = $keeper->getAnimalSize();
                      $parametersKeeper["price"] = $keeper->getPrice();
                      $parametersKeeper["cbu"] = $keeper->getCBU();
                      $parametersKeeper["rank"]=$keeper->getPoints();
                      $this->connection->ExecuteNonQuery($queryKeeper, $parametersKeeper);
                    };
      } catch (Exception $ex) { throw $ex; }   
    }

  public function searchCBU($cbu){
      $query = "SELECT keeperID
      FROM ".$this->keeperTable." WHERE cbu like '$cbu';";
      $this->connection = Connection::GetInstance();
      $resultSet = $this->connection->Execute($query);
      if($resultSet){
        return true;
      }else{
        return null;
      }
  }

  public function updateCBU($cbu,$keeperID){
      try{
          $query = "UPDATE ".$this->keeperTable." SET cbu = '$cbu' WHERE keeperID = '$keeperID';";
          $this->connection = Connection::GetInstance();
          $result = $this->connection->Execute($query);
            return 3;
      }catch (Exception $ex) { throw $ex; } 
  }

  public function updatePrice($price,$keeperID){
      try{
          $query = "UPDATE ".$this->keeperTable." SET price = '$price' WHERE keeperID = '$keeperID';";
          $this->connection = Connection::GetInstance();
          $this->connection->Execute($query);
          return 3;
        }catch (Exception $ex) { throw $ex; }  
  }

  public function updateAnimalSizeKeeper($animalSize,$keeperID){
    try{
      $query = "UPDATE ".$this->keeperTable." SET animalSize = '$animalSize' WHERE keeperID = '$keeperID';";
      $this->connection = Connection::GetInstance();
      $result = $this->connection->Execute($query);
      return 3;
    }catch (Exception $ex) { throw $ex; }  
  }

  public function getTotalPoints($keeperID){
    try{
      $queryRank = "SELECT SUM(k.rank)/count(r.reviewID)) as 'Promedio' FROM ".$this->reviewTable." r
      JOIN ".$this->bookingTable." b ON b.bookingID = r.bookingID 
      JOIN ".$this->keeperTable." k ON k.keeperID = b.keeperID
      WHERE k.keeperID = '$keeperID'";

      $this->connection = Connection::GetInstance();
      $resultSet = $this->connection->Execute($query);
      if($resultSet){
        return $resultSet['Promedio'];
      }
    }catch (Exception $ex) { throw $ex; }  
  }

  public function updateTotalPoints($points,$keeperID){
    try{
      $query = "UPDATE ".$this->keeperTable." SET rank = '$points' WHERE keeperID = '$keeperID';";
      $this->connection = Connection::GetInstance();
      $result = $this->connection->Execute($query);
      return 3;
    }catch (Exception $ex) { throw $ex; } 
  }

  public function GetAllKeeper() {
      try {
          $query = "SELECT u.firstName, u.lastName, u.email, u.cellphone, u.birthdate, k.keeperID, k.price, k.animalSize, k.cbu, d.day, d.available,k.rank,u.status
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
                      $keeper->setPoints($row['rank']);
                      $keeper->setStatus($row['status']);
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
              return null;
          }
      } catch (Exception $ex) {
          throw $ex;
      }
  }
  
  
  public function updateDaysByBookingIDAndStatus($idBooking, $status){
    try{
        //Estado depende del status pasado (2 es rechazado)
        $availability = ($status == 2) ? 1 : 0;

        // Traigo las fechas de inicio y fin del booking
        $query = "SELECT startDate, endDate FROM ".$this->bookingTable." WHERE bookingID = :idBooking";
        
        $parametersSelect = ['idBooking' => $idBooking];
        $this->connection = Connection::GetInstance();
        $resultSet = $this->connection->Execute($query, $parametersSelect);
        if(!empty($resultSet)){//Si no esta vacio
          $startDate = $resultSet[0]["startDate"];
          $endDate = $resultSet[0]["endDate"];

          $queryUpdate="UPDATE ".$this->daysTable." k
                  SET k.available = :availability
                  WHERE day between :startDate AND :endDate";
                  
                  $parameters["availability"] = $availability;
                  $parameters["startDate"] = $startDate;
                  $parameters["endDate"] = $endDate;
                  // Ejecuta la consulta
                  $this->connection = Connection::GetInstance();
                  $result = $this->connection->ExecuteNonQuery($queryUpdate, $parameters);
        }
        
        return true;
    }
    catch (Exception $ex) {
        throw $ex;
    }
  }

  public function updateDaysByKeeperIDAndDates($idKeeper,$dateStart,$dateEnd){
    try{

      $queryUpdate="UPDATE ".$this->daysTable." k
      SET k.available = 0
      WHERE keeperID = :keeperID and day between :startDate AND :endDate";
      
      $parameters["startDate"] = $dateStart;
      $parameters["endDate"] = $dateEnd;
      $parameters["keeperID"] = $idKeeper;
      // Ejecuta la consulta
      $this->connection = Connection::GetInstance();
      $result = $this->connection->ExecuteNonQuery($queryUpdate, $parameters);

      if($result){
        return true;
      }else{
        return false;
      }
    }catch (Exception $ex) {
      throw $ex;
  }
  }

  public function getKeeperByDisponibility($date1, $date2,$ownerID) {
    try {
        $query = "
        SELECT k.keeperID, u.firstName, u.lastName, u.cellphone,u.birthdate, u.email, k.price, k.animalSize, k.cbu,k.rank
        FROM " . $this->userTable . " u
        JOIN " . $this->keeperTable . " k ON k.userID = u.userID
        JOIN " . $this->daysTable . " d ON d.keeperID = k.keeperID
        LEFT JOIN " . $this->bookingTable . " b ON b.keeperID = k.keeperID
        WHERE d.available = 1 AND u.status = 1
          AND d.day BETWEEN :date1 AND :date2
          AND k.animalSize in (select p.petSize from ". $this->petTable . " p where p.ownerID = :ownerID )
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
        $parameters['ownerID'] = $ownerID;
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
                $keeper->setbirthdate($row['birthdate']);
                $keeper->setCellPhone($row['cellphone']);
                $keeper->setPrice($row['price']);
                $keeper->setCBU($row['cbu']);
                $keeper->setAnimalSize($row['animalSize']);
                $keeper->setPoints($row['rank']);
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
        $query = "SELECT u.firstName, u.lastName, u.cellphone, u.email,u.birthdate, k.keeperID, k.price, k.cbu,
                  u.userDescription,u.questionRecovery,u.answerRecovery,k.animalSize,u.roleID,k.rank,u.status
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
            $keeper->setbirthDate($row['birthdate']);
            $keeper->setEmail($row['email']);
            $keeper->setPrice($row['price']);
            $keeper->setCBU($row['cbu']);
            $keeper->setAnimalSize($row['animalSize']);
            $keeper->setDescription($row['userDescription']);
            $keeper->setQuestionRecovery($row['questionRecovery']);
            $keeper->setAnswerRecovery($row['answerRecovery']);
            $keeper->setRol($row['roleID']);
            $keeper->setPoints($row['rank']);
            $keeper->setStatus($row['status']);
            return $keeper;
          }
        } else { 
          //echo '<div class="alert alert-danger">No existen keepers disponibles</div>';
          return NULL; 
        } 
      }
      catch (Exception $ex) { throw $ex; } 
    }
    public function searchKeeperByID($keeperID){
      try {
        $query = "SELECT u.firstName, u.lastName, u.cellphone, u.email,u.birthdate, k.keeperID, k.price, k.cbu,
                  u.userDescription,u.questionRecovery,u.answerRecovery,k.animalSize,u.roleID,k.rank
                  FROM ".$this->userTable." u JOIN ".$this->keeperTable." k ON u.userID = k.userID
                  WHERE k.keeperID = '$keeperID';";
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
            $keeper->setbirthDate($row['birthdate']);
            $keeper->setCBU($row['cbu']);
            $keeper->setAnimalSize($row['animalSize']);
            $keeper->setDescription($row['userDescription']);
            $keeper->setQuestionRecovery($row['questionRecovery']);
            $keeper->setAnswerRecovery($row['answerRecovery']);
            $keeper->setRol($row['roleID']);
            $keeper->setPoints($row['rank']);
            return $keeper;
          }
        } else { 
          //echo '<div class="alert alert-danger">No existen keepers disponibles</div>';
          return NULL; 
        } 
      }
      catch (Exception $ex) { throw $ex; } 
    }
    //Para buscar sin datos privados
    public function searchKeeperByIDReduce($keeperID){
      try {
        $query = "SELECT u.firstName, u.lastName, u.cellphone, u.email,u.birthdate, k.keeperID, k.price, k.cbu,
                  u.userDescription,k.animalSize,u.roleID,k.rank,u.status
                  FROM ".$this->userTable." u JOIN ".$this->keeperTable." k ON u.userID = k.userID
                  WHERE k.keeperID = '$keeperID';";
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
            $keeper->setbirthDate($row['birthdate']);
            $keeper->setCBU($row['cbu']);
            $keeper->setAnimalSize($row['animalSize']);
            $keeper->setDescription($row['userDescription']);
            $keeper->setRol($row['roleID']);
            $keeper->setPoints($row['rank']);
            $keeper->setStatus($row['status']);
            return $keeper;
          }
        } else { 
          //echo '<div class="alert alert-danger">No existen keepers disponibles</div>';
          return NULL; 
        } 
      }
      catch (Exception $ex) { throw $ex; } 
    }
    public function searchKeeperToLogin($email,$password){
      try {
      $query = "SELECT k.keeperID, k.animalSize, k.price, k.cbu, u.firstName, u.lastName, 
      u.email, u.cellphone, u.birthdate, u.password, u.userDescription, u.roleID,k.rank,u.status 
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
              $keeper->setPoints($row["rank"]);
              $keeper->setStatus($row['status']);
              return $keeper;
              }
          }
      } catch (Exception $ex) {
            throw $ex;
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
                                WHERE keeperID = :keeperID AND day BETWEEN :startDate AND :endDate and available!=0";
          $parametersExisting = [
              'keeperID' => $keeperID,
              'startDate' => $startDate,
              'endDate' => $endDate
          ];
          $existingDays = $this->connection->Execute($queryExistingDays, $parametersExisting);
  
          // Crear un array con los días existentes para una actualizacion rapida
          $existingDaysArray = array_column($existingDays, 'day');
          
          // Crear un array con los días del rango especificado
          $currentDate = $startDate;
          $endDateTimestamp = strtotime($endDate);
          $datesToInsert = [];
          
          while (strtotime($currentDate) <= $endDateTimestamp) {
              if (!in_array($currentDate, $existingDaysArray)) {
                  $datesToInsert[] = $currentDate;
              }
              // Actualizar los días existentes donde el status!=0
              $queryUpdate = "UPDATE ".$this->daysTable."
                              SET available = :available
                              WHERE keeperID = :keeperID AND day = :day and available!=0";
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
  //Funcion para cambiar el estado desde calendario.
    public function changeAvailabilityDay($keeperID,$date,$available){
      try {
        // Conectamos a la base de datos
        $this->connection = Connection::GetInstance();

        // Verificamos si el día ya existe
        $queryExistingDay = "SELECT day FROM ".$this->daysTable."
                              WHERE keeperID = :keeperID AND day = :day";
        $parametersExisting = [
            'keeperID' => $keeperID,
            'day' => $date
        ];
        $existingDay = $this->connection->Execute($queryExistingDay, $parametersExisting);

        // Verifica si el día existe
        if (!empty($existingDay)) {
            // Si el día existe, actualizamos la disponibilidad
            $queryUpdate = "UPDATE ".$this->daysTable."
                            SET available = :available
                            WHERE keeperID = :keeperID AND day = :day ";
            $parametersUpdate = [
                'keeperID' => $keeperID,
                'day' => $date,
                'available' => $available
            ];
              $this->connection->ExecuteNonQuery($queryUpdate, $parametersUpdate);
        } else {
            // Si el día no existe, lo insertamos
            $queryInsert = "INSERT INTO ".$this->daysTable." (keeperID, day, available) 
                            VALUES (:keeperID, :day, :available)";
            $parametersInsert = [
                'keeperID' => $keeperID,
                'day' => $date,
                'available' => $available
            ];
              $this->connection->ExecuteNonQuery($queryInsert, $parametersInsert);
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
    /*
    public function searchKeeperByID($keeperID){
      try {
        $query = "SELECT u.firstName, u.lastName, u.email, u.cellphone, u.birthdate, k.keeperID, 
        k.price,k.cbu, k.animalSize,u.roleID 
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
            $keeper->setRol($row['roleID']);
           // $keeper->setFirstAvailabilityDays($row['firstDate']);
            //$keeper->setLastAvailabilityDays($row['lastDate']);
          }
          return $keeper;
        }
      } catch (Exception $th) {
        throw $th;
      }
    }*/
    
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