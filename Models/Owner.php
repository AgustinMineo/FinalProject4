<?php
namespace Models;

use Models\User as User;

class Owner extends User{
    private $ownerId;
    private $petAmount; 

    public function getOwnerId(){
        return $this->ownerId;
    }
    public function setOwnerId($ownerId){
        $this->ownerId = $ownerId;
   }

     public function getPetAmount(){
    return $this->petAmount;
    }
    public function setPetAmount($petAmount){
    $this->petAmount = $petAmount;
    }

}
?>