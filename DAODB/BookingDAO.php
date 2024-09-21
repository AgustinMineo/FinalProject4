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
            $keeperDaysID = $this->keeperDAO->searchDays($booking->getKeeperID(),$booking->getStartDate(), $booking->getEndDate());
            $query = "INSERT INTO ".$this->bookingTable."(bookingID, keeperID, petID, status, 
            totalValue, amountReservation,startDate,endDate)
                    VALUES (:bookingID, :keeperID, :petID, 
                    :status, :totalValue, :amountReservation,
                    :startDate,:endDate
                    );";
            //Seteamos los valores de booking
            $parameters["bookingID"] = NULL;
            $parameters["keeperID"] = $booking->getKeeperID();
            $parameters["petID"] = $booking->getPetID();
            $parameters["status"] = $booking->getStatus();
            $parameters["totalValue"] = $booking->getTotalValue();
            $parameters["amountReservation"] = $booking->getAmountReservation();
            $parameters["startDate"] = $booking->getStartDate();
            $parameters["endDate"] = $booking->getEndDate();
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
        $query = "SELECT d.startDate, d.endDate, d.keeperID, b.petID, b.totalValue, s.name FROM booking b 
                  JOIN keeperdays d ON d.keeperDaysID = b.keeperDaysID 
                  JOIN status s ON s.statusID = b.status WHERE bookingID = $idBooking;";
        $this->connection = Connection::GetInstance();
        $resultSet = $this->connection->Execute($query);

        if($resultSet){
            foreach($resultSet as $row){
                $booking = new Booking();
                $booking->setBookingID($idBooking);
                $booking->setKeeperID($row['keeperID']);
                $booking->setStartDate($row['startDate']);
                $booking->setEndDate($row['endDate']);
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
                $query = "SELECT b.bookingID, b.startDate, b.endDate, concat(u.firstName, ' ', u.lastName) as nameKeeper,b.petID, b.totalValue,b.amountReservation, p.petName,b.status FROM booking b 
                        JOIN pet p ON p.petID = b.petID
                        left join keeper k on k.keeperID = b.keeperID
                        left join user u ON u.userID = k.userID
                        WHERE b.keeperID = $keeperID;";
                $this->connection = Connection::GetInstance();
                $resultSet = $this->connection->Execute($query);
                if($resultSet){
                    $bookingList = array();
                    foreach($resultSet as $row){
                        $booking = new Booking();
                        $booking->setBookingID($row['bookingID']);
                        $booking->setKeeperID($row['nameKeeper']);
                        $booking->setStartDate($row['startDate']);
                        $booking->setEndDate($row['endDate']);
                        $booking->setPetID($row['petName']);
                        $booking->setTotalValue($row['totalValue']);
                        $booking->setAmountReservation($row['amountReservation']);
                        $booking->setStatus($row['status']);               
                        array_push($bookingList,$booking);
                    } return $bookingList;  
                } else { return NULL; }
            } catch (Exception $ex) { throw $ex; }
        }//FALTA EL ELSE SI ES QUE TIENE VENCIDA LA SESSION
    }
    public function showBookingByOwnerID($petListOwner){
        if(isset($_SESSION["loggedUser"])){
            $ownerID = $_SESSION["loggedUser"]->getOwnerID();
        try{
            $query = "SELECT b.bookingID, b.startDate, b.endDate, 
            b.totalValue,b.amountReservation, p.petName, concat(u.firstName, ' ', u.lastName) as nameKeeper,
            b.status FROM booking b 
                    left join pet p ON p.petID = b.petID
                    left join keeper k ON k.keeperID = b.keeperID
                    left join user u ON u.userID = k.userID  
                    JOIN owner o ON o.ownerID = p.ownerID
                    WHERE o.ownerID = $ownerID
                    ORDER BY b.status ASC";
        
            $this->connection = Connection::GetInstance();
            $resultSet = $this->connection->Execute($query);
            if($resultSet){
                $bookingList = array();
                foreach($resultSet as $row){
                    $booking = new Booking();
                    $booking->setBookingID($row['bookingID']);
                    $booking->setKeeperID($row['nameKeeper']);
                    $booking->setStartDate($row['startDate']);
                    $booking->setEndDate($row['endDate']);
                    $booking->setPetID($row['petName']);
                    $booking->setTotalValue($row['totalValue']);
                    $booking->setAmountReservation($row['amountReservation']);
                    $booking->setStatus($row['status']);               
                    array_push($bookingList,$booking);
                } return $bookingList;  
            } else { return  NULL; }
        } catch (Exception $ex) { throw $ex; }
        }
    }
    public function searchBookingByID($bookingID){
        try{
            $query = "SELECT *
                    FROM booking WHERE bookingID = $bookingID;";
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
                    $booking->setStartDate($row['startDate']);
                    $booking->setEndDate($row['endDate']);
                }
                return $booking;
            } else {return Null;}
        } catch(Exception $ex){ throw $ex;}
    }

    //Cuenta cantidad de dias de reserva
    public function calculateTotalDays($dateStart, $dateEnd, $keeperID) {
        try {
            $query = "SELECT COUNT(*) AS totalDays FROM `keeperdays` 
                      WHERE `keeperID` = $keeperID 
                      AND `day` BETWEEN '$dateStart' AND '$dateEnd';";
            $this->connection = Connection::GetInstance();
            $resultSet = $this->connection->Execute($query);
            // Si no es vacio, opero sobre el valor de la respuesta.
            if ($resultSet && isset($resultSet[0]['totalDays'])) {
                // Transformo el valor para que no sea un string -> De string a entero.
                return intval($resultSet[0]['totalDays']);
            } else {
                return 0; // Si no tiene valor devuelvo 0.
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    
    
}?>