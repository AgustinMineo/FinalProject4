<?php
namespace DAODB;
        //DAO WITH DATA BASE
use DAODB\Connect as Connect;
use DAODB\KeeperDAO as KeeperDAO;
use DAODB\PetDAO as PetDAO;
        //EXCEPTION FOR MYSQL
use \Exception as Exception;
        //INTERFACE
use DAO\IBookingDAO as IBookingDAO;
        // MODELS
use Models\Booking as Booking;
//use Models\Review as Review;

class BookingDAO implements IBookingDAO{

    private $connection;
    private $bookingTable = 'booking';
    private $keeperDaysTable = 'keeperdays';
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
            $query = "INSERT INTO ".$this->bookingTable."(bookingID, keeperDaysID, petID, status, totalValue, amountReservation)
                      VALUES (:bookingID, :keeperDaysID, :petID, :status, :totalValue, :amountReservation);";
            //Seteamos los valores de booking
            $parameters["bookingID"] = NULL;
            $parameters["keeperDaysID"] = $this->keeperDAO->searchDays($booking->getKeeperID(),$booking->getFirstDate(), $booking->getLastDate());
            $parameters["petID"] = $booking->getPetID();
            $parameters["status"] = $booking->getStatus();
            $parameters["totalValue"] = $booking->getTotalValue();
            $parameters["amountReservation"] = $booking->getAmountReservation();
            $this->connection = Connection::GetInstance();
            $this->connection->ExecuteNonQuery($query, $parameters); 
            return true;    

      } catch (Exception $ex) { throw $ex; } 
    }
    public function GetAllBooking(){ 
        // try{
        //     $query = "SELECT b.bookingID, "
        // } catch (Exception $ex) { throw $ex; } 
    }
    public function updateByID($id, $status){
        try{
        $query = "UPDATE ".$this->bookingTable." SET status = $status WHERE bookingID = $id;";
        $this->connection = Connection::GetInstance();
        if($this->connection->Execute($query)){
            return false;
        } else { return true; } }
        catch (Exception $ex) { throw $ex; }
    }
    public function searchByID($idBooking){
        $query = "SELECT d.firstDate, d.lastDate, d.keeperID, b.petID, b.totalValue, s.name FROM booking b 
                  JOIN keeperdays d ON d.keeperDaysID = b.keeperDaysID 
                  JOIN status s ON s.statusID = b.status WHERE bookingID = $idBooking;";
        $this->connection = Connection::GetInstance();
        $resultSet = $this->connection->Execute($query);

        if($resultSet){
            foreach($resultSet as $row){
                $booking = new Booking();
                $booking->setBookingID($idBooking);
                $booking->setKeeperID($row['keeperID']);
                $booking->setFirstDate($row['firstDate']);
                $booking->setLastDate($row['lastDate']);
                $booking->setPetID($row['petID']);
                $booking->setTotalValue($row['totalValue']);
                $booking->setStatus($row['name']);               
                return $booking;
            }
        } else { return NULL; }
    }
    public function showBookingByKeeperID(){
        if(isset($_SESSION["loggedUser"])){
            $keeperID = $_SESSION["loggedUser"]->getKeeperId();
            try{
                $query = "SELECT b.bookingID, d.firstDate, d.lastDate, d.keeperID,b.petID, b.totalValue,b.amountReservation, p.petName,b.status FROM booking b 
                          JOIN keeperdays d ON d.keeperDaysID = b.keeperDaysID
                          JOIN pet p ON p.petID = b.petID
                          WHERE d.keeperID = $keeperID;";
            
                $this->connection = Connection::GetInstance();
                $resultSet = $this->connection->Execute($query);
                if($resultSet){
                    $bookingList = array();
                    foreach($resultSet as $row){
                        $booking = new Booking();
                        $booking->setBookingID($row['bookingID']);
                        $booking->setKeeperID($row['keeperID']);
                        $booking->setFirstDate($row['firstDate']);
                        $booking->setLastDate($row['lastDate']);
                        $booking->setPetID($row['petName']);
                        $booking->setTotalValue($row['totalValue']);
                        $booking->setAmountReservation($row['amountReservation']);
                        $booking->setStatus($row['status']);               
                        array_push($bookingList,$booking);
                    } return $bookingList;  
                } else { echo "<h1>No existen reservas</h1>"; return NULL; }
            } catch (Exception $ex) { throw $ex; }
        }
    }
    public function showBookingByOwnerID($petListOwner){
        if(isset($_SESSION["loggedUser"])){
            $ownerID = $_SESSION["loggedUser"]->getOwnerID();
        try{
            $query = "SELECT b.bookingID, d.firstDate, d.lastDate, d.keeperID,b.petID, b.totalValue,b.amountReservation, p.petName,b.status FROM booking b 
                      JOIN keeperdays d ON d.keeperDaysID = b.keeperDaysID
                      JOIN pet p ON p.petID = b.petID
                      JOIN owner o ON o.ownerID = p.ownerID
                      WHERE o.ownerID = $ownerID";
        
            $this->connection = Connection::GetInstance();
            $resultSet = $this->connection->Execute($query);
            if($resultSet){
                $bookingList = array();
                foreach($resultSet as $row){
                    $booking = new Booking();
                    $booking->setBookingID($row['bookingID']);
                    $booking->setKeeperID($row['keeperID']);
                    $booking->setFirstDate($row['firstDate']);
                    $booking->setLastDate($row['lastDate']);
                    $booking->setPetID($row['petName']);
                    $booking->setTotalValue($row['totalValue']);
                    $booking->setAmountReservation($row['amountReservation']);
                    $booking->setStatus($row['status']);               
                    array_push($bookingList,$booking);
                } return $bookingList;  
            } else { return NULL; }
        } catch (Exception $ex) { throw $ex; }
        }
    }
    public function searchBookingByKeeperID($bookingID){
        try{
            $query = "SELECT b.bookingID, b.petID, b.status, b.totalValue, b.amountReservation, kd.firstDate, kd.lastDate, kd.keeperID
                      FROM booking b INNER JOIN keeperdays kd ON b.keeperdaysID = kd.keeperdaysID
                      WHERE bookingID = $bookingID;";
            $this->connection = Connection::GetInstance();
            $resultSet = $this->connection->Execute($query);
            if($resultSet){
                foreach($resultSet as $row){
                    $booking = new Booking();
                    $booking->setBookingID($row['bookingID']);
                    $booking->setKeeperID($row['keeperID']);
                    $booking->setPetID($row['petID']);
                    $booking->setStatus($row['status']);
                    $booking->setTotalValue($row['totalValue']);
                    $booking->setAmountReservation($row['amountReservation']);
                    $booking->setFirstDate($row['firstDate']);
                    $booking->setLastDate($row['lastDate']);
                }
                return $booking;
            } else {return Null;}
        } catch(Exception $ex){ throw $ex;}
    }
}?>