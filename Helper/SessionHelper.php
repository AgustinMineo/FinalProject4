<?php
namespace Helper;

use Models\Keeper as Keeper;
use Models\Owner as Owner;
use Models\User as User;

//use DAO\OwnerDAO;
//use DAO\KeeperDAO;
use DAODB\OwnerDAO;
use DAODB\KeeperDAO;

class SessionHelper{
    private $OwnerDAO;
    private $KeeperDAO;

    public function __construct(){
       $this->OwnerDAO = new OwnerDAO();
       $this->KeeperDAO = new KeeperDAO();
    }
    /*Return Current user logged*/
    public static function getCurrentUser(){
        return $_SESSION["loggedUser"];
    }
    /*Return keeper ID*/
    public static function getCurrentKeeperID(){
        return $_SESSION["loggedUser"]->getKeeperId();
    }
    /*Return Owner ID*/
    public static function getCurrentOwnerID(){
        return $_SESSION["loggedUser"]->getOwnerId();
    }
    public static function getCurrentPetAmount(){
        return $_SESSION["loggedUser"]->getPetAmount();
    }
    public static function sessionEnd(){
        return session_destroy();
    }

}

?>