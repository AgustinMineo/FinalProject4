<?php
namespace Models;

class Review{
    private $reviewID;
    private $booking;
    private $description;
    private $points;

    public function getReviewID(){
        return $this->reviewID;
    }

    public function setReviewID($reviewID){
        $this->reviewID = $reviewID;
    }

    public function getBooking(){
        return $this->booking;
    }

    public function setBooking($booking){
        $this->booking = $booking;
    }

    public function getDescription(){
        return $this->description;
    }
    public function setDescription($description){
        $this->description = $description;
    }
    public function getPoints(){
        return $this->points;
    }
    public function setPoints($points){
        $this->points = $points;
    }
}
?>