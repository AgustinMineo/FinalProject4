<?php
namespace Models;

class Review{

    private Booking $booking;
    private $description;
    private $points;

    public function getBooking(){
        return $this->booking;
    }

    public function setBooking(Booking $booking){
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