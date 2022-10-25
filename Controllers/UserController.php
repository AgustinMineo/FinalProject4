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
    public function goIndex(){
        require_once(VIEWS_PATH."loginkeeper.php");
    }
    public function goLanding(){
        require_once(VIEWS_PATH."landingPage.php");
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
       // $newKeeper->setkeeperId($keeperId);
        $newKeeper->setLastName($lastName);
        $newKeeper->setfirstName($firstName);
        $newKeeper->setCellPhone($cellPhone);
        $newKeeper->setbirthDate($birthDate);
        $newKeeper->setEmail($email);
        $newKeeper->setPassword($password);
        $newKeeper->setAvailabilityDays($availabilityDays);
        $newKeeper->setAnimalSize($animalSize);

        $KeeperDAO->AddKeeper($newKeeper);
        $this->goIndex();
    }

    public function loginOwner($email,$password){
        $OwnerDAO = new OwnerDAO();
        $newOwner = new Owner();
        $newOwner = $OwnerDAO->searchEmail($email);
        if($newOwner){
            if($newOwner->getPassword()==$password){
                session_start(); // start the session
                $loggedUser = $newOwner;
                $_SESSION["loggedUser"] = $loggedUser;
                $this->goLanding();
            }
        }else{
            $this->goIndex();
            echo "<h2>no entre</h2>";
        }
    }

    public function loginKeeper($email,$password){
        $KeeperDAO = new KeeperDAO();
        $newKeeper = new Keeper();
        $newKeeper = $KeeperDAO->searchEmail($email);
        if($newKeeper->getPassword()==$password){
            session_start(); // start the session
            $loggedUser = $newKeeper;
            $_SESSION["loggedUser"] = $loggedUser;
            $this->goLanding();
            }else{
            $this->goIndex();
            echo "<h2>no entre</h2>";
            }
    }
}
?>