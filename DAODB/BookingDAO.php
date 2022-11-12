<?php
namespace DAODB;
use \Exception as Exception;
use DAODB\Connect as Connect;
use DAO\IBookingDAO as IBookingDAO;
use Models\Booking as Booking;
//use Models\Review as Review;
class BookingDAO implements IBookingDAO{

    private $connection;
    private $bookingTable = 'Booking';
    private $dayBooking = 'DayBooking';
    //private $reviewBooking = 'Review';


    public function AddBooking (Booking $booking){
      try {
         $query = "INSERT INTO ".$this->bookingTable."(bookingID, keeperID, status, petID, totalValue, amountReservation, )
      VALUES (:bookingID,:keeperID,:status, :petID, :totalValue, :amountReservation);";
        //Seteamos los valores de booking
                  $parameters["bookingID"] = NULL;
                  $parameters["keeperID"] = $booking->getKeeperID();
                  $parameters["petID"] = $booking->getPetID();
                  $parameters["status"] = $booking->getStatus();
                  $parameters["totalValue"] = $booking->getTotalValue();
                  $parameters["amountReservation"] = $booking->getAmountReservation();

                  $this->connection = Connection::GetInstance();

                   if($this->connection->ExecuteNonQuery($query, $parameters)){
                      $queryKeeper = "INSERT INTO ".$this->bookingTable."(bookingID, keeperID, petID, status,totalValue,amountReservation)
                                     VALUES (:keeperID, :petID, :status, :totalValue, :amountReservation);
                      ";

                      $parametersDayBooking["dayBookingID"] = NULL;
                      $parametersDayBooking["firstDate"] = $booking->getFirstDate();
                      $parametersDayBooking["lastDate"] = $booking->getLastDate();
                      $parametersDayBooking["idBooking"] = $booking->getBookingID();

                      $this->connection->ExecuteNonQuery($queryBooking, $parametersBooking);
                   };
          

      } catch (Exception $ex) {
          throw $ex;
      }   

  }
  public function GetAllBooking(){

    try {
        $BookingList = array();

        $query = "SELECT * FROM ".$this->bookingTable;

        $this->connection = Connection::GetInstance();

        $resultSet = $this->connection->Execute($query);

        foreach($resultSet as $row){
            $booking = new Booking();
            $booking->setKeeperID($row["keeperID"]);
            $booking->setPetID($row["petID"]);
            $booking->setStatus($row["status"]);
            $booking->setTotalValue($row["totalValue"]);
            $booking->setAmountReservation($row["amountReservation"]);

            array_push($BookingList, $booking);
        }
        return $BookingList;
    } catch (Exception $ex) {
        throw $ex;
    }
}


 /* public function searchKeeperByEmail($email){
    try {
        $query = "SELECT userID FROM ".$this->bookingTable." WHERE email = '$email';";
        
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
  }*/

 /* public function searchKeeperToLogin($email,$password){
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
  }*/
}
?>