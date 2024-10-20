<?php
namespace DAODB;
use \Exception as Exception;
use DAODB\Connect as Connect;
use DAO\IReviewDAO as IReviewDAO;
use Models\Review ;
use Models\Booking;
use Helper\SessionHelper as SessionHelper;

class ReviewDAO implements IReviewDAO{
    private $connection;
    private $reviewTable = 'review';
    private $petTable = 'pet';
    private $bookingTable = 'booking';
    //private $keeperTable = 'keeper';
    private $reviewList = array();

    public function __construct() {
        $this->BookingDAO = new BookingDAO(); // Inicializa el BookingDAO
    }

    public function AddReview(Review $review){
        try{
            $query = "INSERT INTO ".$this->reviewTable."(reviewID, description, rank, bookingID)
            VALUES (:reviewID, :description, :rank, :bookingID);";

            $parameters["reviewID"] = NULL;
            $parameters["description"] = $review->getDescription();
            $parameters["rank"] = $review->getPoints();
            $parameters["bookingID"] = $review->getBooking()->getBookingID();

            $this->connection = Connection::GetInstance();
            $this->connection->Execute($query, $parameters);
            
            return true;
            
        }catch (Exception $ex) 
        { 
            throw new Exception ("Error inserting review: " . $ex->GetMessage()); 
        } 
    }

    public function GetAllReviews(){
        try {
            $query = "SELECT * FROM ".$this->reviewTable." ";

            $this->connection = Connection::GetInstance();

            $resultSet = $this->connection->Execute($query);
            $reviewList = array();
            foreach($resultSet as $row){
                $review = new Review();
                $booking= new Booking();
                
                $review->setReviewID($row["reviewID"]);
                $review->setDescription($row["description"]);
                $review->setPoints($row["rank"]);
                $booking = $this->BookingDAO->searchByID($row["bookingID"]);
                if($booking){
                    $review->setBooking($booking);
                }
                array_push($reviewList, $review);
            }
            return $reviewList;
        } catch (Exception $ex) {
            throw new Exception ("Error searching for the reviews: " . $ex->GetMessage());
        }
    }

    public function getReviewByID($id){
        try {
            $query = "SELECT * FROM ".$this->reviewTable." WHERE reviewID = '$id';";
            $this->connection = Connection::GetInstance();

            $resultSet = $this->connection->Execute($query);

            if($resultSet){
                $review = new Review();
                $booking = new Booking();
                $review->setReviewID($resultSet["reviewID"]);
                $review->setDescription($resultSet["description"]);
                $review->setPoints($resultSet["rank"]);

                $booking = $this->BookingDAO->searchByID($resultSet["bookingID"]);
                if($booking){
                    $review->setBooking($booking);
                }else{
                    $review->setBooking($row["bookingID"]);
                }
                return $review;
            }else{
                return false;
            }
        } catch (Exception $ex) {
            throw new Exception ("Error searching for the reviews: " . $ex->GetMessage());
        }
    }

    function getReviewByOwner($ownerID){
        try {
            $query = "SELECT r.*
                FROM ".$this->reviewTable." r 
                JOIN ".$this->bookingTable." b ON r.bookingID = b.bookingID
                JOIN ".$this->petTable." p ON p.petID = b.petID
                WHERE p.ownerID = '$ownerID';";

            $this->connection = Connection::GetInstance();

            $resultSet = $this->connection->Execute($query);
            $reviewList = array();
            foreach($resultSet as $row){
                $review = new Review();
                $booking= new Booking();
                
                $review->setReviewID($row["reviewID"]);
                $review->setDescription($row["description"]);
                $review->setPoints($row["rank"]);
                $booking = $this->BookingDAO->searchByID($row["bookingID"]);
                if($booking){
                    $review->setBooking($booking);
                }
                array_push($reviewList, $review);
            }
            if($reviewList){
                return $reviewList;
            }else{
               return null;
            }
        } catch (Exception $ex) {
            throw new Exception ("Error searching for the reviews: " . $ex->GetMessage());
        }
    }
    function getReviewByKeeper($keeperID){
        try {
            $query = "SELECT r.*
                FROM ".$this->reviewTable." r 
                JOIN ".$this->bookingTable." b ON r.bookingID = b.bookingID
                WHERE b.keeperID = '$keeperID';";

            $this->connection = Connection::GetInstance();

            $resultSet = $this->connection->Execute($query);
            $reviewList = array();
            foreach($resultSet as $row){
                $review = new Review();
                $booking= new Booking();
                
                $review->setReviewID($row["reviewID"]);
                $review->setDescription($row["description"]);
                $review->setPoints($row["rank"]);
                $booking = $this->BookingDAO->searchByID($row["bookingID"]);
                if($booking){
                    $review->setBooking($booking);
                }
                array_push($reviewList, $review);
            }
            if($reviewList){
                return $reviewList;
            }else{
               return null;
            }
        } catch (Exception $ex) {
            throw new Exception ("Error searching for the reviews: " . $ex->GetMessage());
        }
    }
    function getReviewByBooking($bookingID){
        try {
            $query = "SELECT r.*
                FROM ".$this->reviewTable." r 
                JOIN ".$this->bookingTable." b ON r.bookingID = b.bookingID
                WHERE r.bookingID = '$bookingID';";

            $this->connection = Connection::GetInstance();

            $resultSet = $this->connection->Execute($query);
            //$reviewList = array();
            if($resultSet){
                $review = new Review();
                $booking= new Booking();
                
                $review->setReviewID($row["reviewID"]);
                $review->setDescription($row["description"]);
                $review->setPoints($row["rank"]);
                $booking = $this->BookingDAO->searchBookingByID($row["bookingID"]);
                if($booking){
                    $review->setBooking($booking);
                }
            }
            if($review){
                return $review;
            }else{
               return null;
            }
        } catch (Exception $ex) {
            throw new Exception ("Error searching for the reviews: " . $ex->GetMessage());
        }
    }
}
?>