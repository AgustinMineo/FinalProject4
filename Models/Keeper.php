<?php
namespace Models;

use Models\User as User;

class Keeper extends User{
    private $keeperId; // not null
    private $availabilityDays; // ARRAY DAYS WITH THE FIRST DAY AND LAST DAY OF THE RESERVATION.
    private $animalSize; // not null
   // private $points; // shortint (1 - 5 ) 
   // private $reviews; // object array with the object review

    public function getKeeperId(){
        return $this->keeperId;
    }
    public function setKeeperId($keeperId){
         $this->keeperId = $keeperId;
    }

    public function getAvailabilityDays(){
        return $this->availabilityDays;
    }
    public function setAvailabilityDays($availabilityDays){
         $this->availabilityDays = $availabilityDays;
    }

    public function getAnimalSize(){
        return $this->animalSize;
    }
    public function setAnimalSize($AnimalSize){
         $this->animalSize = $AnimalSize;
    }

  /*  public function getPoints(){
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

}
?>