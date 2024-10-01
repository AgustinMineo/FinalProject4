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

    public function __construct(){
        $this->ReviewDAO = new ReviewDAO();
        $this->BookingDAO = new BookingDAO();
    }

    private function goOwnerMenu(){
        require_once(VIEWS_PATH."ownerNav.php");
    }
    private function goReviewMenu($reviewList){
            if(SessionHelper::getCurrentRole()==2){
                $userRole=2;
                require_once(VIEWS_PATH."ownerNav.php");
                require_once(VIEWS_PATH."reviewViews.php");
            }elseif(SessionHelper::getCurrentRole()==3){
                $userRole=3;
                require_once(VIEWS_PATH."keeperNav.php");
                require_once(VIEWS_PATH."reviewViews.php");
            }else{
                //Vista admin
            }
    }

    public function newReview($rate,$bookingID,$feedback){
        if(SessionHelper::getCurrentRole() == 2){
            if(!empty($rate) && !empty($bookingID) && !empty($feedback)){
                $booking = $this->BookingDAO->searchBookingByID($bookingID);
                if($booking){
                    $review = new Review();
                    $review->setBooking($booking);
                    $review->setDescription($feedback);
                    $review->setPoints($rate);
                    $result=$this->ReviewDAO->AddReview($review);
                }else{
                    echo "<div class='alert alert-danger'>!Error searching the booking!</div>";
                    $this->goOwnerMenu();
                }
            }else{
                echo "<div class='alert alert-danger'>!Error, All fields are required to creating a review!</div>";
                $this->goOwnerMenu();
            }
        }else{
            echo "<div class='alert alert-danger'>!Error, Insufficient permissions to creating a review!</div>";
            $this->goOwnerMenu();
        }
        var_dump($result);
        if($result){
            $this->BookingDAO->updateByID($bookingID,'7');
            echo "<div class='alert alert-success'>The review was create successfully!</div>";
            $this->goOwnerMenu();
        }else{
            echo "<div class='alert alert-danger'>Ups!Error creating the review!</div>";
            $this->goOwnerMenu();
        }
    }

    public function getAllReviews(){//Metodo para perfil admin
        if(SessionHelper::getCurrentRole()==1){
            $reviewList = $this->ReviewDAO->GetAllReviews();
        }else{
            echo "<div class='alert alert-danger'>!Error, Insufficient permissions to see all reviews!</div>";
            $this->goReviewMenu();
        }
    }

    public function getReviewByOwner(){
        if(SessionHelper::getCurrentRole()==2){
            $reviewList = $this->ReviewDAO->getReviewByOwner(SessionHelper::getCurrentOwnerID());
            if($reviewList){
                $this->goReviewMenu($reviewList);
            }else{
                echo "<div class='alert alert-danger'>!Error, There is no reviews yet!</div>";
                $this->goOwnerMenu();
            }
        }else{
            echo "<div class='alert alert-danger'>!Error, Insufficient permissions to see all reviews!</div>";
            $this->goReviewMenu();
        }
    }
    public function getReviewByKeeper(){
        if(SessionHelper::getCurrentRole()==3){
            $reviewList = $this->ReviewDAO->getReviewByKeeper(SessionHelper::getCurrentKeeperID());
            if($reviewList){
                $this->goReviewMenu($reviewList);
            }else{
                echo "<div class='alert alert-danger'>!Error, There is no reviews yet!</div>";
                $this->goReviewMenu($reviewList);
            }
        }else{
            echo "<div class='alert alert-danger'>!Error, Insufficient permissions to see all reviews!</div>";
            $this->goReviewMenu($reviewList);
        }
    }
}

?>