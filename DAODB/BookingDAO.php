<?php
namespace DAODB;
use \Exception as Exception;
use DAODB\Connect as Connect;
use DAO\IBookingDAO as IBookingDAO;
use Models\Booking as Booking;
use DAODB\KeeperDAO as KeeperDAO;
use DAODB\PetDAO as PetDAO;
//use Models\Review as Review;
class BookingDAO implements IBookingDAO{

    private $connection;
    private $bookingTable = 'Booking';
    private $dayBooking = 'DayBooking';
    private $keeperTable = 'keeper';
    private $petDAO;
    private $keeperDAO;
    
    //private $reviewBooking = 'Review';

    public function __construct(){
        //$this->BookingDAO = new BookingDAO();
        $this->keeperDAO = new KeeperDAO();
        $this->petDAO = new petDAO();
    }
    public function AddBooking (Booking $booking){
      try {
            $keeperDaysID = $this->keeperDAO->searchDays($booking->getKeeperID(),$booking->getFirstDate(), $booking->getLastDate());
            $size = $this->petDAO->getSizePet($booking->getPetID());
            $query = "INSERT INTO ".$this->bookingTable."(bookingID, keeperDaysID, petID, status,animalSize, totalValue, amountReservation)
                      VALUES (:bookingID, :keeperDaysID, :petID, :status, :animalSize, :totalValue, :amountReservation);";
            //Seteamos los valores de booking
            $parameters["bookingID"] = NULL;
            $parameters["keeperDaysID"] = $this->keeperDAO->searchDays($booking->getKeeperID(),$booking->getFirstDate(), $booking->getLastDate());
            $parameters["petID"] = $booking->getPetID();
            $parameters["status"] = $booking->getStatus();
            $parameters["animalSize"] = $size;
            $parameters["totalValue"] = $booking->getTotalValue();
            $parameters["amountReservation"] = $booking->getAmountReservation();
            $this->connection = Connection::GetInstance();
            $this->connection->ExecuteNonQuery($query, $parameters);     

      } catch (Exception $ex) { throw $ex; } 
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
        } catch (Exception $ex) { throw $ex; }
    } 

}?>