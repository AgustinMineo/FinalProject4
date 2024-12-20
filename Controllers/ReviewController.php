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
use DAODB\ReviewDAO as ReviewDAO;
use DAODB\Connection as Connection;

use Helper\SessionHelper as SessionHelper;


class ReviewController{
    private $ReviewDAO;
    private $BookingDAO;
    private $KeeperDAO;

    public function __construct(){
        $this->ReviewDAO = new ReviewDAO();
        $this->BookingDAO = new BookingDAO();
        $this->KeeperDAO = new KeeperDAO();
    }

    //Va a la navbar
    public function goMenu(){
        SessionHelper::InfoSession([1,2,3]);
    }
    //Va al reviwe
    public function goReviewMenu($reviewList = null){
        if($reviewList === null){
            if(!SessionHelper::getCurrentUser()){
                SessionHelper::redirectTo403();
            }
        }else{
            $userRole=SessionHelper::InfoSession([1,2,3]);
            require_once(VIEWS_PATH."reviewViews.php");
        }
    }

    public function newReview($rate = null,$bookingID= null,$feedback = null){
        if($rate === null && $bookingID === null && $feedback ===null){
            if(SessionHelper::getCurrentUser()){
                SessionHelper::redirectTo403();
            }
        }else{
            if(SessionHelper::getCurrentRole() == 2){
                if(!empty($rate) && !empty($bookingID) && !empty($feedback)){
                    $booking = $this->BookingDAO->searchBookingByID($bookingID);
                    if($booking){
                        $review = new Review();
                        $review->setBooking($booking);
                        $review->setDescription($feedback);
                        $review->setPoints($rate);
                        $result=$this->ReviewDAO->AddReview($review);
                        if($result){
                            $newPoints = $this->KeeperDAO->getTotalPoints($booking->getKeeperID()->getKeeperID());
                            if($newPoints){
                                $this->KeeperDAO->updateTotalPoints($newPoints,$booking->getKeeperID()->getKeeperID());
                            }else{
                                echo "<div class='alert alert-danger'>!Error getting points from the keeper !</div>";
                                $this->goMenu();
                            }
                        }
                    }else{
                        echo "<div class='alert alert-danger'>!Error searching the booking!</div>";
                        $this->goMenu();
                    }
                }else{
                    echo "<div class='alert alert-danger'>!Error, All fields are required to creating a review!</div>";
                    $this->goMenu();
                }
            }else{
                echo "<div class='alert alert-danger'>!Error, Insufficient permissions to creating a review!</div>";
                $this->goMenu();
            }
            if($result){
                $this->BookingDAO->updateByID($bookingID,'7');
                echo "<div class='alert alert-success'>The review was create successfully!</div>";
                $this->goMenu();
            }else{
                echo "<div class='alert alert-danger'>Ups!Error creating the review!</div>";
                $this->goMenu();
            }
        }
    }

    public function getAllReviews(){
        if(SessionHelper::getCurrentRole()===1){
            $reviewList = $this->ReviewDAO->GetAllReviews();
            $this->goReviewMenu($reviewList);
        }elseif(SessionHelper::getCurrentRole()===2){
            $reviewList = $this->ReviewDAO->getReviewByOwner(SessionHelper::getCurrentOwnerID());
            if($reviewList){
                $this->goReviewMenu($reviewList);
            }else{
                echo "<div class='alert alert-danger'>!Error, There is no reviews yet!</div>";
                $this->goMenu();
            }
        }elseif(SessionHelper::getCurrentRole()===3){
            $reviewList = $this->ReviewDAO->getReviewByKeeper(SessionHelper::getCurrentKeeperID());
            if($reviewList){
                $this->goReviewMenu($reviewList);
            }else{
                echo "<div class='alert alert-danger'>!Error, There is no reviews yet!</div>";
                $this->goMenu();
            }
        }else{ 
            echo "<div class='alert alert-danger'>!Error, Insufficient permissions to see all reviews!</div>";
            $this->goReviewMenu();
        }
    }
}

?>