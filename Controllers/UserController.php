<?php
namespace Controllers;

use DAO\OwnerDAO as OwnerDAO;
use Models\Owner as Owner;
use DAO\KeeperDAO as KeeperDAO;
use Models\Keeper as Keeper;
session_start();
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
    public function goLoginKeeper(){
        require_once(VIEWS_PATH."loginkeeper.php");
    }
    public function goLoginOwner(){
        require_once(VIEWS_PATH."loginOwner.php");
    }
    public function goLandingKeeper(){
        require_once(VIEWS_PATH."keeperNav.php");
    }
    public function goLandingOwner(){
        require_once(VIEWS_PATH."ownerNav.php");
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
        $this->goLoginOwner();
    }

    public function newKeeper(/*$keeperId*/$lastName,$firstName,$cellPhone,$birthDate,$email,$password,$availabilityDays,$animalSize/*,$points,$reviews*/,$price){
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
        $newKeeper->setPrice($price);

        $KeeperDAO->AddKeeper($newKeeper);
        $this->goLoginKeeper();
    }

    public function loginOwner($email,$password){
        $OwnerDAO = new OwnerDAO();
        $newOwner = new Owner();
        $newOwner = $OwnerDAO->searchEmail($email);
        if($newOwner){
            if($newOwner->getPassword()==$password){
              //  session_start(); // start the session
                $loggedUser = $newOwner;
                $_SESSION["loggedUser"] = $loggedUser;
                $this->goLandingOwner();
            }
        }else{
            $this->goIndex();
        }
    }

    public function loginKeeper($email,$password){
        $KeeperDAO = new KeeperDAO();
        $newKeeper = new Keeper();
        $newKeeper = $KeeperDAO->searchEmail($email);
        if($newKeeper->getPassword()==$password){
           // session_start(); // start the session
            $loggedUser = $newKeeper;
            $_SESSION["loggedUser"] = $loggedUser;
            $this->goLandingKeeper();
            }else{
            $this->goIndex();
            echo "<h2>no entre</h2>";
            }
    }

    public function showKeepers(){
        $KeeperDAO = new KeeperDAO();
        $listKeepers = array();
        $listKeepers = $KeeperDAO->getAllKeeper();
        require_once(VIEWS_PATH. "showKeeper.php");
    }

    public function updateAvailabilityDays($date1,$date2){
        $KeeperDAO = new KeeperDAO();
        $value=$KeeperDAO->changeAvailabilityDays($_SESSION["loggedUser"]->getEmail(),$date1,$date2);
        if($value){
            echo"<h1>Los cambios fueron realizados correctamente</h1>";
        }else{
            echo"<h1>Error al realizar los cambios</h1>";
        }
        $this->goLandingKeeper();
    }
}
?>