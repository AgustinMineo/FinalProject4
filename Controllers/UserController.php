<?php
namespace Controllers;

use DAO\OwnerDAO as OwnerDAO;
//use DAODB\OwnerDAO as OwnerDAO;
use Models\Owner as Owner;
use DAO\KeeperDAO as KeeperDAO;
//use DAODB\KeeperDAO as KeeperDAO;
use Models\Keeper as Keeper;

class UserController{
    private $OwnerDAO;
    private $KeeperDAO;

    public function __construct(){
        $this->OwnerDAO = new OwnerDAO();
        $this->KeeperDAO = new KeeperDAO();
    }

    public function addOwnerView(){
        require_once(VIEWS_PATH."owner-add.php");
    }
    public function addKeeperView(){
        require_once(VIEWS_PATH."keeper-add.php");
    }
    public function goLandingKeeper(){
        require_once(VIEWS_PATH."keeperNav.php");
    }
    public function goLandingOwner(){
        require_once(VIEWS_PATH."landingPage.php");
    }
    public function gologinUser(){
        require_once(VIEWS_PATH."loginUser.php");
    }

    public function logOut(){
        session_destroy();
        require_once(VIEWS_PATH."mainLanding.php");
    }

    public function loginUser($email,$password){
            if($email && $password){
                try{
                    $newOwner = $this->OwnerDAO->searchOwnerToLogin($email,$password);
                    if($newOwner){
                        $loggedUser = $newOwner;
                        $_SESSION["loggedUser"] = $loggedUser;
                        echo '<div class="alert alert-success">Login successful!</div>';
                        $this->goLandingOwner();
                    } else if($newKeeper = $this->KeeperDAO->searchKeeperToLogin($email,$password)){
                        if($newKeeper){
                            $loggedUser = $newKeeper;
                            $_SESSION["loggedUser"] = $loggedUser;
                            echo '<div class="alert alert-success">Login successful!</div>';
                            $this->goLandingKeeper();
                            }else{
                                $this->gologinUser();
                                echo '<div class="alert alert-danger">The user doesn´t  ERROR KEEPER NO EXISTE exist. Please register!</div>';
                            }
                    }else{
                        echo '<div class="alert alert-danger">The user doesn´t exist. Please register!</div>';
                        $this->gologinUser();
                    }
                }catch ( Exception $ex) {
                    throw $ex;
                }
        }else if($password){
            echo '<div class="alert alert-danger">The Email is requeried to login!</div>';
            $this->gologinUser();
        }else if($email){
            echo '<div class="alert alert-danger">The Password is requeried to login!</div>';
            $this->gologinUser();
        }else{
            echo '<div class="alert alert-danger">The Email and Password is requeried to login!</div>';
            $this->gologinUser();
        }
    }
}
?>