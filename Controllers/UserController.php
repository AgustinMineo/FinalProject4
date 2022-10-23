<?php
namespace Controllers;

use DAO\OwnerDAO as OwnerDAO;
use Models\Owner as Owner;
use DAO\KeeperDAO as KeeperDAO;
use Models\Keeper as Keeper;

class UserController{
    private $OwnerDAO;

    public function __constructor(){
        $this->OwnerDAO = new OwnerDAO();
    }
    public function addOwnerView(){
        require_once(VIEWS_PATH."owner-add.php");
    }
    public function addKeeperView(){
        require_once(VIEWS_PATH."keeper-add.php");
    }

    public function newOwner(/*$ownerId*/$lastName,$firstName,$cellPhone,$birthDate,$email,$password){
        $OwnerDAO = new OwnerDAO();
        $newOwner = new Owner();
      //  $newOwner->setOwnerId($ownerId);
        $newOwner->setLastName($lastName);
        $newOwner->setfirstName($firstName);
        $newOwner->setCellPhone($cellPhone);
        $newOwner->setbirthDate($birthDate);
        $newOwner->setEmail($email);
        $newOwner->setPassword($password);
        //$newOwner->setPetAmount('0');
        $OwnerDAO->AddOwner($newOwner);
        $this->addOwnerView();
    }

    public function newKeeper(/*$keeperId*/$lastName,$firstName,$cellPhone,$birthDate,$email,$password,$availabilityDays,$animalSize/*,$points,$reviews*/){
        $KeeperDAO = new KeeperDAO();
        $newKeeper = new Keeper();
       // $newKeeper->setOwnerId($keeperId);
        $newKeeper->setLastName($lastName);
        $newKeeper->setfirstName($firstName);
        $newKeeper->setCellPhone($cellPhone);
        $newKeeper->setbirthDate($birthDate);
        $newKeeper->setEmail($email);
        $newKeeper->setPassword($password);
        $newKeeper->setAvailabilityDays($availabilityDays);
        $newKeeper->setAnimalSize($animalSize);

        $KeeperDAO->AddKeeper($newKeeper);
        $this->addKeeperView();
    }
}
?>