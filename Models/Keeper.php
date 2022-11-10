<?php
namespace Models;

use Models\User as User;

class Keeper extends User{
    private $keeperId; // not null
    //private $firstAvailabilityDays; /// Change for the database
    //private $lastAvailabilityDays; ///Change for the database
    private $animalSize; // not null
    //private $points; // shortint (1 - 5 ) 
    //private $reviews; // object array with the object review
   private $price;

    public function getKeeperId(){
        return $this->keeperId;
    }
    public function setKeeperId($keeperId){
         $this->keeperId = $keeperId;
    }
/*
    public function getFirstAvailabilityDays(){
        return $this->firstAvailabilityDays;
    }
    public function setFirstAvailabilityDays($firstAvailabilityDays){
         $this->firstAvailabilityDays = $firstAvailabilityDays;
    }

    public function getLastAvailabilityDays(){
        return $this->lastAvailabilityDays;
    }
    public function setLastAvailabilityDays($lastAvailabilityDays){
         $this->lastAvailabilityDays = $lastAvailabilityDays;
    }
*/
    public function getAnimalSize(){
        return $this->animalSize;
    }
    public function setAnimalSize($AnimalSize){
         $this->animalSize = $AnimalSize;
    }

/*    public function getPoints(){
        return $this->points;
    }
    public function setPoints($points){
         $this->points = $points;
   }

    public function getReviews(){
        return $this->reviews;
    }
    public function setReviews($Reviews){
         $this->reviews = $Reviews;
    }*/

    public function getPrice(){
        return $this->price;
    }
    public function setPrice($price){
         $this->price = $price;
    }

}
?>