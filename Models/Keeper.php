<?php
namespace Models;

use Models\User as User;

class Keeper extends User{
    private $keeperId; // not null
    private $animalSize; // not null
    private $price;
    private $points; // shortint (1 - 5 ) 
    private $firstAvailabilityDays; /// Change for the database
    private $lastAvailabilityDays; ///Change for the database
    private $KeeperCUIT;

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
            $date=date_create($firstAvailabilityDays);
            $this->firstAvailabilityDays = date_format($date,"d/m/Y");
        }

        public function getLastAvailabilityDays(){
            return $this->lastAvailabilityDays;
        }
        public function setLastAvailabilityDays($lastAvailabilityDays){
            $date=date_create($lastAvailabilityDays);
            $this->lastAvailabilityDays = date_format($date,"d/m/Y");
        }

        public function getPoints(){
            return $this->points;
        }
        public function setPoints($points){
            $this->points = $points;
        }
        public function getKeeperCUIT(){
            return $this->KeeperCUIT;
        }
        public function setKeeperCUIT($KeeperCUIT){
            $this->KeeperCUIT = $KeeperCUIT;
    }
    
}
?>