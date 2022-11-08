<?php
 namespace Controllers;

//use DAO\OwnerDAO as OwnerDAO;
use DAODB\OwnerDAO as OwnerDAO;
use Models\Owner as Owner;

 class OwnerController
 {
    private $OwnerDAO;
    private $newOwner;
    
    public function __construct(){
        $this->OwnerDAO = new OwnerDAO();
    }

    public function goLoginOwner(){
        require_once(VIEWS_PATH."loginOwner.php");
    }
    public function goLandingOwner(){
        require_once(VIEWS_PATH."landingPage.php");
    }
    public function addOwnerView(){
        require_once(VIEWS_PATH."owner-add.php");
    }

     public function newOwner($lastName,$firstName,$cellPhone,$birthDate,$email,$password){
          $newOwner = new Owner();
          $newOwner->setLastName($lastName);
          $newOwner->setfirstName($firstName);
          $newOwner->setCellPhone($cellPhone);
          $newOwner->setbirthDate($birthDate);
          $newOwner->setEmail($email);
          $newOwner->setPassword($password);
          //$newOwner->setPetAmount('0');
          $this->OwnerDAO->AddOwner($newOwner);
          //$this->OwnerDAODB->AddOwner($newOwner);
          $this->goLoginOwner();
    }
    
      public function loginOwner($email,$password){
        $newOwner = $this->OwnerDAO->loginOwner($email,$password);
        if($newOwner){
                 //  session_start(); // start the session
                $loggedUser = $newOwner;
                $_SESSION["loggedUser"] = $loggedUser;
                $this->goLandingOwner();
            }else{
                $this->goIndex();
            }
    }
 }
?>