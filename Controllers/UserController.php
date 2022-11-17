<?php
namespace Controllers;

use DAO\OwnerDAO as OwnerDAO;
//use DAODB\OwnerDAO as OwnerDAO;
use Models\Owner as Owner;
use DAO\KeeperDAO as KeeperDAO;
//use DAODB\KeeperDAO as KeeperDAO;
use Models\Keeper as Keeper;
use Helper\SessionHelper as SessionHelper;

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
    public function goEditOwner($owner){
        require_once(VIEWS_PATH."myProfileOwner.php");
    }

    public function logOut(){
        SessionHelper::sessionEnd();
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

    public function updateLastName($newName){
        $response=$this->OwnerDAO->updateName($newName,SessionHelper::getCurrentUser()->getEmail());
        if($response){
            echo '<div class="alert alert-success">You have successful update your Last Name!</div>';
            $this->goEditOwner($response);
        }else{
            echo '<div class="alert alert-danger">First Name cannot be empty!!</div>';
            $this->goEditOwner(SessionHelper::getCurrentUser());
        }
    }
    public function updateFirstName($newFirstName){
        if($newFirstName){
            $response=$this->OwnerDAO->updateFirstName($newFirstName,SessionHelper::getCurrentUser()->getEmail());
            if($response){
                echo '<div class="alert alert-success">You have successful update your Last Name!</div>';
                $this->goEditOwner($response);
            }
        }else{
            echo '<div class="alert alert-danger">Last Name cannot be empty!!</div>';
            $this->goEditOwner(SessionHelper::getCurrentUser());
        }
    }
    public function UpdateUserCellphone($newCellphone){
        if($newCellphone){
            $response=$this->OwnerDAO->updateCellphone($newCellphone,SessionHelper::getCurrentUser()->getEmail());
            if($response){
                echo '<div class="alert alert-success">You have successful update your Cellphone!</div>';
                $this->goEditOwner($response);
            }
        }else{
            echo '<div class="alert alert-danger">The Cellphone cannot be empty!!</div>';
            $this->goEditOwner(SessionHelper::getCurrentUser());
        }
    }
    public function UpdateDescription($newDescription){
        if($newDescription){
            $response=$this->OwnerDAO->updateDescription($newDescription,SessionHelper::getCurrentUser()->getEmail());
            if($response){
                echo '<div class="alert alert-success">You have successful update your Last Name!</div>';
                $this->goEditOwner($response);
            }
        }else{
            echo '<div class="alert alert-danger">Last Name cannot be empty!!</div>';
            $this->goEditOwner(SessionHelper::getCurrentUser());
        }
    }
}
?>