<?php
namespace Controllers;

//use DAO\OwnerDAO as OwnerDAO;
use DAODB\OwnerDAO as OwnerDAO;
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

    public function logOut(){
        session_destroy();
        require_once(VIEWS_PATH."mainLanding.php");
    }

    //CHECK OUT FOR USER FUNCTIONALITIES (CHANGES IN NAME, PASSWORD, ETC)
}
?>