<?php
namespace Models;

class Booking {
    private $bookingID;  /// booking ID
    private $status;   /// 1 pending - 2 rejected -- 3 accepted -- 4 Confirmed -- 5 Finish
    private $petID;    /// id pet and owner from petOwner
    private $firstDate; // start date
    private $lastDate; /// finish date
    private $keeperID; /// keeper id
    private $totalValue; /// Total value from the reservation
    private $amountReservation; /// amount reservation
    

    public function getBookingID(){
        return $this->bookingID;
    }
    public function setBookingID($bookingID){
        $this->bookingID = $bookingID;
    }

    public function getStatus(){
        return $this->status;
    }
    public function setStatus($status){
        $this->status = $status;
    }

    public function getPetID(){
        return $this->petID;
    }
    public function setPetID($petID){
        $this->petID = $petID;
    }

    public function getFirstDate(){
        return $this->firstDate;
    }
    public function setFirstDate($firstDate){
        $this->firstDate = $firstDate;
    }

    public function getLastDate(){
        return $this->lastDate;
    }
    public function setLastDate($lastDate){
        $this->lastDate = $lastDate;
    }

    public function getKeeperID(){
        return $this->keeperID;
    }
    public function setKeeperID($keeperID){
        $this->keeperID = $keeperID;
    }

    public function getTotalValue(){
        return $this->totalValue;
    }
    public function setTotalValue($totalValue){
        $this->totalValue = $totalValue;
    }

    public function getAmountReservation(){
        return $this->amountReservation;
    }
    public function setAmountReservation($amountReservation){
        $this->amountReservation = $amountReservation;
    }
}
?>