<?php
namespace Models;

class Booking {
    private $bookingID;  /// booking ID
    private $status;   /// 1 pending - 2 rejected -- 3 accepted -- 4 Confirmed -- 5 Finish 
    private $petID;    /// id pet and owner from petOwner
    private $startDate; // start date
    private $endDate; /// finish date
    private $keeperID; /// keeper id
    private $totalValue; /// Total value from the reservation
    private $amountReservation; /// amount reservation
    private $payment;  //Url comprobante de pago
    

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
    public function setPetID(Pet $petID){
        $this->petID = $petID;
    }
    public function getStartDate(){
        return $this->startDate;
    }
    public function setStartDate($startDate){
        $this->startDate = $startDate;
    }
    public function getEndDate(){
        return $this->endDate;
    }
    public function setEndDate($endDate){
        $this->endDate = $endDate;
    }
    public function getKeeperID(){
        return $this->keeperID;
    }
    public function setKeeperID(Keeper $keeperID){
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
    public function getPayment(){
        return $this->payment;
    }
    public function setPayment($payment){
        $this->payment = $payment;
    }
}
?>