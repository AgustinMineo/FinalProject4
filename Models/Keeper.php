<?php
namespace Models;

use Models\User as User;

class Keeper extends User{
    private $keeperId; // not null
    private $animalSize; // not null
    private $price;
    private $CBU;
    private $points;
    private $availability; // Disponibilidad
    private $questionRecovery;
    private $answerRecovery; 

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

    public function getQuestionRecovery(){
        return $this->questionRecovery;
    }
    public function setQuestionRecovery($questionRecovery){
        $this->questionRecovery = $questionRecovery;
    }
    public function getAnswerRecovery(){
        return $this->answerRecovery;
    }
    public function setAnswerRecovery($answerRecovery){
        $this->answerRecovery = $answerRecovery;
    }
    public function getAvailability() {
        return $this->availability;
    }
    
    public function setAvailability($availability) {
        $this->availability = $availability;
    }
    public function getCBU(){
        return $this->CBU;
    }
    public function setCBU($CBU){
        $this->CBU = $CBU;
    }

    public function getPoints(){
        return $this->points;
    }
    public function setPoints($points){
        $this->points = $points;
    }
}
?>