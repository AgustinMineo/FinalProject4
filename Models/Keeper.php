<?php
namespace Models;

use Models\User as User;

class Keeper extends User{
    private $keeperId; // not null
    private $animalSize; // not null
    private $price;
    private $firstAvailabilityDays; /// Change for the database
    private $lastAvailabilityDays; ///Change for the database
    private $CBU;
    // private $point;

        public function getKeeperId(){
            return $this->keeperId;
        }
        public function setKeeperId($keeperId){
         $this->keeperId = $keeperId;
        }
        public function getAnimalSize(){
            return $this->animalSize;
        }
        public function setAnimalSize($AnimalSize){
             $this->animalSize = $AnimalSize;
        }
        public function getPrice(){
            return $this->price;
        }
        public function setPrice($price){
             $this->price = $price;
        }
        public function getFirstAvailabilityDays(){
            return $this->firstAvailabilityDays;
        }
        public function setFirstAvailabilityDays($firstAvailabilityDays){
            $this->firstAvailabilityDays =$firstAvailabilityDays ;
        }

        public function getLastAvailabilityDays(){
            return $this->lastAvailabilityDays;
        }
        public function setLastAvailabilityDays($lastAvailabilityDays){
            $this->lastAvailabilityDays =$lastAvailabilityDays;
        }

        // public function getPoints(){
        //     return $this->points;
        // }
        // public function setPoints($points){
        //     $this->points = $points;
        // }
        public function getCBU(){
            return $this->CBU;
        }
        public function setCBU($CBU){
            $this->CBU = $CBU;
    }
    
}
?>