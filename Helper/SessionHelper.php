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
        if(!isset($_SESSION["loggedUser"])){
            header("Location: /FinalProject4/Views/loginUser.php");
            exit();
        }else{
            return $_SESSION["loggedUser"];
        }
    }
    /*Return keeper ID*/
    public static function getCurrentKeeperID(){
        if($_SESSION["loggedUser"]->getKeeperId()){
            return $_SESSION["loggedUser"]->getKeeperId();
        }else{
            header("Location: /FinalProject4/Views/loginUser.php");
            exit();
        }
    }
    /*Return Owner ID*/
    public static function getCurrentOwnerID(){
        if($_SESSION["loggedUser"]->getOwnerId()){
            return $_SESSION["loggedUser"]->getOwnerId();
        }else{
            header("Location: /FinalProject4/Views/loginUser.php");
            exit();
        }
    }
    
    public static function getCurrentPetAmount(){
        return $_SESSION["loggedUser"]->getPetAmount();
    }

    public static function sessionEnd(){
        if (isset($_SESSION['loggedUser'])) {
            unset($_SESSION['loggedUser']);
        }
        return session_destroy();
    }

    public static function InfoSession($requiredRole){
        //Valido que el usuario tenga el rol que necesita para acceder a la vista
        //Si $userRole no es valido, va al login, si ocurre algo y no se valida eso, entra al if
        //Si el if no es valido el valor, lo que hace es ir al validateSession
        $userRole = self::validateUserRole($requiredRole);
        if($userRole){
            require_once(VIEWS_PATH . "mainNavBar.php");
            return $userRole;
        }else{
            self::validateSession();
        }
    }

    public static function validateSession() {
        if (!isset($_SESSION)) {
            session_start();
        }
    
        if (!isset($_SESSION["loggedUser"])) {
            header("Location: " . VIEWS_PATH . "loginUser.php");
            exit();
        }
    }

    public static function getCurrentRole(){
        if(!isset($_SESSION["loggedUser"])){
            header("Location: /FinalProject4/Views/login.php");
            exit();
        }else{
            return $userRole = (int)$_SESSION["loggedUser"]->getRol() ?? null;
        }
    }

    public static function redirectTo403(){
        if (self::getCurrentUser()) {//Si el usuario esta logeado, mando el 403
            $userRole= self::getCurrentRole();
            require_once(VIEWS_PATH . "mainNavBar.php");
            require_once(VIEWS_PATH . "Error403.php");
            return $userRole;
        } else {
            header("Location: /FinalProject4/Views/Error403.php");
            exit();
        } 
    }

    public static function redirectTo404(){
        if (self::getCurrentUser()) {
            $userRole= self::getCurrentRole();
            require_once(VIEWS_PATH . "mainNavBar.php");
            require_once(VIEWS_PATH . "Error404.php");
        } else {
        header("Location: /FinalProject4/Views/loginUser.php");
        exit();
        }
    }

    public static function validateUserRole($requiredRole){
        self::validateSession();
        $userRole = (int)$_SESSION["loggedUser"]->getRol();
        if (!in_array($userRole, $requiredRole, true)) {
            $userRole= self::getCurrentRole();
            require_once(VIEWS_PATH . "mainNavBar.php");
            require_once(VIEWS_PATH . "Error403.php");
            self::redirectTo403(); 
            exit();
        }else{
            return $userRole;
        }
    }

}

?>