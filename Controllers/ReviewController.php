<?php
namespace Controllers;

use DAO\MailerDAO as MailerDAO;
        // DAO WITH JSON

/*
use DAO\BookingDAO as BookingDAO;
use DAO\PetDAO as PetDAO;
use DAO\KeeperDAO as KeeperDAO;
use DAO\ReviewDAO as ReviewDAO;
*/
        // MODELS
use Models\Booking as Booking;
use Models\Keeper as Keeper;
use Models\Review as Review;
// DAO WITH DATA BASE
use DAODB\PetDAO as PetDAO;
use DAODB\KeeperDAO as KeeperDAO;
use DAODB\BookingDAO as BookingDAO;
use DAODB\Connection as Connection;

use Helper\SessionHelper as SessionHelper;


class ReviewController{
    private $ReviewDAO;
    private $BookingDAO;

    public function __construct(){
        $this->ReviewDAO = new ReviewDAO();
        $this->BookingDAO = new BookingDAO();
    }

    public function goOwnerMenu(){
        require_once(VIEWS_PATH."ownerNav.php");
    }
 
    public function newReview($rate,$booking,$feedback){
        if(SessionHelper::getCurrentUser()){
            $review = new Review();
            $review->setReviewID('1');
            $review->setBooking($booking);
            $review->setDescription($feedback);
            $review->setPoints($rate);
            $result=$this->ReviewDAO->AddReview($review);
        }
        if($result){
            $this->BookingDAO->updateByID($booking,'7');
            echo "<div class='alert alert-success'>The review was create successfully!</div>";
            $this->goOwnerMenu();
        }else{
            echo "<div class='alert alert-danger'>Ups!Error creating the review!</div>";
            $this->goOwnerMenu();
        }
    }
}

   /* public function newReview($booking,$feedback,$rate){
        try {
            $query = "INSERT INTO ".$this->reviewTable."(booking, feedback, rate)
            VALUES (:bookingID,:description, :rank);";

            $parameters["reviewID"] = NULL;
            $parameters["bookingID"] = $booking;
            $parameters["description"] = $feedback;
            $parameters["rank"] = $rate;

            $this->connection = Connection::GetInstance();

            if($this->connection->ExecuteNonQuery($query, $parameters)){
                echo "<div class='alert alert-success'>OK</div>";
                $this->goBookingView();
            }else{
                echo "<div class='alert alert-danger'>Oops! Something was wrong</div>";
                $this->goBookingView();
            }
           
        } catch (Exception $ex) {
            throw $ex;
        }  
    }*/
?>